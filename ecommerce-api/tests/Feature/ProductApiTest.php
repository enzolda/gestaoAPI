<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Product;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    // Helper para dados de produto
    private function productData(): array
    {
        return [
            'name' => 'Notebook Gamer',
            'description' => 'Notebook com TRX 4090',
            'price' => 15999.90,
            'stock' => 10,
            'category' => 'Eletrônicos'
        ];
    }

    // Teste: POST /api/product - Criar produto
    public function test_create_product()
    {
        $response = $this->postJson('api/products', $this->productData());

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'name', 'description', 'price', 'stock', 'category'
            ]);
    }

    // Teste: GET /api/products - Listar produtos
    public function test_list_products()
    {
        Product::factory()->count(5)->create();

        // Sem filtros
        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');

        // Com filtro de categoria
        $response = $this->getJson('/api/products?category=Eletrônicos');
        $response->assertStatus(200);
    }

    //Teste: GET /api/products/{id} - Obter produto específico
    public function test_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");
        $response->assertStatus(200)
            ->assertJson([
                'id' => $product->id,
                'name' => $product->name,
            ]);
    }

    //Teste: PUT /api/products/{id} - Atualizar produto
    public function test_update_product()
    {
        $product = Product::factory()->create();
        $updateData = ['name' => 'Nome Atualizado', 'price' => 2000];

        $response = $this->putJson("/api/products/{$product->id}", $updateData);
        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Nome Atualizado',
                'price' => 2000
            ]);
    }

    //Teste: DELETE /api/products/{id} - Deletar produto
    public function test_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    //Teste: Validação de campos obrigatórios
    public function test_validation_errors()
    {
        // POST sem nome (deve falhar)
        $response = $this->postJson('/api/products', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price']);
    }
}
