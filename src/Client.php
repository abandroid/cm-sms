<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms;

use Endroid\CmSms\Exception\InvalidRecipientException;
use Endroid\CmSms\Exception\InvalidSenderException;
use Endroid\CmSms\Exception\RequestException;
use Exception;
use GuzzleHttp\Psr7\Response;
use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use stdClass;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Client
{
    const UNICODE_AUTO = 'auto';
    const UNICODE_FORCE = 'force';
    const UNICODE_NEVER = 'never';

    private $baseUrl = 'https://gw.cmtelecom.com/v1.0/message';
    private $disableDelivery;
    private $deliveryPhoneNumbers;
    private $productToken;
    private $options;
    private $defaultOptions = [
        'sender' => null,
        'unicode' => self::UNICODE_AUTO,
        'minimum_number_of_message_parts' => 1,
        'maximum_number_of_message_parts' => 1,
    ];

    public function __construct(string $productToken, array $options = [], array $deliveryPhoneNumbers = [], bool $disableDelivery = false)
    {
        $this->disableDelivery = $disableDelivery;
        $this->deliveryPhoneNumbers = $deliveryPhoneNumbers;
        $this->productToken = $productToken;

        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->defaultOptions);
        $this->options = $resolver->resolve($options + $this->defaultOptions);
    }

    public static function getUnicodeOptions(): array
    {
        return [
            self::UNICODE_AUTO,
            self::UNICODE_FORCE,
            self::UNICODE_NEVER,
        ];
    }

    public function sendMessage(Message $message, array $options = []): void
    {
        $this->sendMessages([$message], $options);
    }

    public function sendMessages(array $messages, array $options = []): void
    {
        if ($this->disableDelivery) {
            return;
        }

        if (count($this->deliveryPhoneNumbers) > 0) {
            foreach ($messages as $message) {
                $message->setTo($this->deliveryPhoneNumbers);
            }
        }

        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults($this->options);
        $options = $optionsResolver->resolve($options + $this->options);

        $json = (object) [
            'messages' => (object) [
                'authentication' => (object) [
                    'producttoken' => $this->productToken,
                ],
                'msg' => $this->createMessagesJson($messages, $options),
            ],
        ];

        $client = new HttpMethodsClient(HttpClientDiscovery::find(), MessageFactoryDiscovery::find());

        try {
            $response = $client->post($this->baseUrl, [
                'content-type' => 'application/json',
            ], json_encode($json));
        } catch (Exception $exception) {
            throw new RequestException('Unable to perform API call: '.$exception->getMessage());
        }

        if (!$response instanceof Response || 200 != $response->getStatusCode()) {
            throw new RequestException('Invalid response');
        }

        foreach ($messages as $message) {
            $message->setStatusCode(StatusCode::SENT);
        }
    }

    private function createMessagesJson(array $messages, array $options): array
    {
        $messagesJson = [];

        foreach ($messages as $message) {
            if (0 == count($message->getTo())) {
                throw new InvalidRecipientException('Please provide valid SMS recipients for your message');
            }

            if (is_null($message->getFrom())) {
                $message->setFrom($options['sender']);
            }

            $this->assertValidSender($message->getFrom());

            $messageJson = (object) [
                'from' => $message->getFrom(),
                'to' => $this->createRecipientsJson($message->getTo()),
                'body' => (object) [
                    'content' => $message->getBody(),
                ],
                'reference' => $message->getId(),
                'minimum_number_of_message_parts' => $options['minimum_number_of_message_parts'],
                'maximum_number_of_message_parts' => $options['maximum_number_of_message_parts'],
            ];

            $messageJson = $this->incorporateUnicodeOption($messageJson, $options['unicode']);

            $messagesJson[] = $messageJson;
        }

        return $messagesJson;
    }

    public static function assertValidSender(string $sender): void
    {
        if (!preg_match('#^[a-z0-9]+$#i', $sender)) {
            throw new InvalidSenderException('The sender should only be composed of letters and numbers');
        }

        if (preg_match('#^[0-9]+$#', $sender) && strlen($sender) > 14) {
            throw new InvalidSenderException('A numeric sender should not be longer than 14 numbers');
        }

        if (strlen($sender) > 11) {
            throw new InvalidSenderException('An alphanumeric sender should not contain more than 11 characters');
        }
    }

    private function createRecipientsJson(array $recipients): array
    {
        $recipientsJson = [];

        foreach ($recipients as $recipient) {
            $recipientsJson[] = (object) [
                'number' => $recipient,
            ];
        }

        return $recipientsJson;
    }

    private function incorporateUnicodeOption(stdClass $messageJson, string $unicode): stdClass
    {
        switch ($unicode) {
            case self::UNICODE_AUTO:
                $messageJson->body->type = 'AUTO';
                break;
            case self::UNICODE_FORCE:
                $messageJson->dcs = 8;
                break;
            default:
                break;
        }

        return $messageJson;
    }
}
