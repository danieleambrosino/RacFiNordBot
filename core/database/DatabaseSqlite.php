<?php

/*
 * This file is part of RacFiNordBot,
 * the official Telegram Bot of the Rotaract Club Firenze Nord.
 * 
 * (c) 2018 Rotaract Club Firenze Nord <rotaractfirenzenord@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */
require_once realpath(__DIR__ . '/../../vendor/autoload.php');
/**
 * Description of DatabaseSqlite
 *
 * @author Daniele Ambrosino
 */
class DatabaseSqlite extends Database
{

  protected function __construct()
  {
    $this->handle = new SQLite3(DATABASE_SQLITE_PATH);
  }

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

  protected function fetchAll($result): array
  {
    if ( !($result instanceof SQLite3Result) )
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
