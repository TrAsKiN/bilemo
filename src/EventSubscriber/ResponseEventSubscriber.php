<?php

namespace App\EventSubscriber;

use App\Service\HateoasService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly HateoasService $hateoas
    ) {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $entity = $event->getRequest()->attributes->get(HateoasService::KEY);
        if (!$entity) {
            return;
        }
        $response = $event->getResponse();
        $content = json_decode($response->getContent(), true);
        $response->setContent(json_encode($this->hateoas->addHypermedia($content, $entity)));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
