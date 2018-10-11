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
 * Description of Echoer
 *
 * @author Daniele Ambrosino
 */
class Echoer extends Communicator
{
  public function __construct(int $chatId)
  {
    parent::__construct($chatId);
    header('Content-Type: text/plain');
  }
  
  public function sendIsTyping()
  {
    echo "Sending message to $this->chatId...\n\n";
  }

  public function sendMessage(string $text): string
  {
    echo "--MESSAGGIO A $this->chatId--\n" . $text . "\n\n";
    return json_encode([
      'result' => [
        'message_id' => rand(),
        'date' => time()
      ]
    ]);
  }
  
}
