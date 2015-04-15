
SEOBundle
===========

The SEOBundle provides a Symfony Bundle capable of handling auto generated meta tags, specific tags by route and sitemaps.



## Installation

1. Install the package

```bash
composer require 'alpixel/seobundle:~1.0'
```

2. Update routing.yml

```yaml
seo:
    resource: "@SEOBundle/Controller/"
    type:     annotation
    prefix:   /    
```


3. Update AppKernel.php

```php
new Alpixel\Bundle\SEOBundle\SEOBundle(),
```


4. Update DB Schema

```
php app/console doctrine:schema:update --force --dump-sql
```

5. Update deploy.rb

```ruby
    run "php #{current_path}/app/console seo:metatag:patterns"
    run "php #{current_path}/app/console seo:sitemap"
```

## Meta tags annotation

There are 2 options for defining meta tags in your application : 

### Static tags

Work in progress


### Dynamic tags with placholders

If you have meta tags which need to be defined from entities value, you can use the @MetaTag annotation in your controller.


```php

    /**
     * @Route("/paupiette")
     * @MetaTag("paupiette", providerClass="My\Project\Entity\Paupiette", title="Paupiette page")
     */
    public function displayAction()
    {

```

After you set up the annotation, you'll need to run the following command which will register your new annotation in database.

```bash
php app/console seo:metatag:patterns
```


Then you will have a new entry in the back office on the "SEO" panel. You should be able to configure the meta tags pattern for the given controller.


## Sitemap

There are two options to provide the sitemap.xml with your routes :

### Manual declaration

For simple case, like a homepage, you can include the route in your site map with the follow annotation in your controller :

```php
    /**
     * @Route("/", name="front_home", options={"sitemap" = true})
    public function homepageAction()
    {
```

### Batch declaration

In more complexe case, you'll need to setup a listener which will be in charge to provide your sitemap.xml.

First you start to declare a new listener in the services.yml of your bundle (example from OKAZADO project) :

```yaml  
services:   
    ad.sitemap:
        class: Okazado\AdBundle\Listener\SitemapListener
        arguments: [@doctrine, @router]
        tags:
            - { name: kernel.event_listener, event: 'seo.sitemap.populate', method: populateSitemap }
```

Then you have to setup the listener. This is an example of a setup on OKAZADO :

```php      
<?php

namespace Okazado\AccountBundle\Listener;

use Alpixel\Component\SEOBundle\Service\SitemapListenerInterface;
use Alpixel\Component\SEOBundle\Event\SitemapPopulateEvent;
use Alpixel\Component\SEOBundle\Sitemap\Url\UrlConcrete;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Routing\RouterInterface;

class SitemapListener implements SitemapListenerInterface
{
    protected $doctrine;
    private $router;

    public function __construct(Registry $doctrine, RouterInterface $router)
    {
        $this->doctrine = $doctrine;
        $this->router   = $router;
    }

    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $section = $event->getSection();
        if (is_null($section) || $section == 'account') {
            $accounts = $this->doctrine
                            ->getManager()
                            ->getRepository('AccountBundle:User')
                            ->findAllActive()
                        ;

            foreach ($accounts as $user) {
                $url = $this->router->generate('front_account', array('user'=>$user->getUsernameCanonical()), true);
                $event->getGenerator()->addUrl(
                    new UrlConcrete(
                        $url,
                        new \DateTime(),
                        UrlConcrete::CHANGEFREQ_MONTHLY,
                        .7
                    ),
                    'account'
                );
            }
        }
    }
}
```



## Avalaible commands

php app/console seo:metatag:patterns
php app/console seo:sitemap