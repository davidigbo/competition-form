<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function members() {
        return $this->hasMany(Member::class);
    }

    public function proposal() {
        $this->hasOne(PolicyProposal::class);
    }
}
