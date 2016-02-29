<?php

namespace Alpixel\Bundle\SEOBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * The Sluggable annotation class authorize a manual
 * update of the slug from the administration interface.
 *
 * @Annotation
 * @Target("CLASS")
 */
class Sluggable extends Annotation
{
}
