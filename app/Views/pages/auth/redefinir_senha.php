<?= $this->extend('templates/auth') ?>

<?= $this->section('title') ?>Redefinir senha<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<form action="<?= site_url('redefinir-senha'); ?>" method="POST">
    <?= csrf_field() ?>

    <input type="hidden" name="token" value="<?= esc($token) ?>">

    <div class="mb-3">
        <label class="form-label">Nova senha</label>
        <input 
            type="password" 
            name="senha" 
            class="form-control" 
            placeholder="Digite a nova senha"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Confirmar nova senha</label>
        <input 
            type="password" 
            name="confirmar_senha" 
            class="form-control" 
            placeholder="Confirme a nova senha"
            required
        >
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Salvar nova senha
    </button>
</form>

<?= $this->endSection() ?>