<?php

/**
 * @file
 * Contains \Drupal\rewrite_field\Plugin\Field\FieldFormatter\RewriteFieldFormatter.
 */

namespace Drupal\rewrite_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

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
 *     "custom_text" = "",
 *     "make_link" = FALSE,
 *     "link_path" = "",
 *     "external_link" = FALSE,
 *     "absolute_link" = FALSE,
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
      'make_link' => FALSE,
      'link_path' => '',
      'external_link' => FALSE,
      'absolute_link' => FALSE,
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

    $element['make_link'] = array(
      '#title' => t('Output this field as a custom link'),
      '#type' => 'checkbox',
      '#default_value' => $this->settings['make_link'],
    );

    $element['link_path'] = array(
      '#title' => t('Link path'),
      '#type' => 'textfield',
      '#default_value' => $this->settings['link_path'],
      '#description' => $this->t('The Drupal path or absolute URL for this link.'),
      '#states' => array(
        'visible' => array(
          ':input[name$="[settings_edit_form][settings][make_link]"]' => array('checked' => TRUE),
        ),
      ),
      '#maxlength' => 255,
    );

    $element['external_link'] = array(
      '#type' => 'checkbox',
      '#title' => t('External URL'),
      '#default_value' => $this->settings['external_link'],
      '#description' => $this->t("A link to external server: e.g. 'http://www.example.com' or 'www.example.com'."),
      '#states' => array(
        'visible' => array(
          ':input[name$="[settings_edit_form][settings][make_link]"]' => array('checked' => TRUE),
        ),
      ),
    );

    $element['absolute_link'] = array(
      '#type' => 'checkbox',
      '#title' => t('Absolute path'),
      '#default_value' => $this->settings['absolute_link'],
      '#description' => $this->t("Generate Absolute link"),
      '#states' => array(
        'visible' => array(
          ':input[name$="[settings_edit_form][settings][make_link]"]' => array('checked' => TRUE),
        ),
      ),
    );

    // @todo: Link title and target
    // @todo: Text Transform(Lower, upper), No Results behaviour
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
    $link_path = strip_tags($this->settings['link_path']);
    $external_link = (bool) $this->settings['external_link'];
    $absolute_link = (bool) $this->settings['absolute_link'];
    foreach ($items as $delta => $item) {
      $output = $item->value;
      if (!empty($output) && !empty($custom_text)) {
        $output = $custom_text;
      }
      else {
        // @todo: Add No Results Behaviour
      }
      if ($this->settings['make_link'] && !empty($link_path)) {
        if ($external_link) {
          $url = Url::fromUri($link_path);
        }
        else {
          $link_path = (strpos($link_path, '/') !== 0) ? '/' . $link_path : $link_path;
          $url = Url::fromUserInput($link_path, array('absolute' => $absolute_link));
        }
        $output = Link::fromTextAndUrl($output, $url)->toString();
      }
      if (!empty($prefix)) {
        $output = $prefix . $output;
      }
      if (!empty($suffix)) {
        $output = $output . $suffix;
      }
      $elements[$delta] = [
        '#markup' => $output
      ];
    }
    return $elements;
  }

}
