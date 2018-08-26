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
   * @var int User's Telegram chat ID.
   */
  protected $chatId;

  /**
   * @var int Request's Telegram ID.
   */
  protected $requestId;

  /**
   * @var array Bot's responses.
   */
  protected $responses;

  /**
   * @var Database Data Access Object.
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
   * Update method from Observer design pattern.
   * 
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
   * Send bot's responses.
   */
  protected abstract function sendResponses();

  /**
   * Alert user that the bot is computing the answer.
   */
  protected abstract function sendIsTyping();
}
