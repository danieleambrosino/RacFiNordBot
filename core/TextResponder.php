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
 * Description of TextResponder
 *
 * @author Daniele Ambrosino
 */
class TextResponder extends Responder
{

  private $requestText;

  public function __construct(TextRequest $request)
  {
    if ( !($this->request instanceof TextRequest) )
    {
      throw new UnsupportedRequestException('Expected TextRequest, ' . $this->request::class . ' given');
    }
    parent::__construct($request);
    $this->requestText = $request->getText();
  }

  public function run()
  {
    if ( substr($this->requestText, 0, 1) === '/' )
    {
      $this->evaluateCommand();
    }
    else
    {
      $this->evaluatePhrase();
    }
  }

  private function evaluateCommand()
  {
    if ( substr($this->requestText, 0, 1) !== '/' )
    {
      throw new ErrorException(__METHOD__ . ': not a command');
    }
    $text = substr($this->requestText, 1);
    if ( $text === 'start' )
    {
      $this->responses[] = "Ciao $this->userFirstName, benvenuto!\n" . file_get_contents(RES_DIR . '/info.md');
    }
    elseif ( $text === 'info' )
    {
      $this->responses[] = file_get_contents(RES_DIR . '/info.md');
    }
    elseif ( $text === 'prossimo_evento' )
    {
      $this->fetchEvents(1);
    }
    elseif ( $this->requestText === 'prossimi_eventi' )
    {
      $this->fetchEvents(3);
    }
    elseif ( $this->requestText === 'quote_annuali' )
    {
      $this->responses[] = new TextResponse(file_get_contents(RES_DIR . '/paymentInfo.md'));
    }
  }

  private function evaluatePhrase()
  {
    if ( preg_match('/prossimo\s+evento/i', $this->request) )
    {
      $this->fetchEvents(1);
    }
    elseif ( preg_match('/prossimi\s+eventi/i', $this->request) )
    {
      $this->fetchEvents(3);
    }
    elseif ( preg_match('/prossimi\s+(\d{1,2})\s+eventi/i', $this->request,
                        $matches) )
    {
      $maxEvents = filter_var($matches[1], FILTER_VALIDATE_INT);
      if ( $maxEvents === 0 )
      {
        $maxEvents = 1;
      }
      elseif ( $maxEvents > 10 )
      {
        $maxEvents = 10;
      }
      $this->fetchEvents($maxEvents);
    }
  }

  private function fetchEvents(int $max)
  {
    $events = EventFetcher::getNextEvents($max);
    if ( empty($events) )
    {
      $this->responses[] = new TextResponse('Non ci sono eventi in programma');
    }
    foreach ($events as $event)
    {
      $this->responses[] = new TextResponse($event);
    }
  }

}
