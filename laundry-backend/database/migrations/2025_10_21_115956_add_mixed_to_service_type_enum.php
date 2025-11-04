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
        $driver = DB::getDriverName();
        
        if ($driver === 'pgsql') {
            // PostgreSQL: Drop and recreate the column with new enum values
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('service_type');
            });
            
            Schema::table('orders', function (Blueprint $table) {
                $table->string('service_type')->default('wash_dry')->after('status');
            });
            
            // Add check constraint for PostgreSQL
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_service_type_check CHECK (service_type IN ('wash_dry', 'wash_only', 'dry_only', 'mixed'))");
        } else {
            // MySQL/MariaDB: Use MODIFY COLUMN
            DB::statement("ALTER TABLE orders MODIFY COLUMN service_type ENUM('wash_dry', 'wash_only', 'dry_only', 'mixed') DEFAULT 'wash_dry'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'pgsql') {
            // PostgreSQL: Drop and recreate the column with original enum values
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('service_type');
            });
            
            Schema::table('orders', function (Blueprint $table) {
                $table->string('service_type')->default('wash_dry')->after('status');
            });
            
            // Add check constraint for PostgreSQL with original values
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_service_type_check CHECK (service_type IN ('wash_dry', 'wash_only', 'dry_only'))");
        } else {
            // MySQL/MariaDB: Use MODIFY COLUMN
            DB::statement("ALTER TABLE orders MODIFY COLUMN service_type ENUM('wash_dry', 'wash_only', 'dry_only') DEFAULT 'wash_dry'");
        }
    }
};
