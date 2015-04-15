<?php

namespace Alpixel\Bundle\SEOBundle\Service;

use Alpixel\Bundle\SEOBundle\Event\SitemapPopulateEvent;

/**
 * Inteface for sitemap event listeners.
 *
 * @author Konstantin Tjuterev <kostik.lv@gmail.com>
 */
interface SitemapListenerInterface
{
    /**
     * @abstract
     * Should check $event->getSection() and then populate the sitemap
     * using $event->getGenerator()->addUrl(\Alpixel\Bundle\SEOBundleSitemap\Url\Url $url, $section)
     * if $event->getSection() is null or matches the listener's section
     *
     * @param \Alpixel\Bundle\SEOBundleEvent\SitemapPopulateEvent $event
     */
    public function populateSitemap(SitemapPopulateEvent $event);
}
