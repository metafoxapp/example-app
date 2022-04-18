<?php

use MetaFox\Platform\Support\DbTableHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notes')) {
            Schema::create('notes', function (Blueprint $table) {
                $table->bigIncrements('id');

                DbTableHelper::setupResourceColumns($table, true, true, true, true);

                DbTableHelper::totalColumns($table, ['view', 'like', 'comment', 'reply', 'share', 'attachment']);

                $table->string('title', 255);

                $table->unsignedTinyInteger('is_draft')->default(0);

                DbTableHelper::featuredColumn($table);
                DbTableHelper::sponsorColumn($table);
                DbTableHelper::approvedColumn($table);
                DbTableHelper::imageColumns($table);
                DbTableHelper::tagsColumns($table);

                $table->timestamps();
            });
        }

        DbTableHelper::categoryTable('note_categories', true);
        DbTableHelper::categoryDataTable('note_category_data');
        DbTableHelper::createTagDataTable('note_tag_data');
        DbTableHelper::textTable('note_text');
        DbTableHelper::streamTables('note');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DbTableHelper::dropStreamTables('note');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('note_text');
        Schema::dropIfExists('note_categories');
        Schema::dropIfExists('note_category_data');
        Schema::dropIfExists('note_tag_data');
    }
}
