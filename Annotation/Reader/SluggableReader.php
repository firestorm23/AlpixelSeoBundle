<?php

namespace Alpixel\Bundle\SEOBundle\Annotation\Reader;

use Alpixel\Bundle\SEOBundle\Annotation\Sluggable;
use Doctrine\Common\Annotations\AnnotationReader;

class SluggableReader
{
    public function hasAnnotation($fqcn)
    {
        $reader = new AnnotationReader();
        $annotation = $reader->getClassAnnotation(new \ReflectionClass($fqcn), Sluggable::class);

        return (!empty($annotation)) ? true : false;
    }
}
