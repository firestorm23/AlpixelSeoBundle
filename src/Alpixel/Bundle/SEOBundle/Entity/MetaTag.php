<?php

namespace Alpixel\Bundle\SEOBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * MetaTag.
 *
 * @ORM\Table(name="seo_metatag")
 * @ORM\Entity(repositoryClass="Alpixel\Bundle\SEOBundle\Entity\Repository\MetaTagRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class MetaTag extends BaseMetaTag
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
     * @ORM\Column(name="url", type="text", nullable=false, unique=true)
     */
    protected $url;

    /**
     * Constructor.
     */
    public function __construct()
    {
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
     * Gets the value of url.
     *
     * @return integer
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the value of url.
     *
     * @param integer $url the url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
