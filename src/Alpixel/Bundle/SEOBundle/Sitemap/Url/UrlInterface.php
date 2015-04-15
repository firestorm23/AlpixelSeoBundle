<?php

namespace Alpixel\Bundle\SEOBundle\Sitemap\Url;

/**
 * Representation of an Url in urlset.
 *
 * @author depely
 */
interface UrlInterface
{
    /**
     * render element as xml.
     *
     * @return string
     */
    public function toXml();

    /**
     * list of used namespaces.
     *
     * @return array - [{ns} => {location}]
     */
    public function getCustomNamespaces();
}
