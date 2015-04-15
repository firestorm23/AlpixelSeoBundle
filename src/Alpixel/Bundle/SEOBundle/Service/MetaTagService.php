<?php
namespace Alpixel\Bundle\SEOBundle\Service;

use Alpixel\Bundle\SEOBundle\Entity\MetaTagPattern;
use Alpixel\Bundle\SEOBundle\Annotation as SEOAnnotation;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Annotations\FileCacheReader;
use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Alpixel\Bundle\SEOBundle\Entity\MetaTagPlaceholderInterface;

class MetaTagService
{
    protected $doctrine;
    protected $sonataSEO;
    protected $annotationReader;

    public function __construct(SeoPage $page, FileCacheReader $reader, Registry $doctrine)
    {
        $this->annotationReader = $reader;
        $this->doctrine         = $doctrine;
        $this->sonataSEO        = $page;
    }

    public function onControllerFound(FilterControllerEvent $event)
    {
        if (!is_array($controllerData = $event->getController())) {
            return;
        }

        $controllerData  = $event->getController();

        if($controllerData[0] === null) {
            return;
        }

        $method      = new \ReflectionMethod($controllerData[0], $controllerData[1]);

        if (!$annotations = $this->annotationReader->getMethodAnnotations($method)) {
            return;
        }

        foreach ($annotations as $annotation) {
            if ($annotation instanceof SEOAnnotation\MetaTag) {
                $request    = $controllerData[0]->getRequest();
                $controller = $request->get('_controller');
                $object     = $request->get($annotation->value);

                if(empty($object))
                    continue;

                $class      = new \ReflectionClass($object);

                $exists = $this
                            ->doctrine
                            ->getManager()
                            ->getRepository('SEOBundle:MetaTagPattern')
                            ->findOneBy(array(
                                'controller'    => $controller,
                                'entityClass'   => $class->getName(),
                            ));

                if (!is_null($exists)) {
                    $this->sonataSEO
                        ->setTitle($this->getMeta('title', $object))
                        ->addMeta('name', 'description', $this->getMeta('description', $object))
                        ->addMeta('name', 'keywords', $this->getMeta('keywords', $object))
                    ;
                }
            }
        }
    }

    public function getMeta($type, MetaTagPlaceholderInterface $object)
    {
        //Fetch the placeholders
        $pattern  = $this->doctrine
                         ->getManager()
                         ->getRepository('SEOBundle:MetaTagPattern')
                         ->findOneByEntityClass(get_class($object))
                     ;

        if (empty($pattern)) {
            return;
        }

        $placeholders = $object->getPlaceholders($pattern);

        switch ($type) {
            case "title":
                return strtr($pattern->getMetaTitle(), $placeholders);
            break;
            case "description":
                return strtr($pattern->getMetaDescription(), $placeholders);
            break;
            case "keywords":
                return strtr($pattern->getMetaKeywords(), $placeholders);
            break;
        }
    }

    public function getPlaceholders(MetaTagPattern $pattern)
    {
        if (!is_null($pattern->getEntityClass())) {
            return $pattern
                    ->instantiate()
                    ->getPlaceholders()
            ;
        }
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}
