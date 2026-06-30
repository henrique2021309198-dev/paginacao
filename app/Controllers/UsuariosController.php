<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\ProdutoModel;
use App\Models\EstoqueModel;

class UsuariosController extends BaseController
{
    private UsuariosModel $usuariosModel;
    private array $usuariosColumns = [];

    public function __construct()
    {
        $this->usuariosModel = new UsuariosModel();

        try {
            $this->usuariosColumns = \Config\Database::connect()->getFieldNames('usuarios');
        } catch (\Throwable $e) {
            $this->usuariosColumns = [];
        }
    }

    private function hasUsuariosColumn(string $column): bool
    {
        return in_array($column, $this->usuariosColumns, true);
    }

    private function requireLogin()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('login')->with('error', 'Faca login para continuar.');
        }
        return null;
    }

    private function requireAdmin()
    {
        $usuario = session()->get('usuario');

        $role = $usuario['role'] ?? ((($usuario['tipo'] ?? 'user') === 'admin') ? 'super_admin' : 'usuario');
        if (!$usuario || $role !== 'super_admin') {
            return redirect()->to('usuarios/perfil')->with('error', 'Acesso restrito.');
        }
        return null;
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $email = $this->request->getPost('email');
            $senha = $this->request->getPost('senha');
            $usuario = $this->usuariosModel->findByEmail($email ?? '');

            if ($usuario && ($usuario['status'] ?? 1) == 1) {
                $senhaBanco = $usuario['senha'] ?? ($usuario['senha_hash'] ?? '');
                $senhaValida = password_verify($senha, $senhaBanco)
                    || $senhaBanco === $senha;

                if ($senhaValida) {
                    $usuario['role'] = $usuario['role'] ?? ((($usuario['tipo'] ?? 'user') === 'admin') ? 'super_admin' : 'usuario');
                    $usuario['status'] = $usuario['status'] ?? 1;

                    session()->set([
                        'usuario_id' => $usuario['id'],
                        'usuario'    => $usuario,
                        'logado'     => true,
                    ]);
                    return redirect()->to('painel/consumo');
                }
            }

            return redirect()->back()->with('error', 'Credenciais invalidas ou usuario bloqueado.');
        }

        return view('usuarios_login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }

    // ---- Usuarios -----------------------------------------------------------

    public function index()
    {
        $r = $this->requireLogin(); if ($r) return $r;
        $r = $this->requireAdmin(); if ($r) return $r;

        $buscar = $this->request->getGet('buscar') ?? '';
        return view('usuarios_list', [
            'usuarios' => $this->usuariosModel->buscar($buscar),
            'buscar'   => $buscar,
        ]);
    }

    public function novo()
    {
        $r = $this->requireLogin(); if ($r) return $r;
        $r = $this->requireAdmin(); if ($r) return $r;
        return view('usuarios_form', ['usuario' => [], 'isEdit' => false]);
    }

    public function editar($id)
    {
        $r = $this->requireLogin(); if ($r) return $r;
        $r = $this->requireAdmin(); if ($r) return $r;

        $usuario = $this->usuariosModel->find($id);
        if (!$usuario) {
            return redirect()->to('usuarios')->with('error', 'Usuario nao encontrado.');
        }
        return view('usuarios_form', ['usuario' => $usuario, 'isEdit' => true]);
    }

    public function salvar()
    {
        $r = $this->requireLogin(); if ($r) return $r;
        $r = $this->requireAdmin(); if ($r) return $r;

        $id    = $this->request->getPost('id');
        $senha = $this->request->getPost('senha');

        if (empty($id) && empty($senha)) {
            return redirect()->back()->withInput()->with('error', 'A senha e obrigatoria para novos usuarios.');
        }

        $roleSelecionada = $this->request->getPost('role') ?? 'usuario';
        $dados = [];

        if ($this->hasUsuariosColumn('nome')) {
            $dados['nome'] = $this->request->getPost('nome');
        }

        if ($this->hasUsuariosColumn('email')) {
            $dados['email'] = $this->request->getPost('email');
        }

        if ($this->hasUsuariosColumn('telefone')) {
            $dados['telefone'] = $this->request->getPost('telefone');
        }

        if ($this->hasUsuariosColumn('role')) {
            $dados['role'] = $roleSelecionada;
        }

        if ($this->hasUsuariosColumn('tipo')) {
            $dados['tipo'] = $roleSelecionada === 'super_admin' ? 'admin' : 'user';
        }

        if ($this->hasUsuariosColumn('status')) {
            $dados['status'] = $this->request->getPost('status') ? 1 : 0;
        }

        if (!empty($senha)) {
            if ($this->hasUsuariosColumn('senha')) {
                $dados['senha'] = password_hash($senha, PASSWORD_DEFAULT);
            }

            if ($this->hasUsuariosColumn('senha_hash')) {
                $dados['senha_hash'] = password_hash($senha, PASSWORD_DEFAULT);
            }
        }

        if (!empty($id)) {
            $this->usuariosModel->skipValidation(true)->update($id, $dados);
        } else {
            $this->usuariosModel->skipValidation(true)->insert($dados);
        }

        return redirect()->to('usuarios')->with('success', 'Usuario salvo com sucesso.');
    }

    public function status($id)
    {
        $r = $this->requireLogin(); if ($r) return $r;
        $r = $this->requireAdmin(); if ($r) return $r;

        $usuario = $this->usuariosModel->find($id);
        if (!$usuario) {
            return redirect()->to('usuarios')->with('error', 'Usuario nao encontrado.');
        }

        $novoStatus = ($usuario['status'] ?? 1) == 1 ? 0 : 1;
        $this->usuariosModel->skipValidation(true)->update($id, ['status' => $novoStatus]);

        $msg = $novoStatus ? 'Usuario desbloqueado com sucesso.' : 'Usuario bloqueado com sucesso.';
        return redirect()->to('usuarios')->with('success', $msg);
    }

    // ---- Perfil -------------------------------------------------------------

    public function perfil()
    {
        $r = $this->requireLogin(); if ($r) return $r;
        return view('usuarios_perfil', ['usuario' => session()->get('usuario')]);
    }

    public function salvarPerfil()
    {
        $r = $this->requireLogin(); if ($r) return $r;

        $usuarioAtual = session()->get('usuario');
        $dados = [];

        if ($this->hasUsuariosColumn('nome')) {
            $dados['nome'] = $this->request->getPost('nome');
        }

        if ($this->hasUsuariosColumn('email')) {
            $dados['email'] = $this->request->getPost('email');
        }

        if ($this->hasUsuariosColumn('telefone')) {
            $dados['telefone'] = $this->request->getPost('telefone');
        }

        $senha = $this->request->getPost('senha');
        if (!empty($senha)) {
            if ($this->hasUsuariosColumn('senha')) {
                $dados['senha'] = password_hash($senha, PASSWORD_DEFAULT);
            }

            if ($this->hasUsuariosColumn('senha_hash')) {
                $dados['senha_hash'] = password_hash($senha, PASSWORD_DEFAULT);
            }
        }

        $this->usuariosModel->skipValidation(true)->update($usuarioAtual['id'], $dados);
        session()->set('usuario', $this->usuariosModel->find($usuarioAtual['id']));

        return redirect()->to('usuarios/perfil')->with('success', 'Dados atualizados com sucesso.');
    }

    // ---- Paineis ------------------------------------------------------------

    public function painelConsumo()
    {
        $r = $this->requireLogin(); if ($r) return $r;

        $db           = \Config\Database::connect();
        $produtoModel = new ProdutoModel();
        $estoqueModel = new EstoqueModel();

        $totalProdutos      = 0;
        $totalMovimentacoes = 0;
        $categorias         = [];
        $produtos           = [];

        $categoria = $this->request->getGet('categoria') ?? '';
        $buscar    = $this->request->getGet('buscar')    ?? '';

        try {
            $totalProdutos      = $produtoModel->countAll();
            $totalMovimentacoes = $estoqueModel->countAll();

            $categorias = $db->table('produtos')
                ->select('categoria')->distinct()
                ->orderBy('categoria', 'ASC')
                ->get()->getResultArray();

            $builder = $db->table('produtos p')
                ->select('p.id, p.nome, p.foto, p.categoria, p.estoque, p.estoque_limite,
                          COALESCE(SUM(pp.quantidade), 0) AS quantidade_vendida')
                ->join('pedidos_produtos pp', 'pp.id_produto = p.id', 'left')
                ->groupBy('p.id')
                ->orderBy('p.nome', 'ASC');

            if (!empty($categoria)) {
                $builder->where('p.categoria', $categoria);
            }
            if (!empty($buscar)) {
                $builder->like('p.nome', $buscar);
            }

            $produtos = $builder->get()->getResultArray();
        } catch (\Throwable $e) {
            // Tables may not exist yet
        }

        return view('usuarios_consumo', [
            'usuario'            => session()->get('usuario'),
            'totalProdutos'      => $totalProdutos,
            'totalMovimentacoes' => $totalMovimentacoes,
            'categorias'         => $categorias,
            'produtos'           => $produtos,
            'categoria'          => $categoria,
            'buscar'             => $buscar,
        ]);
    }

    public function painelVendas()
    {
        $r = $this->requireLogin(); if ($r) return $r;

        $db = \Config\Database::connect();

        $periodo    = $this->request->getGet('periodo')     ?? '';
        $dataInicio = $this->request->getGet('data_inicio') ?? date('Y-m-d', strtotime('-30 days'));
        $dataFim    = $this->request->getGet('data_fim')    ?? date('Y-m-d');

        if ($periodo === '7') {
            $dataInicio = date('Y-m-d', strtotime('-7 days'));
            $dataFim    = date('Y-m-d');
        } elseif ($periodo === '30') {
            $dataInicio = date('Y-m-d', strtotime('-30 days'));
            $dataFim    = date('Y-m-d');
        } elseif ($periodo === 'all') {
            $dataInicio = '2000-01-01';
            $dataFim    = date('Y-m-d');
        }

        $totalPedidos = 0;
        $totalValor   = 0.0;
        $porStatus    = [];
        $vendasPorDia = [];
        $totensResumo = [];
        $topTotem = null;
        $chartLabels  = '[]';
        $chartData    = '[]';

        try {
            $totalPedidos = (int) $db->table('pedidos')
                ->where('DATE(created_at) >=', $dataInicio)
                ->where('DATE(created_at) <=', $dataFim)
                ->countAllResults();

            $totalValor = (float) ($db->table('pedidos')
                ->where('status !=', 'cancelado')
                ->where('DATE(created_at) >=', $dataInicio)
                ->where('DATE(created_at) <=', $dataFim)
                ->selectSum('total')
                ->get()->getRow()->total ?? 0);

            $porStatus = $db->table('pedidos')
                ->select('status, COUNT(*) AS quantidade, SUM(total) AS valor')
                ->where('DATE(created_at) >=', $dataInicio)
                ->where('DATE(created_at) <=', $dataFim)
                ->groupBy('status')
                ->get()->getResultArray();

            $vendasPorDia = $db->query(
                "SELECT DATE(created_at) AS data, SUM(total) AS total, COUNT(*) AS pedidos
                 FROM pedidos
                 WHERE status != 'cancelado'
                   AND DATE(created_at) BETWEEN ? AND ?
                 GROUP BY DATE(created_at)
                 ORDER BY data DESC
                 LIMIT 30",
                [$dataInicio, $dataFim]
            )->getResultArray();

            $chartRows = $db->query(
                "SELECT DATE(created_at) AS data, SUM(total) AS total
                 FROM pedidos
                 WHERE status != 'cancelado'
                   AND DATE(created_at) BETWEEN ? AND ?
                 GROUP BY DATE(created_at)
                 ORDER BY data ASC",
                [$dataInicio, $dataFim]
            )->getResultArray();

            $chartLabels = json_encode(array_column($chartRows, 'data'));
            $chartData   = json_encode(array_column($chartRows, 'total'));

            try {
                $totensResumo = $db->query(
                    "SELECT t.id, t.nome, t.codigo, COUNT(p.id) AS pedidos, COALESCE(SUM(p.total), 0) AS total
                     FROM pedidos p
                     LEFT JOIN totens t ON t.id = p.cliente_id
                     WHERE DATE(p.created_at) BETWEEN ? AND ?
                     GROUP BY t.id, t.nome, t.codigo
                     ORDER BY pedidos DESC, total DESC",
                    [$dataInicio, $dataFim]
                )->getResultArray();

                $totensResumo = array_values(array_filter($totensResumo, static function ($row) {
                    return !empty($row['id']) || !empty($row['nome']);
                }));

                $topTotem = $totensResumo[0] ?? null;
            } catch (\Throwable $e) {
                $totensResumo = [];
                $topTotem = null;
            }
        } catch (\Throwable $e) {
            // Tables may not exist yet
        }

        return view('usuarios_vendas', [
            'usuario'      => session()->get('usuario'),
            'totalPedidos' => $totalPedidos,
            'totalValor'   => $totalValor,
            'porStatus'    => $porStatus,
            'vendasPorDia' => $vendasPorDia,
            'totensResumo' => $totensResumo,
            'topTotem' => $topTotem,
            'chartLabels'  => $chartLabels,
            'chartData'    => $chartData,
            'dataInicio'   => $dataInicio,
            'dataFim'      => $dataFim,
            'periodo'      => $periodo,
        ]);
    }
}