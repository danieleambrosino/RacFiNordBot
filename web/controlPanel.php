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
$factory = DEVELOPMENT ? DevelopmentFactory::getInstance() : ProductionFactory::getInstance();
$userDao = $factory->createUserDao();
$users = $userDao->getAllUsers();
$members = $userDao->getAllClubMembers();
?>
<h1>Pannello di controllo</h1>
<h2>Utenti</h2>
<table id="usersTable">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Cognome</th>
      <th>Username</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($users as $user)
    {
      ?>
    <tr>
      <td><a href="viewRequests.php?userId=<?= $user->getId() ?>"><?= $user->getId() ?></a></td>
      <td><?= $user->getFirstName() ?></td>
      <td><?= $user->getLastName() ?></td>
      <td><?= $user->getUsername() ?></td>
    </tr>
      <?php
    }
    ?>
  </tbody>
</table>

<h2>Soci</h2><button>Aggiungi soci</button>
<table id="membersTable">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Cognome</th>
      <th>Username</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($members as $user)
    {
      ?>
    <tr>
      <td><?= $user->getId() ?></td>
      <td><?= $user->getFirstName() ?></td>
      <td><?= $user->getLastName() ?></td>
      <td><?= $user->getUsername() ?></td>
    </tr>
      <?php
    }
    ?>
  </tbody>
</table>

<h2>Direttivo</h2>
Presidente: <?= $userDao->getPresident() ?><br>
Vice Presidente: <?= $userDao->getVicePresident() ?><br>
Segretario: <?= $userDao->getSecretary() ?><br>
Tesoriere: <?= $userDao->getTreasurer() ?><br>
Prefetto: <?= $userDao->getSergeantAtArms() ?><br>