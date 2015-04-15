<?php

namespace Alpixel\Bundle\SEOBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class AdminMetaTagPattern extends Admin
{
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_by'    => 'title',
        '_sort_order' => 'ASC',
    );

    protected function configureRoutes(RouteCollection $collection)
    {
        // to remove a single route
        $collection->remove('create');
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
                'label' => 'Type',
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
                'label' => 'Type',
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
        $container = $this->getConfigurationPool()->getContainer();
        $pattern   = $this->getSubject();

        $patterns  = $container->get('seo.tags')->getPlaceholders($pattern);
        $help      = $container->get('templating')->render('SEOBundle:admin:blocks/help_message.html.twig', array('placeholders' => $patterns));

        $formMapper
            ->with('Edition du modèle de metatags : '.$pattern->getTitle())
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
                'metaTitle' => $help,
            ))
            ->end()
        ;
    }
}
