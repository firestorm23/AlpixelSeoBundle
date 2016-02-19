<?php

namespace Alpixel\Bundle\SEOBundle\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @deprecated should be removed in 2.0.  you should use gedmo/sluggable and the sluggableInterface
 **/
trait SluggableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    protected $slug;

    public function generateSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }

        return $this->slug;
    }

    /**
     * @ORM\PrePersist
     */
    public function persistSlug()
    {
        $this->generateSlug();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateSlug(PreUpdateEventArgs $eventArgs)
    {
        $this->generateSlug();
    }

    public function getClassName()
    {
        return get_class($this);
    }

    /**
     * Gets the value of slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Sets the value of slug.
     *
     * @param string $slug the slug
     *
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}
