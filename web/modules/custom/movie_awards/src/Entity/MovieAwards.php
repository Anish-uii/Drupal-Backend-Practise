<?php

declare(strict_types=1);

namespace Drupal\movie_awards\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\movie_awards\MovieAwardsInterface;

/**
 * Defines the movie awards entity type.
 *
 * @ConfigEntityType(
 *   id = "movie_awards",
 *   label = @Translation("Movie awards"),
 *   label_collection = @Translation("Movie awardss"),
 *   label_singular = @Translation("movie awards"),
 *   label_plural = @Translation("movie awardss"),
 *   label_count = @PluralTranslation(
 *     singular = "@count movie awards",
 *     plural = "@count movie awardss",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\movie_awards\MovieAwardsListBuilder",
 *     "form" = {
 *       "add" = "Drupal\movie_awards\Form\MovieAwardsForm",
 *       "edit" = "Drupal\movie_awards\Form\MovieAwardsForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *   },
 *   config_prefix = "movie_awards",
 *   admin_permission = "administer movie_awards",
 *   links = {
 *     "collection" = "/admin/structure/movie-awards",
 *     "add-form" = "/admin/structure/movie-awards/add",
 *     "edit-form" = "/admin/structure/movie-awards/{movie_awards}",
 *     "delete-form" = "/admin/structure/movie-awards/{movie_awards}/delete",
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *   },
 * )
 */
final class MovieAwards extends ConfigEntityBase implements MovieAwardsInterface {

  /**
   * The example ID.
   */
  protected string $id;

  /**
   * The example label.
   */
  protected string $label;

  /**
   * The example description.
   */
  protected string $description;

}
