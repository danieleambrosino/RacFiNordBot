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

/**
 * Description of TextRequest
 *
 * @author Daniele Ambrosino
 */
class TextRequest extends Request
{
  private $text;
  
  public function __construct(int $id, string $datetime, User $user, string $text)
  {
    parent::__construct($id, $datetime, $user);
    $this->text = $text;
  }
  
  public function getText()
  {
    return $this->text;
  }


}
