<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProdutoModel;
use App\Models\EstoqueModel;

class EstoqueController extends BaseController
{
    protected ProdutoModel $produtoModel;
    protected EstoqueModel $estoqueModel;

    public function __construct(){
        $this->produtoModel = new ProdutoModel();
        $this->estoqueModel = new EstoqueModel();
    }

    public function index()
    {
        $produtos = $this->produtoModel->findAll();
        return view ('pages/estoques/index', ['produtos' => $produtos]);
    }

    //carregar o form de cadastro de estoque
    //recebe id do produto
    //busca no banco
    //retorna formulário com os dados do produto   
    public function adicionar($id){
        $produto = $this->produtoModel->find($id);
        if(empty($produto)){
            return redirect()->to(site_url('estoque'));
        }

        return view('pages/estoques/adicionar', ['produto' => $produto]);
        
    }

    //função que recebe os dados do form e salva o estoque para o produto x

 public function salvar()
    {
        //valida se produto existe...

        //recebe os dados do formulário
        $dados = [
            'id_produto' => $this->request->getPost('id_produto'),
            'quantidade' => $this->request->getPost('quantidade'),
            'fornecedor' => $this->request->getPost('fornecedor') ?? null,
            'observacao' => $this->request->getPost('observacao'),
            'tipo' => $this->request->getPost('tipo')

        ];

        //salva o estoque na tabela estoques
        $this->estoqueModel->insert($dados);

        //atualiza a tabela de produtos
        $produto = $this->produtoModel->find($dados['id_produto']);

        if($dados['tipo'] == 'entrada'){
            $produto['estoque'] = $produto['estoque'] 
            + (int) $dados['quantidade'];
            
        }
        else if($dados['tipo'] == 'saida'){
            $produto['estoque'] = $produto['estoque'] 
            - (int) $dados['quantidade'];
        }
        else{
            $produto['estoque'] = $produto['estoque'];
            //ou trazer um erro para o usuario
        }

        // Atualiza apenas o campo `estoque` para evitar validações no model
        $this->produtoModel->update($produto['id'], ['estoque' => (int) $produto['estoque']]);

        return redirect()->to(site_url('admin/produtos'))->with('success', 'Estoque atualizado com sucesso.');
    }


    public function remover($id)
    {
        $produto = $this->produtoModel->find($id);

        if (empty($produto)) {
            return redirect()->to(site_url('estoque'));
        }
        
        return view('pages/estoques/remover', 
	        ['produto' => $produto]
	       );
    }

    public function historico($id = null)
    {
        if (empty($id) || is_null($id)) {
            return redirect()->to(site_url('estoque'));
        }

        $produto = $this->produtoModel->find($id);

        if (empty($produto)) {
            return redirect()->to(site_url('estoque'));
        }

        $estoques = $this->
        estoqueModel->where('id_produto', $id)->findAll();

        return view('pages/estoques/historico', 
		        ['estoques' => $estoques, 'produto' => $produto]
        );


    }
}
