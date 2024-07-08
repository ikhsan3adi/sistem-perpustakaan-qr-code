<?php

namespace App\Database\Seeds;

use App\Models\LoanModel;
use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class FineSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'loan_id'       => 3,
                'amount_paid'   => null,
                'fine_amount'   => 20000,
                'paid_at'       => null
            ],
            [
                'loan_id'       => 4,
                'amount_paid'   => 15000,
                'fine_amount'   => 15000,
                'paid_at'       => Time::parse((new LoanModel)->find(4)['return_date'])
                    ->addDays(rand(0, 30))
                    ->toDateTimeString()
            ],
        ];

        $this->db->table('fines')->insertBatch($data);
    }
}
