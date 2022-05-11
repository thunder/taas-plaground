<?php

namespace Drupal\front\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for custom1 routes.
 */
class FrontController extends ControllerBase {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  private Connection $database;

  /**
   * @var \Drupal\Core\Routing\RouteProviderInterface
   */
  private RouteProviderInterface $routeProvider;

  /**
   * Constructs a new controller object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *  The route provider.
   */
  public function __construct(
    Connection $database,
    RouteProviderInterface $routeProvider
  ) {
    $this->database = $database;
    $this->routeProvider = $routeProvider;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('router.route_provider'),
    );
  }

  /**
   * Builds the response.
   */
  public function build() {
    $build = [];
    $links = [];

    $query = $this->database->query("SELECT name FROM {router} WHERE name LIKE :name", [":name" => '%example']);
    $results = $query->fetchAll();

    foreach ($results as $result) {
      $url = Url::fromRoute($result->name);
      $route = $this->routeProvider->getRouteByName($result->name);

      $links[] = [
        '#type' => 'html_tag',
        '#tag' => 'li',
        'child' => Link::fromTextAndUrl($route->getDefault('_title'), $url)->toRenderable(),
      ];
    }

    $build['content'] = [
      '#type' => 'html_tag',
      '#tag' => 'ul',
      'child' => $links,
    ];

    return $build;
  }

}
