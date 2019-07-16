<?php

namespace Drupal\event_pull\Controller;

use Drupal\event_pull\Model\Event;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\TermInterface;

class EventController {

  /**
   * Create a new event node.
   *
   * @param \Drupal\event_pull\Model\Event $event
   *   The event model.
   * @param \Drupal\taxonomy\TermInterface $venue
   *   The venue term.
   * @param int $remoteId
   *   The remote ID.
   *
   * @return \Drupal\node\NodeInterface
   *   The new event node.
   *
   * @throws \Exception
   */
  public function create(Event $event, TermInterface $venue, int $remoteId): NodeInterface {
    $values = [
      'changed' => $event->getCreatedDate(),
      'created' => $event->getCreatedDate(),
      'body' => [
        'format' => 'basic_html',
        'value' => $event->getDescription(),
      ],
      'field_event_date' => $event->getEventDate(),
      'field_event_id' => $remoteId,
      'field_event_link' => $event->getRemoteUrl(),
      'field_venue' => $venue->id(),
      'status' => NodeInterface::PUBLISHED,
      'title' => $event->getName(),
      'type' => 'event',
    ];

    return tap(Node::create($values), function (NodeInterface $event): void {
      $event->save();
    });
  }

  /**
   * Update an existing event node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The event node.
   * @param \Drupal\event_pull\Model\Event $event
   *   The event model.
   *
   * @return \Drupal\node\NodeInterface
   *   The updated node.
   */
  public function update(NodeInterface $node, Event $event): NodeInterface {
    return tap($node, function (NodeInterface $node) use ($event): void {
      $node->setTitle($event->getName());
      $node->set('body', $event->getDescription());
      $node->set('field_event_date', $event->getEventDate());

      $node->setNewRevision();
      $node->save();
    });
  }

}
