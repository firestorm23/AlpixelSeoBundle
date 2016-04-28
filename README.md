
SEOBundle
===========

The SEOBundle provides a Symfony Bundle capable of handling auto generated meta tags, specific tags by route and sitemaps.



## Installation

1. Install the package

```bash
composer require 'alpixel/seobundle'
```

3. Update AppKernel.php

```php
new Alpixel\Bundle\SEOBundle\SEOBundle(),
```


4. Update DB Schema

```
php app/console doctrine:schema:update --force --dump-sql
```

## Meta tags annotation

There are 2 options for defining meta tags in your application :

### Static tags

Work in progress


### Dynamic tags with placholders

If you have meta tags which need to be defined from entities value, you can use the @MetaTag annotation in your controller.


```php
    use Alpixel\Bundle\SEOBundle\Annotation\MetaTag;
    ...

    /**
     * @Route("/paupiette")
     * @MetaTag("paupiette", providerClass="My\Project\Entity\Paupiette", title="Paupiette page")
     */
    public function displayAction()
    {

```

After you set up the annotation, you'll need to run the following command which will register your new annotation in database.

```bash
php app/console alpixel:seo:metatag:dump
```


Then you will have a new entry in the back office on the "SEO" panel. You should be able to configure the meta tags pattern for the given controller.

The impacted entity should provide placeholders.
First, it should implements the Alpixel\Bundle\SEOBundle\Entity\MetaTagPlaceholderInterface
Then you have to implement the getPlaceholders() method in your entity. This is an example :

```php
use Alpixel\Bundle\SEOBundle\Entity\MetaTagPlaceholderInterface;
class News implements MetaTagPlaceholderInterface
{
    public function getPlaceholders() {
        return array(
            "[news:title]"  => $this->title,
            "[news:resume]" => substr(strip_tags($this->content), 0, 150)
        );
    }
}
```

