<?php

namespace Drupal\jv_tabs\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'TabsBlock' block.
 *
 * @Block(
 *  id = "jv_tabs",
 *  admin_label = @Translation("Tabs block"),
 * )
 */
class TabsBlock extends BlockBase {

  var $links = [
        '01' => 'link01',
        '02' => 'link02',
        '03' => 'link03',
        '04' => 'link04',
        '05' => 'link05',
        '06' => 'link06',
        '07' => 'link07',
  ];

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['jv']=[];
    $build['#theme'] = 'jv_tabs';
    $build['#title'] = 'JV Tabs';
    $items = [];
    \Drupal::request()->query->all();
    foreach($this->links as $key => $link){
      if(!empty($this->configuration[$link])) {
        $label = $this->configuration[$link.'label'];
        $items[] = Link::fromTextAndUrl($label,Url::fromUserInput($this->configuration[$link],['query'=>  \Drupal::request()->query->all()]));
      }
    }
    $build['#items'] = $items;
    $build['children'] = $items;
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
      foreach($this->links as $key => $link) {
        $form[$key] = [
          '#type' => 'details',
          '#title' => "Tab $key",
            'label' => [
          '#type' => 'textfield',
          '#title' => 'Label',
          '#default_value' => $this->configuration[$link.'label'],
        ],
        'link' => [
          '#type' => 'textfield',
          '#title' => 'Link',
          '#default_value' => $this->configuration[$link],
          '#description' => t('The link must start with a /'),
        ]];
      }
    return $form;
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    foreach($this->links as $key => $link) {
      $this->configuration[$link] = $values[$key]['link'];
      $this->configuration[$link .'label'] = $values[$key]['label'];
    }
  }

  public function getCacheMaxAge() {
    return 0;
  }

}
