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
?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<nav>
  <div class="nav-wrapper container">
    <a href="controlPanel.php"><img src="logo.png" class="brand-logo" style="height: 100%"></a>
    <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    <ul id="nav-mobile" class="right hide-on-med-and-down">
      <li><a href="controlPanel.php">Pannello di controllo</a></li>
      <li><a href="editMembers.php">Modifica soci</a></li>
      <li><a href="editBoard.php">Modifica direttivo</a></li>
    </ul>
  </div>
</nav>
<ul class="sidenav" id="mobile-demo">
  <li><a href="controlPanel.php">Pannello di controllo</a></li>
  <li><a href="editMembers.php">Modifica soci</a></li>
  <li><a href="editBoard.php">Modifica direttivo</a></li>
</ul>
<script>
  $(function ()
  {
    $('.sidenav').sidenav();
  });
</script>