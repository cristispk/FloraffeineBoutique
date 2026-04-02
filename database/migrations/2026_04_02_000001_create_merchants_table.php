<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('status', 50)->index();
            $table->string('business_name', 255);
            $table->text('description')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('website', 255)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->text('suspension_reason')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
        });

        $this->backfillMerchantsForExistingMerchantUsers();
    }

    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }

    /**
     * Insert a draft merchant row for every user with role merchant that has no merchants row.
     */
    protected function backfillMerchantsForExistingMerchantUsers(): void
    {
        $userIds = DB::table('users')
            ->where('role', 'merchant')
            ->whereNotExists(function ($query): void {
                $query->select(DB::raw(1))
                    ->from('merchants')
                    ->whereColumn('merchants.user_id', 'users.id');
            })
            ->pluck('id');

        foreach ($userIds as $userId) {
            $name = DB::table('users')->where('id', $userId)->value('name') ?? '';

            DB::table('merchants')->insert([
                'user_id' => $userId,
                'status' => 'draft',
                'business_name' => $name !== '' ? $name : '—',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
