<?php

/*
 * This file is part of the RacFiNordBot package.
 * 
 * (c) 2018 Daniele Ambrosino <mail@danieleambrosino.it>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

require_once realpath(__DIR__ . '/../vendor/autoload.php');

/**
 * Computational engine
 *
 * @author Daniele Ambrosino <mail@danieleambrosino.it>
 */
class Bot implements SplSubject
{

  /**
   * @var string
   */
  private $request;

  /**
   * @var array
   */
  private $responses;

  /**
   * @var int
   */
  private $chatId;

  /**
   * @var int
   */
  private $requestId;
  
  /**
   * @var string
   */
  private $userFirstName;

  /**
   * @var Communicator
   */
  private $communicator;
  
  /**
   * @var Database
   */
  private $db;

  /**
   * @param string $update
   */
  public function __construct(string $update)
  {
    $update = json_decode($update, TRUE);
    if ( empty($update) )
    {
      throw new ErrorException(__METHOD__ . ': Failed to decode Telegram update');
    }
    
    $message = &$update['message'];
    if ( !isset($message['text']) )
    {
      exit;
    }
    if ( !isset(
         $message['message_id'], 
         $message['date'], 
         $message['chat']['id'],
         $message['from']['id'],
         $message['from']['first_name']) )
    {
      throw new ErrorException(__METHOD__ . ': Malformed Telegram update');
    }
    
    $this->request = $message['text'];
    $this->requestId = $message['message_id'];
    $this->chatId = $message['message_id'];
    $this->userFirstName = $message['from']['first_name'];
    
    $this->responses = [];
    
    $this->db = DEVELOPMENT ? DatabaseSQLite::getInstance() : DatabaseMySQL::getInstance();
    $this->db->saveUser(
         $message['from']['id'],
         $message['from']['first_name'],
         $message['from']['last_name'],
         $message['from']['username']);
    $this->db->saveRequest(
         $this->requestId,
         date(FORMAT_DATETIME_DATABASE, $message['date']), 
         $message['from']['id'],
         $this->request);
  }

  /**
   * 
   */
  public function evaluate()
  {
    $this->notify();
    if ( $this->request === '/start' )
    {
      $this->responses[] = "Ciao $this->userFirstName, benvenuto!\n" . file_get_contents(RES_DIR . '/info.md');
    }
    elseif ( $this->request === '/info' )
    {
      $this->responses[] = file_get_contents(RES_DIR . '/info.md');
    }
    elseif ( $this->request === '/prossimo_evento' || preg_match('/prossimo\s+evento/i', $this->request) )
    {
      $events = $this->getNextEvents(1);
    }
    elseif ( $this->request === '/prossimi_eventi' || preg_match('/prossimi\s+eventi/i', $this->request) )
    {
      $events = $this->getNextEvents(3);
    }
    elseif ( preg_match('/prossimi\s+(\d+)\s+eventi/', $this->request, $matches) )
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
      $events = $this->getNextEvents($maxEvents);
    }
    else
    {
      $this->responses[] = 'Mi dispiace, non ho capito';
    }
    
    if ( isset($events) )
    {
      if ( empty($events) )
      {
        $this->responses[] = 'Non ci sono eventi in programma';
      }
      else
      {
        foreach ($events as $event)
        {
          $this->responses[] = $this->textifyEvent($event);
        }
      }
    }
    $this->notify();
  }

  /**
   * @return array
   */
  public function getResponses(): array
  {
    return $this->responses;
  }

  /**
   * @return int
   */
  public function getChatId(): int
  {
    return $this->chatId;
  }

  /**
   * @return int
   */
  public function getRequestId(): int
  {
    return $this->chatId;
  }

  /**
   * 
   */
  public function notify()
  {
    $this->communicator->update($this);
  }

  /**
   * @param SplObserver $observer
   */
  public function attach(SplObserver $observer)
  {
    $this->communicator = $observer;
  }

  /**
   * @param SplObserver $observer
   */
  public function detach(SplObserver $observer)
  {
    $this->communicator = null;
  }

  /**
   * @param int $maxResults
   */
  private function getNextEvents(int $maxResults)
  {
    $client = new Google_Client();
    $client->setAuthConfig(GOOGLE_CREDENTIALS_FILE_PATH);
    $client->setScopes(GOOGLE_CALENDAR_SCOPE_READONLY);
    
    $service = new Google_Service_Calendar($client);
    
    $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Rome'));
    $events = $service->events->listEvents(
         'rotaractfirenzenord@gmail.com', [
             'timeMin' => $now->format(DateTime::RFC3339),
             'singleEvents' => true,
             'orderBy' => 'startTime',
             'maxResults' => $maxResults
         ]);
    return $events->getItems();
  }

  /**
   * @param Google_Service_Calendar_Event $event
   * @return string
   */
  private function textifyEvent(Google_Service_Calendar_Event $event): string
  {
    if ( isset($event['location']) )
    {
      $locationUrl = GOOGLE_MAPS_SEARCH_URL . urlencode($event['location']);
      $location = "[{$event['location']}]($locationUrl)";
    }
    else
    {
      $location = 'non stabilito';
    }
    
    $start = isset($event['start']['date']) ? $event['start']['date'] : $event['start']['dateTime'];
    $end = isset($event['end']['date']) ? $event['end']['date'] : $event['end']['dateTime'];
    
    $format = (strlen($start) === 10) ? FORMAT_DATE_STRFTIME : FORMAT_DATETIME_STRFTIME;
    
    $start = strftime($format, strtotime($start));
    $end = strftime($format, strtotime($end));
    
    $start = preg_replace('/ {2,}/', ' ', $start);
    $end = preg_replace('/ {2,}/', ' ', $end);
    
    $notes = isset($event['description']) ? $event['description'] : 'nessuna';
    
    $response = <<<MD
*Evento*: {$event['summary']}

*Luogo*: $location

*Inizio*: $start
*Fine*: $end

*Note*: $notes
MD;
    
    return $response;
  }

}
