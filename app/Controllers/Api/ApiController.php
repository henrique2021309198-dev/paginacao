<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PedidoModel;
use App\Models\PedidoProdutoModel;
use App\Models\ProdutoModel;
use App\Models\EstoqueModel;
use App\Models\TotemModel;

class ApiController extends BaseController
{
    use ResponseTrait;

    public function api_status()
    {
        return $this->respond([], 200, "Api funcionando");
    }
    
    public function get_produtos()
    {
        $produtoModel = new ProdutoModel();
        $produtos = $produtoModel->findAll();
        return $this->respond($produtos, 200);
    }

    public function get_totens()
    {
        $authFail = $this->validateApiKey();
        if ($authFail instanceof ResponseInterface) {
            return $authFail;
        }

        $totemModel = new TotemModel();

        return $this->respond([
            'totens' => $totemModel->listarAtivos(),
        ], 200);
    }

    public function criarTotem()
    {
        $authFail = $this->validateApiKey();
        if ($authFail instanceof ResponseInterface) {
            return $authFail;
        }

        $dados = $this->request->getJSON(true);
        $nome = trim((string) ($dados['nome'] ?? ''));

        if ($nome === '') {
            return $this->failValidationErrors('Nome do totem é obrigatório.');
        }

        $codigo = url_title($nome, '-', true);
        if ($codigo === '') {
            $codigo = 'totem-' . time();
        }

        $totemModel = new TotemModel();
        $baseCodigo = $codigo;
        $suffix = 1;
        while ($totemModel->buscarPorCodigo($codigo)) {
            $suffix++;
            $codigo = $baseCodigo . '-' . $suffix;
        }

        $id = $totemModel->insert([
            'nome' => $nome,
            'codigo' => $codigo,
            'ativo' => 1,
        ]);

        return $this->respondCreated([
            'status' => true,
            'totem' => $totemModel->find($id),
        ]);
    }

    public function checkout()
    {
        $authFail = $this->validateApiKey();
        if ($authFail instanceof ResponseInterface) {
            return $authFail;
        }

        $dados = $this->request->getJSON(true);

        //verifica se os dados do pedido foram informados
        if (!$dados) {
            return $this->failValidationErrors('JSON inválido.');
        }

        if (!isset($dados['produtos']) || empty($dados['produtos'])) {
            return $this->failValidationErrors('O pedido precisa ter pelo menos um produto.');
        }

        $totemId = (int) ($dados['totem_id'] ?? 0);
        if ($totemId <= 0) {
            return $this->failValidationErrors('Totem não informado.');
        }

        $totemModel = new TotemModel();
        $totem = $totemModel->find($totemId);
        if (!$totem || (int) ($totem['ativo'] ?? 0) !== 1) {
            return $this->failValidationErrors('Totem inválido ou inativo.');
        }


        $pedidoModel = new PedidoModel();
        $pedidoProdutoModel = new PedidoProdutoModel();
        $produtoModel = new ProdutoModel();
        $estoqueModel = new EstoqueModel();

        $status = $this->normalizeStatus($dados['status'] ?? 'novo');
        $total = 0;
        foreach ($dados['produtos'] as $produto) {
            $total += (float) ($produto['preco_unitario'] ?? 0) * (int) ($produto['quantidade'] ?? 0);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $idPedido = $pedidoModel->insert([
            'cliente_id' => $totemId,
            'total' => number_format($total, 2, '.', ''),
            'status' => $status,
        ]);

        foreach ($dados['produtos'] as $produto) {
            $pedidoProdutoModel->insert([
                'id_pedido' => $idPedido,
                'id_produto' => $produto['id_produto'],
                'quantidade' => $produto['quantidade'],
                'preco_unitario' => $produto['preco_unitario']
            ]);

            $produtoAtual = $produtoModel->find($produto['id_produto']);

            if (!$produtoAtual) {
                $db->transRollback();
                return $this->failNotFound('Produto do pedido não encontrado (ID ' . $produto['id_produto'] . ').');
            }

            $qtdBaixa = (int) ($produto['quantidade'] ?? 0);
            $estoqueAtual = (int) ($produtoAtual['estoque'] ?? 0);

            if ($qtdBaixa <= 0) {
                $db->transRollback();
                return $this->failValidationErrors('Quantidade inválida no item do pedido.');
            }

            if ($estoqueAtual < $qtdBaixa) {
                $db->transRollback();
                return $this->failValidationErrors('Estoque insuficiente para o produto: ' . ($produtoAtual['nome'] ?? $produtoAtual['id']) . '.');
            }

            $produtoModel->update($produtoAtual['id'], [
                'estoque' => $estoqueAtual - $qtdBaixa,
            ]);

            $estoqueModel->insert([
                'id_produto' => $produtoAtual['id'],
                'quantidade' => $qtdBaixa,
                'fornecedor' => null,
                'observacao' => 'Baixa automática do pedido #' . $idPedido,
                'tipo' => 'saida',
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() == false) {
            return $this->failServerError('Erro ao cadastrar pedido.');
        }

        return $this->respondCreated([
            'status'    => true,
            'message'   => 'Pedido cadastrado com sucesso.',
            'id_pedido' => $idPedido,
            'totem'     => $totem,
        ]);
    }

    public function atualizarStatusPedido($idPedido = null)
    {
        $authFail = $this->validateApiKey();
        if ($authFail instanceof ResponseInterface) {
            return $authFail;
        }

        if (empty($idPedido)) {
            return $this->failValidationErrors('ID do pedido não informado.');
        }

        $dados = $this->request->getJSON(true);
        $status = $this->normalizeStatus((string) ($dados['status'] ?? ''));

        if (empty($status)) {
            return $this->failValidationErrors('Status inválido.');
        }

        $pedidoModel = new PedidoModel();
        $pedidoProdutoModel = new PedidoProdutoModel();
        $produtoModel = new ProdutoModel();
        $estoqueModel = new EstoqueModel();

        $pedido = $pedidoModel->find($idPedido);
        if (!$pedido) {
            return $this->failNotFound('Pedido não encontrado.');
        }

        $statusAnterior = $this->normalizeStatus((string) ($pedido['status'] ?? 'pendente'));

        // Evita baixa duplicada de estoque se já estiver concluído.
        if ($statusAnterior === 'concluido' && $status === 'concluido') {
            return $this->respond([
                'status' => true,
                'message' => 'Pedido já estava concluído.',
                'id_pedido' => (int) $idPedido,
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        if ($statusAnterior !== 'concluido' && $status === 'concluido') {
            $itensPedido = $pedidoProdutoModel->where('id_pedido', $idPedido)->findAll();

            $baixaJaAplicada = $estoqueModel
                ->where('observacao', 'Baixa automática do pedido #' . $idPedido)
                ->countAllResults() > 0;

            if ($baixaJaAplicada) {
                $pedidoModel->update($idPedido, ['status' => $status]);
                $db->transComplete();

                if ($db->transStatus() === false) {
                    return $this->failServerError('Erro ao atualizar status do pedido.');
                }

                return $this->respond([
                    'status' => true,
                    'message' => 'Status do pedido atualizado com sucesso.',
                    'id_pedido' => (int) $idPedido,
                    'status_anterior' => $statusAnterior,
                    'status_atual' => $status,
                ]);
            }

            foreach ($itensPedido as $item) {
                $produto = $produtoModel->find($item['id_produto']);

                if (!$produto) {
                    $db->transRollback();
                    return $this->failNotFound('Produto do pedido não encontrado (ID ' . $item['id_produto'] . ').');
                }

                $qtdBaixa = (int) ($item['quantidade'] ?? 0);
                $estoqueAtual = (int) ($produto['estoque'] ?? 0);

                if ($qtdBaixa <= 0) {
                    $db->transRollback();
                    return $this->failValidationErrors('Quantidade inválida no item do pedido.');
                }

                if ($estoqueAtual < $qtdBaixa) {
                    $db->transRollback();
                    return $this->failValidationErrors('Estoque insuficiente para o produto: ' . ($produto['nome'] ?? $produto['id']) . '.');
                }

                $produtoModel->update($produto['id'], [
                    'estoque' => $estoqueAtual - $qtdBaixa,
                ]);

                $estoqueModel->insert([
                    'id_produto' => $produto['id'],
                    'quantidade' => $qtdBaixa,
                    'fornecedor' => null,
                    'observacao' => 'Baixa automática do pedido #' . $idPedido,
                    'tipo' => 'saida',
                ]);
            }
        }

        $pedidoModel->update($idPedido, ['status' => $status]);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Erro ao atualizar status do pedido.');
        }

        return $this->respond([
            'status' => true,
            'message' => 'Status do pedido atualizado com sucesso.',
            'id_pedido' => (int) $idPedido,
            'status_anterior' => $statusAnterior,
            'status_atual' => $status,
        ]);
    }

    private function validateApiKey(): ?ResponseInterface
    {
        $apiKey = $this->request->getHeaderLine('apiKey');

        if (!$apiKey) {
            return $this->failUnauthorized('API Key não informada.');
        }

        if ($apiKey !== env('API_KEY')) {
            return $this->failUnauthorized('API Key inválida.');
        }

        return null;
    }

    private function normalizeStatus(string $status): string
    {
        $status = strtolower(trim($status));

        if ($status === '') {
            return '';
        }

        return match ($status) {
            'novo', 'pendente' => 'pendente',
            'processando' => 'processando',
            'finalizado', 'concluido' => 'concluido',
            'cancelado' => 'cancelado',
            default => 'pendente',
        };
    }
}
