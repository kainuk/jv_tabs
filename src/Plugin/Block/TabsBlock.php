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
  ];

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['jv']=[];
    $build['#theme'] = 'jv_tabs';
    $build['#title'] = 'Nog een title';
    $items = [];
    \Drupal::request()->query->all();
    foreach($this->links as $key => $link){
      if(isset($this->configuration[$link])) {
        $label = $this->configuration[$link.'label'];
        $items[] = Link::fromTextAndUrl($label,Url::fromUserInput('/'.$link,['query'=>  \Drupal::request()->query->all()]));
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
    $form['jv'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('List of TabLinks')
      ];
      foreach($this->links as $key => $link) {
        $form['jv'][$link.'label'] = [
          '#type' => 'textfield',
          '#title' => 'Label ' . $key,
          '#default_value' => $this->configuration[$link.'label'],
        ];
        $form['jv'][$link] = [
          '#type' => 'textfield',
          '#title' => 'Link ' . $key,
          '#default_value' => $this->configuration[$link],
        ];
      }
    return $form;
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    foreach($this->links as $key => $link) {
      $this->configuration[$link] = $values['jv'][$link];
      $this->configuration[$link .'label'] = $values['jv'][$link .'label'];
    }
  }

}
