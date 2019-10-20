<?php

namespace ludcis;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    public function document(){
    	return $this->belongsTo(Document::class);
    }
}
