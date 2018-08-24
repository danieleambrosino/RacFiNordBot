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
 * 
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
   * @var Database
   */
  protected $db;

  /**
   * @param SplSubject $subject
   */
  public function update(SplSubject $subject)
  {
    // TODO implement here
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
