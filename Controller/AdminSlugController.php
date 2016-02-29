<?php

namespace Alpixel\Bundle\SEOBundle\Controller;

use Alpixel\Bundle\SEOBundle\Annotation\Reader\SluggableReader;
use Alpixel\Bundle\SEOBundle\Form\SlugForm;
use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;

class AdminSlugController extends CRUDController
{
    const MAX_ELEMENTS_PER_PAGE = 50;

    public function listAction(Request $request = null)
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entities = $this->getSluggableEntities($entityManager);
        $objects = $this->resolveSluggableEntities($request, $entityManager, $entities);
        $form = $this->slugForm($request, $entityManager, $objects);
        if ($form === true) {
            return $this->redirect($request->getRequestUri());
        }

        return $this->render('SEOBundle:admin:pages/list.html.twig', [
            'action'     => 'list',
            'objects'    => $objects,
            'form'       => $form->createView(),
            'pagination' => $this->getPagination($request),
        ], null, $request);
    }

    private function getPagination(Request $request = null)
    {
        $page = $request->query->get('page');

        if ($page <= 0) {
            $page = 1;
        }

        $previous = 1;
        $next = 2;

        if ($page > $previous) {
            $previous = $page - 1;
        }

        if ($page >= $next) {
            $next = $page + 1;
        }

        return ['previous' => $previous, 'next' => $next];
    }

    private function slugForm(Request $request = null, EntityManager $entityManager, $objects = [])
    {
        $form = $this->createForm(new SlugForm($objects));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                foreach ($objects as $object) {
                    $oldSlug = $object->getSlug();
                    $newSlug = $form->get('slug_'.md5(get_class($object).$object->getId()))->getData();

                    if ($oldSlug !== $newSlug) {
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
                return true;
            }
        }

        return $form;
    }

    private function getSluggableEntities(EntityManager $entityManager)
    {
        $entities = [];
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $sluggableReader = new SluggableReader();
        foreach ($metadata as $class) {
            $fqcn = $class->getName();
            if ($sluggableReader->hasAnnotation($fqcn)) {
                $entities[] = $class->getName($fqcn);
            }
        }

        return $entities;
    }

    private function resolveSluggableEntities(Request $request = null, EntityManager $entityManager, $entities = [])
    {
        $page = $request->query->get('page');
        if ($page <= 0) {
            $page = 1;
        }

        $objects = [];
        foreach ($entities as $entity) {
            $founds = $entityManager
                ->getRepository($entity)
                ->createQueryBuilder('e')
                ->setFirstResult(($page - 1) * self::MAX_ELEMENTS_PER_PAGE)
                ->setMaxResults(self::MAX_ELEMENTS_PER_PAGE)
                ->getQuery()
                ->getResult();
            foreach ($founds as $found) {
                $objects[] = $found;
            }
        }

        return $objects;
    }
}
