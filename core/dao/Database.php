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
   * Connection handle.
   */
  protected $handle;

  /**
   * Private constructor to enforce Singleton property.
   */
  protected function __construct(){}

  /**
   * @return Database If no Database objects have been initialized, it creates
   * a new one and returns it. Otherwise, it returns the pre-existing object.
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
   * @param string $lastName [optional]
   * @param string $username [optional]
   */
  public abstract function saveUser(int $id, string $firstName, string $lastName = NULL, string $username = NULL);

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
   * Execute a query against the database.
   * If $values is passed, the query will be treated as a prepared statement
   * and the values into $values will be bound to it.
   * This implementation depends on 'bind' and 'fetchResults' methods.
   * 
   * @param string $query The query to be execute.
   * @param array $values [optional] The values to be bound to the query.
   * 
   * @return array|bool An associative array with query results. If the query
   * if resultless, returns TRUE on success.
   * @throws ErrorException
   */
  protected final function query(string $query, array $values = NULL)
  {
    $result = NULL;
    if ( !empty($values) )
    {
      $stmt = $this->bind($query, $values);

      if ( !$stmt )
      {
        throw new ErrorException(__METHOD__ . ': unable to bind statement');
      }

      $result = $stmt->execute();
    }
    else
    {
      $result = $this->handle->query($query);
    }

    if ( FALSE === $result )
    {
      throw new ErrorException(__METHOD__ . ': query failed');
    }

    if ( TRUE === $result )
    {
      $results = TRUE;
    }
    else
    {
      $results = $this->fetchResults($result);
      if ( method_exists($result, 'finalize') )
      {
        $result->finalize();
      }
      elseif ( method_exists($result, 'free') )
      {
        $result->free();
      }
    }
    return $results;
  }

  /**
   * Prepares the statement with the given query. Throws an ErrorException if
   * query preparing fails. Then, binds the given values to the prepared
   * statement and returns the statement object. Throws an ErrorException if
   * binding fails.
   * 
   * @param string $query The query to be execute.
   * @param array $values The values to be bound to the query.
   * @returns mixed A database results object, whose type depends on the DBMS.
   */
  protected abstract function bind(string $query, array $values);

  /**
   * Fetches results from a database result set.
   * 
   * @return array A database-independent associative array with results.
   */
  protected abstract function fetchResults($result): array;
}
