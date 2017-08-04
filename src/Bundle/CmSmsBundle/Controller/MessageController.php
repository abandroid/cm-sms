<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms\Bundle\CmSmsBundle\Controller;

use Doctrine\ORM\EntityManager;
use Endroid\CmSms\Bundle\CmSmsBundle\Entity\Message;
use Endroid\CmSms\Bundle\CmSmsBundle\Entity\Status;
use Endroid\CmSms\Bundle\CmSmsBundle\Exception\InvalidStatusDataException;
use Endroid\CmSms\Bundle\CmSmsBundle\Repository\MessageRepository;
use Endroid\CmSms\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Endroid\CmSms\Message as DomainMessage;
use Endroid\CmSms\Status as DomainStatus;
use JMS\Serializer\SerializerBuilder;

/**
 * @Route("/")
 */
class MessageController extends Controller
{
    /**
     * @Route("/", name="endroid_cmsms_message_index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        // Disable web profiler when using React
        if ($this->has('profiler')) {
            $this->get('profiler')->disable();
        }

        return [];
    }

    /**
     * @Route("/state", name="endroid_cmsms_message_state")
     *
     * @return Response
     */
    public function stateAction()
    {
        $messages = $this->getMessageRepository()->findAll();

        $serializer = SerializerBuilder::create()->build();

        return new Response(
            $serializer->serialize(['messages' => $messages], 'json'),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/update-status", name="endroid_cmsms_message_updatestatus")
     *
     * @param Request $request
     * @return Response
     */
    public function updateStatusAction(Request $request)
    {
        // Support both GET and POST
        $data = $request->getMethod() === Request::METHOD_GET ? $request->query->all() : $request->request->all();

        try {
            $status = DomainStatus::fromWebHookData($data);
        } catch (InvalidStatusDataException $exception) {
            return new Response();
        }

        /** @var Message $message */
        $message = $this->getMessageRepository()->find($status->getMessageId());

        if (!$message instanceof Message) {
            return new Response();
        }

        $message->addStatus(Status::fromDomain($status));
        $this->getMessageRepository()->save($message);

        return new Response();
    }

    /**
     * @Route("/send-test/{phoneNumber}", name="endroid_cmsms_message_test")
     *
     * @param string $phoneNumber
     * @return Response|array
     */
    public function testAction($phoneNumber)
    {
        $message = new DomainMessage();
        $message->addTo($phoneNumber);
        $message->setBody('Test message');

        $client = $this->getSmsClient();

        /**
         * Make sure the entity is persisted before sending so status
         * updates received between sending and persisting can be linked.
         */
        $this->getMessageRepository()->save(Message::fromDomain($message));

        /**
         * When sending the message its properties are altered: defaults
         * are set and the sent status is set upon success.
         */
        $client->sendMessage($message);

        /**
         * Update the stored message so it reflects the domain message.
         */
        $this->getMessageRepository()->save(Message::fromDomain($message));

        return new JsonResponse($message);
    }

    /**
     * @return Client
     */
    protected function getSmsClient()
    {
        return $this->get('endroid.cm_sms.client');
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return MessageRepository
     */
    protected function getMessageRepository()
    {
        return $this->getDoctrine()->getRepository('EndroidCmSmsBundle:Message');
    }
}
