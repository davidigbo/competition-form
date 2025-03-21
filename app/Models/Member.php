<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'full_name', 'email', 'phone_number', 'institution_name',
    'department_name', 'level', 'matric_number', 'proof_document', 'document_path'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function policyProposal() {
        return $this->hasOne(PolicyProposal::class);
    }
}

