<?php

namespace Drupal\mod_context_reaction\Plugin\ContextReaction;

use Drupal\context\ContextInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\context\ContextReactionPluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a content reaction that will let you place blocks in the current
 * themes regions.
 *
 * @ContextReaction(
 *   id = "theme_html",
 *   label = @Translation("Theme HTML")
 * )
 */

class ThemeHTML extends ContextReactionPluginBase implements  ContainerFactoryPluginInterface{

    /**
     * Provides a human readable summary of the condition's configuration.
     *
     * @return \Drupal\Core\StringTranslation\TranslatableMarkup
     */
    public function summary()
    {
        return $this->t('Provides this text as an additional body class (in attributes in html.html.twig) when this section is active.');
    }

    /**
     * Creates an instance of the plugin.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *   The container to pull out services used in the plugin.
     * @param array $configuration
     *   A configuration array containing information about the plugin instance.
     * @param string $plugin_id
     *   The plugin ID for the plugin instance.
     * @param mixed $plugin_definition
     *   The plugin implementation definition.
     *
     * @return static
     *   Returns an instance of this plugin.
     */
    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
        return new static(
            $configuration,
            $pluginId,
            $pluginDefinition
        );
    }

    /**
     * Executes the plugin.
     *
     * @param array $variables
     *   The current variables of the preprocess html.
     *
     * @return array
     */
    public function execute(&$variables = array())
    {
        if($configration = $this->getConfiguration()) {
            $theme_html = isset($configration['theme_html']) ? $configration['theme_html'] : '';
            if (isset($theme_html['class']) && !empty($theme_html['class'])) {
                $variables['attributes']['class'][] = $theme_html['class'];
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration() {
        return [
            'theme_html' => []
        ] + parent::defaultConfiguration();
    }

    /**
     * Form constructor.
     *
     * Plugin forms are embedded in other forms. In order to know where the plugin
     * form is located in the parent form, #parents and #array_parents must be
     * known, but these are not available during the initial build phase. In order
     * to have these properties available when building the plugin form's
     * elements, let this method return a form element that has a #process
     * callback and build the rest of the form in the callback. By the time the
     * callback is executed, the element's #parents and #array_parents properties
     * will have been set by the form API. For more documentation on #parents and
     * #array_parents, see \Drupal\Core\Render\Element\FormElement.
     *
     * @param array $form
     *   An associative array containing the initial structure of the plugin form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the complete form.
     *
     * @return array
     *   The form structure.
     */
    public function buildConfigurationForm(array $form, FormStateInterface $form_state)
    {

        $configration = $this->getConfiguration();

        $theme_html = isset($configration['theme_html']) ? $configration['theme_html'] : '';
        $form['class'] = array(
                '#title' => t('Section class'),
                '#description' => t('Provides this text as an additional body class (in <strong>attributes</strong> in html.html.twig) when this section is active.'),
                '#type' => 'textfield',
                '#maxlength' => 64,
                '#default_value' => !empty($theme_html['class']) ? $theme_html['class'] : '',

        );

        return $form;
    }

    /**
     * Form submission handler.
     *
     * @param array $form
     *   An associative array containing the structure of the plugin form as built
     *   by static::buildConfigurationForm().
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the complete form.
     */

    public function submitConfigurationForm(array &$form, FormStateInterface $form_state,ContextInterface $context = NULL)
    {
        $classes = $form_state->getValue('class');
        if(isset($classes) && !empty($classes)){
           $this->setConfiguration(array('theme_html' => array('class' => $classes)));
        }
    }
}