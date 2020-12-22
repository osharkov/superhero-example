<?php

namespace Drupal\superhero\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;
use Drupal\superhero\Services\SuperheroService;
use Drupal\superhero\Services\SuperheroServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SuperHeroPageController provide functions for superhero pages.
 *
 * @package Drupal\superhero\Controller
 */
class SuperHeroPageController extends ControllerBase {

  /**
   * Superhero service.
   *
   * @var \Drupal\superhero\Services\SuperheroServiceInterface
   */
  protected $superheroService;

  /**
   * Node Storage.
   *
   * @var \Drupal\node\NodeStorageInterface
   */
  protected $nodeStorage;

  /**
   * SuperHeroPageController constructor.
   *
   * @param \Drupal\superhero\Services\SuperheroService $superheroService
   *   Superhero service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type manager.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(SuperheroService $superheroService, EntityTypeManagerInterface $entityTypeManager) {
    $this->superheroService = $superheroService;
    $this->nodeStorage = $entityTypeManager->getStorage(SuperheroServiceInterface::SUPERHERO_ENTITY_TYPE);
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('superhero.service'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Hero page.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request.
   * @param \Drupal\node\NodeInterface $hero
   *   Hero node.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse|array
   *   Hero Info.
   */
  public function pageCallback(Request $request, NodeInterface $hero) {
    if (!$this->returnDataValidation($hero)) {
      throw new NotFoundHttpException();
    }

    if ($request->query->has('_format') && $request->query->get('_format') == 'json') {
      return new JsonResponse($this->superheroService->getHeroRender($hero->id(), TRUE));
    }

    return [
      '#type' => 'markup',
      '#markup' => $this->superheroService->getHeroRender($hero->id())->jsonSerialize(),
      '#prefix' => '<div class="superhero-wrapper">',
      '#suffix' => '</div>',
    ];
  }

  /**
   * Hero page title.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request.
   * @param \Drupal\node\NodeInterface $hero
   *   Hero node.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   HeroPage title.
   */
  public function titleCallback(Request $request, NodeInterface $hero) {
    if (!$this->returnDataValidation($hero)) {
      throw new NotFoundHttpException();
    }

    return $this->t('@hero personal page', ['@hero' => $hero->label()]);
  }

  /**
   * Check ability to render node.
   *
   * @param \Drupal\node\NodeInterface $hero
   *   Superhero node.
   *
   * @return bool
   *   Validation result
   */
  private function returnDataValidation(NodeInterface $hero) {
    if ($hero->bundle() != SuperheroServiceInterface::SUPERHERO_ENTITY_BUNDLE) {
      return FALSE;
    }

    return TRUE;
  }

}
