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
 *
 * @author Daniele Ambrosino
 */
abstract class ResponseDao
{

  /**
   *
   * @var Database
   */
  protected $db;

  public abstract function __construct();

  public abstract function createResponse(Response $response);

  public abstract function getResponse(int $id): Response;

  public abstract function updateResponse(Response $response);

  public abstract function deleteResponse(Response $response);
}
