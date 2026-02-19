<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Core organization identity
            $table->string('organization_name');
            $table->string('slug')->nullable()->index();
            $table->string('alias')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();

            // Registration / tax
            $table->string('chamber_of_commerce')->nullable(); // KVK
            $table->string('tax_number')->nullable(); // VAT

            // Invoicing preferences
            $table->string('invoice_email')->nullable();
            $table->string('invoice_email_cc')->nullable();
            $table->string('invoice_email_bcc')->nullable();
            $table->unsignedSmallInteger('payment_due_days')->default(14);
            $table->string('currency', 3)->default('EUR');
            $table->string('preferred_language', 5)->default('nl'); // e.g. nl, en

            // Billing address
            $table->string('billing_attention')->nullable();
            $table->string('billing_street')->nullable();
            $table->string('billing_house_number')->nullable();
            $table->string('billing_zipcode', 32)->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_region')->nullable();
            $table->string('billing_country', 2)->nullable(); // ISO2

            // Shipping address
            $table->string('shipping_attention')->nullable();
            $table->string('shipping_street')->nullable();
            $table->string('shipping_house_number')->nullable();
            $table->string('shipping_zipcode', 32)->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_region')->nullable();
            $table->string('shipping_country', 2)->nullable(); // ISO2

            // Banking (optional)
            $table->string('iban')->nullable();
            $table->string('bic')->nullable();

            // Flags
            $table->boolean('is_customer')->default(true);
            $table->boolean('is_supplier')->default(false);
            $table->boolean('is_active')->default(true);

            // Notes
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
