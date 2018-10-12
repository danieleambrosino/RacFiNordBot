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
  
  public function addClubMember(User $user)
  {
    $query = "INSERT INTO ClubMembers (id) VALUES (?)";
    $values = [$user->getId()];
    $this->db->query($query, $values);
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
  
  public function getAllBoardMembers(): array
  {
    $query = "SELECT * FROM Users WHERE id IN (SELECT id FROM ClubMembers WHERE roleId > 1)";
    $members = $this->db->query($query);
    $membersArray = [];
    foreach ($members as $user)
    {
      $membersArray[] = new User($user['id'], $user['firstName'],
                               $user['lastName'], $user['username']);
    }
    return $membersArray;
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
  
  public function getAllClubMembers(): array
  {
    $query = "SELECT * FROM Users WHERE id IN (SELECT id FROM ClubMembers)";
    $members = $this->db->query($query);
    $membersArray = [];
    foreach ($members as $user)
    {
      $membersArray[] = new User($user['id'], $user['firstName'],
                               $user['lastName'], $user['username']);
    }
    return $membersArray;
  }

  public function getPresident(): User
  {
    return $this->getMember('President');
  }

  public function getSecretary(): User
  {
    return $this->getMember('Secretary');
  }

  public function getSergeantAtArms(): User
  {
    return $this->getMember('Sergeant At Arms');
  }

  public function getTreasurer(): User
  {
    return $this->getMember('Treasurer');
  }

  public function getVicePresident(): User
  {
    return $this->getMember('Vice President');
  }

  public function setPresident(User $user)
  {
    $this->setMember($user, 'President');
  }

  public function setSecretary(User $user)
  {
    $this->setMember($user, 'Secretary');
  }

  public function setSergeantAtArms(User $user)
  {
    $this->setMember($user, 'Sergeant At Arms');
  }

  public function setTreasurer(User $user)
  {
    $this->setMember($user, 'Treasurer');
  }

  public function setVicePresident(User $user)
  {
    $this->setMember($user, 'Vice President');
  }

  public function updateUser(User $user)
  {
    $query = "UPDATE Users SET firstName = ?, lastName = ?, username = ? WHERE id = ?";
    $values = [$user->getFirstName(), $user->getLastName(), $user->getUsername(), $user->getId()];
    $this->db->query($query, $values);
  }
  
  public function removeClubMember(User $user)
  {
    $query = "DELETE FROM ClubMembers WHERE id = ?";
    $values = [$user->getId()];
    $this->db->query($query, $values);
  }
  
  protected function getMember(string $role): User
  {
    if ( !in_array($role,
                   ['Member', 'Counselor', 'Sergeant At Arms', 'Treasurer', 'Secretary', 'Vice President', 'President']) )
    {
      throw new ErrorException(__METHOD__ . ': Unexpected role');
    }
    $query = <<<SQL
SELECT U.*
FROM Users U
  JOIN ClubMembers CM ON U.id = CM.id
  JOIN Roles R ON CM.roleId = R.id
WHERE role = '$role'
SQL;
    $userData = $this->db->query($query);
    if ( empty($userData) )
    {
      throw new ResourceNotFoundException();
    }
    return new User($userData[0]['id'], $userData[0]['firstName'],
                    $userData[0]['lastName'], $userData[0]['username']);
  }

  protected function setMember(User $user, string $role)
  {
    if ( !in_array($role,
                   ['Member', 'Counselor', 'Sergeant At Arms', 'Treasurer', 'Secretary', 'Vice President', 'President']) )
    {
      throw new ErrorException(__METHOD__ . ': Unexpected role');
    }
    $query = "UPDATE ClubMembers SET roleId = 1 WHERE roleId = (SELECT id FROM Roles WHERE role = '$role')";
    $this->db->query($query);
    $query = "UPDATE ClubMembers SET roleId = (SELECT id FROM Roles WHERE role = '$role') WHERE id = {$user->getId()}";
    $this->db->query($query);
  }

}
