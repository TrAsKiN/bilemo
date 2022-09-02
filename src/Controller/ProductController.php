<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\PaginatorService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_products', methods: Request::METHOD_GET)]
    public function products(
        Request $request,
        ProductRepository $productRepository,
        PaginatorService $paginator
    ): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $products = $productRepository->getPaginateProducts($page, ProductRepository::MAX_PER_PAGE);
        $result = $paginator->paginate($products, 'app_products', $page, ProductRepository::MAX_PER_PAGE);
        return $this->json($result);
    }

    #[Route('/{id}', name: 'app_product', methods: Request::METHOD_GET)]
    public function product(
        Product $product
    ): JsonResponse {
        return $this->json($product);
    }
}
