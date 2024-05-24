<?php

namespace App\Models;

use App\Models\Abstract\BaseUuidModel;

class VerificationCode extends BaseUuidModel
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = self::generateRandomIntegerString();
        });
    }

    private static function generateRandomIntegerString()
    {
        $digits = '0123456789';
        $randomString = '';

        for ($i = 0; $i < 6; $i++) {
            $index = rand(0, strlen($digits) - 1);
            $randomString .= $digits[$index];
        }

        return $randomString;
    }

    protected $fillable = [
        'user_id',
        'code',
    ];

    public function getRules(): array
    {
        return [
            'user_id' => 'required|uuid|exists:users,id',
            'code' => 'required|string',
        ];
    }
}
