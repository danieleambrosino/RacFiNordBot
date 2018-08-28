<?php

/*
 * This file is part of the RacFiNordBot package.
 * 
 * (c) 2018 Daniele Ambrosino <mail@danieleambrosino.it>
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

$bot = new Bot($update);

$observer = DEVELOPMENT ? new Echoer() : new Sender();
$bot->attach($observer);

http_response_code(202);

if ( DEVELOPMENT )
{
  $bot->evaluate();
}
else
{
  try
  {
    $bot->evaluate();
  } catch (Exception $ex)
  {
    $errorMsg = date(FORMAT_DATETIME_DATABASE) . " => {$ex->getMessage()} ({$ex->getFile()} at line {$ex->getCode()})\n{$ex->getTraceAsString()}\n";
    file_put_contents(LOG_FILE_PATH, $errorMsg, FILE_APPEND);
  }
}
