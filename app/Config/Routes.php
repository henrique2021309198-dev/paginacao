<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'UsuariosController::login');
$routes->post('login', 'UsuariosController::login');
$routes->get('logout', 'UsuariosController::logout');

$routes->get('usuarios', 'UsuariosController::index');
$routes->get('usuarios/novo', 'UsuariosController::novo');
$routes->post('usuarios/salvar', 'UsuariosController::salvar');
$routes->get('usuarios/editar/(:num)', 'UsuariosController::editar/$1');
$routes->get('usuarios/status/(:num)', 'UsuariosController::status/$1');
$routes->get('usuarios/perfil', 'UsuariosController::perfil');
$routes->post('usuarios/perfil', 'UsuariosController::salvarPerfil');

$routes->get('painel/consumo', 'UsuariosController::painelConsumo');
$routes->get('painel/vendas', 'UsuariosController::painelVendas');

//Aula dia 14/04
//rota para exibir o formulário de novo lanche
//$routes->get('admin/lanches/novo', 'LancheController::novo');

//rota para receber os dados do formulário
//$routes->post('admin/lanches/salvar', 'LancheController::salvar');

//rota para editar lanche
//$routes->get('admin/lanches/editar/(:num)', 'LancheController::editar/$1', ['filter' => 'admin:admin']);

//rota para excluir lanche
//$routes->get('admin/lanches/excluir/(:num)', 'LancheController::excluir/$1');

//Aula dia 28/04
//rota para cadastrar e login
$routes->get('cadastrar', 'UsuarioController::cadastrar');

//rota para salvar usuario
$routes->post('salvar_usuario', 'UsuarioController::salvarUsuario');

// Rotas de login/logout gerenciadas pelo UsuariosController
// $routes->get('login', 'UsuarioController::login');
// $routes->post('login', 'UsuarioController::autenticar');
// $routes->get('logout', 'UsuarioController::logout');

// recuperação de senha
$routes->get('recuperar-senha', 'UsuarioController::recuperarSenha');
$routes->post('recuperar-senha', 'UsuarioController::enviarRecuperacao');

$routes->get('redefinir-senha/(:segment)', 'UsuarioController::abrirRedefinicao/$1');
$routes->post('redefinir-senha', 'UsuarioController::salvarNovaSenha');

$routes->get('erro/401', function () {
    return view('errors/html/error_401');
});

//1 -> global
//2 -> rota especifica 
//3 -> agrupamento de rotas

// Rota pública para visualizar produtos
$routes->get('admin/produtos', 'ProdutoController::index');

$routes->group('', ['filter'=>'admin:admin'], function($routes) {
    $routes->get('admin/produtos/excluir/(:num)', 'ProdutoController::excluir/$1');
    $routes->get('admin/produtos/novo', 'ProdutoController::novo');
    $routes->post('admin/produtos/salvar', 'ProdutoController::salvar');
    $routes->get('admin/produtos/editar/(:num)', 'ProdutoController::editar/$1');
});

//ESTOQUE
//retorna todos os produtos e seus estoques
$routes->get('estoque', 'EstoqueController::index');

//retorna o formulário para adicionar o estoque para o produto x
//o :num é o id do produto
$routes->get('estoque/adicionar/(:num)', 'EstoqueController::adicionar/$1');

//retorna o formulário para remover o estoque do produto x
$routes->get('estoque/remover/(:num)', 'EstoqueController::remover/$1');

//salvar o estoque do produto x
$routes->post('estoque/salvar', 'EstoqueController::salvar');

//retorna o histórico de movimentações de estoque do produto x
$routes->get('estoque/historico/(:num)', 'EstoqueController::historico/$1');

//Rotas de API
$routes->get('api/status', '\App\Controllers\Api\ApiController::api_status');
$routes->get('api/produtos', '\App\Controllers\Api\ApiController::get_produtos');
$routes->get('api/totens', '\App\Controllers\Api\ApiController::get_totens');
$routes->post('api/totens', '\App\Controllers\Api\ApiController::criarTotem');
$routes->post('api/checkout', '\App\Controllers\Api\ApiController::checkout');
$routes->post('api/pedidos/(:num)/status', '\App\Controllers\Api\ApiController::atualizarStatusPedido/$1');



