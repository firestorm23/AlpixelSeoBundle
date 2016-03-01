<?php

namespace Alpixel\Bundle\SEOBundle\Service;

use Alpixel\Bundle\SEOBundle\Sitemap;
use Doctrine\Common\Cache\Cache;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Sitemap Manager service.
 *
 * @author David Epely <depely@prestaconcept.net>
 * @author Christophe Dolivet
 * @author Konstantin Myakshin <koc-dp@yandex.ru>
 */
class Generator extends AbstractGenerator
{
    protected $router;
    protected $cache;
    protected $cacheTtl;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param int                      $itemsBySet
     * @param RouterInterface          $router
     * @param Cache|null               $cache
     * @param int|null                 $cacheTtl
     */
    public function __construct(EventDispatcherInterface $dispatcher, RouterInterface $router, Cache $cache = null, $cacheTtl = null, $itemsBySet = null)
    {
        parent::__construct($dispatcher, $itemsBySet);
        $this->router = $router;
        $this->cache = $cache;
        $this->cacheTtl = $cacheTtl;
    }

    /**
     * Generate all datas and store in cache if it is possible.
     */
    public function generate()
    {
        $this->populate();

        //---------------------
        //---------------------
        // cache management
        if ($this->cache) {
            $this->cache->save('root', $this->getRoot(), $this->cacheTtl);

            foreach ($this->urlsets as $name => $urlset) {
                $this->cache->save($name, $urlset, $this->cacheTtl);
            }
        }
        //---------------------
    }

    /**
     * Get eventual cached data or generate whole sitemap.
     *
     * @param string $name
     *
     * @return Sitemap\Sitemapindex or Urlset - can be <null>
     */
    public function fetch($name)
    {
        if ($this->cache && $this->cache->contains($name)) {
            return $this->cache->fetch($name);
        }

        $this->generate();

        if ('root' == $name) {
            return $this->getRoot();
        }

        if (array_key_exists($name, $this->urlsets)) {
            return $this->urlsets[$name];
        }
    }

    /**
     * Factory method for create Urlsets.
     *
     * @param string $name
     *
     * @return Sitemap\Urlset
     */
    protected function newUrlset($name, \DateTime $lastmod = null)
    {
        return new Sitemap\Urlset(
            $this->router->generate('seo_sitemap_section', ['name' => $name, '_format' => 'xml'], true),
            $lastmod
        );
    }
}
