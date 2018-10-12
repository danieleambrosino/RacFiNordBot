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

if ( !isset($_POST['memberIds']) )
{
  exit;
}

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

$factory = DEVELOPMENT ? DevelopmentFactory::getInstance() : ProductionFactory::getInstance();
$userDao = $factory->createUserDao();
$oldMembers = $userDao->getAllClubMembers();

$newMembers = [];
foreach ($_POST['memberIds'] as $userId)
{
  $newMembers[] = $userDao->getUser($userId);
}

$membersToAdd = [];
foreach ($newMembers as $newMember)
{
  if ( !in_array($newMember, $oldMembers) )
  {
    $membersToAdd[] = $newMember;
  }
}
$membersToRemove = [];
foreach ($oldMembers as $oldMember)
{
  if ( !in_array($oldMember, $newMembers) )
  {
    $membersToRemove[] = $oldMember;
  }
}

foreach ($membersToAdd as $memberToAdd)
{
  $userDao->addClubMember($memberToAdd);
}
foreach ($membersToRemove as $memberToRemove)
{
  $userDao->removeClubMember($memberToRemove);
}