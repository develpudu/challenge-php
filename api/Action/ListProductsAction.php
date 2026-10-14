<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\ProductRepository;
use App\Responder\JsonResponse;

final class ListProductsAction
{
    private ProductRepository $repository;

    public function __construct()
    {
        $this->repository = new ProductRepository();
    }

    public function __invoke(): void
    {
        try {
            $products = $this->repository->findAll();
            $precioUsdRate = \App\Config\Config::getInstance()->getPrecisionUsd();

            $data = array_map(
                fn($product) => $product->toArray($precioUsdRate),
                $products
            );

            JsonResponse::success(['productos' => $data]);
        } catch (\Throwable $e) {
            JsonResponse::error('Error al cargar los productos', 500);
        }
    }
}
