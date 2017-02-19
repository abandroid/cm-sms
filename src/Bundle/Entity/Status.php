<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Endroid\CmSms\Bundle\Repository\StatusRepository")
 * @ORM\Table(name="cm_sms_status")
 */
class Status
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    protected $data;

    /**
     * @ORM\ManyToOne(targetEntity="Endroid\CmSms\Bundle\Entity\Message", inversedBy="statuses", cascade={"persist"})
     *
     * @var Message
     */
    protected $message;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param Message $message
     * @return $this
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param array $data
     * @return $this
     */
    public static function fromArray(array $data)
    {
        $status = new self();
        $status->setData($data);

        return $status;
    }
}
