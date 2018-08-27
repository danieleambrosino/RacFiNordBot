<?php

/*
 * This file is part of the RacFiNordBot package.
 * 
 * (c) 2018 Daniele Ambrosino <mail@danieleambrosino.it>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

/**
 * 
 */
class Sender extends Communicator
{

  /**
   * @var resource
   */
  private $curlHandle;

  public function __construct()
  {
    parent::__construct();
    $this->curlHandle = curl_init();
    if ( FALSE === $this->curlHandle )
    {
      throw new ErrorException(__METHOD__ . ': unable to inizialize cURL handle');
    }
  }

  /**
   * 
   * @throws ErrorException
   */
  protected function sendResponses()
  {
    curl_setopt_array($this->curlHandle, [
        CURLOPT_URL => BOT_API_URL . '/sendMessage',
        CURLOPT_POST => TRUE,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => TRUE
    ]);
    $postFieldsArray = [
        'chat_id' => $this->chatId,
        'parse_mode' => 'Markdown'
    ];

    foreach ($this->responses as $text)
    {
      $text = mb_convert_encoding($text, 'UTF-8');
      $postFieldsArray['text'] = $text;
      $postFields = json_encode($postFieldsArray);
      if ( FALSE === $postFields )
      {
        $error = json_last_error_msg();
        throw new ErrorException(__METHOD__ . ": Unable to JSON-encode postfields! $error");
      }
      curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $postFields);
      $result = $this->performSession();
      $this->db->saveResponse(
           $result['message_id'],
           date(FORMAT_DATETIME_DATABASE, $result['date']),
           $this->requestId,
           $result['text']);
    }
  }

  /**
   * 
   * @throws ErrorException
   */
  protected function sendIsTyping()
  {
    if ( empty($this->chatId) )
    {
      throw new ErrorException(__METHOD__ . 'Cannot send "is typing": chat ID unset!');
    }
    curl_setopt($this->curlHandle, CURLOPT_URL, BOT_API_URL . '/sendChatAction');
    $postFields = [
        'chat_id' => $this->chatId,
        'action' => 'typing'
    ];
    curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, json_encode($postFields));
    $this->performSession();
  }

  /**
   * 
   * @return bool|array
   * @throws ErrorException
   */
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
