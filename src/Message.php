<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms;

use Endroid\CmsSms\Exceptions\InvalidSenderException;
use stdClass;

class Message
{
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
     * Message constructor.
     */
    public function __construct()
    {
        $this->to = [];
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
     */
    public function addTo($to)
    {
        if (!in_array($to, $this->to)) {
            $this->to[] = $to;
        }
    }
}
