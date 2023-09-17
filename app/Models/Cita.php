<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
  use HasFactory;

  protected $fillable = [
    'fecha_inicio', 'fecha_fin', 'estado', 'cliente_id', 'usuario_id', 'diagnostico', 'practicas_a_desarrollar', ' ubicacion'
  ];

  public function cliente()
  {
    return $this->belongsTo(Cliente::class, 'cliente_id');
  }

  public function usuario()
  {
    return $this->belongsTo(User::class, 'usuario_id');
  }
}
