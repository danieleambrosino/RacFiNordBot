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
 * Implementation of Database class for MySQL DBMS.
 * 
 * @author Daniele Ambrosino <mail@danieleambrosino.it>
 */
class DatabaseMySQL extends Database
{

  protected function __construct()
  {
    $this->handle = new mysqli(
         DATABASE_MYSQL_HOST,
         DATABASE_MYSQL_USERNAME,
         DATABASE_MYSQL_PASSWORD,
         DATABASE_MYSQL_DBNAME);
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
    $query = 'INSERT IGNORE INTO Users (id, firstName, lastName, username) VALUES (?, ?, ?, ?)';
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
   * Saves a response
   * 
   * @param int $id Message's Telegram ID
   * @param string $datetime Message's date and time
   * @param int $requestId Request's ID
   * @param string $text Text of the response
   */
  public function saveResponse(int $id, string $datetime, int $requestId, string $text)
  {
    $query = 'INSERT INTO Requests (id, datetime, userId, text) VALUES (?, ?, ?, ?)';
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
   */
  protected function bind(string $query, array $values)
  {
    if ( !($stmt = $this->handle->prepare($query)) )
    {
      throw new ErrorException(__METHOD__ . ': unable to prepare statement');
    }

    $paramCount = $stmt->param_count;
    if ( $paramCount !== count($values) )
    {
      throw new ErrorException(__METHOD__ . ': parameters count mismatch');
    }

    $typesString = $this->getTypesString($values);
    $arguments[] = &$typesString;
    for ($i = 0; $i < $paramCount; ++$i)
    {
      $arguments[] = &$values[$i];
    }

    if ( FALSE === call_user_func_array([$stmt, 'bind_param'], $arguments) )
    {
      throw new ErrorException(__METHOD__ . ': unable to bind parameters');
    }

    return $stmt;
  }

  /**
   * Fetches results from a database result set.
   * 
   * @param mysqli_result $result
   */
  protected function fetchResults($result): array
  {
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  /**
   * Get the types string of the given values
   * 
   * @param array $values
   * @return string
   * @throws ErrorException
   */
  private function getTypesString(array $values)
  {
    $typesString = '';
    foreach ($values as $value)
    {
      if ( is_int($value) )
      {
        $typesString .= 'i';
      }
      elseif ( is_string($value) )
      {
        $typesString .= 's';
      }
      elseif ( is_null($value) )
      {
        $typesString .= 'b';
      }
      else
      {
        throw new ErrorException(__METHOD__ . ': unsupported data type');
      }
    }
    return $typesString;
  }

}
