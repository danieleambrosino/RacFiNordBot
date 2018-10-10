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
require_once realpath(__DIR__ . '/../vendor/autoload.php');

/**
 * Description of Factory
 *
 * @author Daniele Ambrosino
 */
abstract class Factory
{

  protected static $instance;

  protected function __construct()
  {
    // tadà
  }

  public static function getInstance()
  {
    if ( empty(static::$instance) )
    {
      static::$instance = new static();
    }
    return static::$instance;
  }

  public abstract function createCommunicator(int $chatId): Communicator;

  public abstract function createUserDao(): UserDao;

  public abstract function createRequestDao(): RequestDao;

  public abstract function createResponseDao(): ResponseDao;
}
