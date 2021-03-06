<?php

namespace Alpixel\Bundle\SEOBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MetaTagPattern.
 *
 * @ORM\Table(name="seo_metatag_patterns")
 * @ORM\Entity(repositoryClass="Alpixel\Bundle\SEOBundle\Entity\Repository\MetaTagPatternRepository")
 */
class MetaTagPattern extends BaseMetaTag
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="tag_id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="controller", type="string", unique=false, nullable=false)
     */
    protected $controller;

    /**
     * @var int
     *
     * @ORM\Column(name="entity_class", type="string", unique=false, nullable=false)
     */
    protected $entityClass;

    /**
     * Gets the value of id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param int $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function instantiate()
    {
        if (!class_exists($this->entityClass)) {
            throw new \InvalidArgumentException($this->entityClass.' is not a valid entity');
        }

        return new $this->entityClass();
    }

    /**
     * Gets the value of entityClass.
     *
     * @return int
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Sets the value of entityClass.
     *
     * @param int $entityClass the entity class
     *
     * @return self
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * Gets the value of controller.
     *
     * @return int
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Sets the value of controller.
     *
     * @param int $controller the controller
     *
     * @return self
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }
}
