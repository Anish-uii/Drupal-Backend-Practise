<?php

declare(strict_types=1);

namespace Drupal\movie_entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a movie entity entity type.
 */
interface MovieEntityInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
