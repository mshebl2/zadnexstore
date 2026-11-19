<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Category;
use App\Models\Product;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Main Category: قسم العطور
        $perfumes = Category::updateOrCreate(
            ['slug' => 'perfumes'],
            [
                'name_ar' => 'قسم العطور',
                'name_en' => 'Perfumes Section',
                'description_ar' => 'أرقى العطور الشرقية والفرنسية الملكية للرجال والنساء وعطور الجسم والشعر.',
                'image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=800&q=80',
                'display_order' => 1,
                'parent_id' => null,
                'is_active' => true,
            ]
        );

        $mensPerfumes = Category::updateOrCreate(
            ['slug' => 'mens-perfumes'],
            [
                'parent_id' => $perfumes->id,
                'name_ar' => 'عطور رجالية',
                'name_en' => "Men's Perfumes",
                'description_ar' => 'عطور رجالية فاخرة بفوحان قوي وهيبة تدوم طويلاً.',
                'image' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=800&q=80',
                'display_order' => 1,
                'is_active' => true,
            ]
        );

        $womensPerfumes = Category::updateOrCreate(
            ['slug' => 'womens-perfumes'],
            [
                'parent_id' => $perfumes->id,
                'name_ar' => 'عطور نسائية',
                'name_en' => "Women's Perfumes",
                'description_ar' => 'عطور أنثوية ساحرة بنفحات الزهور النادرة والفانيلا والمسك.',
                'image' => 'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=800&q=80',
                'display_order' => 2,
                'is_active' => true,
            ]
        );

        $bodyMists = Category::updateOrCreate(
            ['slug' => 'body-mists'],
            [
                'parent_id' => $perfumes->id,
                'name_ar' => 'عطور الجسم',
                'name_en' => 'Body Mists',
                'description_ar' => 'معطرات جسم منعشة ولطيفة تدوم طوال اليوم.',
                'image' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=800&q=80',
                'display_order' => 3,
                'is_active' => true,
            ]
        );

        $hairMists = Category::updateOrCreate(
            ['slug' => 'hair-mists'],
            [
                'parent_id' => $perfumes->id,
                'name_ar' => 'عطور الشعر',
                'name_en' => 'Hair Mists',
                'description_ar' => 'خلاصات عطرية غنية بالفيتامينات مصممة لحماية وتعطير الشعر.',
                'image' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=800&q=80',
                'display_order' => 4,
                'is_active' => true,
            ]
        );

        // 2. Main Category: التجميل من إنسينس
        $beauty = Category::updateOrCreate(
            ['slug' => 'beauty'],
            [
                'name_ar' => 'التجميل من إنسينس',
                'name_en' => 'Beauty & Care',
                'description_ar' => 'مستحضرات تجميل وعناية استثنائية بالبشرة والشعر والجسم بجودة لا تضاهى.',
                'image' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80',
                'display_order' => 2,
                'parent_id' => null,
                'is_active' => true,
            ]
        );

        $makeup = Category::updateOrCreate(
            ['slug' => 'makeup'],
            [
                'parent_id' => $beauty->id,
                'name_ar' => 'المكياج',
                'name_en' => 'Makeup',
                'description_ar' => 'أدوات ومستحضرات مكياج فاخرة لإطلالة متألقة.',
                'image' => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=800&q=80',
                'display_order' => 1,
                'is_active' => true,
            ]
        );

        $hairCare = Category::updateOrCreate(
            ['slug' => 'hair-care'],
            [
                'parent_id' => $beauty->id,
                'name_ar' => 'العناية بالشعر',
                'name_en' => 'Hair Care',
                'description_ar' => 'زيوت وسيرومات تغذية وترميم الشعر من الجذور حتى الأطراف.',
                'image' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?auto=format&fit=crop&w=800&q=80',
                'display_order' => 2,
                'is_active' => true,
            ]
        );

        $skinCare = Category::updateOrCreate(
            ['slug' => 'skin-care'],
            [
                'parent_id' => $beauty->id,
                'name_ar' => 'العناية بالبشرة',
                'name_en' => 'Skin Care',
                'description_ar' => 'منتجات ترطيب ونضارة البشرة بمكونات طبيعية نقية.',
                'image' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=800&q=80',
                'display_order' => 3,
                'is_active' => true,
            ]
        );

        $bodyCare = Category::updateOrCreate(
            ['slug' => 'body-care'],
            [
                'parent_id' => $beauty->id,
                'name_ar' => 'العناية بالجسم',
                'name_en' => 'Body Care',
                'description_ar' => 'كريمات ولوشنات ترطيب وتنعيم الجسم بنفحات عطرية ملكية.',
                'image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=800&q=80',
                'display_order' => 4,
                'is_active' => true,
            ]
        );

        // 3. Main Category: العود من إنسينس
        $oud = Category::updateOrCreate(
            ['slug' => 'oud'],
            [
                'name_ar' => 'العود من إنسينس',
                'name_en' => 'Oud Collection',
                'description_ar' => 'أنفس أدهان العود الصافية وكسر العود الطبيعي والمحسن الفاخر.',
                'image' => 'https://images.unsplash.com/photo-1616949755610-8c9bbc08f138?auto=format&fit=crop&w=800&q=80',
                'display_order' => 3,
                'parent_id' => null,
                'is_active' => true,
            ]
        );

        $naturalOud = Category::updateOrCreate(
            ['slug' => 'natural-oud'],
            [
                'parent_id' => $oud->id,
                'name_ar' => 'العود الطبيعي',
                'name_en' => 'Natural Oud',
                'description_ar' => 'أدهان وكسر عود طبيعية صافية 100% دون أي إضافات صناعية.',
                'image' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=800&q=80',
                'display_order' => 1,
                'is_active' => true,
            ]
        );

        $enhancedOud = Category::updateOrCreate(
            ['slug' => 'enhanced-oud'],
            [
                'parent_id' => $oud->id,
                'name_ar' => 'العود المحسن',
                'name_en' => 'Enhanced Oud',
                'description_ar' => 'أجود كسر العود المروكي والكلمنتان المحسن بريسين فاخر ونكهة زكية.',
                'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format&fit=crop&w=800&q=80',
                'display_order' => 2,
                'is_active' => true,
            ]
        );

        // Map existing products to their new subcategories
        Product::where('slug', 'samarkand-royal-perfume')->update(['category_id' => $mensPerfumes->id]);
        Product::where('slug', 'taif-splendor-extrait')->update(['category_id' => $womensPerfumes->id]);
        Product::where('slug', 'eternal-najd-edp')->update(['category_id' => $mensPerfumes->id]);
        Product::where('slug', 'prachin-sweet-oud')->update(['category_id' => $naturalOud->id]);
        Product::where('slug', 'maroki-double-super-oud')->update(['category_id' => $enhancedOud->id]);
        Product::where('slug', 'royal-signature-bakhoor')->update(['category_id' => $oud->id]);
        Product::where('slug', 'diriyah-palace-marble-burner')->update(['category_id' => $oud->id]);

        // Keep dehn-al-oud category alive under oud so legacy route & tests pass seamlessly
        if ($oldDehn = Category::where('slug', 'dehn-al-oud')->first()) {
            $oldDehn->update([
                'parent_id' => $oud->id,
                'is_active' => true,
            ]);
            Product::where('slug', 'trat-vintage-oud-oil')->update(['category_id' => $oldDehn->id]);
        } else {
            Product::where('slug', 'trat-vintage-oud-oil')->update(['category_id' => $naturalOud->id]);
        }
    }

    public function down(): void
    {
    }
};
