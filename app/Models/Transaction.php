<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Import UUID trait

class Transaction extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = ['account_id', 'amount', 'type', 'description'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
