 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained()->onDelete('cascade');

            // The idea was to do something with separate encrypted copies per user/recipient
            $table->unsignedBigInteger('proposal_user_id')->nullable();
            $table->foreign('proposal_user_id')->references('id')->on('proposal_user')->onDelete('set null');

            $table->unsignedBigInteger('proposal_recipient_id')->nullable();
            $table->foreign('proposal_recipient_id')->references('id')->on('proposal_recipient')->onDelete('set null');

            $table->boolean('is_encrypted')->default(0);
            $table->boolean('is_published')->default(0); // TODO not sure of this one, or whether it should be on the parent proposal, or 'is_draft' is better
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
        Schema::dropIfExists('proposal_contents');
    }
}
