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

require_once './templates/head.php';
require_once './templates/nav.php';
?>
<link rel="stylesheet" href="<?= SIMPLEMDE_CSS_URL ?>">
<script src="<?= SIMPLEMDE_JS_URL ?>"></script>
<style>
  .CodeMirror {
    min-height: 50px;
    max-height: 300px
  }
</style>
<title>Spamma messaggio</title>
<div class="container">
  <h1>Spamma messaggio</h1>
  <p>
    Spamma a
    <select id="addresseeSelect">
      <option value="everyone">tutti gli utenti</option>
      <option value="members">soci</option>
      <option value="board">membri del direttivo</option>
    </select>
  </p>
  <textarea></textarea>
  <button id="sendButton" class="btn right">Invia</button>
</div>
<script>
  $(function ()
  {
    let textarea = new SimpleMDE({
      blockStyles: {
        bold: '**',
        italic: '_'
      },
      toolbar: [
        'bold',
        'italic',
        'code',
        'link'
      ],
      spellChecker: false
    });
    $('select').formSelect();
    
    $('#sendButton').click(function()
    {
      $.post(
        'functions/spamMessage.php',
        {
          addressee: $('#addresseeSelect').prop('value'),
          message: textarea.value().replace('**', '*')
        },
        function(){
          alert('Messaggio inviato!');
          location.assign('controlPanel.php');
        });
    });
  });
</script>