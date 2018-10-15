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

session_start();
if ( !isset($_SESSION['userId']) )
{
  exit;
}

if ( !isset($_POST['addressee'], $_POST['message']) )
{
  exit;
}

if ( !in_array($_POST['addressee'], ['everyone', 'members', 'board']) )
{
  exit;
}

$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

$factory = DEVELOPMENT ? DevelopmentFactory::getInstance() : ProductionFactory::getInstance();
$userDao = $factory->createUserDao();

switch ($_POST['addressee'])
{
  case 'everyone':
    $users = $userDao->getAllUsers();
    break;
  case 'members':
    $users = $userDao->getAllClubMembers();
    break;
  case 'board':
    $users = $userDao->getAllBoardMembers();
    break;
  default:
    exit;
}

$communicator = $factory->createCommunicator(0);

foreach ($users as $user)
{
  $communicator->setChatId($user->getId());
  $communicator->sendMessage($message);
}
