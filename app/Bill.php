<?php

namespace ludcis;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    public function document(){
    	return $this->belongsTo(Document::class);
    }
    public function resolution(){
    	return $this->belongsTo(Resolution::class);
    }
}
