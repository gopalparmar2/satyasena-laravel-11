<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booth extends Model
{
    use SoftDeletes;

    public function assembly() {
        return $this->belongsTo(AssemblyConstituency::class);
    }
}
