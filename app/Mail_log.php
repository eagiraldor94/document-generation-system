<?php

namespace ludcis;

use Illuminate\Database\Eloquent\Model;

class Mail_log extends Model
{
	protected $table = "mails";
    //
    public function document(){
    	return $this->belongsTo(Document::class);
    }
}
