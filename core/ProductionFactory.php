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
 * Description of ProductionFactory
 *
 * @author Daniele Ambrosino
 */
class ProductionFactory extends Factory
{

  public function createCommunicator(int $chatId): Communicator
  {
    return new Sender($chatId);
  }

  public function createRequestDao(): RequestDao
  {
    return new RequestDaoMysql();
  }

  public function createResponseDao(): ResponseDao
  {
    return new ResponseDaoMysql();
  }

  public function createUserDao(): UserDao
  {
    return new UserDaoMysql();
  }

}
