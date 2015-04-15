<?php

namespace Alpixel\Bundle\SEOBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

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

        return $this->render('SEOBundle:admin:pages/list.html.twig', array(
            'action'     => 'list',
            'objects'    => $objects,
        ));
    }
}
