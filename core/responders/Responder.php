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
 * Description of Responder
 *
 * @author Daniele Ambrosino
 */
abstract class Responder
{

  /**
   *
   * @var Request
   */
  protected $request;

  /**
   *
   * @var array
   */
  protected $responses;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function getResponses()
  {
    return $this->responses;
  }

  abstract public function run();
}
