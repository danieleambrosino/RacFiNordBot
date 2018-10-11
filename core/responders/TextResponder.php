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
 * Description of TextResponder
 *
 * @author Daniele Ambrosino
 */
class TextResponder extends Responder
{

  private $requestText;

  public function __construct(TextRequest $request)
  {
    parent::__construct($request);
    $this->requestText = $request->getContent();
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
      $this->responses[] = new TextResponse("Ciao {$this->request->getUser()->getFirstName()}, benvenuto!\n" . file_get_contents(RES_DIR . '/info.md'), $this->request);
    }
    elseif ( $text === 'info' )
    {
      $this->responses[] = new TextResponse(file_get_contents(RES_DIR . '/info.md'), $this->request);
    }
    elseif ( $text === 'prossimo_evento' )
    {
      $this->fetchEvents(1);
    }
    elseif ( $text === 'prossimi_eventi' )
    {
      $this->fetchEvents(3);
    }
    elseif ( $text === 'quote_annuali' )
    {
      $this->responses[] = new TextResponse(file_get_contents(RES_DIR . '/paymentInfo.md'), $this->request);
    }
    else
    {
      $this->responses[] = new TextResponse('Mi dispiace, non so come aiutarti!', $this->request);
    }
  }

  private function evaluatePhrase()
  {
    if ( preg_match('/prossimo\s+evento/i', $this->requestText) )
    {
      $this->fetchEvents(1);
    }
    elseif ( preg_match('/prossimi\s+eventi/i', $this->requestText) )
    {
      $this->fetchEvents(3);
    }
    elseif ( preg_match('/prossimi\s+(\d{1,2})\s+eventi/i', $this->requestText,
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
    elseif ( preg_match('/^ciao/i', $this->requestText) )
    {
      $this->responses[] = new TextResponse('Mi associo ai saluti precedentemente fatti',
                                            $this->request);
    }
    elseif ( preg_match('/^calendario/i', $this->requestText) )
    {
      $this->handleCalendarAccessRequest();
    }
    else
    {
      $this->responses[] = new TextResponse('Mi dispiace, non so come aiutarti!',
                                            $this->request);
    }
  }

  private function fetchEvents(int $max)
  {
    $events = EventFetcher::getNextEvents($max);
    if ( empty($events) )
    {
      $this->responses[] = new TextResponse('Non ci sono eventi in programma', $this->request);
    }
    foreach ($events as $event)
    {
      $this->responses[] = new TextResponse($event, $this->request);
    }
  }
  
  private function handleCalendarAccessRequest()
  {
    $pattern = substr(REGEX_EMAIL, 0, 2) . 'calendario\s+(' . substr(REGEX_EMAIL, 2, -3) . ')' . substr(REGEX_EMAIL, -3);
      if ( preg_match($pattern, $this->requestText, $matches) )
      {
        $emailAddress = filter_var($matches[1], FILTER_VALIDATE_EMAIL);
        $message = <<<TXT
Caro segretario, {$this->request->getUser()->getFirstName()} ha richiesto l'autorizzazione ad accedere al calendario del club.

Per proseguire, aggiungi l'indirizzo $emailAddress alla lista di condivisione del calendario seguendo [questo link](https://calendar.google.com/calendar/r/settings/calendar/cm90YXJhY3RmaXJlbnplbm9yZEBnbWFpbC5jb20) dall'account Google del club.

Per ulteriori istruzioni, consulta [questo link](https://support.google.com/calendar/answer/37082?hl=it)
TXT;
        $factory = DEVELOPMENT ? DevelopmentFactory::getInstance() : ProductionFactory::getInstance();
        $secretary = $factory->createUserDao()->getSecretary();
        $factory->createCommunicator($secretary->getId())->sendMessage($message);
        $this->responses[] = new TextResponse('Ho inoltrato la tua richiesta al segretario del club, presto riceverai una risposta!',
                                              $this->request);
      }
  }

}
