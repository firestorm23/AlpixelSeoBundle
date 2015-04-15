<?php

namespace Alpixel\Bundle\SEOBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Alpixel\Bundle\SEOBundle\Form\SlugForm;

class AdminSlugController extends CRUDController
{
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

        $objects        = array();
        foreach($entities as $entity) {
            $founds = $entityManager
                        ->getRepository($entity)
                        ->findAll()
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
            'action'     => 'list',
            'objects'    => $objects,
            'form'       => $form->createView(),
        ));
    }
}
