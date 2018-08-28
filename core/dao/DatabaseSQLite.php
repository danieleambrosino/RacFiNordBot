<?php

/*
 * This file is part of the RacFiNordBot package.
 * 
 * (c) 2018 Rotaract Club Firenze Nord <rotaractfirenzenord@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

/**
 * Implementation of Database class for SQLite DBMS.
 * 
 * @author Daniele Ambrosino <mail@danieleambrosino.it>
 */
class DatabaseSQLite extends Database
{

  protected function __construct()
  {
    $this->handle = new SQlite3(DATABASE_SQLITE_PATH, SQLITE3_OPEN_READWRITE);
  }
  
  public function __destruct()
  {
    $this->handle->exec('ANALYZE');
    parent::__destruct();
  }

    /**
   * Saves user's data
   * 
   * @param int $id User's Telegram ID
   * @param string $firstName User's first name
   * @param string $lastName [optional] User's last name
   * @param string $username [optional] Username
   */
  public function saveUser(int $id, string $firstName, string $lastName = NULL, string $username = NULL)
  {
    $query = 'INSERT OR IGNORE INTO Users (id, firstName, lastName, username) VALUES (?, ?, ?, ?)';
    $values = [$id, $firstName, $lastName, $username];
    $this->query($query, $values);
  }

  /**
   * Saves the incoming request
   * 
   * @param int $id Message's Telegram ID
   * @param string $datetime Message's date and time
   * @param int $userId User's ID
   * @param string $text Text of the request
   */
  public function saveRequest(int $id, string $datetime, int $userId, string $text)
  {
    $query = 'INSERT INTO Requests (id, datetime, userId, text) VALUES (?, ?, ?, ?)';
    $values = [$id, $datetime, $userId, $text];
    $this->query($query, $values);
  }

  /**
   * Save a response
   * 
   * @param int $id Message's Telegram ID
   * @param string $datetime Message's date and time
   * @param int $requestId Request's ID
   * @param string $text Text of the response
   */
  public function saveResponse(int $id, string $datetime, int $requestId, string $text)
  {
    $query = 'INSERT INTO Responses (id, datetime, requestId, text) VALUES (?, ?, ?, ?)';
    $values = [$id, $datetime, $requestId, $text];
    $this->query($query, $values);
  }

  /**
   * Prepares the statement with the given query. Throws an ErrorException if
   * query preparing fails. Then, binds the given values to the prepared
   * statement and returns the statement object. Throws an ErrorException if
   * binding fails.
   * 
   * @param string $query
   * @param array $values
   * @return SQLite3Stmt
   */
  protected function bind(string $query, array $values)
  {
    if ( !($stmt = $this->handle->prepare($query)) )
    {
      throw new ErrorException(__METHOD__ . ': unable to bind statement');
    }

    $paramCount = $stmt->paramCount();
    if ( $paramCount !== count($values) )
    {
      throw new ErrorException(__METHOD__ . ': parameters count mismatch');
    }

    for ($i = 0; $i < $paramCount; ++$i)
    {
      $stmt->bindValue($i + 1, $values[$i], $this->getAffinity($values[$i]));
    }

    return $stmt;
  }

  /**
   * Fetches the results and closes the results set.
   * 
   * @param SQLite3Result $result
   * @throws ErrorException if an invalid object is passed.
   */
  protected function fetchResults($result): array
  {
    if ( !method_exists($result, 'fetchArray') ||
         !method_exists($result, 'numColumns') )
    {
      throw new ErrorException(__METHOD__ . ': invalid object passed as parameter');
    }

    $results = [];

    if ( $result->numColumns() !== 0 )
    {
      while ($row = $result->fetchArray(SQLITE3_ASSOC))
      {
        $results[] = $row;
      }
    }

    $result->finalize();
    return $results;
  }

  /**
   * Gets the SQLite3 affinity of the given $value.
   * It maps:
   * <ul>
   * <li>Integers to INTEGER</li>
   * <li>Strings to TEXT</li>
   * <li>Null values to NULL</li>
   * </ul>
   * 
   * @param mixed $value The value to be analyzed.
   */
  private function getAffinity($value)
  {
    if ( is_int($value) )
    {
      return SQLITE3_INTEGER;
    }
    if ( is_string($value) )
    {
      return SQLITE3_TEXT;
    }
    if ( is_null($value) )
    {
      return SQLITE3_NULL;
    }
    throw new ErrorException(__METHOD__ . ': unsupported data type');
  }

}
