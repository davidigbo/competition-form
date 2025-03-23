<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'member_id',
        'document_path',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function member() {
        return $this->belongsTo(Member::class);
    }
}
