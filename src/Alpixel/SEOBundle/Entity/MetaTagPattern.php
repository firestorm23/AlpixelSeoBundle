<?php

namespace Alpixel\Bundle\SEOBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * MetaTagPattern.
 *
 * @ORM\Table(name="seo_metatag_patterns")
 * @ORM\Entity(repositoryClass="Alpixel\Bundle\SEOBundle\Entity\Repository\MetaTagPatternRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class MetaTagPattern
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="tag_id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    protected $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="controller", type="string", unique=false, nullable=false)
     */
    protected $controller;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_class", type="string", unique=false, nullable=false)
     */
    protected $entityClass;

    /**
     * @var integer
     *
     * @ORM\Column(name="meta_title", type="string", length=255, nullable=true)
     */
    protected $metaTitle;

    /**
     * @var integer
     *
     * @ORM\Column(name="meta_description", type="string", length=255, nullable=true)
     */
    protected $metaDescription;

    /**
     * @var integer
     *
     * @ORM\Column(name="meta_keywords", type="string", length=255, nullable=true)
     */
    protected $metaKeywords;

    public function __toString()
    {
        return $this->title;
    }

    public function instantiate()
    {
        if (!class_exists($this->entityClass)) {
            throw new \InvalidArgumentException($this->entityClass.' is not a valid entity');
        }

        return new $this->entityClass();
    }

    /**
     * Gets the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param integer $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of entityClass.
     *
     * @return integer
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Sets the value of entityClass.
     *
     * @param integer $entityClass the entity class
     *
     * @return self
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * Gets the value of metaTitle.
     *
     * @return integer
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Sets the value of metaTitle.
     *
     * @param integer $metaTitle the meta title
     *
     * @return self
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Gets the value of metaDescription.
     *
     * @return integer
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Sets the value of metaDescription.
     *
     * @param integer $metaDescription the meta description
     *
     * @return self
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Gets the value of metaKeywords.
     *
     * @return integer
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Sets the value of metaKeywords.
     *
     * @param integer $metaKeywords the meta keywords
     *
     * @return self
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Gets the value of title.
     *
     * @return integer
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the value of title.
     *
     * @param integer $title the title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the value of controller.
     *
     * @return integer
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Sets the value of controller.
     *
     * @param integer $controller the controller
     *
     * @return self
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }
}
