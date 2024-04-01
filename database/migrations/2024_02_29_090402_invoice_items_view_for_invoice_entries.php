<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW invoice_items AS
        SELECT invoice_entries.invoice_id, parents.id parent_id, CONCAT_WS('-', parents.name, GROUP_CONCAT(DISTINCT categories.name SEPARATOR '+')) description, shoes.retail_price, sum(invoice_entries.count) count, (shoes.retail_price * sum(invoice_entries.count)) total_price
        FROM invoice_entries
        INNER JOIN shoes ON invoice_entries.shoe_id = shoes.id
        INNER JOIN categories ON shoes.category_id = categories.id
        INNER JOIN categories parents ON categories.parent_id = parents.id
        GROUP BY invoice_entries.invoice_id, parents.id, parents.name, shoes.retail_price
        ORDER BY invoice_entries.invoice_id ASC, parents.id ASC, shoes.retail_price DESC");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::statement("drop view invoice_items");
    }
};
