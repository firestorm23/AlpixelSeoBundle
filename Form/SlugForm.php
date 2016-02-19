<?php

namespace Alpixel\Bundle\SEOBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class SlugForm extends AbstractType
{
    protected $objects;

    public function __construct($objects)
    {
        $this->objects = $objects;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->objects as $object) {
            $builder
                ->add('slug_'.md5(get_class($object).$object->getId()), 'text', [
                    'label'          => 'Nom du métier',
                    'error_bubbling' => true,
                    'data'           => $object->getSlug(),
                    'required'       => true,
                    'constraints'    => [
                       new Constraints\NotBlank(['message' => 'Le champ nom ne doit pas être vide']),
                    ],
                ]);
        }
    }

    public function getName()
    {
        return 'slugForm';
    }
}
