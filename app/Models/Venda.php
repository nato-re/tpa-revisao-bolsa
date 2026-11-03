<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    public $fillable = ['cliente_id'];


    public function ativos(){
        return $this->belongsToMany(Ativo::class, 'ativo_venda')->withPivot(['quantidade', 'preco_unitario']);
    }
}
