<?php

namespace ludcis;

use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    public function bills(){
        return $this->hasMany(Bill::class);
    }
}
