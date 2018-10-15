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

if ( !isset($_POST['president'], $_POST['vicePresident'], $_POST['secretary'],
            $_POST['treasurer'], $_POST['sergeantAtArms']) )
{
  exit;
}

$frequencies = array_count_values($_POST);
foreach ($frequencies as $frequency)
{
  if ( $frequency >= 2 )
  {
    exit('Non puoi assegnare più ruoli alla stessa persona. <a href="../editBoard.php">Riprova</a>');
  }
}
unset($frequencies);
unset($frequency);

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

$factory = DEVELOPMENT ? DevelopmentFactory::getInstance() : ProductionFactory::getInstance();
$userDao = $factory->createUserDao();

$president = $userDao->getUser($_POST['president']);
$vicePresident = $userDao->getUser($_POST['vicePresident']);
$secretary = $userDao->getUser($_POST['secretary']);
$treasurer = $userDao->getUser($_POST['treasurer']);
$sergeantAtArms = $userDao->getUser($_POST['sergeantAtArms']);

$userDao->setPresident($president);
$userDao->setVicePresident($vicePresident);
$userDao->setSecretary($secretary);
$userDao->setTreasurer($treasurer);
$userDao->setSergeantAtArms($sergeantAtArms);

header('Location: ../controlPanel.php');