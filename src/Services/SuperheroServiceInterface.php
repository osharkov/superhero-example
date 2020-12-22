<?php

namespace Drupal\superhero\Services;

/**
 * Super hero service interface.
 */
interface SuperheroServiceInterface {

  /**
   * Superhero entity type id.
   *
   * @var string
   */
  const SUPERHERO_ENTITY_TYPE = 'node';

  /**
   * Superhero entity type bundle name.
   *
   * @var string
   */
  const SUPERHERO_ENTITY_BUNDLE = 'superhero';

  /**
   * Get random hero.
   *
   * @return \Drupal\Component\Render\FormattableMarkup|null
   *   Random superhero info.
   */
  public function randomHero();

  /**
   * Get render info about hero.
   *
   * @param int $superhero
   *   Superhero ID.
   * @param bool $array
   *   Return data like array.
   *
   * @return \Drupal\Component\Render\FormattableMarkup|null|array
   *   Random superhero info.
   */
  public function getHeroRender(int $superhero, bool $array = FALSE);

  /**
   * Get list of all superhero ids.
   *
   * @return array
   *   List of all superheros nodes.
   */
  public function getAllSuperheroIds();

}
