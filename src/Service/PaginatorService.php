<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaginatorService
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function paginate(Paginator $items, string $route, int $page = 1, int $maxPerPage = 10, array $params = []): array
    {
        $result = [
            'data' => $items,
        ];
        if (count($items) > $maxPerPage) {
            if ($page > 1) {
                $result['links'][] = [
                    'rel' => 'previous',
                    'url' => $this->urlGenerator->generate($route, array_merge($params, ['page' => $page - 1]), UrlGeneratorInterface::ABSOLUTE_URL)
                ];
            }
            if (count($items) > $page * $maxPerPage) {
                $result['links'][] = [
                    'rel' => 'next',
                    'url' => $this->urlGenerator->generate($route, array_merge($params, ['page' => $page + 1]), UrlGeneratorInterface::ABSOLUTE_URL)
                ];
            }
        }
        return $result;
    }
}
