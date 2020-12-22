<?php

namespace Drupal\superhero\Services;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class SuperheroService provide functions for getting info about superheroes.
 *
 * @package Drupal\superhero\Services
 */
class SuperheroService implements SuperheroServiceInterface {

  use StringTranslationTrait;

  /**
   * Entity Type Manager Interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * DateFormatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * SuperheroService constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type manager.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
   *   Date formatter.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, DateFormatterInterface $dateFormatter) {
    $this->entityTypeManager = $entityTypeManager;
    $this->dateFormatter = $dateFormatter;
  }

  /**
   * {@inheritDoc}
   */
  public function randomHero() {
    $all_superheroes = $this->getAllSuperheroIds();

    if (!empty($all_superheroes)) {
      $superhero = array_rand($all_superheroes);

      return $this->getHeroRender($superhero);
    }

    return NULL;
  }

  /**
   * {@inheritDoc}
   */
  public function getAllSuperheroIds() {
    $query = $this->entityTypeManager->getStorage(self::SUPERHERO_ENTITY_TYPE)->getQuery();
    $query->condition('type', self::SUPERHERO_ENTITY_BUNDLE);

    return $query->execute();
  }

  /**
   * {@inheritDoc}
   */
  public function getHeroRender(int $superhero, bool $array = FALSE) {
    /** @var \Drupal\node\NodeInterface $superhero */
    $superhero = $this->entityTypeManager->getStorage(self::SUPERHERO_ENTITY_TYPE)->load($superhero);
    $name = $superhero->label();
    $power = $superhero->field_superpower->value;

    if ($array) {
      return [
        'id' => $superhero->id(),
        'name' => $name,
        'superpower' => $power,
      ];
    }

    $info = $this->t('My name is @name and I have the power of @superpower.', [
      '@name' => $name,
      '@superpower' => $power,
    ]);
    $born_time = $this->t('I was born @time ago.', ['@time' => $this->dateFormatter->formatTimeDiffSince($superhero->getCreatedTime())]);
    $author_info = $this->t('My creator is @username', ['@username' => $superhero->getOwner()->getDisplayName()]);

    return new FormattableMarkup('<div class="info">@info</div><div class="time">@time</div><div class="author">@author</div>', [
      '@info' => $info->render(),
      '@time' => $born_time->render(),
      '@author' => $author_info->render(),
    ]);
  }

}
