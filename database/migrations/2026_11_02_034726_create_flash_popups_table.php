<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_popups', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->text('subtitle_ar')->nullable();
            $table->string('badge_ar')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('button_text_ar')->default('تسوق العرض الآن');
            $table->string('button_url')->default('/products');
            $table->string('image_url')->nullable();
            $table->unsignedInteger('delay_seconds')->default(3);
            $table->unsignedInteger('duration_seconds')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed initial default popups
        \App\Models\FlashPopup::create([
            'title_ar' => 'مفاجأة خاصة وحصرية لزوارنا!',
            'subtitle_ar' => 'احصل على خصم 15% إضافي على أندر العطور الشرقية ودهن العود الملكي مع شحن مجاني.',
            'badge_ar' => 'عرض استثنائي لفترة محدودة',
            'coupon_code' => 'INCENSE10',
            'button_text_ar' => 'تسوق العرض الآن',
            'button_url' => '/products',
            'image_url' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=700&q=80',
            'delay_seconds' => 3,
            'duration_seconds' => 5,
            'is_active' => true,
        ]);

        \App\Models\FlashPopup::create([
            'title_ar' => 'عرض كرم الضيافة الملكية',
            'subtitle_ar' => 'اشترِ تولة من دهن العود المعتق واحصل على كسرة عود مروكي فاخر مجاناً.',
            'badge_ar' => 'هدية مميزة مع كل طلب',
            'coupon_code' => 'ROYAL20',
            'button_text_ar' => 'اغتنم العرض الآن',
            'button_url' => '/category/oud',
            'image_url' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format&fit=crop&w=700&q=80',
            'delay_seconds' => 3,
            'duration_seconds' => 5,
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_popups');
    }
};
