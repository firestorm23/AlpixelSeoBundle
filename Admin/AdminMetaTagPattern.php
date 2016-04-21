<?php

namespace Alpixel\Bundle\SEOBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class AdminMetaTagPattern extends Admin
{
    protected $datagridValues = [
        '_page'       => 1,
        '_sort_by'    => 'title',
        '_sort_order' => 'ASC',
    ];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['edit', 'list']);
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
            ]);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title', null, [
                'label' => 'Titre',
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
        $container = $this->getConfigurationPool()->getContainer();
        $pattern = $this->getSubject();

        $patterns = $container->get('seo.tags')->getPlaceholders($pattern);
        $help = $container->get('templating')->render('SEOBundle:admin:blocks/help_message.html.twig', ['placeholders' => $patterns]);

        $formMapper
            ->with('Edition du modèle de metatags : '.$pattern->getTitle())
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
                'metaTitle' => $help,
            ])
            ->end();
    }
}
