<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cita;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
      'nombre',
      'apellido',
      'username',
      'tipo_cliente',
      'organizacion',
      'numero_documento',
      'tiene_visitas',
  ];

  public function citas() {
      return $this->hasMany(Cita::class);
  }
}
