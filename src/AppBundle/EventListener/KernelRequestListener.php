<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class KernelRequestListener
{
    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (strpos($request->headers->get('Content-Type'), 'application/json') === 0) {
            $content = json_decode($request->getContent(), true);
            $request->request->replace(is_array($content) ? $content : ['content' => $content]);
        }
    }
}
