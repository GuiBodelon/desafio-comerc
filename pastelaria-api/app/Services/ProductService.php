<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Retorna todos os produtos.
     */
    public function getAllProducts()
    {
        return Product::all();
    }

    /**
     * Retorna um produto pelo ID.
     */
    public function getProductById(int $id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Cria um novo produto.
     */
    public function createProduct(array $data)
    {
        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $data['photo'] = $this->storeImage($data['photo'], $data['name']);
        }

        return Product::create($data);
    }

    /**
     * Atualiza um produto existente.
     */
    public function updateProduct(int $id, array $data)
    {
        $product = Product::findOrFail($id);

        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            // Remover imagem antiga antes de salvar a nova
            $this->deleteImage($product->photo);
            $data['photo'] = $this->storeImage($data['photo'], $data['name']);
        }

        $product->update($data);

        return $product;
    }

    /**
     * Exclui um produto e sua imagem associada.
     */
    public function deleteProduct(int $id)
    {
        $product = Product::findOrFail($id);
        //$this->deleteImage($product->photo);
        $product->delete();
    }

    /**
     * Salva a imagem no Storage e retorna o caminho.
     */
    private function storeImage($file, string $productName): string
    {
        $fileName = str_replace(' ', '-', strtolower($productName)) . '-' . time() . '.' . $file->getClientOriginalExtension();

        return $file->storeAs('products', $fileName, 'public');
    }

    /**
     * Remove a imagem do Storage.
     */
    private function deleteImage(?string $filePath): void
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}
