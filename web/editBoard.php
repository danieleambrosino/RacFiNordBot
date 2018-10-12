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
$members = $userDao->getAllClubMembers();

$president = $userDao->getPresident();
$vicePresident = $userDao->getVicePresident();
$secretary = $userDao->getSecretary();
$treasurer = $userDao->getTreasurer();
$sergeantAtArms = $userDao->getSergeantAtArms();

require_once realpath(__DIR__ . '/templates/head.php');
require_once realpath(__DIR__ . '/templates/nav.php');
?>
<title>Modifica direttivo</title>
<div class="container">
  <h1>Modifica direttivo</h1>
  <form method="post" action="functions/editBoard.php">
    <button type="submit" class="btn">Salva</button>
    <table>
      <tbody>
        <tr>
          <th>
            Presidente
          </th>
          <td class="input-field">
            <select name="president">
              <?php
              foreach ($members as $user)
              {
                ?>
                <option value="<?= $user->getId() ?>" <?= $user->getId() === $president->getId() ? 'selected' : null ?>><?= $user ?></option>
                <?php
              }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <th>
            Vice Presidente
          </th>
          <td class="input-field">
            <select name="vicePresident">
              <?php
              foreach ($members as $user)
              {
                ?>
                <option value="<?= $user->getId() ?>" <?= $user->getId() === $vicePresident->getId() ? 'selected' : null ?>><?= $user ?></option>
                <?php
              }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <th>
            Segretario
          </th>
          <td class="input-field">
            <select name="secretary">
              <?php
              foreach ($members as $user)
              {
                ?>
                <option value="<?= $user->getId() ?>" <?= $user->getId() === $secretary->getId() ? 'selected' : null ?>><?= $user ?></option>
                <?php
              }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <th>
            Tesoriere
          </th>
          <td class="input-field">
            <select name="treasurer">
              <?php
              foreach ($members as $user)
              {
                ?>
                <option value="<?= $user->getId() ?>" <?= $user->getId() === $treasurer->getId() ? 'selected' : null ?>><?= $user ?></option>
                <?php
              }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <th>
            Prefetto
          </th>
          <td class="input-field">
            <select name="sergeantAtArms">
              <?php
              foreach ($members as $user)
              {
                ?>
                <option value="<?= $user->getId() ?>" <?= $user->getId() === $sergeantAtArms->getId() ? 'selected' : null ?>><?= $user ?></option>
                <?php
              }
              ?>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script>
  $(function ()
  {
    $('select').formSelect();
  });
</script>