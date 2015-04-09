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
class MetaTag extends MetaTagPattern
{
    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    protected $entityId;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Gets the value of entityId.
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Sets the value of entityId.
     *
     * @param integer $entityId the entity id
     *
     * @return self
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }
}
