<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ativo extends Model
{
    public $fillable = [
        'ticker',
        'empresa',
        'setor',
        'estoque',
        'preco',
        'vendedor_id',
    ];

    public function vendedor(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    
}
