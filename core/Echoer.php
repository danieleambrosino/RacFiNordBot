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
 * Description of Echoer
 *
 * @author Daniele Ambrosino
 */
class Echoer extends Communicator
{
  public function __construct(int $chatId)
  {
    parent::__construct($chatId);
  }
  
  public function sendIsTyping()
  {
    echo "Sending message...";
  }

  public function sendMessage(string $text)
  {
    echo $text;
  }
  
}
