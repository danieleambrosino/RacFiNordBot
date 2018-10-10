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
 * Description of DatabaseMysql
 *
 * @author Daniele Ambrosino
 */
class DatabaseMysql extends Database
{
  protected function __construct()
  {
    $this->handle = new mysqli(
         DATABASE_MYSQL_HOST,
         DATABASE_MYSQL_USERNAME,
         DATABASE_MYSQL_PASSWORD,
         DATABASE_MYSQL_DBNAME);
  }

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

  protected function fetchAll($results): array
  {
    return $result->fetch_all(MYSQLI_ASSOC);
  }
  
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
