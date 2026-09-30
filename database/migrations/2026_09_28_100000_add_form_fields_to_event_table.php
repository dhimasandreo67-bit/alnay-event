    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Kolom form_fields dipakai model Event (cast array) tapi belum dibuat migration mana pun
    public function up(): void
    {
        if (! Schema::hasColumn('event', 'form_fields')) {
            Schema::table('event', function (Blueprint $table) {
                $table->text('form_fields')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('event', 'form_fields')) {
            Schema::table('event', function (Blueprint $table) {
                $table->dropColumn('form_fields');
            });
        }
    }
};