<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\HateoasService;
use App\Service\PaginatorService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
#[OA\Tag('Products')]
#[Security(name: 'Bearer')]
class ProductController extends AbstractController
{
    public function __construct(
        RequestStack $requestStack
    ) {
        $requestStack->getCurrentRequest()->attributes->add([HateoasService::KEY => Product::class]);
    }

    #[Route(name: 'app_products_list', methods: [Request::METHOD_GET])]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        required: false,
        schema: new OA\Schema(
            type: 'integer'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Products list",
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model(
                    type: Product::class
                )
            )
        ),
    )]
    public function productsList(
        Request $request,
        ProductRepository $productRepository,
        PaginatorService $paginator
    ): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $products = $productRepository->getPaginateProducts($page);
        $result = $paginator->paginate($products, 'app_products_list', $page, ProductRepository::MAX_PER_PAGE);
        return $this->json($result)->setSharedMaxAge(3600);
    }

    #[Route('/{id}', name: 'app_products_show', methods: [Request::METHOD_GET])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Product details",
        content: new OA\JsonContent(ref: new Model(type: Product::class))
    )]
    public function productsShow(
        Product $product
    ): JsonResponse {
        return $this->json($product)->setSharedMaxAge(3600);
    }
}
