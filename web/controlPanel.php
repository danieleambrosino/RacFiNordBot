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

require_once realpath(__DIR__ . '/templates/head.php');
require_once realpath(__DIR__ . '/templates/nav.php');
?>
<title>Pannello di controllo</title>
<div class="container" style="overflow-x: auto">
  <h1>Pannello di controllo</h1>
  <h2>Utenti</h2>
  <table id="usersTable" class="hover">
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

  <h2>Soci</h2>
  <a href="editMembers.php" class="btn">Aggiungi/rimuovi soci</a>
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

  <h2>Direttivo</h2>
  <a href="editBoard.php" class="btn">Modifica direttivo</a>
  <table>
    <tbody>
      <tr>
        <th>
          Presidente
        </th>
        <td>
          <?= $userDao->getPresident() ?>
        </td>
      </tr>
      <tr>
        <th>
          Vice Presidente
        </th>
        <td>
          <?= $userDao->getVicePresident() ?>
        </td>
      </tr>
      <tr>
        <th>
          Segretario
        </th>
        <td>
          <?= $userDao->getSecretary() ?>
        </td>
      </tr>
      <tr>
        <th>
          Tesoriere
        </th>
        <td>
          <?= $userDao->getTreasurer() ?>
        </td>
      </tr>
      <tr>
        <th>
          Prefetto
        </th>
        <td>
          <?= $userDao->getSergeantAtArms() ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<script>
  $(function(){
    $('#usersTable').DataTable();
    $('#membersTable').DataTable();
    $('select').formSelect();
  });
</script>