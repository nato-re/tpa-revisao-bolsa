<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ativo extends Model
{
    public $fillable = [
        'ticker',
        'empresa',
        'setor',
        'preco',
        'estoque',
        'vendedor_id',
    ];

    public function vendedor(){
        return $this->belongsTo(User::class);
    }
}
