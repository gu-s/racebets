<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CustomerRepository{
    public function createCustomer($data){
        return DB::insert("insert into customers (first_name, last_name, email, country, gender, bonus) 
            values ( ?, ?, ?, ?, ?, ?)", 
            [
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['country'],
                $data['gender'],
                rand(5, 20)
            ]
        );

    }

    public function updateCustomer($id, $data){
        return DB::update("update customers 
        set first_name = ? , last_name = ? , email = ?, country = ?, gender = ?
        where id = ?
        ", 
        [
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['country'],
            $data['gender'],
            $id
        ]
    );
    }

    public function showCustomer($id){
        return  DB::selectOne('select * from customers where id = :id', ['id' => $id]);

    }

    public function listCustomer(){
        return  DB::select('select * from customers');
    }

}