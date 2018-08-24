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
class DatabaseSQLite extends Database
{

  /**
   * @param int $id
   * @param string $firstName
   * @param string $lastName
   * @param string $username
   */
  public function saveUser(int $id, string $firstName, string $lastName, string $username)
  {
// TODO implement here
  }

  /**
   * @param int $id
   * @param string $datetime
   * @param int $userId
   * @param string $text
   */
  public function saveRequest(int $id, string $datetime, int $userId, string $text)
  {
// TODO implement here
  }

  /**
   * @param int $id
   * @param string $datetime
   * @param string $requestId
   * @param string $text
   */
  public function saveResponse(int $id, string $datetime, string $requestId, string $text)
  {
// TODO implement here
  }

  /**
   * @param string $query
   * @param array $values
   */
  protected function bind(string $query, array $values)
  {
    
  }

}
