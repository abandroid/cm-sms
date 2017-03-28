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
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Client
{
    const UNICODE_AUTO = 'auto';
    const UNICODE_FORCE = 'force';
    const UNICODE_NEVER = 'never';

    /**
     * @var string
     */
    protected $baseUrl = 'https://gw.cmtelecom.com/v1.0/message';

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $productToken;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $defaultOptions = [
        'sender' => null,
        'unicode' => self::UNICODE_AUTO,
        'minimum_number_of_message_parts' => 1,
        'maximum_number_of_message_parts' => 1,
    ];

    /**
     * @var OptionsResolver
     */
    protected $optionsResolver;

    /**
     * @param string $productToken
     * @param array|null $options
     * @param bool $enabled
     */
    public function __construct($productToken, array $options = [], $enabled = true)
    {
        $this->enabled = $enabled;
        $this->productToken = $productToken;

        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->defaultOptions);
        $this->options = $resolver->resolve($options + $this->defaultOptions);
    }

    /**
     * @return array
     */
    public static function getUnicodeOptions()
    {
        return [
            self::UNICODE_AUTO,
            self::UNICODE_FORCE,
            self::UNICODE_NEVER,
        ];
    }

    /**
     * @param Message $message
     * @param array|null $options
     */
    public function sendMessage(Message $message, array $options = [])
    {
        $this->sendMessages([$message], $options);
    }

    /**
     * @param Message[] $messages
     * @param array|null $options
     * @throws RequestException
     */
    public function sendMessages(array $messages, array $options = [])
    {
        if (!$this->enabled) {
            return;
        }

        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults($this->options);
        $options = $optionsResolver->resolve($options + $this->options);

        $json = (object) [
            'messages' => (object) [
                'authentication' => (object) [
                    'producttoken' => $this->productToken
                ],
                'msg' => $this->createMessagesJson($messages, $options)
            ]
        ];

        $client = new GuzzleClient();
        $adapter = new GuzzleAdapter($client);
        $request = new Request('POST', $this->baseUrl, ['Content-Type' => 'application/json'], json_encode($json));

        try {
            $response = $adapter->sendRequest($request);
        } catch (Exception $exception) {
            throw new RequestException('Unable to perform API call: '.$exception->getMessage());
        }

        if (!$response instanceof Response || $response->getStatusCode() != 200) {
            throw new RequestException('Invalid response');
        }

        foreach ($messages as $message) {
            $message->setStatusCode(StatusCode::SENT);
        }
    }

    /**
     * @param Message[] $messages
     * @param array $options
     * @return array
     * @throws InvalidSenderException
     * @throws InvalidRecipientException
     */
    protected function createMessagesJson(array $messages, array $options)
    {
        $messagesJson = [];

        foreach ($messages as $message) {
            if (count($message->getTo()) == 0) {
                throw new InvalidRecipientException('Please provide valid SMS recipients for your message');
            }

            if (is_null($message->getFrom())) {
                $message->setFrom($options['sender']);
            }

            $this->assertValidSender($message->getFrom());

            $messageJson = (object)[
                'from' => $message->getFrom(),
                'to' => $this->createRecipientsJson($message->getTo()),
                'body' => (object)[
                    'content' => $message->getBody()
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

    /**
     * @param $sender
     * @throws InvalidSenderException
     */
    public static function assertValidSender($sender)
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

    /**
     * @param array $recipients
     * @return array
     */
    protected function createRecipientsJson(array $recipients)
    {
        $recipientsJson = [];

        foreach ($recipients as $recipient) {
            $recipientsJson[] = (object) [
                'number' => $recipient
            ];
        }

        return $recipientsJson;
    }

    /**
     * @param object $messageJson
     * @param string $unicode
     * @return object
     */
    protected function incorporateUnicodeOption($messageJson, $unicode)
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
