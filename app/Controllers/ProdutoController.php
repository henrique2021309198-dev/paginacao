<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ProdutoModel;


class ProdutoController extends BaseController
{

    protected ProdutoModel $model;

    public function __construct()
    {
        $this->model = new ProdutoModel();
    }

    //Listar todos os produtos
    public function index(): string
    {
        //verificar a sessão
        //redirecionar o usuário

        $this->verificarLogin();

        //BUSCAR TODOS OS REGISTROS
        //$lanches = $this->model->findAll();

        //RECEBE VALOR POR GET
        $busca = $this->request->getGet('busca');
        $preco = $this->request->getGet('preco');
        $ordenacao = $this->request->getGet('ordenacao');

        if($busca){
            //se existe a variável, aplica o filtro
            $this->model->like('nome', $busca);
        }

        // FILTRO DE PREÇO
        if ($preco) {
            if ($preco === 'baixo') {
                // Preço abaixo de R$ 5
                $this->model->where('preco <', 5);
            } elseif ($preco === 'medio') {
                // Preço entre R$ 5 e R$ 10
                $this->model->where('preco >=', 5);
                $this->model->where('preco <=', 10);
            } elseif ($preco === 'alto') {
                // Preço acima de R$ 10
                $this->model->where('preco >', 10);
            }
        }

         // Ordenação: Crescente ou Decrescente
        if ($ordenacao === 'asc') {
            $this->model->orderBy('preco', 'asc');  // Ordenar preço de forma crescente
        } elseif ($ordenacao === 'desc') {
            $this->model->orderBy('preco', 'desc'); // Ordenar preço de forma decrescente
        }

        //COM PAGINAÇÃO
        $produtos = $this->model->paginate(5);

        return view(
            'pages/produtos/index',
            [
                'titulo' => 'Lista de produtos',
                'produtos' => $produtos,
                'pager' => $this->model->pager,
                'busca' => $busca,
                'preco' => $preco
            ]
        );
    }

    public function novo(){
        return view("pages/produtos/cadastro", 
            [
                'produto' => null
            ]
        );
    }

    public function salvar(){
        $id = $this->request->getPost('id');
        $isUpdate = !empty($id);

        $dados = [
            'nome' => $this->request->getPost('nome'),
            'preco' => $this->request->getPost('preco'),
            'categoria' => $this->request->getPost('categoria'),
        ];

        $regrasFoto = [
            'foto' => 'if_exist|is_image[foto]|mime_in[foto,image/jpeg,image/png,image/gif]|ext_in[foto,jpg,jpeg,png,gif]|max_size[foto,2048]'  
        ];

        //validação dos campos de texto e validação do arquivo 
        $erros = [];

        if(!$this->model->validate($dados)){
            $erros = array_merge($erros, $this->model->errors());
        }

        if(!$this->validate($regrasFoto)){
            $erros = array_merge($erros, $this->validator->getErrors());
        }

        if(!empty($erros)){
            return redirect()->back()->withInput()->with('errors', $erros);
        }

        //manipulação do arquivo
        $foto = $this->request->getFile('foto');
        $nomeFotoRandomico = null;

        if($foto && $foto->isValid()){
            //gerar nome randomico
            $nomeFotoRandomico = $foto->getRandomName();

            //mover o arquivo para a pasta de uploads
            $foto->move(FCPATH . 'uploads/produtos/', $nomeFotoRandomico);
        }

        // Preparar dados para salvar
        $dadosParaSalvar = [
            'nome' => $this->request->getPost('nome'),
            'preco' => $this->request->getPost('preco'),
            'categoria' => $this->request->getPost('categoria'),
        ];

        if($nomeFotoRandomico){
            $dadosParaSalvar['foto'] = $nomeFotoRandomico;

            // Se é update e havia foto antiga, deletar
            if($isUpdate){
                $produtoAntigo = $this->model->find($id);
                if($produtoAntigo && !empty($produtoAntigo['foto'])){
                    $caminhoAntigo = FCPATH . 'uploads/produtos/' . $produtoAntigo['foto'];
                    if(file_exists($caminhoAntigo)){
                        unlink($caminhoAntigo);
                    }
                }
            }
        } elseif(!$isUpdate){
            // Para insert, se não há foto, não definir
            // Mas no model, talvez seja nullable
        }

        //salvar o registro no banco
        if($isUpdate){
            if(!$this->model->update($id, $dadosParaSalvar)){
                // Se falhou e moveu arquivo, deletar
                if($nomeFotoRandomico){
                    unlink(FCPATH . 'uploads/produtos/' . $nomeFotoRandomico);
                }
                return redirect()->back()->withInput()->with('errors', $this->model->errors());
            }
            $mensagem = 'Produto atualizado com sucesso.';
        } else {
            if(!$this->model->insert($dadosParaSalvar)){
                // Se falhou e moveu arquivo, deletar
                if($nomeFotoRandomico){
                    unlink(FCPATH . 'uploads/produtos/' . $nomeFotoRandomico);
                }
                return redirect()->back()->withInput()->with('errors', $this->model->errors());
            }
            $mensagem = 'Produto cadastrado com sucesso.';
        }

        return redirect()->to(site_url('admin/produtos'))->with('success', $mensagem);
    }

    public function excluir($id){
        $produto = $this->model->find($id);

        if(!$produto){
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        // Excluir o arquivo físico se existir
        if(!empty($produto['foto'])){
            $caminhoArquivo = FCPATH . 'uploads/produtos/' . $produto['foto'];
            if(file_exists($caminhoArquivo)){
                unlink($caminhoArquivo);
            }
        }

        // Excluir o registro do banco
        $this->model->delete($id);

        return redirect()->to(site_url('admin/produtos'))->with('success', 'Produto excluído com sucesso.');
    }

    public function editar($id){
        $produto = $this->model->find($id);

        if(!$produto){
            return redirect()->to(site_url('admin/produtos'))->with('error', 'Produto não encontrado.');
        }

        return view("pages/produtos/cadastro", 
            [
                'produto' => $produto
            ]
        );
    }


}
