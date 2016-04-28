<?php

namespace Alpixel\Bundle\SEOBundle\Command;

use Alpixel\Bundle\SEOBundle\Annotation as SEOAnnotation;
use Alpixel\Bundle\SEOBundle\Entity\MetaTagPattern;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class MetaTagCommand extends ContainerAwareCommand
{
    protected $savedController = [];

    public function configure()
    {
        $this
            ->setName('alpixel:seo:metatag:dump')
            ->setDescription('Save automatic patterns in database');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $routes = $this->getContainer()->get('router')->getRouteCollection()->all();
        $entityManager = $this->getContainer()->get('doctrine')->getManager();

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
                    $this->saveAnnotation($controller, $annotation);
                }
            }
        }

        // Load all registered bundles
        $bundles = $this->getContainer()->getParameter('kernel.bundles');

        foreach ($bundles as $name => $class) {
            $namespaceParts = explode('\\', $class);
            // remove class name
            array_pop($namespaceParts);
            $bundleNamespace = implode('\\', $namespaceParts);
            $rootPath = $this->getContainer()->get('kernel')->getRootDir().'/../src/';
            $controllerDir = $rootPath.$bundleNamespace.'/Controller';
            $controllerDir = strtr($controllerDir, ['\\' => '/']);
            if (is_dir($controllerDir)) {
                $finder = new Finder();
                $files = $finder->in($controllerDir)->name('*.php');

                foreach ($files as $file) {
                    $filename = basename($file->getFilename(), '.'.$file->getExtension());
                    $basePath = '\\'.strtr(strtr($file->getPath(), [$rootPath => '']), ['/' => '\\']);

                    $class = $basePath.'\\'.$filename;
                    $reflectedClass = new \ReflectionClass($class);

                    foreach ($reflectedClass->getMethods() as $reflectedMethod) {
                        // the annotations
                        $annotations = $this->getContainer()->get('annotation_reader')->getMethodAnnotations($reflectedMethod);
                        foreach ($annotations as $annotation) {
                            if ($annotation instanceof SEOAnnotation\MetaTag) {
                                $this->saveAnnotation(ltrim($class, '\\').'::'.$reflectedMethod->getName(), $annotation);
                            }
                        }
                    }
                }
            }
        }

        //Cleaning up old controllers
        $patterns = $entityManager
            ->getRepository('SEOBundle:MetaTagPattern')
            ->findAll();

        foreach ($patterns as $pattern) {
            if (!in_array($pattern->getController(), $this->savedController)) {
                $entityManager->remove($pattern);
            }
        }

        $entityManager->flush();
    }

    protected function saveAnnotation($controller, $annotation)
    {
        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        $exists = $entityManager
            ->getRepository('SEOBundle:MetaTagPattern')
            ->findOneBy([
                'controller'  => $controller,
                'entityClass' => $annotation->providerClass,
            ]);

        $this->savedController[] = $controller;

        if (is_null($exists)) {
            $pattern = new MetaTagPattern();
            $pattern->setTitle($annotation->title);
            $pattern->setEntityClass($annotation->providerClass);
            $pattern->setController($controller);
            $entityManager->persist($pattern);
        }
    }
}
