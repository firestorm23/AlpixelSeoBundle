<?php

namespace Alpixel\Bundle\SEOBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;

class AdminSlug extends Admin
{
    protected $baseRouteName    = 'admin_slug';
    protected $baseRoutePattern = 'slug';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list'));
    }
}
