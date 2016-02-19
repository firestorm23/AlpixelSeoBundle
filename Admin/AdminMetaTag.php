<?php

namespace Alpixel\Bundle\SEOBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class AdminMetaTag extends Admin
{
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_by'    => 'title',
        '_sort_order' => 'ASC',
    );

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
            ->add('id', null, array(
                'label' => 'ID',
            ))
            ->add('title', null, array(
                'label' => 'Titre',
            ))
            ->add('url', null, array(
                'label' => 'URL',
            ))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array(
                'label' => 'ID',
            ))
            ->addIdentifier('title', null, array(
                'label' => 'Titre',
            ))
            ->add('url', null, array(
                'label' => 'URL',
            ))
            ->add('metaTitle', null, array(
                'label' => 'Meta : Titre',
            ))
            ->add('metaDescription', null, array(
                'label' => 'Meta : Description',
            ))
            ->add('metaKeywords', null, array(
                'label' => 'Meta : mots clefs',
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array(
                'label' => 'Titre',
            ))
            ->add('url', 'text', array(
                'label' => 'URL',
            ))
            ->add('metaTitle', null, array(
                'label' => 'Meta : Titre',
            ))
            ->add('metaDescription', null, array(
                'label' => 'Meta : Description',
            ))
            ->add('metaKeywords', null, array(
                'label' => 'Meta : mots clefs',
            ))
            ->setHelps(array(
                'url' => 'Doit être de la forme /mon-url (pas de http:// ou www.)',
            ))
        ;
    }
}
