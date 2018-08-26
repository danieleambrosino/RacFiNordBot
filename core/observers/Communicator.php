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

  public function __construct()
  {
    $this->db = DEVELOPMENT ? DatabaseSQLite::getInstance() : DatabaseMySQL::getInstance();
    $this->chatId = NULL;
    $this->requestId = NULL;
    $this->responses = [];
  }

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


    $this->sendResponses();
  }

  /**
   * @param string $text
   */
  protected abstract function sendResponses();

  /**
   * 
   */
  protected abstract function sendIsTyping();
}
