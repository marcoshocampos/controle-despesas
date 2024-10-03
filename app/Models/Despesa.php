<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;

    protected $table = 'despesas';

    protected $fillable = [
        'descricao',
        'valor',
        'data_ocorrencia',
        'id_user'
    ];

    // converte atributos para um tipo específico
    protected $casts = [
        'data_ocorrencia' => 'date',
        'valor' => 'decimal:2',
    ];

    // relacionamento com user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
