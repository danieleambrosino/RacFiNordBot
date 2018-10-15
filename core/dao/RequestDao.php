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
 *
 * @author Daniele Ambrosino
 */
abstract class RequestDao
{

  /**
   *
   * @var Database
   */
  protected $db;

  public abstract function __construct();

  public function createRequest(Request $request)
  {
    $query = "INSERT INTO Requests (id, datetime, userId, content) VALUES (?, ?, ?, ?)";
    $values = [$request->getId(), $request->getDatetime(), $request->getUser()->getId(), $request->getContent()];
    $this->db->query($query, $values);
  }

  public function deleteRequest(Request $request)
  {
    $query = "DELETE FROM Requests WHERE id = ?";
    $values = [$request->getId()];
    $this->db->query($query, $values);
  }

  public function getRequest(int $id): Request
  {
    $query = "SELECT * FROM Requests WHERE id = ?";
    $values = [$id];
    $requestData = $this->db->query($query, $values);
    if ( empty($requestData) )
    {
      throw new ResourceNotFoundException();
    }
    $factory = DEVELOPMENT ? DevelopmentFactory::getInstance() : ProductionFactory::getInstance();
    $userDao = $factory->createUserDao();
    $user = $userDao->getUser($requestData[0]['userId']);
    return new TextRequest($requestData[0]['content'], $user,
                           $requestData[0]['id'], $requestData[0]['datetime']);
  }

  public function getAllRequestsByUser(User $user): array
  {
    $query = "SELECT * FROM Requests WHERE userId = ?";
    $values = [$user->getId()];
    $requests = $this->db->query($query, $values);
    $requestsArray = [];
    foreach ($requests as $request)
    {
      $requestsArray[] = new TextRequest($request['content'], $user,
                                         $request['id'], $request['datetime']);
    }
    return $requestsArray;
  }

  public function updateRequest(Request $request)
  {
    $query = "UPDATE Requests SET datetime = ?, userId = ?, content = ? WHERE id = ?";
    $values = [$request->getDatetime(), $request->getUser()->getId(), $request->getContent(), $request->getId()];
    $this->db->query($query, $values);
  }

}
