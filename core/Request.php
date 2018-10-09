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
 * Description of Request
 *
 * @author Daniele Ambrosino
 */
abstract class Request
{

  protected $id;
  protected $datetime;
  protected $user;

  public function __construct(User $user, int $id, string $datetime)
  {
    $this->id = $id;
    $this->datetime = $datetime;
    $this->user = $user;
  }
  
  public function getId()
  {
    return $this->id;
  }

  public function getDatetime()
  {
    return $this->datetime;
  }

}
