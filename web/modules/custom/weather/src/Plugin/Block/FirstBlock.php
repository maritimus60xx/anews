<?php

namespace Drupal\weather\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "weather_first_block_block",
 *   admin_label = @Translation("My first block"),
 * )
 */
class FirstBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    if (!empty($config['weather_first_block_settings'])) {
      $text = $this->t('Hello @name in block!', ['@name' => $config['weather_first_block_settings']]);
    }
    else {
      $text = $this->t('Hello World in block!');
    }

    return [
      '#markup' => $text,
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    $form['weather_first_block_settings'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#description' => $this->t('Who do you want to say hello to?'),
      '#default_value' => !empty($config['weather_first_block_settings']) ? $config['weather_first_block_settings'] : '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['weather_first_block_settings'] = $form_state->getValue('weather_first_block_settings');
  }

}
//
//
//$xmldata =
//  "
//<chapter>
//<item>
//	<date>2019-07-02</date>
//	<code>978</code>
//	<char3>EUR</char3>
//	<size>100</size>
//	<name>ЄВРО</name>
//	<rate>2974.1749</rate>
//	<change>-2.2568</change>
//</item>
//<item>
//	<date>2019-07-02</date>
//	<code>840</code>
//	<char3>USD</char3>
//	<size>100</size>
//	<name>доларів США</name>
//	<rate>2620.6493</rate>
//	<change>2.8554</change>
//</item>
//<item>
//	<date>2019-07-02</date>
//	<code>643</code>
//	<char3>RUB</char3>
//	<size>10</size>
//	<name>російських рублів</name>
//	<rate>4.1562</rate>
//	<change>0.004</change>
//</item>
//</chapter>";
//
//$data = simplexml_load_string("$xmldata") or die("Error Found");
//$USDchar3 = $data->item[1]->char3;
//$USDrate = $data->item[1]->rate;
//$USDchange = $data->item[1]->change;
//
//$EURchar3 = $data->item[0]->char3;
//$EURrate = $data->item[0]->rate;
//$EURchange = $data->item[0]->change;
//
//$RUBchar3 = $data->item[2]->char3;
//$RUBrate = $data->item[2]->rate;
//$RUBchange = $data->item[2]->change;
//$RUBsum = round($RUBrate, -3);
//echo $RUBsum;
//echo 'Валюта ', $USDchar3, ' Продаж ', ($USDrate / 100), ' Купівля ', ($USDrate / 100 - $USDchange);
//echo 'Валюта ', $EURchar3, ' Продаж ', ($EURrate / 100), ' Купівля ', ($EURrate / 100 - $EURchange);
//echo 'Валюта ', $RUBchar3, ' Продаж ', $RUBrate, ' Купівля ', number_format(($RUBsum - $RUBchange), 3, '.', ' ');
