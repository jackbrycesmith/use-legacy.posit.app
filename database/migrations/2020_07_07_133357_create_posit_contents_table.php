 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posit_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posit_id')->constrained()->onDelete('cascade');

            // The idea was to do something with separate encrypted copies per user/recipient
            $table->unsignedBigInteger('posit_user_id')->nullable();
            $table->foreign('posit_user_id')->references('id')->on('posit_user')->onDelete('set null');

            $table->unsignedBigInteger('posit_recipient_id')->nullable();
            $table->foreign('posit_recipient_id')->references('id')->on('posit_recipient')->onDelete('set null');

            $table->json('content')->nullable();
            $table->json('revisions')->nullable(); // Not sure whether this could be a good idea?: ->default(new Expression('(JSON_ARRAY())'));
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
        Schema::dropIfExists('posit_contents');
    }
}
