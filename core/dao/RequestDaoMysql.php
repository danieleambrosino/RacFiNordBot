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
 * Description of RequestDaoMysql
 *
 * @author Daniele Ambrosino
 */
class RequestDaoMysql extends RequestDao
{

  public function __construct()
  {
    $this->db = DatabaseMysql::getInstance();
  }

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
    return $this->db->query($query, $values);
  }

  public function updateRequest(Request $request)
  {
    $query = "UPDATE Requests SET datetime = ?, userId = ?, content = ? WHERE id = ?";
    $values = [$request->getDatetime(), $request->getUser()->getId(), $request->getContent(), $request->getId()];
    $this->db->query($query, $values);
  }

}
