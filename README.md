
CRONBundle
===========

The CRONBundle provides a Symfony Bundle capable of saving cronjob and running them at given intervals.



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

5. Start using the bundle

```
//analyze all the cron task available and register them
php app/console cron:scan 

//Run the cron analyzer
php app/console cron:run
```


## Creating a new task

Creating your own tasks with CronBundle couldn't be easier - all you have to do is create a normal Symfony2 Command (or ContainerAwareCommand) and tag it with the @CronJob annotation, as demonstrated below:

```php
/**
 * @CronJob("PT1H")
 */
class DemoCommand extends Command
{
    public function configure()
    {
        // Must have a name configured
        // ...
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Your code here
    }
}
```

The interval spec ("PT1H" in the above example) is documented on the [DateInterval](http://php.net/dateinterval) documentation page, and can be modified whenever you choose. For your CronJob to be scanned and included in future runs, you must first run app/console cron:scan - it will be scheduled to run the next time you run app/console cron:run
