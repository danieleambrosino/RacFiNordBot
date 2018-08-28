<?php

/*
 * This file is part of the RacFiNordBot package.
 * 
 * (c) 2018 Rotaract Club Firenze Nord <rotaractfirenzenord@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

/**
 * Manages all interactions to database.
 * 
 * @author Daniele Ambrosino <mail@danieleambrosino.it>
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
   * Protected constructor to enforce Singleton property.
   */
  protected abstract function __construct();

  public function __destruct()
  {
    $this->handle->close();
  }

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
   * Saves user's data
   * 
   * @param int $id User's Telegram ID
   * @param string $firstName User's first name
   * @param string $lastName [optional] User's last name
   * @param string $username [optional] Username
   */
  public abstract function saveUser(int $id, string $firstName, string $lastName = NULL, string $username = NULL);

  /**
   * Saves the incoming request
   * 
   * @param int $id Message's Telegram ID
   * @param string $datetime Message's date and time
   * @param int $userId User's ID
   * @param string $text Text of the request
   */
  public abstract function saveRequest(int $id, string $datetime, int $userId, string $text);

  /**
   * Saves a response
   * 
   * @param int $id Message's Telegram ID
   * @param string $datetime Message's date and time
   * @param int $requestId Request's ID
   * @param string $text Text of the response
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
