<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LoanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'book_id' => 1,
                'quantity' => 1,
                'member_id' => 1,
                'uid' => sha1('1'),
            ],
            [
                'id' => 2,
                'book_id' => 4,
                'quantity' => 1,
                'member_id' => 2,
                'uid' => sha1('2'),
            ],
            [
                'id' => 3,
                'book_id' => 2,
                'quantity' => 5,
                'member_id' => 3,
                'uid' => sha1('3'),
            ],
            [
                'id' => 4,
                'book_id' => 1,
                'quantity' => 1,
                'member_id' => 4,
                'uid' => sha1('4'),
            ],
        ];

        $data = array_map(function (array $loan) {
            $loanDate = Time::now()->subDays(rand(31, 40))->subMinutes(rand(0, 240));
            $dueDate = (clone $loanDate)->addDays(rand(7, 30))->addMinutes(rand(0, 240));
            $returnDate = (clone $dueDate)->subDays(rand(0, 6))->subMinutes(rand(0, 240));
            $lateReturnDate = (clone $dueDate)
                ->addDays(rand(1, 30))
                ->addMinutes(rand(0, 240))
                ->toDateTimeString();

            $loan['loan_date'] = $loanDate->toDateTimeString();

            $loan['due_date'] = $dueDate->toDateTimeString();
            if ($loan['id'] == 2) {
                $loan['due_date'] = Time::now()->addDays(rand(2, 14))->toDateTimeString();
            }

            $loan['return_date'] = null;
            if ($loan['id'] == 1) {
                $loan['return_date'] = $returnDate;
            } else if ($loan['id'] > 2) {
                $loan['return_date'] = $lateReturnDate;
            }

            return $loan;
        }, $data);

        $this->db->table('loans')->insertBatch($data);

        $this->call('FineSeeder');
    }
}
