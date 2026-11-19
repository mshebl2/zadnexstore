<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_renders_successfully(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee('تواصل مع مستشاري إنسينس السعودية');
        $response->assertSee('نموذج المراسلة المباشر');
    }

    public function test_contact_form_submits_and_stores_message(): void
    {
        $response = $this->post('/contact', [
            'name' => 'فيصل الحربي',
            'phone' => '0501234567',
            'email' => 'faisal@example.com',
            'subject' => 'استفسار عن عطر أو منتج',
            'message' => 'أود الاستفسار عن توفر دهن العود المعتق القديم.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'فيصل الحربي',
            'phone' => '0501234567',
            'subject' => 'استفسار عن عطر أو منتج',
        ]);
    }

    public function test_admin_can_view_and_delete_messages(): void
    {
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@incense-sa.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $message = ContactMessage::create([
            'name' => 'سارة الشمري',
            'phone' => '0555555555',
            'email' => 'sara@example.com',
            'message' => 'رسالة تجريبية لخدمة العملاء',
        ]);

        $response = $this->actingAs($admin)->get('/admin/messages');
        $response->assertStatus(200);
        $response->assertSee('سارة الشمري');

        $deleteResponse = $this->actingAs($admin)->delete('/admin/messages/' . $message->id);
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }
}

