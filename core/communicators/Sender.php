<?php

/*
 * This file is part of RacFiNordBot,
 * the official Telegram Bot of the Rotaract Club Firenze Nord.
 * 
 * (c) 2018 Rotaract Club Firenze Nord <rotaractfirenzenord@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

/**
 * Description of Sender
 *
 * @author Daniele Ambrosino
 */
class Sender extends Communicator
{

  private $curlHandle;

  public function __construct(int $chatId)
  {
    $this->curlHandle = curl_init();
    curl_setopt_array($this->curlHandle,
                      [
      CURLOPT_POST           => TRUE,
      CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
      CURLOPT_RETURNTRANSFER => TRUE
    ]);
    if ( FALSE === $this->curlHandle )
    {
      throw new ErrorException(__METHOD__ . ': unable to inizialize cURL handle');
    }
  }

  public function sendIsTyping()
  {
    if ( empty($this->chatId) )
    {
      throw new ErrorException(__METHOD__ . 'Cannot send "is typing": chat ID unset!');
    }
    curl_setopt($this->curlHandle, CURLOPT_URL,
                TELEGRAM_BOT_API_URL . '/sendChatAction');
    $postFields = [
      'chat_id' => $this->chatId,
      'action'  => 'typing'
    ];
    curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, json_encode($postFields));
    $this->performSession();
  }

  public function sendMessage(string $text): string
  {
    $text = mb_convert_encoding($text, 'UTF-8');
    $postFieldsArray = [
      'text'       => $text,
      'chat_id'    => $this->chatId,
      'parse_mode' => 'Markdown'
    ];
    $postFields = json_encode($postFieldsArray);
    if ( FALSE === $postFields )
    {
      $error = json_last_error_msg();
      throw new ErrorException(__METHOD__ . ": Unable to JSON-encode postfields! $error");
    }
    curl_setopt_array($this->curlHandle,
                      [
      CURLOPT_URL        => TELEGRAM_BOT_API_URL . '/sendMessage',
      CURLOPT_POSTFIELDS => $postFields
    ]);
    return $this->performSession();
  }

  private function performSession()
  {
    $rawResponse = curl_exec($this->curlHandle);
    if ( FALSE === $rawResponse )
    {
      throw new ErrorException(__METHOD__ . ': cURL error, curl_exec() failed');
    }
    $httpCode = intval(curl_getinfo($this->curlHandle, CURLINFO_RESPONSE_CODE));
    if ( $httpCode >= 500 )
    {
      throw new ErrorException(__METHOD__ . ": Telegram server error (HTTP code $httpCode");
    }
    if ( is_bool($rawResponse) )
    {
      if ( FALSE === $rawResponse )
      {
        throw new ErrorException(__METHOD__ . ': Telegram refused our request');
      }
      return TRUE;
    }
    $response = json_decode($rawResponse, TRUE);
    if ( FALSE === $response )
    {
      throw new ErrorException(__METHOD__ . ': Bad content (unable to decode JSON)');
    }
    if ( $response['ok'] !== TRUE )
    {
      throw new ErrorException(__METHOD__ . ": Telegram refused our request, error code {$response['error_code']}: {$response['description']}");
    }
    $result = $response['result'];
    if ( TRUE === $result )
    {
      return TRUE;
    }
    if ( !isset($result['message_id'], $result['date'], $result['text']) )
    {
      throw new ErrorException(__METHOD__ . ': Invalid response returned by Telegram');
    }
    return $result;
  }

}
