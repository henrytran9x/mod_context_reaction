<?php

namespace Drupal\mod_context_reaction\Plugin\DisplayVariant;


use Drupal\context\ContextManager;
use Drupal\Core\Display\VariantBase;
use Drupal\Core\Display\PageVariantInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a page display variant that decorates the main content with blocks.
 *
 * @see \Drupal\Core\Block\MainContentBlockPluginInterface
 * @see \Drupal\Core\Block\MessagesBlockPluginInterface
 *
 * @PageDisplayVariant(
 *   id = "context_class_page",
 *   admin_label = @Translation("Class with page")
 * )
 */

class ContextClassPageVariant extends  VariantBase implements PageVariantInterface,ContainerFactoryPluginInterface{

    /**
     * @var ContextManager
     */
    protected $contextManager;

    /**
     * The render array representing the main page content.
     *
     * @var array
     */
    protected $mainContent = [];

    /**
     * The page title: a string (plain title) or a render array (formatted title).
     *
     * @var string|array
     */
    protected $title = '';

    /**
     * {@inheritdoc}
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, ContextManager $contextManager) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->contextManager = $contextManager;
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
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('context.manager')
        );
    }

    /**
     * Sets the main content for the page being rendered.
     *
     * @param array $main_content
     *   The render array representing the main content.
     *
     * @return $this
     */
    public function setMainContent(array $main_content)
    {
        $this->mainContent = $main_content;
        return $this;
    }

    /**
     * Sets the title for the page being rendered.
     *
     * @param string|array $title
     *   The page title: either a string for plain titles or a render array for
     *   formatted titles.
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Builds and returns the renderable array for the display variant.
     *
     * The variant can contain cacheability metadata for the configuration that
     * was passed in setConfiguration(). In the build() method, this should be
     * added to the render array that is returned.
     *
     * @return array
     *   A render array for the display variant.
     */
    public function build()
    {
        var_dump('Debug');
    }


}