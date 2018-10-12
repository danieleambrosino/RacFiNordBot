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

require_once realpath(__DIR__ . '/../vendor/autoload.php');
if ( !isset($_GET['requestId']) )
{
  exit;
}

$factory = DEVELOPMENT ? DevelopmentFactory::getInstance() : ProductionFactory::getInstance();

$requestDao = $factory->createRequestDao();
$responseDao = $factory->createResponseDao();

$request = $requestDao->getRequest($_GET['requestId']);
$responses = $responseDao->getAllResponsesByRequest($request);

require_once realpath(__DIR__ . '/templates/head.php');
require_once realpath(__DIR__ . '/templates/nav.php');
?>
<div class="container">
  <h1><?= $request ?></h1>
  <h2>Risposte</h2>
  <table id="requestsTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Data</th>
        <th>Contenuto</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($responses as $response)
      {
        ?>
        <tr>
          <td><?= $response->getId() ?></td>
          <td><?= $response->getDatetime() ?></td>
          <td><?= $response->getContent() ?></td>
        </tr>
        <?php
      }
      ?>
    </tbody>
  </table>
</div>