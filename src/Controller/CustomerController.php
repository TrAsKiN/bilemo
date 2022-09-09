<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Security\Voter\CustomerVoter;
use App\Service\HateoasService;
use App\Service\PaginatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/customers')]
class CustomerController extends AbstractController
{
    public function __construct(
        RequestStack $requestStack
    ) {
        $requestStack->getCurrentRequest()->attributes->add([HateoasService::KEY => Customer::class]);
    }

    #[Route(name: 'app_customers_list', methods: [Request::METHOD_GET])]
    public function customersList(
        Request $request,
        CustomerRepository $customerRepository,
        PaginatorService $paginator
    ): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $customers = $customerRepository->getPaginateCustomers($this->getUser(), $page);
        $result = $paginator->paginate($customers, 'app_customers_list', $page, CustomerRepository::MAX_PER_PAGE);
        return $this->json($result, Response::HTTP_OK)->setSharedMaxAge(3600);
    }

    #[Route(name: 'app_customers_add', methods: [Request::METHOD_POST])]
    public function customersAdd(
        Request $request,
        CustomerRepository $customerRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $newCustomer = $serializer->deserialize($request->getContent(), Customer::class, 'json');
        $newCustomer->setOwner($this->getUser());
        $customerRepository->add($newCustomer, true);
        return $this->json($newCustomer, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_customers_show', methods: [Request::METHOD_GET])]
    public function customersShow(
        Customer $customer
    ): JsonResponse {
        $this->denyAccessUnlessGranted(CustomerVoter::IS_MINE, $customer);
        return $this->json($customer, Response::HTTP_OK)->setSharedMaxAge(3600);
    }

    #[Route('/{id}', name: 'app_customers_update', methods: [Request::METHOD_PUT])]
    public function customersUpdate(
        Request $request,
        Customer $customer,
        SerializerInterface $serializer,
        CustomerRepository $customerRepository
    ): JsonResponse {
        $this->denyAccessUnlessGranted(CustomerVoter::IS_MINE, $customer);
        $serializer->deserialize($request->getContent(), Customer::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $customer
        ]);
        $customerRepository->add($customer, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'app_customers_delete', methods: [Request::METHOD_DELETE])]
    public function customersDelete(
        Customer $customer,
        CustomerRepository $customerRepository
    ): JsonResponse {
        $this->denyAccessUnlessGranted(CustomerVoter::IS_MINE, $customer);
        $customerRepository->remove($customer, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
