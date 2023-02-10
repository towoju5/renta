<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    public function add_order(){
        $order = new Order();
        $data['order_type']             =   "xoxo";
        $data['amount']                 =   "xoxo";
        $data['receipient_name']        =   "xoxo";
        $data['receipient_source']      =   "xoxo";
        $data['receipient_source_name'] =   "xoxo";
        $data['sender_name']            =   "xoxo";
        $data['sender_source']          =   "xoxo";
        $data['sender_source_name']     =   "xoxo";
        $data['order_item']             =   "xoxo";
        $data['transaction_id']         =   "xoxo";
        $order->save($data);
    }
}
