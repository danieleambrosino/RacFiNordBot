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
require_once realpath(__DIR__ . '/../../vendor/autoload.php');

/**
 * Description of TextRequest
 *
 * @author Daniele Ambrosino
 */
class TextRequest extends Request
{

  private $text;

  public function __construct(string $text, User $user, int $id,
                              string $datetime)
  {
    parent::__construct($user, $id, $datetime);
    $this->text = $text;
  }

  public function getContent(): string
  {
    return $this->text;
  }

}
