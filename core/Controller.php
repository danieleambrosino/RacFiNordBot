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

/**
 * Description of Controller
 *
 * @author Daniele Ambrosino
 */
class Controller
{

  private $message;
  private $chat;
  private $user;

  public function __construct(string $update)
  {
    $update = json_decode($update, TRUE);
    if ( empty($update) )
    {
      throw new MalformedUpdateException(__METHOD__ . ': Failed to decode Telegram update');
    }

    if ( isset($update['message']) )
    {
      $this->message = &$update['message'];
    }
    else
    {
      exit;
    }

    if ( !isset($this->message['text']) )
    {
      exit;
    }
    if ( !isset(
              $this->message['message_id'], $this->message['date'],
              $this->message['chat']['id'], $this->message['from']['id'],
              $this->message['from']['first_name']) )
    {
      throw new MalformedUpdateException();
    }

    $this->chat = &$this->message['chat'];
    $this->user = &$this->message['from'];
  }

  public function run()
  {
    $user = new User($this->user['id'], $this->user['first_name'],
                     $this->user['last_name'], $this->user['username']);

    
    $communicator = DEVELOPMENT ? new Echoer() : new Sender($this->chat['id']);

    if ( isset($this->message['text']) )
    {
      $request = new TextRequest($this->message['message_id'],
                                 date(FORMAT_DATETIME_DATABASE), $user,
                                      $this->message['text']);

      $responder = new TextResponder($request);
      $communicator->sendIsTyping();
      $responder->run();
      $responses = $responder->getResponses();
      foreach ($responses as $response)
      {
        $communicator->sendMessage($response->getText());
      }
    }
  }

}
