<?php

/*
 * This file is part of the RacFiNordBot package.
 * 
 * (c) 2018 Rotaract Club Firenze Nord <rotaractfirenzenord@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

require_once realpath(__DIR__ . '/../vendor/autoload.php');

/**
 * Computational engine.
 * 
 * This class evaluates the input text from Telegram user, and computes
 * the appropriate response.
 *
 * @author Daniele Ambrosino <mail@danieleambrosino.it>
 */
class Bot implements SplSubject
{

  /**
   * Telegram user's request.
   * 
   * @var string The incoming request from user, stored as it is.
   */
  private $request;

  /**
   * Computed responses.
   * 
   * @var array This array stores all the computed responses to be sent.
   */
  private $responses;

  /**
   * Telegram chat ID.
   * 
   * @var int The identification number that Telegram univocally gives to the
   * chat to which the responses must be sent.
   */
  private $chatId;

  /**
   * Telegram message ID.
   * 
   * @var int The univocal identifier of the message containing user's request.
   */
  private $requestId;
  
  /**
   * User's first name.
   * 
   * @var string The name that user has chosen on Telegram.
   */
  private $userFirstName;

  /**
   * Observer that outputs responses.
   * 
   * @var Communicator Handles the communications with the outer and scary world.
   */
  private $communicator;
  
  /**
   * Database handle.
   * 
   * @var Database Gives all the methods to store user's and request's informations.
   */
  private $db;

  /**
   * Constructs the bot.
   * 
   * This function tries to decode the JSON string, then checks if the update
   * is missing some crucial information (if so, throws an ErrorException).
   * Then, initializes all class members.
   * If the database module is activated, stores user's and request's
   * informations into the database.
   * 
   * @param string $update JSON-encoded update incoming from Telegram servers.
   * @throws ErrorException
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
    $this->chatId = $message['chat']['id'];
    $this->userFirstName = $message['from']['first_name'];
    
    $this->responses = [];
    
    if ( DATABASE_ENABLED )
    {
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
  }

  /**
   * Evaluates user's request and produces the appropriate responses.
   * 
   * This function notifies the Communicator object that evaluation started.
   * Then, analyzes the request's text and produces the appropriate responses
   * (eventually fetching some data from Google).
   * Finally, notifies the Communicator that evaluation finished.
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
    elseif ( preg_match('/prossimi\s+(\d+)\s+eventi/i', $this->request, $matches) )
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
   * Returns the responses array.
   * 
   * @return array The array containing the evaluated responses. If no response
   * is found, returns an empty array.
   */
  public function getResponses(): array
  {
    return $this->responses;
  }

  /**
   * Returns the chat ID.

   * @return int Telegram's chat univocal identifier.
   */
  public function getChatId(): int
  {
    return $this->chatId;
  }

  /**
   * Returns the request's ID.
   * 
   * @return int Request's univocal idenfifier.
   */
  public function getRequestId(): int
  {
    return $this->requestId;
  }

  /**
   * Notifies observer that something changed.
   */
  public function notify()
  {
    $this->communicator->update($this);
  }

  /**
   * Attaches an observer.
   * 
   * @param SplObserver $observer The observer to be attached.
   */
  public function attach(SplObserver $observer)
  {
    $this->communicator = $observer;
  }

  /**
   * Detaches an observer
   * 
   * Basically, sets to null the reference to the observer.
   * 
   * @param SplObserver $observer
   */
  public function detach(SplObserver $observer)
  {
    $this->communicator = null;
  }

  /**
   * Get the next events scheduled into Google Calendar's account.
   * 
   * Connects to Google Calendar using the dedicated PHP library.
   * Then, downloads a maximum number of events depending on the value of
   * the $maxResults parameter.
   * Finally, returns an array containg the Google_Service_Calendar_Event objects.
   * 
   * @param int $maxResults The maximum number of events to be downloaded.
   * @return array The Google_Service_Calendar_Event-s.
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
   * Creates a human-readable version of the event.
   * 
   * Analyzes the Google_Service_Calendar_Event passed as parameter, then
   * writes a textual version of the event itself.

   * @param Google_Service_Calendar_Event $event The Google event object.
   * @return string The textual version of the event.
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
