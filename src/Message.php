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

final class Message
{
    private $id;
    private $body;
    private $from;
    private $to;
    private $options;
    private $dateCreated;
    private $dateUpdated;
    private $statusCode;

    public function __construct()
    {
        $this->id = str_replace('-', '', Uuid::uuid4());
        $this->from = '';
        $this->to = [];
        $this->options = [];
        $this->dateCreated = new DateTime();
        $this->dateUpdated = new DateTime();
        $this->statusCode = StatusCode::UNSENT;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    public function getTo(): array
    {
        return $this->to;
    }

    public function setTo(array $to): void
    {
        $this->to = $to;
    }

    public function addTo(string $to): void
    {
        if (!in_array($to, $this->to)) {
            $this->to[] = $to;
        }
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }

    public function getDateUpdated(): DateTime
    {
        return $this->dateUpdated;
    }
}
