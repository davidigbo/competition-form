<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyProposal extends Model
{
    use HsaFactory;

    protected $fillable = [
        'policy_id',
        'user_id',
        'status',
        'comment',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }
}
