<?php

namespace App\Models;

use CodeIgniter\Model;

class CodeModel extends Model
{
    protected $table = 'codes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'code',
        'valeur',
        'utilise'
    ];

    public function trouverValide(string $code): ?array
    {
        return $this->where('code', trim($code))
            ->where('utilise', 0)
            ->first();
    }

    public function marquerUtilise(int $codeId): bool
    {
        return (bool) $this->update($codeId, ['utilise' => 1]);
    }
}