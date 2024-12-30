<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ngo extends Model
{
    //
    protected $guarded = [];

    public function ngoTrans()
    {
        return $this->hasMany(NgoTran::class);
    }

    public function ngoType()
    {
        return $this->belongsTo(NgoType::class, 'ngo_type_id');
    }

    public function ngoStatus()
    {
        return $this->hasOne(NgoStatus::class, 'ngo_id', 'id');
    }


    public function agreement()
    {
        return $this->hasOne(Agreement::class, 'ngo_id');
    }
}
