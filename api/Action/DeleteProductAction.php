<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\ProductRepository;
use App\Responder\JsonResponse;

final class DeleteProductAction
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

            $deleted = $this->repository->delete($id);
            if (!$deleted) {
                JsonResponse::error('Error borrando el producto', 500);
                return;
            }

            JsonResponse::noContent();
        } catch (\Throwable $e) {
            JsonResponse::error('Error borrando el producto', 500);
        }
    }
}
