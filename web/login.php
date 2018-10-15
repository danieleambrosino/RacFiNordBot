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
require_once realpath(__DIR__ . '/templates/head.php');
?>
<title>Login</title>
<?php
if ( DEVELOPMENT )
{
  $params = [
    'id'         => 99881252,
    'first_name' => 'Daniele',
    'last_name'  => 'Ambrosino',
    'hash'       => 'tuttappost',
    'auth_date'  => time()
  ];
  ?>
  <a href="functions/checkLogin.php?<?= http_build_query($params) ?>" class="btn">Login</a>
  <?php
}
else
{
  ?>
  <script async src="https://telegram.org/js/telegram-widget.js?4" data-telegram-login="RacFiNordBot" data-size="large" data-auth-url="functions/checkLogin.php" data-request-access="write"></script>
  <?php
}