<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory,HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'id', 'account_name', 'account_type', 'currency', 'balance', 'account_number', 'user_id'
    ];

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
