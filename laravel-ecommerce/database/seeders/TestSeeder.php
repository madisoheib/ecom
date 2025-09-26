<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\City;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create roles and permissions
        $adminRole = Role::create(['name' => 'admin']);
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $userRole = Role::create(['name' => 'user']);

        $permissions = [
            'view_admin_panel',
            'manage_products',
            'manage_categories',
            'manage_brands',
            'manage_orders',
            'manage_users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole->givePermissionTo(['view_admin_panel', 'manage_products', 'manage_categories', 'manage_brands', 'manage_orders']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'country_code' => 'FR',
        ]);
        $admin->assignRole('super-admin');

        // Create test brands
        $brands = Brand::factory(5)->create();

        // Create test categories
        $parentCategories = Category::factory(3)->create();

        foreach ($parentCategories as $parent) {
            Category::factory(2)->create(['parent_id' => $parent->id]);
        }

        // Create test products
        $categories = Category::all();
        $products = Product::factory(20)->create();

        // Attach categories to products
        foreach ($products as $product) {
            $product->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
