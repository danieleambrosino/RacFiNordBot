<?php

/*
 * This file is part of the RacFiNordBot package.
 * 
 * (c) 2018 Rotaract Club Firenze Nord <rotaractfirenzenord@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

require_once realpath(__DIR__ . '/vendor/autoload.php');

$update = file_get_contents('php://input');

if ( empty($update) )
{
  exit;
}

if ( DEVELOPMENT )
{
  $bot = new Bot($update);

  $observer = new Echoer();
  $bot->attach($observer);

  http_response_code(202);
  
  $bot->evaluate();
}
else
{
  try
  {
    $bot = new Bot($update);

    $observer = new Sender();
    $bot->attach($observer);

    http_response_code(202);

    $bot->evaluate();
  } catch (Exception $ex)
  {
    $errorMsg = date(FORMAT_DATETIME_DATABASE) . " => {$ex->getMessage()} ({$ex->getFile()} at line {$ex->getCode()})\n{$ex->getTraceAsString()}\n";
    file_put_contents(LOG_FILE_PATH, $errorMsg, FILE_APPEND);
  }
}
