<?= $this->extend('templates/painel') ?>

<?= $this->section('conteudo') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Adicionar estoque</h4>
    <a href="<?= site_url('admin/produtos') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Voltar para produtos
    </a>
</div>

<div class="card shadow-sm border-0" style="max-width: 640px;">
    <div class="card-body p-4">
        <p class="mb-3"><strong><?= esc($produto['nome']) ?></strong> - Estoque atual: <span class="badge bg-secondary"><?= (int) $produto['estoque'] ?></span></p>

        <form action="<?= site_url('estoque/salvar') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id_produto" value="<?= esc($produto['id']) ?>">
            <input type="hidden" name="tipo" value="entrada">

            <div class="mb-3">
                <label class="form-label fw-semibold">Quantidade</label>
                <input type="number" name="quantidade" class="form-control" value="<?= old('quantidade') ?? 1 ?>" step="1" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Fornecedor</label>
                <input type="text" name="fornecedor" class="form-control" value="<?= old('fornecedor') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Observacao</label>
                <input type="text" name="observacao" class="form-control" value="<?= old('observacao') ?>">
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> Confirmar entrada
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>