<?php

namespace Drupal\pannellum\Plugin\Field\FieldFormatter;

use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;


/**
 * @FieldFormatter(
 *  id = "pannellum",
 *  label = @Translation("Pannellum"),
 *  field_types = {
 *     "image"
 *  },
 *  quickedit = {
 *    "editor" = "image"
 *  }
 * )
 */
class Pannellumformatter extends ImageFormatterBase
{


  /**
   * {@inheritdoc}
   */
  public static function defaultSettings()
  {
    return [
      'pannellum_type' => '',
      'pannellum_display_style' => '',
      'pannellum_autoload' => '1',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state)
  {
    $image_styles = image_style_options(FALSE);
    $description_link = Link::fromTextAndUrl(
      $this->t('Configure Image Styles'),
      Url::fromRoute('entity.image_style.collection')
    );
    $element['pannellum_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Pannellum type'),
      '#options' => $this->pannellumEffectTypes(),
      '#default_value' => $this->getSetting('pannellum_type'),
    ];

    $element['pannellum_display_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Image style'),
      '#options' => $image_styles,
      '#default_value' => $this->getSetting('pannellum_display_style'),
      '#empty_option' => $this->t('None (original image)'),
      '#description' => $description_link->toRenderable(),
    ];

    $element['pannellum_autoload'] = [
      '#title' => $this->t('Autoload'),
      '#type' => 'checkbox',
      '#description' => $this->t('Select the Checkbox for autoload preview.'),
      '#default_value' => $this->getSetting('pannellum_autoload'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary()
  {
    $image_styles = image_style_options(FALSE);
    $summary = [];
    unset($image_styles['']);
    $pannellum_type = $this->getSetting('pannellum_type');
    $image_display_setting = $this->getSetting('pannellum_display_style');
    $pannellum_effect_types = $this->pannellumEffectTypes();
    $pannellum_autoload = $this->getSetting('pannellum_autoload');

    if (isset($pannellum_effect_types[$pannellum_type])) {
      $summary[] = $this->t('Pannellum type: @style', ['@style' => $pannellum_effect_types[$pannellum_type]]);
      if(isset($image_styles[$image_display_setting])) {
        $summary[] = $this->t('Display image style: @style', ['@style' => $image_styles[$image_display_setting]]);
      }
      else
        {
          $summary[] = $this->t('Original image');
        }
    } else {
      $summary[] = $this->t('Configure Pannellum Settings');
      $summary[] = $this->t('Pannellum type (Default)  : Equirectangular');
    }
    return $summary;
  }


  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $display_style = $this->getSetting('pannellum_display_style');
    $pannellum_autoload = $this->getSetting('pannellum_autoload');

    // Settings array keep the value of panorama effect type.
    $settings = [
      'type' => $this->getSetting('pannellum_type'),
      'id' => 'panorama-',
    ];

    $element = [];
    $index = 0;
    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#theme' => 'panorama_effect',
        '#item' => $item,
        '#display_style' => $display_style,
        '#settings' => $settings,
        '#pannellum_autoload' => $pannellum_autoload,
      ];
      $element['#attached']['drupalSettings']['pannellum'][$index++] = $settings;
    }

    return $element;
  }


  /**
   * Returns an array of available panorama effect types.
   */
  public function pannellumEffectTypes()
  {
    $types = [
      'equirectangular' => $this->t('Equirectangular'),
      // 'cubemap' => $this->t('Cube Map'),
      // 'multires' => $this->t('Multiresolution'),
    ];

    return $types;
  }
}
