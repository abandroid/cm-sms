<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms\Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Endroid\CmSms\Message as DomainMessage;
use Endroid\CmSms\Status as DomainStatus;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Endroid\CmSms\Bundle\Repository\MessageRepository")
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
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $sent;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $delivered;

    /**
     * @ORM\OneToMany(targetEntity="Endroid\CmSms\Bundle\Entity\Status", mappedBy="message", cascade={"persist"})
     */
    protected $statuses;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->delivered = false;
        $this->statuses = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isDelivered()
    {
        return $this->delivered;
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
        $message->sent = $domainMessage->isSent();

        return $message;
    }

    /**
     * @param Status $status
     */
    public function addStatus(Status $status)
    {
        $status->setMessage($this);
        $this->statuses->add($status);

        if ($status->getCode() == DomainStatus::CODE_DELIVERED) {
            $this->delivered = true;
        }
    }
}
