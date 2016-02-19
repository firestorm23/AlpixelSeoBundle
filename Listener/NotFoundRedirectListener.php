<?php
namespace Alpixel\Bundle\SEOBundle\Listener;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;

class NotFoundRedirectListener
{
    protected $container;

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $hotfix = explode('.', $event->getRequest()->getPathInfo());

        if (!in_array(end($hotfix), array('js', 'css')) && $event->getException() instanceof NotFoundHttpException && !in_array($this->container->getParameter('kernel.environment'), array('dev', 'test'))) {
            $response = new RedirectResponse($this->container->get('router')->generate('front_404'));
            $event->setResponse($response);
        }
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}
