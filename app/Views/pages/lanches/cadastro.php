<?= $this->extend('templates/admin') ?>

<?= $this->section('conteudo') ?>
<!--Validação de erros - pegar do outro projeto -->

<form method="post" action="<?=site_url('admin/lanches/salvar');?>" enctype="multipart/form-data">
    <?=csrf_field();?>

    <?php if(isset($lanche) && $lanche): ?>
        <input type="hidden" name="id" value="<?=$lanche['id']?>">
    <?php endif; ?>

    <label>Nome:</label>
    <input type="text" name="nome" value="<?=old('nome', $lanche['nome'] ?? '')?>"> <br>

    <label>Preço:</label>
    <input type="number" name="preco" value="<?=old('preco', $lanche['preco'] ?? '')?>"> <br>

    <label>Foto:</label>
    <input type="file" name="foto" accept="image/*"> <br>
    <?php if(isset($lanche) && $lanche && !empty($lanche['foto'])): ?>
        <p>Foto atual: <img src="<?=base_url('uploads/lanches/' . $lanche['foto'])?>" width="100"></p>
    <?php endif; ?>

    <button type="submit"><?=(isset($lanche) && $lanche) ? 'Atualizar' : 'Cadastrar'?></button>

</form>


<?=$this->endSection()?>
