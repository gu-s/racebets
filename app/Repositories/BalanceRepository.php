<?php

namespace App\Repositories;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class BalanceRepository{

    const DEFAULT_TIME_FRAME_REPORT = 7; // last 7 days

    public function deposit($data){
        try{
            DB::beginTransaction();
            $customerBonus =  DB::scalar('select bonus from customers where id = :id FOR SHARE', ['id' => $data['customer_id']]);
            $customerBalance =  DB::selectOne('select * from balances where customer_id = :customer_id', ['customer_id' => $data['customer_id']]);
            $currentAmount = 0;
            $currentDeposit = 0;
            $currentBonus = 0;

            $bonusToApply = 0;

            DB::insert(
                "insert into balance_transactions (customer_id, deposit_amount, operation) values ( ?, ?, ?)",
                [
                    $data['customer_id'],
                    $data['amount'],
                    'deposit',
                ]
            );


            if(!empty($customerBalance)){
                $currentAmount = $customerBalance->amount;
                $currentDeposit = $customerBalance->deposit_amount;
                $currentBonus = $customerBalance->bonus_amount;
                
                $depositsNumber =  DB::scalar("select count(*) from balance_transactions where operation = 'deposit' and customer_id = :customer_id", ['customer_id' => $data['customer_id']]);
                if($depositsNumber % 3 == 0){
                    // $bonusToApply =  DB::scalar('select bonus from customers where id = :id', ['id' => $data['customer_id']]);
                    $bonusToApply =  $customerBonus;
                }
                
                
            }

            $bonusDeposit = $data['amount'] * $bonusToApply / 100;

            DB::statement("insert into balances(customer_id, amount, deposit_amount, bonus_amount) 
            VALUES (?, ?, ?, ?) as new
            ON DUPLICATE KEY UPDATE amount = new.amount, deposit_amount = new.deposit_amount, bonus_amount = new.bonus_amount"
            ,[
                $data['customer_id'],
                $currentAmount + $data['amount'] + $bonusDeposit,
                $currentDeposit + $data['amount'],
                $currentBonus + $bonusDeposit,
            ]
            );



            DB::commit();
        }
        catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function withdraw($data){
        try{
            DB::beginTransaction();

            $customerBalance =  DB::selectOne('select * from balances where customer_id = :customer_id FOR SHARE', ['customer_id' => $data['customer_id']]);
            
            if (!empty($customerBalance) && $customerBalance->deposit_amount >0 && $customerBalance->deposit_amount >= $data['amount']) {
                DB::insert(
                    "insert into balance_transactions (customer_id, deposit_amount, operation) 
                    values ( ?, ?, ?)",
                    [
                        $data['customer_id'],
                        $data['amount'],
                        'withdraw',
                    ]
                );

                DB::update(
                    "update balances set amount = ?, deposit_amount =? where customer_id = ? ",
                    [
                        $customerBalance->amount - $data['amount'],
                        $customerBalance->deposit_amount - $data['amount'],
                        $data['customer_id']
                    ]
                );


            }
            else {
                throw new Exception("not enough amount to withdraw ");
            }

            DB::commit();
        }
        catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function report($timeFrame = null)
    {
        $timeFrame = $timeFrame ?? self::DEFAULT_TIME_FRAME_REPORT;
        $fromDate =  Carbon::now()->subDays($timeFrame);

        return  DB::select("select 
            DATE(bt.date_time) as 'date',
            c.country, 
            count(distinct c.id) as unique_customers,
            SUM(bt.operation = 'deposit') as no_deposits,
            SUM(if(bt.operation = 'deposit', bt.deposit_amount, 0)) as total_deposit_amount,
            SUM(bt.operation = 'withdraw') as no_withdrawals,
            (-1 * SUM(if(bt.operation = 'withdraw', bt.deposit_amount, 0))) as total_withdrawal_amount
            from balance_transactions bt
            inner join balances b on b.customer_id = bt.customer_id
            inner join customers c on c.id = b.customer_id
            where DATE(bt.date_time) >= ?
            group by DATE(bt.date_time), c.country
        ", [$fromDate->format('Y-m-d')]);
    }

}