<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms;

use DateTime;
use Ramsey\Uuid\Uuid;

class Message
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var array
     */
    protected $to;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * @var DateTime
     */
    protected $dateUpdated;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->id = str_replace('-', '', Uuid::uuid4());
        $this->dateCreated = new DateTime();
        $this->dateUpdated = new DateTime();
        $this->to = [];
        $this->statusCode = StatusCode::UNSENT;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param array $to
     * @return $this
     */
    public function setTo(array $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param string $to
     * @return $this
     */
    public function addTo($to)
    {
        if (!in_array($to, $this->to)) {
            $this->to[] = $to;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @return DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }
}
