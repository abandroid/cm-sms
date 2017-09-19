<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms;

use DateTime;
use Endroid\CmSms\Bundle\CmSmsBundle\Exception\InvalidStatusDataException;

class Status
{
    /**
     * @var array
     */
    protected $webHookData;

    /**
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * Creates a new instance.
     */
    public function __construct()
    {
        $this->dateCreated = new DateTime();
    }

    /**
     * @param array $data
     *
     * @return static
     *
     * @throws InvalidStatusDataException
     */
    public static function fromWebHookData(array $data)
    {
        if (!isset($data['STATUS'])) {
            throw new InvalidStatusDataException('Web hook status data should contain a status code');
        }

        if (!isset($data['REFERENCE'])) {
            throw new InvalidStatusDataException('Web hook status data should contain a message reference');
        }

        $status = new static();
        $status->webHookData = $data;

        return $status;
    }

    /**
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->webHookData['STATUS'];
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->webHookData['REFERENCE'];
    }

    /**
     * @return array
     */
    public function getWebHookData()
    {
        return $this->webHookData;
    }
}
