<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms;

use MyCLabs\Enum\Enum;

final class StatusCode extends Enum
{
    const UNSENT = -2;      // Not sent by the client
    const SENT = -1;        // Sent by the client
    const ACCEPTED = 0;     // Accepted by the operator (CM state)
    const REJECTED = 1;     // Rejected by CM or the operator (CM state)
    const DELIVERED = 2;    // Delivered (CM state)
    const FAILED = 3;       // Failed and will not be delivered (CM state)

    public static function getTranslationKeys(): array
    {
        return [
            self::UNSENT => 'unsent',
            self::SENT => 'sent',
            self::ACCEPTED => 'accepted',
            self::REJECTED => 'rejected',
            self::DELIVERED => 'delivered',
            self::FAILED => 'failed',
        ];
    }
}
