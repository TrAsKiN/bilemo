<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Product;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HateoasService
{
    public const KEY = 'entity';

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function addHypermedia(mixed $data, ?string $entity): mixed
    {
        $updatedData = [];
        if (isset($data['data'])) {
            $updatedData['data'] = [];
            $updatedData['links'] = $data['links'];
            foreach ($data['data'] as $entry) {
                if ($links = $this->parseEntity($entry, $entity)) {
                    $entry['links'] = $links;
                }
                $updatedData['data'][] = $entry;
            }
        } else {
            if ($links = $this->parseEntity($data, $entity)) {
                $data['links'] = $links;
                $updatedData = $data;
            }
        }
        return $updatedData;
    }

    private function parseEntity(?array $entry, ?string $entity): ?array
    {
        if ($entity == Customer::class) {
            return [
                $this->createHypermedia('self', 'app_customers_show', ['id' => $entry['id']]),
                $this->createHypermedia('update', 'app_customers_update', ['id' => $entry['id']]),
                $this->createHypermedia('delete', 'app_customers_delete', ['id' => $entry['id']]),
            ];
        } elseif ($entity == Product::class) {
            return [
                $this->createHypermedia('self', 'app_products_show', ['id' => $entry['id']]),
            ];
        }
        return null;
    }

    private function createHypermedia(string $relation, string $route, array $attributes = []): array
    {
        return [
            'rel' => $relation,
            'url' => $this->urlGenerator->generate($route, $attributes, UrlGeneratorInterface::ABSOLUTE_URL),
        ];
    }
}
