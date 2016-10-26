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
 *   id = "template_suggestion",
 *   label = @Translation("Template Suggestion")
 * )
 */

class TemplateSuggestion extends ContextReactionPluginBase implements  ContainerFactoryPluginInterface{

    /**
     * Provides a human readable summary of the condition's configuration.
     *
     * @return \Drupal\Core\StringTranslation\TranslatableMarkup
     */
    public function summary()
    {
        return $this->t('Provides this text as an additional template suggestion such as "page__front"  when this section is active.');
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
    public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition)
    {
        return new static(
            $configuration,
            $pluginId,
            $pluginDefinition
        );
    }

    /**
     * Executes the plugin.
     */
    public function execute(&$suggestions = array(), &$variables = array())
    {
        if($configration = $this->getConfiguration()) {
            $values = isset($configration['template_suggestion']) ? $configration['template_suggestion'] : '';
            if (isset($values['templates']) && !empty($values['templates'])) {
                // Convert it to an list and reverse it (as higher priority items
                // should be on the bottom).
                $suggetion_template = array_reverse(explode("\n", $values['templates']));
                // Append the suggested list to the existing list.
                $suggestions = array_merge($suggestions, $suggetion_template);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration()
    {
        return [
            'template_suggestion' => []
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
    {  $configration = $this->getConfiguration();
        $value = isset($configration['template_suggestion']) ? $configration['template_suggestion'] : '';
        $form['templates'] = array(
            '#title' => t('Template suggestions'),
            '#type' => 'textarea',
            '#description' => t('Enter template suggestions such as "page__front", one per line, in order of preference (using underscores instead of hyphens).'),
            '#default_value' => !empty($value['templates']) ? $value['templates'] : '',

        );
        return $form;// TODO: Implement buildConfigurationForm() method.
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
    public function submitConfigurationForm(array &$form, FormStateInterface $form_state)
    {
        $template = $form_state->getValue('templates');
        if(isset($template) && !empty($template)){
            $this->setConfiguration(array('template_suggestion' => array('templates' => $template)));
        }
    }


}