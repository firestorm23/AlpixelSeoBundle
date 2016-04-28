<?php

namespace Alpixel\Bundle\SEOBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class AdminMetaTag extends Admin
{
    protected $datagridValues = [
        '_page'       => 1,
        '_sort_by'    => 'title',
        '_sort_order' => 'ASC',
    ];

    protected function configureRoutes(RouteCollection $collection)
    {
        // to remove a single route
        $collection->remove('show');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, [
                'label' => 'ID',
            ])
            ->add('title', null, [
                'label' => 'Titre',
            ])
            ->add('url', null, [
                'label' => 'URL',
            ]);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, [
                'label' => 'ID',
            ])
            ->addIdentifier('title', null, [
                'label' => 'Titre',
            ])
            ->add('url', null, [
                'label' => 'URL',
            ])
            ->add('metaTitle', null, [
                'label' => 'Meta : Titre',
            ])
            ->add('metaDescription', null, [
                'label' => 'Meta : Description',
            ])
            ->add('metaKeywords', null, [
                'label' => 'Meta : mots clefs',
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit'   => [],
                ],
            ]);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, [
                'label' => 'Titre',
            ])
            ->add('url', 'text', [
                'label' => 'URL',
            ])
            ->add('metaTitle', null, [
                'label' => 'Meta : Titre',
            ])
            ->add('metaDescription', null, [
                'label' => 'Meta : Description',
            ])
            ->add('metaKeywords', null, [
                'label' => 'Meta : mots clefs',
            ])
            ->setHelps([
                'url' => 'Doit être de la forme /mon-url (pas de http:// ou www.)',
            ]);
    }
}
