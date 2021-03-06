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
if ( !isset($_GET['userId']) )
{
  exit;
}

$factory = DEVELOPMENT ? DevelopmentFactory::getInstance() : ProductionFactory::getInstance();

$userDao = $factory->createUserDao();
$requestDao = $factory->createRequestDao();

$user = $userDao->getUser($_GET['userId']);
$requests = $requestDao->getAllRequestsByUser($user);

require_once realpath(__DIR__ . '/templates/head.php');
require_once realpath(__DIR__ . '/templates/nav.php');
?>
<div class="container">
  <h1><?= $user ?></h1>
  <h2>Richieste</h2>
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
      foreach ($requests as $request)
      {
        ?>
        <tr>
          <td><a href="viewResponses.php?requestId=<?= $request->getId() ?>"><?= $request->getId() ?></a></td>
          <td><?= strftime('%e %B %Y alle %H:%M', strtotime($request->getDatetime())) ?></td>
          <td><?= $request->getContent() ?></td>
        </tr>
        <?php
      }
      ?>
    </tbody>
  </table>
</div>