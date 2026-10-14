<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\ProductRepository;
use App\Responder\JsonResponse;

final class GetProductAction
{
    private ProductRepository $repository;

    public function __construct()
    {
        $this->repository = new ProductRepository();
    }

    public function __invoke(int $id): void
    {
        try {
            $product = $this->repository->findById($id);

            if ($product === null) {
                JsonResponse::error('No existe el producto', 404);
                return;
            }

            $precioUsdRate = \App\Config\Config::getInstance()->getPrecisionUsd();
            JsonResponse::success($product->toArray($precioUsdRate));
        } catch (\Throwable $e) {
            JsonResponse::error('Error al cargar el producto', 500);
        }
    }
}
