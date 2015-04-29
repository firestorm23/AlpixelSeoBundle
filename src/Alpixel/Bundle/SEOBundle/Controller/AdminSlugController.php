<?php

namespace Alpixel\Bundle\SEOBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Alpixel\Bundle\SEOBundle\Form\SlugForm;

class AdminSlugController extends CRUDController
{

    const MAX_ELEMENTS_PER_PAGE = 100;

    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $entities      = array();
        $entityManager = $this->getDoctrine()->getManager();
        $meta          = $entityManager->getMetadataFactory()->getAllMetadata();

        foreach ($meta as $m) {
            if(in_array('Alpixel\\Bundle\\SEOBundle\\Entity\\SluggableTrait', class_uses($m->getName()))) {
                $entities[] = $m->getName();
            }
        }

        $page = (int) $this->get('request')->query->get('page');

        if($page <= 0) {
            $page = 1;
        }

        $previous = 1;
        $next     = 2;

        if($page > $previous) {
            $previous = $page - 1;
        }

        if($page >= $next) {
           $next = $page + 1;
        }

        $objects = array();
        foreach($entities as $entity) {
            $founds = $entityManager
                        ->getRepository($entity)
                        ->createQueryBuilder('e')
                        ->setFirstResult(($page - 1) * self::MAX_ELEMENTS_PER_PAGE)
                        ->setMaxResults(self::MAX_ELEMENTS_PER_PAGE)
                        ->getQuery()
                        ->getResult()
            ;
            foreach($founds as $found) {
                $objects[] = $found;
            }
        }

        $form          = $this->createForm(new SlugForm($objects));
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                foreach($objects as $object) {
                    $oldSlug = $object->getSlug();
                    $newSlug = $form->get('slug_'.md5(get_class($object).$object->getId()))->getData();

                    if($oldSlug !== $newSlug) {
                        $object->setSlug($newSlug);
                        $entityManager->persist($object);
                    }
                }
                $entityManager->flush();
                $this->addFlash(
                    'sonata_flash_success',
                    $this->admin->trans(
                        'Modifications enregistrées'
                    )
                );
            }
        }

        return $this->render('SEOBundle:admin:pages/list.html.twig', array(
            'action'   => 'list',
            'objects'  => $objects,
            'form'     => $form->createView(),
            'page'     => $page,
            'previous' => $previous,
            'next'     => $next,
        ));
    }
}
