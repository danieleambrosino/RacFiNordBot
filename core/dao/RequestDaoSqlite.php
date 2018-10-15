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
 * Description of RequestDaoSqlite
 *
 * @author Daniele Ambrosino
 */
class RequestDaoSqlite extends RequestDao
{

  public function __construct()
  {
    $this->db = DatabaseSqlite::getInstance();
  }

}
