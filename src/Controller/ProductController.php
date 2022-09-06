<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\PaginatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_products_list', methods: [Request::METHOD_GET])]
    public function productsList(
        Request $request,
        ProductRepository $productRepository,
        PaginatorService $paginator
    ): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $products = $productRepository->getPaginateProducts($page);
        $result = $paginator->paginate($products, 'app_products_list', $page, ProductRepository::MAX_PER_PAGE);
        return $this->json($result);
    }

    #[Route('/{id}', name: 'app_products_show', methods: [Request::METHOD_GET])]
    public function productsShow(
        Product $product
    ): JsonResponse {
        return $this->json($product);
    }
}
