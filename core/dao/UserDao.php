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
  
  public abstract function addClubMember(User $user);

  public abstract function createUser(User $user);

  public abstract function getUser(int $id): User;

  public abstract function getAllUsers(): array;

  public abstract function getAllClubMembers(): array;
  
  public abstract function getAllBoardMembers(): array;

  public abstract function getPresident(): User;

  public abstract function getVicePresident(): User;

  public abstract function getSecretary(): User;

  public abstract function getTreasurer(): User;

  public abstract function getSergeantAtArms(): User;

  public abstract function setPresident(User $user);

  public abstract function setVicePresident(User $user);

  public abstract function setSecretary(User $user);

  public abstract function setTreasurer(User $user);

  public abstract function setSergeantAtArms(User $user);

  public abstract function updateUser(User $user);

  public abstract function deleteUser(User $user);
  
  protected abstract function getMember(string $role): User;
  
  protected abstract function setMember(User $user, string $role);
}
