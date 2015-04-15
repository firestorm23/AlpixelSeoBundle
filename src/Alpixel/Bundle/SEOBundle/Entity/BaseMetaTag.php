<?php

namespace Alpixel\Bundle\SEOBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


abstract class BaseMetaTag
{
    /**
     * @var integer
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    protected $title;


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

}
