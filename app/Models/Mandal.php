<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mandal extends Model
{
    use SoftDeletes;

    public function zila() {
        return $this->belongsTo(Zilla::class, 'zilla_id');
    }
}
