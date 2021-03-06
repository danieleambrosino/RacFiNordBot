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
abstract class ResponseDao
{

  /**
   *
   * @var Database
   */
  protected $db;

  public abstract function __construct();

  public function createResponse(Response $response)
  {
    $query = "INSERT INTO Responses (id, datetime, requestId, content) VALUES (?, ?, ?, ?)";
    $values = [$response->getId(), $response->getDatetime(), $response->getRequest()->getId(), $response->getContent()];
    $this->db->query($query, $values);
  }

  public function deleteResponse(Response $response)
  {
    $query = "DELETE FROM Responses WHERE id = ?";
    $values = [$response->getId()];
    $this->db->query($query, $values);
  }

  public function getResponse(int $id): Response
  {
    $query = "SELECT * FROM Responses WHERE id = ?";
    $values = [$id];
    return $this->db->query($query, $values);
  }

  public function getAllResponsesByRequest(Request $request): array
  {
    $query = "SELECT * FROM Responses WHERE requestId = ?";
    $values = [$request->getId()];
    $responses = $this->db->query($query, $values);
    $responsesArray = [];
    foreach ($responses as $response)
    {
      $responsesArray[] = new TextResponse($response['content'], $request,
                                           $response['id'],
                                           $response['datetime']);
    }
    return $responsesArray;
  }

  public function updateResponse(Response $response)
  {
    $query = "UPDATE Responses SET datetime = ?, requestId = ?, content = ? WHERE id = ?";
    $values = [$response->getDatetime(), $response->getRequest()->getId(), $response->getContent(), $response->getId()];
    $this->db->query($query, $values);
  }

}
