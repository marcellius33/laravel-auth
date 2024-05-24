<?php

namespace App\Models;

use App\Models\Abstract\BaseUuidUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Laravel\Passport\HasApiTokens;
use Watson\Validating\ValidatingTrait;

class User extends BaseUuidUser
{
    use Notifiable;
    use ValidatingTrait;
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'password',
        'facebook_id',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function findForPassport(string $username): ?self
    {
        return $this->where('email', $username)
            ->whereNotNull('email_verified_at')
            ->first();
    }

    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getRules(): array
    {
        return [
            'name' => 'required|string',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignoreModel($this)],
            'address' => 'nullable|string',
            'phone' => 'nullable|string|phone:ID',
            'facebook_id' => ['nullable', 'string', Rule::unique('users', 'facebook_id')->ignoreModel($this)],
            'google_id' => ['nullable', 'string', Rule::unique('users', 'google_id')->ignoreModel($this)],
        ];
    }
}
