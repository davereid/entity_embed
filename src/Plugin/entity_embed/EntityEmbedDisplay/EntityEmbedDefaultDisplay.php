<?php

/**
 * @file
 * Contains \Drupal\entity_embed\Plugin\entity_embed\EntityEmbedDisplay\EntityEmbedDefaultDisplay.
 */

namespace Drupal\entity_embed\Plugin\entity_embed\EntityEmbedDisplay;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\entity_embed\EntityEmbedDisplay\EntityEmbedDisplayBase;

/**
 * Default embed display, which renders the entity using entity_view().
 *
 * @EntityEmbedDisplay(
 *   id = "default",
 *   label = @Translation("Default")
 * )
 *
 * @todo Should this use a derivative? http://cgit.drupalcode.org/page_manager/tree/src/Plugin/Deriver/EntityViewDeriver.php
 */
class EntityEmbedDefaultDisplay extends EntityEmbedDisplayBase {

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account = NULL) {
    if (!parent::access($account)) {
      return FALSE;
    }

    // Cannot render the entity type if it does not have a view controller.
    return $this->canRenderEntityType($this->getEntityTypeFromContext());
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'view_mode' => 'default',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['view_mode'] = array(
      '#type' => 'select',
      '#title' => t('View mode'),
      '#options' => $this->entityManager()->getViewModeOptions($this->getContextValue('entity')->getEntityTypeId()),
      '#default_value' => $this->getConfigurationValue('view_mode'),
      '#required' => TRUE,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $entity = $this->getContextValue('entity');
    $view_mode = $this->getConfigurationValue('view_mode');
    $langcode = $this->getAttributeValue('data-langcode');
    return $this->renderEntity($entity, $view_mode, $langcode);
  }
}
