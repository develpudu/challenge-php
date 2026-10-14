<?php

declare(strict_types=1);

namespace App\Domain;

use App\Database\Connection;
use PDO;

final class ProductRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM productos ORDER BY id');
        $rows = $stmt->fetchAll();
        return array_map([$this, 'mapRow'], $rows);
    }

    public function findById(int $id): ?Product
    {
        $stmt = $this->db->prepare('SELECT * FROM productos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->mapRow($row) : null;
    }

    public function create(Product $product): Product
    {
        $stmt = $this->db->prepare(
            'INSERT INTO productos (nombre, descripcion, precio) VALUES (:nombre, :descripcion, :precio)'
        );
        $stmt->execute([
            'nombre' => $product->getNombre(),
            'descripcion' => $product->getDescripcion(),
            'precio' => $product->getPrecio(),
        ]);

        $id = (int) $this->db->lastInsertId();
        return $this->findById($id) ?? $product;
    }

    public function update(int $id, Product $product): ?Product
    {
        $stmt = $this->db->prepare(
            'UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio WHERE id = :id'
        );
        $stmt->execute([
            'nombre' => $product->getNombre(),
            'descripcion' => $product->getDescripcion(),
            'precio' => $product->getPrecio(),
            'id' => $id,
        ]);

        return $stmt->rowCount() > 0 ? $this->findById($id) : null;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM productos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    private function mapRow(array $row): Product
    {
        return new Product(
            id: (int) $row['id'],
            nombre: $row['nombre'],
            descripcion: $row['descripcion'],
            precio: (float) $row['precio'],
            createdAt: $row['created_at'],
            updatedAt: $row['updated_at'],
        );
    }
}
