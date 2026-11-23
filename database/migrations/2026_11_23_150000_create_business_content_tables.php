<?php

use App\Support\LocationPages;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Business content edited through MCP: offices, FAQs, testimonials and pricing plans.
 * Offices are seeded from the facility details already in App\Support\LocationPages, so the
 * location pages render exactly as before.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('offices')) {
            Schema::create('offices', function (Blueprint $table) {
                $table->id();
                $table->string('key', 40)->unique(); // dubai, sharjah: used in {{address:key}}
                $table->string('name', 150);
                $table->string('street', 255);
                $table->string('locality', 150);
                $table->string('region', 100);
                $table->string('map_link', 500)->nullable();
                $table->string('hours', 150)->nullable();
                $table->string('phone', 40)->nullable();
                $table->string('email', 150)->nullable();
                $table->decimal('lat', 10, 7)->nullable();
                $table->decimal('lng', 10, 7)->nullable();
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });

            $now = now();
            foreach (LocationPages::codeFacilities() as $key => $f) {
                DB::table('offices')->insert([
                    'key' => $key,
                    'name' => $f['facilityName'],
                    'street' => $f['schemaAddress']['streetAddress'],
                    'locality' => $f['schemaAddress']['addressLocality'],
                    'region' => $f['schemaAddress']['addressRegion'],
                    'map_link' => $f['directions'],
                    'lat' => $f['geo'][0] ?? null,
                    'lng' => $f['geo'][1] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        if (!Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->text('question');
                $table->longText('answer');
                $table->json('page_slugs')->nullable();
                $table->unsignedInteger('position')->default(0);
                $table->boolean('is_published')->default(false);
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('testimonials')) {
            Schema::create('testimonials', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->text('text');
                $table->string('source', 100); // Google, Facebook, email, ...
                $table->string('source_url', 500)->nullable();
                $table->date('review_date')->nullable();
                $table->boolean('is_published')->default(false);
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pricing_plans')) {
            Schema::create('pricing_plans', function (Blueprint $table) {
                $table->id();
                $table->string('slug', 80)->unique(); // used in {{plan:slug}}
                $table->string('name', 150);
                $table->string('unit_size', 100)->nullable();
                $table->decimal('price', 10, 2)->nullable();
                $table->string('currency', 3)->default('AED');
                $table->string('period', 20)->default('month');
                $table->json('features')->nullable();
                $table->unsignedInteger('position')->default(0);
                $table->boolean('is_published')->default(false);
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('offices');
    }
};
