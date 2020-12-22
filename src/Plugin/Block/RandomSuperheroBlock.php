<?php

namespace Drupal\superhero\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\superhero\Services\SuperheroServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RandomSuperheroBlock provide custom block for display random hero.
 *
 * @Block(
 *  id = "superhero_random_block",
 *  admin_label = @Translation("Random hero block"),
 *  category = @Translation("Superhero"),
 * )
 *
 * @package Drupal\superhero\Plugin\Block
 */
class RandomSuperheroBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Superhero service.
   *
   * @var \Drupal\superhero\Services\SuperheroServiceInterface
   */
  protected $superheroService;

  /**
   * RandomSuperheroBlock constructor.
   *
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SuperheroServiceInterface $superheroService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->superheroService = $superheroService;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('superhero.service')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function build() {
    $superhero_info = $this->superheroService->randomHero();

    if (empty($superhero_info)) {
      return [];
    }

    return [
      '#markup' => $superhero_info->jsonSerialize(),
      '#prefix' => '<div class="superhero-wrapper">',
      '#suffix' => '</div>',
    ];
  }

}
