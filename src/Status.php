<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms;

use Endroid\CmSms\Bundle\Exception\InvalidStatusDataException;

class Status
{
    /**
     * Accepted by the operator.
     */
    const CODE_ACCEPTED = 0;

    /**
     * Rejected by CM or the operator.
     */
    const CODE_REJECTED = 1;

    /**
     * Delivered.
     */
    const CODE_DELIVERED = 2;

    /**
     * Failed and will not be delivered.
     */
    const CODE_FAILED = 3;

    /**
     * @var array
     */
    protected $webHookData;

    /**
     * @param array $data
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