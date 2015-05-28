<?php

namespace Alpixel\Bundle\SEOBundle\Event;

use Alpixel\Bundle\SEOBundle\Service\AbstractGenerator;
use Symfony\Component\EventDispatcher\Event;

/**
 * Manage populate event.
 *
 * @author depely
 */
class SitemapPopulateEvent extends Event
{
    const ON_SITEMAP_POPULATE = 'seo.sitemap.populate';

    protected $generator;

    /**
     * Allows creating EventListeners for particular sitemap sections, used when dumping.
     *
     * @var string
     */
    protected $section;

    public function __construct(AbstractGenerator $generator, $section = null)
    {
        $this->generator = $generator;
        $this->section = $section;
    }

    /**
     * @return AbstractGenerator
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * Section to be processed, null means any.
     *
     * @return null|string
     */
    public function getSection()
    {
        return $this->section;
    }
}
