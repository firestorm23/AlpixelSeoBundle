<?php
namespace Alpixel\Bundle\SEOBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpFoundation\Request;
use Alpixel\Bundle\CronBundle\Annotation\CronJob;

/**
 * @CronJob("P1D")
 */
class SitemapCommand extends ContainerAwareCommand
{
    const ERR_INVALID_HOST = -1;
    const ERR_INVALID_DIR  = -2;

    /**
     * Configure CLI command, message, options.
     */
    protected function configure()
    {
        $this->setName('seo:sitemap')
            ->setDescription('Dumps sitemaps to given location')
            ->addOption(
                'gzip',
                null,
                InputOption::VALUE_NONE,
                'Gzip sitemap'
            )
            ->addArgument(
                'target',
                InputArgument::OPTIONAL,
                'Location where to dump sitemaps. Generated urls will not be related to this folder.',
                'web'
            );
    }

    /**
     * Code to execute for the command.
     *
     * @param InputInterface  $input  Input object from the console
     * @param OutputInterface $output Output object for the console
     *
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $targetDir  = rtrim($input->getArgument('target'), '/');
        $targetDir = 'web';

        $container  = $this->getContainer();

        $dumper     = $container->get('seo.sitemap.dumper');

        $baseUrl    = $container->getParameter('seo.sitemap.base_url');
        $baseUrl    = rtrim($baseUrl, '/').'/';

        if (!parse_url($baseUrl, PHP_URL_HOST)) { //sanity check
            throw new \InvalidArgumentException("Invalid base url. Use fully qualified base url, e.g. http://acme.com/", self::ERR_INVALID_HOST);
        }

        $request = Request::create($baseUrl);

        $container->set('request', $request);
        $container->get('router')->getContext()->fromRequest($request);

        $output->writeln(
            sprintf(
                "Dumping <comment>all sections</comment> of sitemaps into <comment>%s</comment> directory",
                $targetDir
            )
        );

        $options = array(
            // 'gzip' => (Boolean)$input->getOption('gzip'),
            'gzip' => true,
        );

        $filenames = $dumper->dump($targetDir, $baseUrl, null, $options);

        if ($filenames === false) {
            $output->writeln("<error>No URLs were added to sitemap by EventListeners</error> - this may happen when provided section is invalid");

            return;
        }

        $output->writeln("<info>Created/Updated the following sitemap files:</info>");
        foreach ($filenames as $filename) {
            $output->writeln("    <comment>$filename</comment>");
        }
    }
}
