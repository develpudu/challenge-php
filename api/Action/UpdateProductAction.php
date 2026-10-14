<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Product;
use App\Domain\ProductRepository;
use App\Responder\JsonResponse;

final class UpdateProductAction
{
    private ProductRepository $repository;

    public function __construct()
    {
        $this->repository = new ProductRepository();
    }

    public function __invoke(int $id): void
    {
        try {
            $existing = $this->repository->findById($id);
            if ($existing === null) {
                JsonResponse::error('No existe el producto', 404);
                return;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            if ($input === null) {
                JsonResponse::error('JSON Invalido', 400);
                return;
            }

            $errors = $this->validate($input);
            if (!empty($errors)) {
                JsonResponse::error(implode(', ', $errors), 422);
                return;
            }

            $product = new Product(
                id: $id,
                nombre: trim($input['nombre']),
                descripcion: trim($input['descripcion']),
                precio: (float) $input['precio'],
            );

            $updated = $this->repository->update($id, $product);
            if ($updated === null) {
                JsonResponse::error('Error actualizando el producto', 500);
                return;
            }

            $precioUsdRate = \App\Config\Config::getInstance()->getPrecisionUsd();
            JsonResponse::success($updated->toArray($precioUsdRate));
        } catch (\Throwable $e) {
            JsonResponse::error('Error actualizando el producto', 500);
        }
    }

    private function validate(array $input): array
    {
        $errors = [];

        if (!isset($input['nombre']) || trim((string) $input['nombre']) === '') {
            $errors[] = 'nombre es obligatorio';
        } elseif (strlen(trim((string) $input['nombre'])) > 255) {
            $errors[] = 'nombre debe tener 255 caracteres o menos';
        }

        if (!isset($input['descripcion']) || trim((string) $input['descripcion']) === '') {
            $errors[] = 'descripción es obligatorio';
        }

        if (!isset($input['precio'])) {
            $errors[] = 'precio es obligatorio';
        } elseif (!is_numeric($input['precio']) || (float) $input['precio'] < 0) {
            $errors[] = 'precio debe ser un número positivo';
        }

        return $errors;
    }
}
