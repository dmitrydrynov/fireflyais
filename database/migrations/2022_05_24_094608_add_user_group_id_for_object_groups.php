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
        Schema::table('object_groups', static function (Blueprint $table) {
            if (!Schema::hasColumn('object_groups', 'user_group_id')) {
                $table->bigInteger('user_group_id', false, true)->nullable();
                $table->foreign('user_group_id', 'object_group_user_group_id')->references('id')->on('user_groups')->onDelete('cascade')->onUpdate('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'object_groups',
            function (Blueprint $table) {
                $table->dropForeign('object_group_user_group_id');
                if (Schema::hasColumn('object_groups', 'user_group_id')) {
                    $table->dropColumn('user_group_id');
                }
            }
        );
    }
};
