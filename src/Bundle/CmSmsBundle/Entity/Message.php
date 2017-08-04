<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms\Bundle\CmSmsBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Endroid\CmSms\Message as DomainMessage;
use Endroid\CmSms\StatusCode;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Endroid\CmSms\Bundle\CmSmsBundle\Repository\MessageRepository")
 * @ORM\Table(name="cm_sms_message")
 */
class Message
{
    /**
     * @ORM\Column(type="string")
     * @ORM\Id
     *
     * @var string
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    protected $body;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $sender;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    protected $recipients;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    protected $options;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime<'Y-m-d H:i'>")
     *
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime<'Y-m-d H:i'>")
     *
     * @var DateTime
     */
    protected $dateUpdated;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $statusCode;

    /**
     * @ORM\OneToMany(targetEntity="Endroid\CmSms\Bundle\CmSmsBundle\Entity\Status", mappedBy="message", cascade={"persist"})
     *
     * @var ArrayCollection|Status[]
     */
    protected $statuses;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->statuses = new ArrayCollection();
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return string
     */
    public function getTranslationKey()
    {
        $translationKeys = StatusCode::getTranslationKeys();

        return $translationKeys[$this->statusCode];
    }

    /**
     * @param DomainMessage $domainMessage
     * @return static
     */
    public static function fromDomain(DomainMessage $domainMessage)
    {
        $message = new static();
        $message->id = $domainMessage->getId();
        $message->body = $domainMessage->getBody();
        $message->sender = $domainMessage->getFrom();
        $message->recipients = $domainMessage->getTo();
        $message->options = $domainMessage->getOptions();
        $message->dateCreated = $domainMessage->getDateCreated();
        $message->dateUpdated = $domainMessage->getDateUpdated();
        $message->statusCode = $domainMessage->getStatusCode();

        return $message;
    }

    /**
     * @param Status $status
     * @return $this
     */
    public function addStatus(Status $status)
    {
        $status->setMessage($this);
        $this->statuses->add($status);

        // Never change the resulting status code when a delivery confirmation was sent
        if ($this->statusCode == StatusCode::DELIVERED) {
            return $this;
        }

        $this->statusCode = $status->getCode();
        $this->dateUpdated = new DateTime();
    }
}
