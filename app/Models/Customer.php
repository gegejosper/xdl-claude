<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //

    public function branch_details(){
        return $this->belongsTo('App\Models\Branch', 'branch_id', 'id');
    }
    public function transaction_details(){
        return $this->hasMany('App\Models\Transaction','customer_id', 'id');
    }
}
