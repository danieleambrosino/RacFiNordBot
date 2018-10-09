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

/**
 * Description of Database
 *
 * @author Daniele Ambrosino
 */
abstract class Database
{

  protected $handle;
  protected static $instance;

  protected abstract function __construct();

  public static function getInstance(): Database
  {
    if ( empty(static::$instance) )
    {
      static::$instance = new static();
    }
    return static::$instance;
  }

  public final function query(string $query, array $values = NULL): array
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
      $results = $this->fetchAll($result);
    }
    return $results;
  }

  protected abstract function bind(string $query, array $values);

  protected abstract function fetchAll($results): array;
}
