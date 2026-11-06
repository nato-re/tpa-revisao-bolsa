<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Venda extends Model
{
    public $fillable = ['cliente_id'];

    public function ativos():BelongsToMany {
        return $this->belongsToMany(Ativo::class, 'ativo_venda')->withPivot(['quantidade', 'preco']);
    }
    
}
