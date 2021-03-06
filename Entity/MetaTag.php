<?php

namespace Alpixel\Bundle\SEOBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MetaTag.
 *
 * @ORM\Table(name="seo_metatag")
 * @ORM\Entity(repositoryClass="Alpixel\Bundle\SEOBundle\Entity\Repository\MetaTagRepository")
 */
class MetaTag extends BaseMetaTag
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
     * @ORM\Column(name="url", type="text", nullable=false)
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

    /**
     * Gets the value of url.
     *
     * @return int
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the value of url.
     *
     * @param int $url the url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
