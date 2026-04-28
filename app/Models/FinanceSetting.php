<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceSetting extends Model
{
    protected $table = 'finance_settings';

    protected $fillable = [
        'weekly_fee',
    ];

    public static function singleton(): self
    {
        $setting = self::query()->first();

        if ($setting) {
            return $setting;
        }

        return self::create(['weekly_fee' => 10000]);
    }

    public static function weeklyFee(): int
    {
        return (int) self::singleton()->weekly_fee;
    }
}
