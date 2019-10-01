<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist_attributes', function (Blueprint $table) {
            $table->bigIncrements('checklist_attr_id');
            $table->string("object_id");
            $table->string("object_domain");
            $table->string("description");
            $table->boolean("is_completed")->default(false);
            $table->timestamp("completed_at")->nullable();
            $table->string("updated_by")->nullable();
            $table->timestamp("updated_at")
                ->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
                ->nullable();
            $table->timestamp("created_at")
                ->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp("due")->nullable();
            $table->integer("urgency");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklist_attributes');
    }
}
