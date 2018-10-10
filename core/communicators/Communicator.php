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
 * Description of Communicator
 *
 * @author Daniele Ambrosino
 */
abstract class Communicator
{
  protected $chatId;
  
  public function __construct(int $chatId)
  {
    $this->chatId = $chatId;
  }
  
  public function setChatId(int $chatId)
  {
    $this->chatId = $chatId;
  }
  
  abstract public function sendMessage(string $text): string;
  
  abstract public function sendIsTyping();
}
