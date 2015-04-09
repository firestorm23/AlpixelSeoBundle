<?php

namespace Alpixel\Bundle\SEOBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Provides action to render sitemap files.
 *
 * @author David Epely <depely@prestaconcept.net>
 */
class SitemapController extends Controller
{
    /**
     * list sitemaps.
     *
     * @Route("%seo.sitemap.file_prefix%.xml", name="seo_sitemap")
     *
     * @param $_format
     *
     * @return Response
     */
    public function indexAction()
    {
        $sitemapindex = $this->get('seo.sitemap.generator')->fetch('root');

        if (!$sitemapindex) {
            throw $this->createNotFoundException();
        }

        $response = Response::create($sitemapindex->toXml());
        $response->setPublic();
        $response->setClientTtl($this->getTtl());

        return $response;
    }

    /**
     * list urls of a section.
     *
     * @Route("%seo.sitemap.file_prefix%.{name}.xml", name="seo_sitemap_section")
     *
     * @param string
     *
     * @return Response
     */
    public function sectionAction($name)
    {
        $section = $this->get('seo.sitemap.generator')->fetch($name);

        if (!$section) {
            throw $this->createNotFoundException();
        }

        $response = Response::create($section->toXml());
        $response->setPublic();
        $response->setClientTtl($this->getTtl());

        return $response;
    }

    /**
     * Time to live of the response in seconds.
     *
     * @return int
     */
    protected function getTtl()
    {
        return $this->container->getParameter('seo.sitemap.timetolive');
    }
}
