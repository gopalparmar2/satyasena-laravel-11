<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    public function assembly() {
        return $this->belongsTo(AssemblyConstituency::class, 'assembly_id');
    }
}
