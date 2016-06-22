<?php

/**
 * @file
 * Contains \Drupal\rewrite_field\Annotation\TransformCase.
 */

namespace Drupal\rewrite_field\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * @Annotation
 */
class TransformCase extends Plugin {
  public $id;
  public $title = "";
  public $description = "";
}
