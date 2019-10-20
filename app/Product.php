<?php

namespace ludcis;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    public function documents(){
    	return $this->hasMany(Document::class);
    }
}
