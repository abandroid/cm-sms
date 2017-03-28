<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms;

use MyCLabs\Enum\Enum;

class StatusCode extends Enum
{
    /**
     * Not sent by the client.
     */
    const UNSENT = -2;

    /**
     * Sent by the client.
     */
    const SENT = -1;

    /**
     * Accepted by the operator (CM state).
     */
    const ACCEPTED = 0;

    /**
     * Rejected by CM or the operator (CM state).
     */
    const REJECTED = 1;

    /**
     * Delivered (CM state).
     */
    const DELIVERED = 2;

    /**
     * Failed and will not be delivered (CM state).
     */
    const FAILED = 3;

    /**
     * @return array
     */
    public static function getTranslationKeys()
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
