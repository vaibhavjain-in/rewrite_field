<?php

/**
 * @file
 * Contains \Drupal\rewrite_field\TransformCasePluginInterface.
 */

namespace Drupal\rewrite_field;

interface TransformCasePluginInterface {

  /**
   * @param $output string
   * @return string
   */
  public static function transform($output);
}
