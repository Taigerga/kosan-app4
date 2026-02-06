<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gmail_messages', function (Blueprint $table) {
            $table->id('id_gmail_message');
            $table->string('gmail_message_id', 255)->unique()->comment('Gmail Message ID');
            $table->string('from_email', 255);
            $table->string('to_email', 255);
            $table->string('subject', 500)->nullable();
            $table->text('body')->nullable();
            $table->enum('message_type', ['sent', 'received'])->default('received');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'delivered', 'failed'])->default('pending');
            $table->enum('related_type', ['pembayaran', 'kontrak', 'general'])->default('general');
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('label_ids', 500)->nullable()->comment('Gmail label IDs');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->text('raw_headers')->nullable();
            $table->text('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['gmail_message_id']);
            $table->index(['from_email', 'to_email']);
            $table->index(['status']);
            $table->index(['related_type', 'related_id']);
            $table->index(['message_type']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('gmail_messages');
    }
};
