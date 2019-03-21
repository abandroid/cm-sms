<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms;

use DateTime;
use Endroid\CmSms\Exception\InvalidStatusDataException;

final class Status
{
    private $webHookData;
    private $dateCreated;

    public function __construct()
    {
        $this->dateCreated = new DateTime();
    }

    public static function fromWebHookData(array $data): self
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

    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }

    public function getCode(): int
    {
        return intval($this->webHookData['STATUS']);
    }

    public function getMessageId(): string
    {
        return $this->webHookData['REFERENCE'];
    }

    public function getWebHookData(): array
    {
        return $this->webHookData;
    }
}
