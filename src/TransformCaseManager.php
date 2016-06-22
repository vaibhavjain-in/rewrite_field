<?php

/**
 * @file
 * Contains \Drupal\rewrite_field\TransformCaseManager.
 */

namespace Drupal\rewrite_field;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

class TransformCaseManager extends DefaultPluginManager {
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/rewrite_field/Transform', $namespaces, $module_handler, 'Drupal\rewrite_field\TransformCasePluginInterface', 'Drupal\rewrite_field\Annotation\TransformCase');
    $this->alterInfo('rewrite_field');
    $this->setCacheBackend($cache_backend, 'rewrite_field_tranform_manager');
  }
}
