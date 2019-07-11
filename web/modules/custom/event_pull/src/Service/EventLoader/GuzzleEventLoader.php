<?php

namespace Drupal\event_pull\Service\EventLoader;

use Drupal\event_pull\Model\Event;
use GuzzleHttp\ClientInterface;
use Tightenco\Collect\Support\Collection;

/**
 * Load events from an external API using GET requests from Guzzle.
 */
abstract class GuzzleEventLoader implements EventLoaderInterface {

  /**
   * The http_client service.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * GuzzleEventLoader constructor.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   The http_client service.
   */
  public function __construct(ClientInterface $client) {
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public function getUpcoming(): Collection {
    // TODO: Add caching.
    $response = $this->client->request('get', $this->getUrl());
    $events = (string) $response->getBody();

    return collect(json_decode($events))->map(function (\stdClass $data) {
      return new Event($data);
    })->sortByDesc->getDate()->values();
  }

  /**
   * Generate the URL to query.
   *
   * @return string
   *   The URL to query.
   */
  abstract public function getUrl(): string;

}
