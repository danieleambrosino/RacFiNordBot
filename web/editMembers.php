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
<title>Modifica soci</title>
<div class="container">
  <h1>Modifica soci</h1>
  <button id="saveButton" class="btn">Salva modifiche</button>
  <table>
    <thead>
      <tr>
        <th>Socio?</th>
        <th>ID</th>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Nome utente</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($users as $user)
      {
        ?>
        <tr data-user-id="<?= $user->getId() ?>">
          <td><label><input type="checkbox" <?= in_array($user, $members) ? 'checked' : NULL ?>><span></span></label></td>
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
</div>
<script>
  $(function ()
  {
    $('#saveButton').click(function ()
    {
      let memberIds = [];
      $(':checked').each(function ()
      {
        memberIds.push($(this).parents('tr').attr('data-user-id'));
      });
      $.post(
              'functions/editMembers.php',
              {memberIds: memberIds},
              function ()
              {
                location.assign('controlPanel.php');
              });
    });
  });
</script>