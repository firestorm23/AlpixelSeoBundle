<?php

namespace Alpixel\Bundle\SEOBundleDependencyInjection\Compiler;

use Alpixel\Bundle\SEOBundleEvent\SitemapPopulateEvent;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registering services tagged with seo.sitemap.listener as actual event listeners.
 *
 * @author Konstantin Tjuterev <kostik.lv@gmail.com>
 */
class AddSitemapListenersPass implements CompilerPassInterface
{
    /**
     * Adds services tagges as seo.sitemap.listener as event listeners for
     * corresponding sitemap event.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @throws \InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('event_dispatcher') && !$container->hasAlias('event_dispatcher')) {
            return;
        }
        $definition = $container->findDefinition('event_dispatcher');
        foreach ($container->findTaggedServiceIds('seo.sitemap.listener') as $id => $tags) {
            $class = $container->getDefinition($id)->getClass();
            $refClass = new \ReflectionClass($class);
            $interface = 'Alpixel\Bundle\SEOBundleService\SitemapListenerInterface';
            if (!$refClass->implementsInterface($interface)) {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, $interface));
            }
            $definition->addMethodCall(
                'addListenerService',
                [SitemapPopulateEvent::ON_SITEMAP_POPULATE, [$id, 'populateSitemap']]
            );
        }
    }
}
