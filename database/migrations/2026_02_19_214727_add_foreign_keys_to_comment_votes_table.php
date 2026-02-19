<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('comment_votes', function (Blueprint $table) {
            $table->foreign(['comment_id'])->references(['id'])->on('comments')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comment_votes', function (Blueprint $table) {
            $table->dropForeign('comment_votes_comment_id_foreign');
            $table->dropForeign('comment_votes_user_id_foreign');
        });
    }
};
