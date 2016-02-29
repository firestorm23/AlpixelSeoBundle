<?php

namespace Alpixel\Bundle\SEOBundle\Twig;

class SEOUtilsExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('md5', [$this, 'md5']),
            new \Twig_SimpleFilter('class_name', [$this, 'getClassName']),
        ];
    }

    public function md5($data)
    {
        return md5($data);
    }

    public function getClassName($object)
    {
        return get_class($object);
    }

    public function getName()
    {
        return 'alpixel_seo_extension_md5';
    }
}
