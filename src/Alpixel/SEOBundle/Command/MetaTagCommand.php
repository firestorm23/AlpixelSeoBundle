<?php
namespace Alpixel\Bundle\SEOBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Alpixel\Bundle\SEOBundle\Entity\MetaTagPattern;
use Alpixel\Bundle\SEOBundle\Annotation as SEOAnnotation;

class MetaTagCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName("seo:metatag:patterns")
            ->setDescription("Generate patterns in database")
         ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $routes         = $this->getContainer()->get('router')->getRouteCollection()->all();
        $entityManager  = $this->getContainer()->get('doctrine')->getManager();

        foreach ($routes as $route) {
            $defaults = $route->getDefaults();

            if (empty($defaults['_controller']) || is_null($controller = $defaults['_controller'])) {
                continue;
            }

            if (3 !== count($parts = explode(':', $controller))) {
                continue;
            }

            list($class, $method) = explode('::', $controller, 2);
            $method = new \ReflectionMethod($class, $method);

            if (!$annotations = $this->getContainer()->get('annotation_reader')->getMethodAnnotations($method)) {
                continue;
            }

            foreach ($annotations as $annotation) {
                if ($annotation instanceof SEOAnnotation\MetaTag) {
                    $exists = $entityManager
                                ->getRepository('SEOBundle:MetaTagPattern')
                                ->findOneBy(array(
                                    'controller'   => $controller,
                                    'entityClass'  => $annotation->providerClass,
                                ));

                    if (is_null($exists)) {
                        $pattern = new MetaTagPattern();
                        $pattern->setTitle($annotation->title);
                        $pattern->setEntityClass($annotation->providerClass);
                        $pattern->setController($controller);
                        $entityManager->persist($pattern);
                    }
                }
            }
        }
        $entityManager->flush();
    }
}
