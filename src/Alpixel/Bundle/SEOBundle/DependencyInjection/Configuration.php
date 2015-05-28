<?php

namespace Alpixel\Bundle\SEOBundle\DependencyInjection;

use Alpixel\Bundle\SEOBundle\Sitemap\XmlConstraint;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    const DEFAULT_FILENAME = 'sitemap';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('seo');

        $rootNode
            ->children()
                ->scalarNode('sitemap_timetolive')
                    ->defaultValue('3600')
                ->end()
                ->scalarNode('sitemap_base_url')
                    ->defaultValue('')
                ->end()
                ->scalarNode('sitemap_file_prefix')
                    ->defaultValue(self::DEFAULT_FILENAME)
                    ->info('Sets sitemap filename prefix defaults to "sitemap" -> sitemap.xml (for index); sitemap.<section>.xml(.gz) (for sitemaps)')
                ->end()
                ->scalarNode('sitemap_items_by_set')
                    // Add one to the limit items value because it's an
                    // index value (not a quantity)
                    ->defaultValue(XmlConstraint::LIMIT_ITEMS + 1)
                    ->info('The maximum number of items allowed in single sitemap.')
                ->end()
                ->scalarNode('sitemap_route_annotation_listener')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
