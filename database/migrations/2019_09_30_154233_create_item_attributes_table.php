<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_attributes', function (Blueprint $table) {
            $table->bigIncrements('item_attr_id');
            $table->string("description")->default(null);
            $table->boolean("is_completed")->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('due')->nullable();
            $table->integer('urgency')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->string('assignee_id')->nullable();
            $table->integer('task_id')->nullable();
            $table->unsignedBigInteger('checklist_attr_id')->index()->nullable();

            $table
                ->foreign('checklist_attr_id')
                ->references('checklist_attr_id')
                ->on('checklist_attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_attributes');
    }
}
