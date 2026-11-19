<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminCategoryAndProductUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin_test@incense-sa.com',
        ]);
    }

    public function test_admin_can_create_category_with_uploaded_image_and_parent(): void
    {
        Storage::fake('public');

        $parent = Category::where('slug', 'perfumes')->first();
        $file = UploadedFile::fake()->image('niche-perfumes.jpg', 600, 600);

        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name_ar' => 'عطور النيش الخاصة',
            'name_en' => 'Special Niche Perfumes',
            'parent_id' => $parent->id,
            'description_ar' => 'تشكيلة نيش حصرية لعشاق التميز',
            'image_file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $category = Category::where('name_ar', 'عطور النيش الخاصة')->first();
        $this->assertNotNull($category);
        $this->assertEquals($parent->id, $category->parent_id);
        $this->assertStringContainsString('storage/categories', $category->image);
    }

    public function test_admin_can_create_product_with_uploaded_mobile_images(): void
    {
        Storage::fake('public');

        $category = Category::where('slug', 'mens-perfumes')->first();
        $primaryPhoto = UploadedFile::fake()->image('new-perfume.jpg', 800, 800);
        $galleryPhoto = UploadedFile::fake()->image('gallery-angle.jpg', 800, 800);

        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'name_ar' => 'عطر الفخامة الملكية الجديد',
            'name_en' => 'New Royal Luxury Perfume',
            'category_id' => $category->id,
            'sku' => 'TEST-ROYAL-999',
            'regular_price' => 380.00,
            'sale_price' => 320.00,
            'stock_quantity' => 20,
            'size' => '100 مل',
            'description_ar' => 'عطر ملكي ساحر بثبات فائق',
            'fragrance_top' => 'هيل أخضر',
            'fragrance_heart' => 'ورد طائفي',
            'fragrance_base' => 'عود كمبودي',
            'image_file' => $primaryPhoto,
            'gallery_files' => [$galleryPhoto],
            'is_featured' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/products');
        $response->assertSessionHas('success');

        $product = Product::where('sku', 'TEST-ROYAL-999')->first();
        $this->assertNotNull($product);
        $this->assertEquals(2, $product->images()->count());
        $this->assertStringContainsString('storage/products', $product->primary_image);
    }

    public function test_admin_can_update_product_with_new_uploaded_image(): void
    {
        Storage::fake('public');

        $product = Product::first();
        $newPhoto = UploadedFile::fake()->image('updated-bottle.jpg', 800, 800);

        $response = $this->actingAs($this->admin)->put('/admin/products/' . $product->id, [
            'name_ar' => $product->name_ar . ' معدل',
            'category_id' => $product->category_id,
            'sku' => $product->sku,
            'regular_price' => 450.00,
            'stock_quantity' => 25,
            'description_ar' => $product->description_ar,
            'image_file' => $newPhoto,
        ]);

        $response->assertRedirect('/admin/products');
        $product->refresh();
        $this->assertStringContainsString('storage/products', $product->primary_image);
    }

    public function test_admin_can_view_profile_and_change_email_and_password(): void
    {
        // 1. View profile page
        $res = $this->actingAs($this->admin)->get('/admin/profile');
        $res->assertStatus(200);
        $res->assertSee($this->admin->email);

        // 2. Update name, email, and password
        $updateRes = $this->actingAs($this->admin)->put('/admin/profile', [
            'name' => 'مدير النظام المحدث',
            'email' => 'newadmin@incense-sa.com',
            'phone' => '0509998877',
            'current_password' => 'password',
            'new_password' => 'newSecretPass123',
            'new_password_confirmation' => 'newSecretPass123',
        ]);

        $updateRes->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertEquals('مدير النظام المحدث', $this->admin->name);
        $this->assertEquals('newadmin@incense-sa.com', $this->admin->email);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newSecretPass123', $this->admin->password));
    }

    public function test_admin_password_change_fails_with_invalid_current_password(): void
    {
        $updateRes = $this->actingAs($this->admin)->put('/admin/profile', [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'current_password' => 'wrong_password',
            'new_password' => 'newSecretPass123',
            'new_password_confirmation' => 'newSecretPass123',
        ]);

        $updateRes->assertSessionHasErrors(['current_password']);
    }
}

