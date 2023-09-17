<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\TecnicoController;
use App\Http\Controllers\Backend\UsuariosController;
use App\Http\Controllers\Backend\ClienteController;
use App\Http\Controllers\Backend\SecretariaController;
use App\Http\Controllers\Backend\ClienteSecretariaController;
use App\Http\Controllers\Backend\VisitaController;
use App\Http\Controllers\Backend\TecnicoVisitasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin'])->group(function () {
  Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

  // Ruta modulo de usuarios
  Route::get('/admin/usuarios', [UsuariosController::class, 'index'])->name('admin.usuarios');
  Route::get('/admin/usuarios/create', [UsuariosController::class, 'create'])->name('admin.usuarios.create');
  Route::post('/admin/usuarios/store', [UsuariosController::class, 'store'])->name('admin.usuarios.store');
  Route::get('/admin/usuarios/{usuario}/editar', [UsuariosController::class, 'edit'])->name('admin.usuarios.edit');
  Route::patch('/admin/usuarios/{usuario}', [UsuariosController::class, 'update'])->name('admin.usuarios.update');
  Route::delete('/admin/usuarios/{usuario}', [UsuariosController::class, 'destroy'])->name('admin.usuarios.destroy');

  // Ruta modulo de Clientes
  Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('admin.clientes');
  Route::get('/admin/clientes/create', [ClienteController::class, 'create'])->name('admin.clientes.create');
  Route::post('/admin/clientes/store', [ClienteController::class, 'store'])->name('admin.clientes.store');
  Route::get('/admin/clientes/{cliente}/editar', [ClienteController::class, 'edit'])->name('admin.clientes.edit');
  Route::patch('/admin/clientes/{cliente}', [ClienteController::class, 'update'])->name('admin.clientes.update');
  Route::delete('/admin/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('admin.clientes.destroy');
});

Route::middleware(['auth', 'role:secretaria'])->group(function () {
  Route::get('/secretaria/dashboard', [SecretariaController::class, 'dashboard'])->name('secretaria.dashboard');
  Route::get('/secretaria/dashboard/citas-del-dia/excel', [SecretariaController::class, 'citasDelDiaExcel'])->name('secretaria.dashboard.citas.del.dia.excel');
  Route::get('/secretaria/dashboard/citas-del-dia/ejecutadas/excel', [SecretariaController::class, 'citasDelDiaEjecutadasExcel'])->name('secretaria.dashboard.citas.del.dia.ejecutadas.excel');
  Route::get('/secretaria/dashboard/citas-del-mes/excel', [SecretariaController::class, 'citasDelMesExcel'])->name('secretaria.dashboard.citas.del.mes.excel');

  // Ruta modulo de Clientes
  Route::get('/secretaria/clientes', [ClienteSecretariaController::class, 'index'])->name('secretaria.clientes');
  Route::get('/secretaria/clientes/create', [ClienteSecretariaController::class, 'create'])->name('secretaria.clientes.create');
  Route::post('/secretaria/clientes/store', [ClienteSecretariaController::class, 'store'])->name('secretaria.clientes.store');
  Route::get('/secretaria/clientes/{cliente}/editar', [ClienteSecretariaController::class, 'edit'])->name('secretaria.clientes.edit');
  Route::patch('/secretaria/clientes/{cliente}', [ClienteSecretariaController::class, 'update'])->name('secretaria.clientes.update');
  Route::delete('/secretaria/clientes/{cliente}', [ClienteSecretariaController::class, 'destroy'])->name('secretaria.clientes.destroy');

  // Ruta modulo de visitas
  Route::get('/secretaria/visitas', [VisitaController::class, 'index'])->name('secretaria.visitas');
  Route::get('/secretaria/visitas/create', [VisitaController::class, 'create'])->name('secretaria.visitas.create');
  Route::post('/secretaria/visitas/store', [VisitaController::class, 'store'])->name('secretaria.visitas.store');

  Route::get('/secretaria/visitas/tecnico/{tecnico}', [VisitaController::class, 'visitasTecnico'])->name('secretaria.visitas.tecnico');
  Route::post('/secretaria/visitas/tecnico', [VisitaController::class, 'store'])->name('secretaria.visitas.tecnico.create');
  Route::get('/secretaria/visitas/{cita}', [VisitaController::class, 'edit'])->name('secretaria.visitas.tecnico.edit');
  Route::patch('/secretaria/visitas/tecnico/{cita}', [VisitaController::class, 'update'])->name('secretaria.visitas.tecnico.update');
  Route::get('/secretaria/visitas/tecnico/download/pdf/{cita}', [VisitaController::class, 'downloadPdf'])->name('secretaria.visitas.tecnico.download.pdf');
  Route::get('/secretaria/visitas/tecnico/download/excel', [VisitaController::class, 'downloadExcel'])->name('secretaria.visitas.download.excel');
  
  Route::delete('/secretaria/visitas/tecnico/{cita}', [VisitaController::class, 'destroy'])->name('secretaria.visitas.tecnico.destroy');
});

Route::middleware(['auth', 'role:tecnico'])->group(function () {
  Route::get('/tecnico/dashboard', [TecnicoController::class, 'dashboard'])->name('tecnico.dashboard');

  Route::get('/tecnico/visitas', [TecnicoVisitasController::class, 'index'])->name('tecnico.visitas');
  Route::get('/tecnico/visitas/tecnico/{cita}', [TecnicoVisitasController::class, 'edit'])->name('tecnico.visitas.tecnico.edit');
  Route::patch('/tecnico/visitas/tecnico/{cita}', [TecnicoVisitasController::class, 'update'])->name('tecnico.visitas.tecnico.update');
  Route::delete('/tecnico/visitas/tecnico/{cita}', [TecnicoVisitasController::class, 'destroy'])->name('tecnico.visitas.tecnico.destroy');
});
