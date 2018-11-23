<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms\Tests;

use Endroid\CmSms\Message;
use Endroid\CmSms\StatusCode;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{
    public function testUnsentWhenCreated()
    {
        $message = new Message();

        $this->assertEquals(StatusCode::UNSENT, $message->getStatusCode());
    }
}
