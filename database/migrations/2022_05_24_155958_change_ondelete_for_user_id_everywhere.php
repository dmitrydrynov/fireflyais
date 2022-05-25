<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tablesForUpdate = [
        'accounts',
        'attachments',
        'bills',
        'budgets',
        'categories',
        'export_jobs',
        'import_jobs',
        'rule_groups',
        'rules',
        'tags',
        'transaction_journals',
        'available_budgets',
        'currency_exchange_rates',
        'recurrences',
        'transaction_groups',
        'telemetry',
        'object_groups',
        'webhooks',
        'preferences',
        // 'group_memberships', // need delete on user delete
        // 'role_user',  // need delete on user delete
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tablesForUpdate as $tableForUpdate) {
            if (Schema::hasTable($tableForUpdate)) {
                Schema::table($tableForUpdate, static function (Blueprint $table) use ($tableForUpdate) {
                    if (Schema::hasColumn($tableForUpdate, 'user_id')) {
                        $table->dropForeign(['user_id']);
                        $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tablesForUpdate as $tableForUpdate) {
            if (Schema::hasTable($tableForUpdate)) {
                Schema::table($tableForUpdate, static function (Blueprint $table) use ($tableForUpdate) {
                    if (Schema::hasColumn($tableForUpdate, 'user_id')) {
                        $table->dropForeign(['user_id']);
                        $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                    }
                });
            }
        }
    }
};
