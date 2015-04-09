<?php

namespace Alpixel\Bundle\SEOBundle\Sitemap;

/**
 * Xml requirements for sitemap protocol.
 *
 * @see http://www.sitemaps.org/protocol.html
 *
 * @author depely
 */
abstract class XmlConstraint implements \Countable
{
    const LIMIT_ITEMS = 49999;
    const LIMIT_BYTES = 10000000; // 10,485,760 bytes - 485,760

    protected $limitItemsReached = false;
    protected $limitBytesReached = false;
    protected $countBytes = 0;
    protected $countItems = 0;

    /**
     * @return bool
     */
    public function isFull()
    {
        return $this->limitItemsReached || $this->limitBytesReached;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->countItems;
    }

    /**
     * Render full and valid xml.
     */
    abstract public function toXml();
}
