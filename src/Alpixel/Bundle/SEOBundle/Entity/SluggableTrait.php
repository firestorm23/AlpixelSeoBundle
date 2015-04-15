<?php
namespace Alpixel\Bundle\SEOBundle\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreUpdateEventArgs;


trait SluggableTrait
{

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    public $slug;


    public function generateSlug() {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }

        return $this->slug;
    }

    /**
     * @ORM\PrePersist
     */
    public function persistSlug() {
        $this->generateSlug();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateSlug(PreUpdateEventArgs $eventArgs) {
        $this->generateSlug();
    }

    public function getClassName() {
        return get_class($this);
    }

}
