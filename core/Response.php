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
 * Description of Response
 *
 * @author Daniele Ambrosino
 */
abstract class Response
{

  protected $id;
  protected $datetime;
  protected $request;

  public function __construct(Request $request, int $id = NULL,
                              string $datetime = NULL)
  {
    $this->request = $request;
    $this->id = $id;
    $this->datetime = $datetime;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getDatetime()
  {
    return $this->datetime;
  }

  public function setId(int $id)
  {
    $this->id = $id;
  }

  public function setDatetime(string $datetime)
  {
    // TODO implement
    $this->datetime = $datetime;
  }

  public abstract function getContent();
}
