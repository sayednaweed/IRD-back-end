<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgoStatus extends Model
{
    //




    public function ngo()
{
    return $this->belongsTo(Ngo::class, 'ngo_id', 'id');
}

}
