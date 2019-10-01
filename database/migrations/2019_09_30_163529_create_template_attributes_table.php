<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_attributes', function (Blueprint $table) {
            $table->bigIncrements('template_attr_id');
            $table->string('name');
            $table->unsignedBigInteger('checklist_attr_id');
            $table->unsignedBigInteger('item_attr_id');

            // for checklist attributes entity..
            $table
                ->foreign('checklist_attr_id')
                ->references('checklist_attr_id')
                ->on('checklist_attributes');

            // for item attributes entity..
            $table
                ->foreign('item_attr_id')
                ->references('item_attr_id')
                ->on('item_attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_attributes');
    }
}
