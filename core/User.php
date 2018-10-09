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
 * Description of User
 *
 * @author Daniele Ambrosino
 */
class User
{

  protected $id;
  protected $firstName;
  protected $lastName;
  protected $username;

  public function __construct(int $id, string $firstName,
                              string $lastName = NULL, string $username = NULL)
  {
    $this->id = $id;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->username = $username;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getFirstName(): string
  {
    return $this->firstName;
  }

  public function getLastName()
  {
    return $this->lastName;
  }

  public function getUsername()
  {
    return $this->username;
  }

}
