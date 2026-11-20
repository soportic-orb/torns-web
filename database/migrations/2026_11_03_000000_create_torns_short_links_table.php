<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('torns_short_links')) {
            return;
        }

        // Short links of blog posts (torns.app/wy89), one per post and language.
        Schema::create('torns_short_links', function (Blueprint $table): void {
            $table->id();
            $table->char('code', 4)->unique();
            $table->unsignedBigInteger('post_id')->index();
            $table->string('locale', 10);
            $table->unsignedInteger('clicks')->default(0);
            $table->timestamp('last_clicked_at')->nullable();
            $table->timestamps();
            $table->unique(['post_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('torns_short_links');
    }
};
