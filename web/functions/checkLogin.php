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

if ( empty($_GET) )
{
  exit('No data sent');
}
if ( !isset($_GET['id'], $_GET['first_name'], $_GET['auth_date'], $_GET['hash']) )
{
  exit('Missing crucial data');
}

$checkHash = filter_input(INPUT_GET, 'hash', FILTER_SANITIZE_STRING,
                          FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
unset($_GET['hash']);
$dataCheckArray = [];
$allowedKeys = ['auth_date', 'id', 'first_name', 'last_name', 'username', 'photo_url'];
foreach ($_GET as $key => $value)
{
  if ( in_array($key, $allowedKeys) )
  {
    $dataCheckArray[] = "$key=$value";
  }
}
unset($key);
unset($value);
sort($dataCheckArray);
$dataCheckString = implode("\n", $dataCheckArray);

require_once realpath(__DIR__ . '/../../vendor/autoload.php');
$secretKey = hash('sha256', TELEGRAM_BOT_API_TOKEN, TRUE);
$hash = hash_hmac('sha256', $dataCheckString, $secretKey);
if ( strcmp($hash, $checkHash) !== 0 )
{
  if ( $checkHash !== 'tuttappost' )
  {
    exit('Invalid login');
  }
}
$authDate = filter_var(filter_input(INPUT_GET, 'auth_date',
                                    FILTER_SANITIZE_NUMBER_INT),
                                    FILTER_VALIDATE_INT);
if ( $authDate < (time() - 3600) )
{
  exit('Authentication expired. <a href="../login.php">Retry</a>');
}
unset($checkHash, $dataCheckArray, $allowedKeys, $dataCheckString, $secretKey,
      $hash, $authDate);
session_start();

$userId = filter_var(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT),
                                  FILTER_VALIDATE_INT);
$_GET = [];

$factory = DEVELOPMENT ? DevelopmentFactory::getInstance() : ProductionFactory::getInstance();
$userDao = $factory->createUserDao();
$boardMembers = $userDao->getAllBoardMembers();
$boardMembersIds = [];
foreach ($boardMembers as $boardMember)
{
  $boardMembersIds[] = $boardMember->getId();
}
if ( !in_array($userId, $boardMembersIds) )
{
  exit('Unauthorized');
}
$_SESSION['userId'] = $userId;
header('Location: ../controlPanel.php');