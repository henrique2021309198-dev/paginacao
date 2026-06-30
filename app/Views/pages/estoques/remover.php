<?= $this->extend('templates/painel') ?>

<?= $this->section('conteudo') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Remover estoque</h4>
    <a href="<?= site_url('admin/produtos') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Voltar para produtos
    </a>
</div>

<div class="card shadow-sm border-0" style="max-width: 640px;">
    <div class="card-body p-4">
        <p class="mb-3">
            <strong><?= esc($produto['nome'] ?? '') ?></strong>
            - Estoque atual: <span class="badge bg-secondary"><?= (int) ($produto['estoque'] ?? 0) ?></span>
        </p>

        <?php if (!empty($produto['foto']) && file_exists(FCPATH . 'uploads/produtos/' . $produto['foto'])) : ?>
            <div class="mb-3">
                <img src="<?= base_url('uploads/produtos/' . esc($produto['foto'])) ?>" style="width:100px;height:100px;object-fit:cover;border-radius:8px;" alt="Foto do produto" />
            </div>
        <?php endif ?>

        <form method="post" action="<?= site_url('estoque/salvar') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="id_produto" value="<?= $produto['id'] ?? '' ?>">
            <input type="hidden" name="tipo" value="saida">

            <div class="mb-3">
                <label class="form-label fw-semibold">Quantidade</label>
                <input type="number" name="quantidade" class="form-control" value="<?= old('quantidade') ?? 1 ?>" step="1" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Observacao</label>
                <input type="text" name="observacao" class="form-control" value="<?= old('observacao') ?? '' ?>">
            </div>

            <button type="submit" class="btn btn-danger">
                <i class="bi bi-dash-lg me-1"></i> Confirmar saida
            </button>
        </form>
    </div>
</div>



<?= $this->endSection() ?>