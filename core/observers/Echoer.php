<?php

/*
 * This file is part of the RacFiNordBot package.
 * 
 * (c) 2018 Daniele Ambrosino <mail@danieleambrosino.it>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

/**
 * Class that echoes bot's responses.
 * 
 * @author Daniele Ambrosino <mail@danieleambrosino.it>
 */
class Echoer extends Communicator
{

  /**
   * @param string text
   */
  protected function sendMessage(string $text)
  {
    echo $text . PHP_EOL;
  }

  /**
   * 
   */
  protected function sendIsTyping()
  {
    echo 'The bot is computing your answer, please wait' . PHP_EOL;
  }

}
