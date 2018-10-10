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
 * Description of DevelopmentFactory
 *
 * @author Daniele Ambrosino
 */
class DevelopmentFactory extends Factory
{

  public function createCommunicator(int $chatId): Communicator
  {
    return new Echoer($chatId);
  }

  public function createRequestDao(): RequestDao
  {
    return new RequestDaoSqlite();
  }

  public function createResponseDao(): ResponseDao
  {
    return new ResponseDaoSqlite();
  }

  public function createUserDao(): UserDao
  {
    return new UserDaoSqlite();
  }

}
