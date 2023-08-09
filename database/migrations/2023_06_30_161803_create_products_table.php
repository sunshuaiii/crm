<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('price', 8, 2);
            $table->string('category')->enum('Beverages', 'Dairy', 'Cereals', 'Health', 'Fresh Food', 'Frozen', 
                'Delicatessen', 'Confectionary', 'Household Cleaning', 'Fabric Wash', 'Personal Wash', 'Hair Care',
                'Facial & Skin Care', 'Oral Care', 'Sanitary Napkins', 'Diapers', 'Unknown')->default('Unknown');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
