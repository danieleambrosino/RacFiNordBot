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

http_response_code(202);
if ( DEVELOPMENT )
{
  $controller = new Controller($update);
  $controller->run();
}
else
{
  try
  {
    $controller = new Controller($update);
    $controller->run();
  }
  catch (Exception $ex)
  {
    $errorMsg = date(FORMAT_DATETIME_DATABASE) . " => {$ex->getMessage()} ({$ex->getFile()} at line {$ex->getCode()})\n{$ex->getTraceAsString()}\n";
    file_put_contents(LOG_FILE_PATH, $errorMsg, FILE_APPEND);
    mail(LOG_EMAIL_ADDRESS, 'RacFiNordBot: error log', $errorMsg);
  }
}
