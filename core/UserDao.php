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
 * Description of UserDao
 *
 * @author Daniele Ambrosino
 */
abstract class UserDao
{

  /**
   *
   * @var Database
   */
  protected $db;
  
  public abstract function __construct();

  public abstract function createUser(User $user);

  public abstract function getUser(int $id): User;

  public abstract function getAllUsers(): array;

  public abstract function updateUser(User $user);

  public abstract function deleteUser(User $user);
}
