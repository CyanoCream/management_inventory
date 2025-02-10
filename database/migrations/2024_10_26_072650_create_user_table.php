<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name', 100);
            $table->timestamps();
        });

        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name', 100);
            $table->decimal('price_limit', 15, 2);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('password');
            $table->string('name', 100);
            $table->string('email', 100);
            $table->enum('role', ['Admin', 'Operator']);
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });

        Schema::create('incoming_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('sub_category_id')->constrained('sub_categories');
            $table->string('source', 200);
            $table->string('letter_number', 100)->nullable();
            $table->string('attachment', 255)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        Schema::create('incoming_item_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incoming_item_id')->constrained('incoming_items')->onDelete('cascade');
            $table->string('name', 200);
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            $table->string('unit', 40);
            $table->date('expired_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incoming_item_details');
        Schema::dropIfExists('incoming_items');
        Schema::dropIfExists('users');
        Schema::dropIfExists('sub_categories');
        Schema::dropIfExists('categories');
    }
};
