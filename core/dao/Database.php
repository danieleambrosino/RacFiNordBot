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
abstract class Database
{

  /**
   * @var Database
   */
  protected static $instance;

  /**
   * 
   */
  protected $handle;

  /**
   * 
   */
  private final function __construct();

  /**
   * @return
   */
  public static function getInstance(): Database
  {
    if ( empty(static::$instance) )
    {
      static::$instance = new static();
    }
    return static::$instance;
  }

  /**
   * @param int $id 
   * @param string $firstName 
   * @param string $lastName 
   * @param string $username
   */
  public abstract function saveUser(int $id, string $firstName, string $lastName, string $username);

  /**
   * @param int $id 
   * @param string $datetime 
   * @param int $userId 
   * @param string $text
   */
  public abstract function saveRequest(int $id, string $datetime, int $userId, string $text);

  /**
   * @param int $id 
   * @param string $datetime 
   * @param int $requestId 
   * @param string $text
   */
  public abstract function saveResponse(int $id, string $datetime, int $requestId, string $text);

  /**
   * @param string $query
   * @param array $values
   */
  protected abstract function query(string $query, array $values);
  
  /**
   * Prepares the statement with the given query. Throws an ErrorException if
   * query preparing fails. Then, binds the given values to the prepared
   * statement and returns the statement object. Throws an ErrorException if
   * binding fails.
   * 
   * @param string $query 
   * @param array $values
   */
  protected abstract function bind(string $query, array $values);
}
