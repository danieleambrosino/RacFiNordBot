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
 * Description of EventFetcher
 *
 * @author Daniele Ambrosino
 */
class EventFetcher
{

  /**
   * 
   * @param int $max
   * @return array
   */
  public static function getNextEvents(int $max)
  {
    $client = new Google_Client();
    $client->setAuthConfig(GOOGLE_CREDENTIALS_FILE_PATH);
    $client->setScopes(GOOGLE_CALENDAR_SCOPE_READONLY);

    $service = new Google_Service_Calendar($client);

    $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Rome'));
    $events = $service->events->listEvents(
         'rotaractfirenzenord@gmail.com',
         [
      'timeMin'      => $now->format(DateTime::RFC3339),
      'singleEvents' => true,
      'orderBy'      => 'startTime',
      'maxResults'   => $max
    ]);
    $events = $events->getItems();
    $textifiedEvents = [];
    foreach ($events as $event)
    {
      $textifiedEvents[] = self::textifyEvent($event);
    }
    return $textifiedEvents;
  }

  private static function textifyEvent(Google_Service_Calendar_Event $event)
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
