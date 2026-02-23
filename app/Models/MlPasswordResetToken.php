<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MlPasswordResetToken extends Model
{
    public $timestamps = false;
    protected $table = 'ml_password_reset_tokens';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}