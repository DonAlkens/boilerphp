<?php

namespace App;

use App\Core\Database\Model;


class Order extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function address() {

        return $this->hasOne(AddressBook::class, ["id" => "address"]);
    }

    public function customer() {

        return $this->hasOne(Customer::class, ["id" => "customer"]);
    }

    public function items() {

        return $this->hasMultiple(OrderItem::class, ["order" => "id"]);
    }

    public function transaction() {

        return $this->hasMultiple(Transaction::class, ["order" => "id"]);
    }

}

?>