<?= $this->extend('templates/auth') ?>

<?= $this->section('title') ?>Recuperar senha<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<form action="<?= site_url('recuperar-senha'); ?>" method="POST">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Informe seu e-mail</label>
        <input 
            type="email" 
            name="email" 
            class="form-control" 
            placeholder="Digite seu e-mail cadastrado"
            value="<?= old('email') ?>"
            required
        >
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Enviar link de recuperação
    </button>
</form>

<p class="mt-3 text-center">
    Lembrou a senha? <a href="<?= site_url('login') ?>">Voltar para o login</a>
</p>

<?= $this->endSection() ?>