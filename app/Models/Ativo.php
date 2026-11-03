<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ativo extends Model
{
    public $fillable = [
        "ticker",
        "empresa",
        "cotacao",
        "setor",
        "estoque",
        "vendedor_id",
    ];
    public function vendedor():BelongsTo {
        return $this->belongsTo(User::class, 'vendedor_id');
    }
}
