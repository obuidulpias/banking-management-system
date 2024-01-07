<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

class Transaction extends Model
{
    use HasFactory;
    

    protected $fillable = [ 
        'user_id',
        'amount',
        'transaction_type',
    ];


    static function getSingle($id){
        return self::find($id);
    }

    static function getAll($user_id){
        $return = self::select('transactions.*')
                    ->where('user_id',$user_id)
                    ->where('transaction_type','1');
                    if(!empty(Request::get('date'))){
                        $return = $return->whereDate('created_at', '=', Request::get('date'));
                    }
        $return  = $return->orderBy('id', 'desc')
                    ->paginate(20);
        return $return;
    }
}
