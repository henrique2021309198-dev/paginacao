<?= $this->extend('templates/painel') ?>

<?= $this->section('conteudo') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold"><?= (isset($produto) && $produto) ? 'Editar produto' : 'Novo produto' ?></h4>
    <a href="<?= site_url('admin/produtos') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Voltar
    </a>
</div>

<div class="card shadow-sm border-0" style="max-width: 680px;">
    <div class="card-body p-4">
        <form method="post" action="<?= site_url('admin/produtos/salvar'); ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <?php if (isset($produto) && $produto) : ?>
                <input type="hidden" name="id" value="<?= $produto['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nome</label>
                <input type="text" name="nome" class="form-control" value="<?= old('nome', $produto['nome'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Categoria</label>
                <input type="text" name="categoria" class="form-control" value="<?= old('categoria', $produto['categoria'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Preço</label>
                <input type="number" step="0.01" name="preco" class="form-control" value="<?= old('preco', $produto['preco'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Foto</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>

            <?php if (isset($produto) && $produto && !empty($produto['foto'])) : ?>
                <div class="mb-3">
                    <p class="fw-semibold mb-2">Foto atual</p>
                    <img src="<?= base_url('uploads/produtos/' . $produto['foto']) ?>" style="width:120px;height:120px;object-fit:cover;border-radius:8px;" alt="Foto do produto">
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-dark">
                <i class="bi bi-check-lg me-1"></i>
                <?= (isset($produto) && $produto) ? 'Atualizar' : 'Cadastrar' ?>
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
