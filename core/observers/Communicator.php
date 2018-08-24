<?php

/*
 * This file is part of the RacFiNordBot package.
 * 
 * (c) 2018 Daniele Ambrosino <mail@danieleambrosino.it>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

/**
 * Abstract class that outputs results and saves responses to DB
 * 
 * @author Daniele Ambrosino <mail@danieleambrosino.it>
 */
abstract class Communicator implements SplObserver
{

  /**
   * @var int
   */
  protected $chatId;

  /**
   * @var int
   */
  protected $requestId;

  /**
   * @var array
   */
  protected $responses;

  /**
   * @var Database
   */
  protected $db;

  /**
   * @param SplSubject $subject
   */
  public final function update(SplSubject $subject)
  {
    $this->chatId = $subject->getChatId();
    $this->requestId = $subject->getRequestId();
    $this->responses = $subject->getResponses();

    if ( empty($this->responses) )
    {
      $this->sendIsTyping();
      return;
    }
    
    foreach ($this->responses as $response)
    {
      $this->sendMessage($response);
    }
  }

  /**
   * @param string $text
   */
  protected abstract function sendMessage(string $text);

  /**
   * 
   */
  protected abstract function sendIsTyping();
}
