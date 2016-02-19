<?php
namespace Alpixel\Bundle\SEOBundle\Service;

use Alpixel\Bundle\SEOBundle\Annotation as SEOAnnotation;
use Alpixel\Bundle\SEOBundle\Entity\MetaTagPattern;
use Alpixel\Bundle\SEOBundle\Entity\MetaTagPlaceholderInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Annotations\Reader;
use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContext;

class MetaTagService
{
    protected $doctrine;
    protected $sonataSEO;
    protected $annotationReader;
    protected $securityContext;

    public function __construct(SeoPage $page, Reader $reader, Registry $doctrine, SecurityContext $securityContext = null)
    {
        $this->annotationReader = $reader;
        $this->doctrine         = $doctrine;
        $this->sonataSEO        = $page;
        $this->securityContext = $securityContext;
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


        //First we check for an automatic optim
        $annotations = $this->annotationReader->getMethodAnnotations($method);

        if(!empty($annotations)) {
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

                        $title = $this->getMeta('title', $object);
                        if($title !== '') {
                            $this->sonataSEO
                                ->setTitle($title)
                            ;
                        }

                        $meta = $this->getMeta('description', $object);
                        if($meta !== '') {
                            $this->sonataSEO
                                ->addMeta('name', 'description', $meta)
                            ;
                        }

                        $meta = $this->getMeta('keywords', $object);
                        if($meta !== '') {
                            $this->sonataSEO
                                ->addMeta('name', 'keywords', $meta)
                            ;
                        }

                    }
                }
            }
        }


        //Then we check an override with a manual optimisation
        $path = $event->getRequest()->getPathinfo();
        if(preg_match('@\.[js|css]@', $path))
            return;

        $optim = $this
                    ->doctrine
                    ->getManager()
                    ->getRepository('SEOBundle:MetaTag')
                    ->findOneBy(array(
                        'url'       => $path,
                    ));

        if($optim !== null) {
            if($optim->getMetaTitle() !== null)
            {
                $this
                    ->sonataSEO
                    ->setTitle($optim->getMetaTitle())
                ;
            }

            if($optim->getMetaDescription() !== null)
            {
                $this
                    ->sonataSEO
                    ->addMeta('name', 'description', $optim->getMetaDescription())
                ;
            }

            if($optim->getMetaKeywords() !== null)
            {
                $this
                    ->sonataSEO
                    ->addMeta('name', 'keywords', $optim->getMetaKeywords())
                ;
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
