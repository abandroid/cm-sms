<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms\Bundle\CmSmsBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Endroid\CmSms\Status as DomainStatus;

/**
 * @ORM\Entity
 * @ORM\Table(name="cm_sms_status")
 */
class Status
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $code;

    /**
     * @ORM\ManyToOne(targetEntity="Endroid\CmSms\Bundle\CmSmsBundle\Entity\Message", inversedBy="statuses", cascade={"persist"})
     *
     * @var Message
     */
    protected $message;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    protected $webHookData;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     *
     * @return $this
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getWebHookData()
    {
        return $this->getWebHookData();
    }

    /**
     * @param DomainStatus $domainStatus
     *
     * @return static
     */
    public static function fromDomain(DomainStatus $domainStatus)
    {
        $status = new static();
        $status->dateCreated = $domainStatus->getDateCreated();
        $status->code = $domainStatus->getCode();
        $status->webHookData = $domainStatus->getWebHookData();

        return $status;
    }
}
