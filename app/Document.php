<?php

namespace ludcis;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    public function product(){
    	return $this->belongsTo(Product::class);
    }
    public function payment(){
    	return $this->hasOne(Payment::class);
    }
    public function bill(){
        return $this->hasOne(Bill::class);
    }
    public function mail(){
    	return $this->hasOne(Mail_log::class);
    }
}
