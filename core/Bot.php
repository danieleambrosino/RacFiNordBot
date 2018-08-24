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
 * Computational engine
 *
 * @author Daniele Ambrosino <mail@danieleambrosino.it>
 */
class Bot implements SplSubject
{

  /**
   * @var string
   */
  private $update;

  /**
   * @var array
   */
  private $responses;

  /**
   * @var int
   */
  private $chatId;

  /**
   * @var int
   */
  private $requestId;

  /**
   * @var Database
   */
  private $db;

  /**
   * @param string $update
   */
  public function __construct(string $update)
  {
// TODO implement here
  }

  /**
   * 
   */
  public function evaluate()
  {
// TODO implement here
  }

  /**
   * @return array
   */
  public function getResponses(): array
  {
    return $this->responses;
  }

  /**
   * @return int
   */
  public function getChatId(): int
  {
    return $this->chatId;
  }

  /**
   * @return int
   */
  public function getRequestId(): int
  {
    return $this->chatId;
  }

  /**
   * 
   */
  public function notify()
  {
// TODO implement here
  }

  /**
   * @param SplObserver $observer
   */
  public function attach(SplObserver $observer)
  {
// TODO implement here
  }

  /**
   * @param SplObserver $observer
   */
  public function detach(SplObserver $observer)
  {
// TODO implement here
  }

  /**
   * @param int $maxResults
   */
  private function getNextEvents(int $maxResults)
  {
// TODO implement here
  }

  /**
   * @param Google_Service_Calendar_Event $event
   * @return string
   */
  private function textifyEvent(Google_Service_Calendar_Event $event): string
  {
// TODO implement here
  }

}
