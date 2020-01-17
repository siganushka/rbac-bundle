<?php

namespace Siganushka\RBACBundle\EventListener;

use Siganushka\RBACBundle\Node\NodeCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class NodeListener implements EventSubscriberInterface
{
    private $authorizationChecker;
    private $nodeCollection;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, NodeCollection $nodeCollection)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->nodeCollection = $nodeCollection;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $route = $event->getRequest()->attributes->get('_route');
        if (!$this->nodeCollection->has($route)) {
            return;
        }

        if (!$this->authorizationChecker->isGranted($route)) {
            throw new AccessDeniedException();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
           KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
