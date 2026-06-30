
<?= $this->extend('templates/public') ?>

<?= $this->section('title') ?>Início<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
    <div class="p-4 bg-white rounded shadow-sm">
        <h1>Bem-vindo(a)!</h1>
        <p>Esta é a página inicial da cantina.</p>
        <a href="<?= site_url('/admin/produtos') ?>" class="btn btn-primary">Acessar área administrativa</a>
    </div>
<?= $this->endSection() ?>
   