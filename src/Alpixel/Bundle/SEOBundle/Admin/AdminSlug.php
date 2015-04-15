<?php

namespace Alpixel\Bundle\SEOBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class AdminSlug extends Admin
{

    protected $baseRouteName    = 'admin_slug';
    protected $baseRoutePattern = 'slug';

    protected function configureRoutes(RouteCollection $collection)
    {
        // to remove a single route
        $collection->clearExcept(array('list'));
    }


}
