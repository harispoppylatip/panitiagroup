<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceSetting extends Model
{
    protected $table = 'finance_settings';

    protected $fillable = [
        'weekly_fee',
        'auto_weekly_enabled',
        'default_weekly_description',
    ];

    public static function singleton(): self
    {
        $setting = self::query()->first();

        if ($setting) {
            return $setting;
        }

        return self::create([
            'weekly_fee' => 10000,
            'auto_weekly_enabled' => true,
            'default_weekly_description' => 'Iuran mingguan otomatis',
        ]);
    }

    public static function weeklyFee(): int
    {
        return (int) self::singleton()->weekly_fee;
    }

    public static function isWeeklyEnabled(): bool
    {
        return (bool) self::singleton()->auto_weekly_enabled;
    }

    public static function defaultWeeklyDescription(): string
    {
        return (string) (self::singleton()->default_weekly_description ?? 'Iuran mingguan otomatis');
    }
}
