<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class PaymentMethodsMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_methods = [
            ['1','Bank Transfer'],
            ['2','Cash On Hand'],
            ['3','Cheque'],
            ['4','Credit Note'],
            ['5','Any']
            ];
        foreach ($payment_methods as $key => $value):
        $payment_method[]     = [
            'id'              => $value[0],
            'payment_methods' => $value[1]
        ];
        endforeach ;
        DB::table('payment_methods')->insert($payment_method);
    }
}
