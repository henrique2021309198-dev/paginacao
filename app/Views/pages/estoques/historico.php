<?= $this->extend('templates/admin') ?>

<?= $this->section('conteudo') ?>

<h1>Gestão de estoque - Histórico de movimentações - <?= $produto['nome'] ?? '' ?>
</h1>

<?php foreach ($estoques as $estoque) : ?>
    <ul>
        <li>
            Data: <?= $estoque['created_at'] ?? '' ?> <br />
            Fornecedor: <?= $estoque['fornecedor'] ?? '' ?> <br />
            Quantidade: <?= $estoque['quantidade'] ?? '' ?> <br />
            Tipo: <?= $estoque['tipo'] ?? '' ?> <br />
            Observação: <?= $estoque['observacao'] ?? '' ?>
        </li>

    </ul>
<?php endforeach; ?>


<?= $this->endSection() ?>