<?php

namespace Alpixel\Bundle\SEOBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SEOExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter($this->getAlias().'.sitemap.base_url', $config['sitemap_base_url']);
        $container->setParameter($this->getAlias().'.sitemap.timetolive', $config['sitemap_timetolive']);
        $container->setParameter($this->getAlias().'.sitemap.file_prefix', $config['sitemap_file_prefix']);
        $container->setParameter($this->getAlias().'.sitemap.items_by_set', $config['sitemap_items_by_set']);

        if (true === $config['sitemap_route_annotation_listener']) {
            $loader->load('route_annotation_listener.yml');
        }
    }
}
