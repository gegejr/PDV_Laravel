<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClientesController;
use App\Http\Controllers\Admin\EstoqueController;
use App\Http\Controllers\Admin\VendasController;
use App\Http\Controllers\Admin\CaixaController;
use App\Http\Controllers\Admin\RelatorioController;
use App\Http\Controllers\Admin\UserSettings;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota principal (acesso raíz)
Route::get('/', function () {
    return view('welcome'); // ou substitua por uma view personalizada
});

// Rotas de autenticação
Auth::routes();

// Página inicial após login
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Área administrativa protegida por auth
Route::middleware(['auth'])->prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CLIENTES
    |--------------------------------------------------------------------------
    */
    Route::get('/clientes/cadastrar', [ClientesController::class, 'index'])->name('clientes.cadastrar');
    Route::post('/clientes/save', [ClientesController::class, 'cadastrar'])->name('clientes.save');
    Route::get('/clientes/todos', [ClientesController::class, 'lista'])->name('clientes.todos');
    Route::post('/cliente/editar', [ClientesController::class, 'editar'])->name('clientes.editar');
    Route::post('/cliente/saveeditar', [ClientesController::class, 'saveEditar'])->name('clientes.saveEdit');

    /*
    |--------------------------------------------------------------------------
    | ESTOQUE
    |--------------------------------------------------------------------------
    */
    Route::get('/estoque/cadastrar', [EstoqueController::class, 'index'])->name('estoque.cadastrar');
    Route::post('/estoque/save', [EstoqueController::class, 'cadastrar'])->name('estoque.save');
    Route::get('/estoque/todos', [EstoqueController::class, 'lista'])->name('estoque.todos');
    Route::get('/estoque/modal', [EstoqueController::class, 'viewModal'])->name('estoque.atributos.modal');
    Route::post('/estoque/somente-add', [EstoqueController::class, 'addAtributo'])->name('estoque.atributos.add');
    Route::get('/estoque/somente/{id}', [EstoqueController::class, 'viewAtributos'])->name('estoque.atributos');
    Route::get('/estoque/atributos', [EstoqueController::class, 'viewAlterarAtributo'])->name('estoque.editar.atributos');
    Route::post('/estoque/atributos', [EstoqueController::class, 'saveAlterarAtributos'])->name('estoque.editar.atributos');

    // API Estoque
    Route::prefix('ap/estoque')->group(function () {
        Route::get('/', [EstoqueController::class, 'APIListar'])->name('estoque.api.listar');
        Route::post('/disponivel', [EstoqueController::class, 'APIDisponivel'])->name('estoque.api.disponivel');
        Route::get('/{id}', [EstoqueController::class, 'APIFind'])->name('estoque.api.find');
        Route::post('/', [EstoqueController::class, 'saveEditar'])->name('estoque.api.save');
        Route::post('/delete', [EstoqueController::class, 'APIapagar'])->name('estoque.api.delete');
        Route::post('/find', [EstoqueController::class, 'APIprocurarEstoqueID'])->name('estoque.api.estoqueID');
    });

    // API Clientes
    Route::prefix('ap/cliente')->group(function () {
        Route::get('/', [ClientesController::class, 'APIListar'])->name('cliente.api.listar');
        Route::get('/{id}', [ClientesController::class, 'APIFind'])->name('cliente.api.find');
    });

    /*
    |--------------------------------------------------------------------------
    | VENDAS
    |--------------------------------------------------------------------------
    */
    Route::get('/venda', [VendasController::class, 'vendasView'])->name('venda');
    Route::post('/venda', [VendasController::class, 'Registrar'])->name('venda.registrar');
    Route::get('/venda/cupom', [VendasController::class, 'GerarCupom'])->name('venda.cupom.route');
    Route::get('/venda/cupom/{id}', [VendasController::class, 'GerarCupom'])->name('venda.cupom');
    Route::post('/venda/cancelar', [VendasController::class, 'CancelarVenda'])->name('venda.cancelar');

    /*
    |--------------------------------------------------------------------------
    | CAIXA
    |--------------------------------------------------------------------------
    */
    Route::get('/caixa/abrir', [CaixaController::class, 'iniciarCaixaView'])->name('caixa.abrir');
    Route::post('/caixa/abrir', [CaixaController::class, 'iniciarCaixa'])->name('caixa.abrir');
    Route::get('/caixa/fechar', [CaixaController::class, 'fecharCaixaView'])->name('caixa.fechar');
    Route::post('/caixa/fechar', [CaixaController::class, 'fecharCaixa'])->name('caixa.fechar');
    Route::get('/caixa/sangria', [CaixaController::class, 'sangriaView'])->name('sangria.view');
    Route::post('/caixa/sangria', [CaixaController::class, 'sangriaPost'])->name('sangria.post');
    Route::get('/caixa/adicionar', [CaixaController::class, 'addCaixaView'])->name('caixa.add.view');
    Route::post('/caixa/adicionar', [CaixaController::class, 'addCaixa'])->name('caixa.add.post');

    /*
    |--------------------------------------------------------------------------
    | HISTÓRICO
    |--------------------------------------------------------------------------
    */
    Route::get('/historico', [CaixaController::class, 'historico'])->name('historico');
    Route::post('/historico/imprimir', [CaixaController::class, 'historicoPrint'])->name('historico.print');
    Route::get('ap/historico/{type}/{id}', [CaixaController::class, 'historicoAPI'])->name('historico.api');
    Route::post('ap/historico', [CaixaController::class, 'historicoAPI'])->name('historico.api.post');

    /*
    |--------------------------------------------------------------------------
    | RELATÓRIOS
    |--------------------------------------------------------------------------
    */
    Route::get('/relatorio', [RelatorioController::class, 'index'])->name('relatorio');
    Route::get('/relatorio/ano/{id}', [RelatorioController::class, 'index'])->name('relatorio.ano');
    Route::get('/relatorio/backup', [RelatorioController::class, 'BackupIndex'])->name('relatorio.backup.view');
    Route::post('/relatorio/backup', [RelatorioController::class, 'ImportBackup'])->name('relatorio.backup.import');

    /*
    |--------------------------------------------------------------------------
    | CONFIGURAÇÕES
    |--------------------------------------------------------------------------
    */
    Route::get('/settings', [UserSettings::class, 'index'])->name('UserSettings.index');
    Route::post('/settings', [UserSettings::class, 'edit'])->name('UserSettings.edit');

    /*
    |--------------------------------------------------------------------------
    | DEBUG
    |--------------------------------------------------------------------------
    */
    Route::get('/debug', [ClientesController::class, 'debug'])->name('clientes.debug');
});
