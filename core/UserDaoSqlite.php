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
    return $this->db->query($query);
  }

  public function getUser(int $id): User
  {
    $query = "SELECT * FROM Users WHERE id = ?";
    $values = [$id];
    $userData = $this->db->query($query, $values);
    return new User($userData['id'], $userData['firstName'], $userData['lastName'], $userData['username']);
  }

  public function updateUser(User $user)
  {
    $query = "UPDATE Users SET firstName = ?, lastName = ?, username = ? WHERE id = ?";
    $values = [$user->getFirstName(), $user->getLastName(), $user->getUsername(), $user->getId()];
    $this->db->query($query, $values);
  }

}
