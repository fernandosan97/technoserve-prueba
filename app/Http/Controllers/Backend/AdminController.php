<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard() {
      $totalClientes = Cita::count();
      $totalUsuarios = User::count();

      return view('admin.dashboard', [
        'totalClientes' => $totalClientes,
        'totalUsuarios' => $totalUsuarios,
    ]);
    }
}
