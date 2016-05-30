<?php

/**
 * @file
 * Contains \Drupal\rewrite_field\Plugin\Field\FieldFormatter\RewriteFieldFormatter.
 */

namespace Drupal\rewrite_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'prefix_suffix' formatter.
 *
 * @FieldFormatter(
 *   id = "rewrite_field",
 *   label = @Translation("Rewrite Field"),
 *   field_types = {
 *     "string",
 *     "text",
 *     "text_long",
 *     "text_with_summary"
 *   },
 *   settings = {
 *     "prefix" = "",
 *     "suffix" = "",
 *     "custom_text" = ""
 *   }
 * )
 */
class RewriteFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'prefix' => '',
      'suffix' => '',
      'custom_text' => '',
    ) + parent::defaultSettings();
  }


  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    $element['prefix'] = array(
      '#title' => t('Prefix'),
      '#type' => 'textfield',
      '#size' => 20,
      '#default_value' => $this->settings['prefix'],
    );

    $element['suffix'] = array(
      '#title' => t('Suffix'),
      '#type' => 'textfield',
      '#size' => 20,
      '#default_value' => $this->settings['suffix'],
    );

    $element['custom_text'] = array(
      '#title' => t('Custom Text'),
      '#type' => 'textarea',
      '#description' => t('Override the output of this field with custom text'),
      '#default_value' => $this->settings['custom_text'],
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode = NULL) {
    $elements = array();
    $prefix = $this->settings['prefix'];
    $suffix = $this->settings['suffix'];
    $custom_text = $this->settings['custom_text'];
    foreach ($items as $delta => $item) {
      $output = $item->value;
      if (!empty($custom_text)) {
        $output = $custom_text;
      }
      if (!empty($prefix)) {
        $output = $prefix . $output;
      }
      if (!empty($suffix)) {
        $output = $output . $suffix;
      }
      $elements[$delta] = [
        '#markup' => $output];
    }
    return $elements;
  }

}
