<?php

namespace App\Models;

use CodeIgniter\Model;

class FinesPerDayModel extends Model
{
    protected $table            = 'fines_per_day';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'amount',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'amount' => 'required|numeric|greater_than_equal_to[1000]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public static function getAmount(): int
    {
        return intval(self::get()['amount'] ?? 0);
    }

    public static function get()
    {
        return (new FinesPerDayModel)->first();
    }

    public static function updateAmount(int $amount)
    {
        $current = self::get();
        if (!$current) {
            return (new FinesPerDayModel)->insert([
                'amount' => $amount ?? 1000,
            ]);
        }
        $data = [
            'amount' => $amount ?? $current['amount'],
        ];
        return (new FinesPerDayModel)->update($current['id'], $data);
    }
}
