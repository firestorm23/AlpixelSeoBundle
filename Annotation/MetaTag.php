<?php

namespace Alpixel\Bundle\SEOBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * The MetaTag class handles the MetaTag annotation parts.
 *
 *
 * @author Benjamin HUBERT <benjamin@alpixel.fr>
 * @Annotation
 * @Target("METHOD")
 */
class MetaTag extends Annotation
{
    public $value;
    public $title;
    public $providerClass;
}
