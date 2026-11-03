<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Venda extends Model
{
    public $fillable = ['cliente_id', 'quantidade', 'preco_unitario'];
    public function ativos(): BelongsToMany{
        return $this->belongsToMany(Ativo::class)->withPivot('quantidade', 'preco_unitario');
    }
}
