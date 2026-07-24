<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpressionSubmitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CategorySeeder::class);
    }

    public function test_user_can_submit_anonymous_expression()
    {
        $category = Category::first();

        $response = $this->post(route('ekspresi.store'), [
            'category_id' => $category->id,
            'is_anonymous' => 1,
            'content' => 'Ini adalah curahan hati untuk testing program STEP yang dibuat untuk penelitian.',
            'consent_agreed' => 1,
            'honeypot' => '',
        ]);

        $response->assertRedirect(route('ekspresi.success'));
        $this->assertDatabaseHas('expressions', [
            'category_id' => $category->id,
            'is_anonymous' => 1,
            'display_name' => 'Anonim',
            'status' => 'pending',
        ]);
    }

    public function test_expression_requires_consent()
    {
        $category = Category::first();

        $response = $this->post(route('ekspresi.store'), [
            'category_id' => $category->id,
            'content' => 'Ini konten tanpa consent.',
            'honeypot' => '',
        ]);

        $response->assertSessionHasErrors('consent_agreed');
    }
}
