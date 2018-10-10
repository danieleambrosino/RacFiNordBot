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
 * Description of UserDaoSqlite
 *
 * @author Daniele Ambrosino
 */
class UserDaoSqlite extends UserDao
{

  public function __construct()
  {
    $this->db = DatabaseSqlite::getInstance();
  }

  public function createUser(User $user)
  {
    $query = "INSERT INTO Users (id, firstName, lastName, username) VALUES (?, ?, ?, ?)";
    $values = [$user->getId(), $user->getFirstName(), $user->getLastName(), $user->getUsername()];
    $this->db->query($query, $values);
  }

  public function deleteUser(User $user)
  {
    $query = "DELETE FROM Users WHERE id = ?";
    $values = [$user->getId()];
    $this->db->query($query, $values);
  }

  public function getAllUsers(): array
  {
    $query = "SELECT * FROM Users";
    $users = $this->db->query($query);
    $usersArray = [];
    foreach ($users as $user)
    {
      $usersArray[] = new User($user['id'], $user['firstName'],
                               $user['lastName'], $user['username']);
    }
    return $usersArray;
  }

  public function getUser(int $id): User
  {
    $query = "SELECT * FROM Users WHERE id = ?";
    $values = [$id];
    $userData = $this->db->query($query, $values);
    if ( empty($userData) )
    {
      throw new ResourceNotFoundException();
    }
    return new User($userData[0]['id'], $userData[0]['firstName'],
                    $userData[0]['lastName'], $userData[0]['username']);
  }

  public function updateUser(User $user)
  {
    $query = "UPDATE Users SET firstName = ?, lastName = ?, username = ? WHERE id = ?";
    $values = [$user->getFirstName(), $user->getLastName(), $user->getUsername(), $user->getId()];
    $this->db->query($query, $values);
  }

}
