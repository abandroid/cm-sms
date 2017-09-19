<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms\Bundle\CmSmsBundle\Controller;

use Endroid\CmSms\Bundle\CmSmsBundle\Entity\Message;
use Endroid\CmSms\Bundle\CmSmsBundle\Entity\Status;
use Endroid\CmSms\Bundle\CmSmsBundle\Repository\MessageRepository;
use Endroid\CmSms\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Endroid\CmSms\Message as DomainMessage;
use JMS\Serializer\SerializerBuilder;

/**
 * @Route("/message")
 */
class MessageController extends Controller
{
    /**
     * @Route("/", name="endroid_cm_sms_message_index")
     *
     * @return Response
     */
    public function indexAction()
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
     * @Route("/send/{phoneNumber}", name="endroid_cm_sms_message_send")
     *
     * @param string $phoneNumber
     *
     * @return Response|array
     */
    public function sendAction($phoneNumber)
    {
        $message = new DomainMessage();
        $message->addTo($phoneNumber);
        $message->setBody('Test message');

        $client = $this->getSmsClient();

        /*
         * Make sure the entity is persisted before sending so status
         * updates received between sending and persisting can be linked.
         */
        $this->getMessageRepository()->save(Message::fromDomain($message));

        /*
         * When sending the message its properties are altered: defaults
         * are set and the sent status is set upon success.
         */
        $client->sendMessage($message);

        /*
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
     * @return MessageRepository
     */
    protected function getMessageRepository()
    {
        return $this->getDoctrine()->getRepository('EndroidCmSmsBundle:Message');
    }
}
