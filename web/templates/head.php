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
if ( !isset($_SESSION['userId']) && substr($_SERVER['SCRIPT_NAME'], -9) !== 'login.php' )
{
  exit('Unauthorized');
}
require_once realpath(__DIR__ . '/../../vendor/autoload.php');
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="<?= JQUERY_URL ?>"></script>

  <link rel="stylesheet" href="<?= MATERIALIZE_CSS_URL ?>">
  <script src="<?= MATERIALIZE_JS_URL ?>"></script>

  <link rel="stylesheet" href="<?= DATATABLES_CSS_URL ?>">
  <script src="<?= DATATABLES_JS_URL ?>"></script>

  <link rel="icon" href="logo.png" type="image/png">
</head>
<style>
  body {
    user-select: none
  }
</style>