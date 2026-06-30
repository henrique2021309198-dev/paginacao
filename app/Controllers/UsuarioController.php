<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\UsuarioModel;


class UsuarioController extends BaseController
{

    protected UsuarioModel $model;

    public function __construct()
    {
        $this->model = new UsuarioModel();
    }

    //função para apresentar o form de cadastro
    public function cadastrar(){
        return view("pages/auth/cadastrar");
    }

    //função para receber os dados de cadastro
    public function salvarUsuario(){
        $data = [
            'email' => $this->request->getPost('email'),
            'senha_hash' => password_hash($this->request->getPost('senha'), PASSWORD_DEFAULT),
            'tipo' => 'user',
        ];
        if(!$this->model->insert($data)){
            return redirect()->back()->withInput()->with('erros', 'Erro ao salvar');
        }
        return redirect()->to('login')->with('sucesso', 'Conta criada. Faça seu login');
    }
    //função para apresentar o form de login
    public function login(){
        return view('pages/auth/login');
    }
    //função para receber os dados de login
    public function autenticar(){
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        //procura registro no banco
        $registro = $this->model->where('email', $email)->first();

        if($registro && password_verify($senha, $registro['senha_hash'])){
            session()->set('logado', true); //var de controle
            session()->set('usuario', $registro); 

            return redirect()->to('/admin/produtos'); 
        }
        else{
            return redirect()->back()->with('erros', 'Email ou senha inválidos')->withInput();
        }
    }
    //função para logout
    public function logout(){
        session()->destroy();
        return redirect()->to('login');
    }

    public function recuperarSenha()
{
    return view('pages/auth/recuperar_senha');
}

public function enviarRecuperacao()
{
    $emailDigitado = $this->request->getPost('email');

    if (!$emailDigitado) {
        return redirect()->back()->withInput()->with('erros', 'Informe o e-mail.');
    }

    // Procura o usuário pelo e-mail
    $usuario = $this->model->where('email', $emailDigitado)->first();

    if (!$usuario) {
        return redirect()->back()->withInput()->with('erros', 'E-mail não encontrado.');
    }

    // Gera token aleatório
    $token = bin2hex(random_bytes(50));

    // Define expiração para 1 hora depois
    $dataExpiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Salva o token no banco
    $this->model->update($usuario['id'], [
        'reset_token' => $token,
        'reset_token_date' => $dataExpiracao
    ]);

    // Cria o link que será enviado por e-mail
    $link = site_url('redefinir-senha/' . $token);

    // Prepara o serviço de e-mail
    $emailService = \Config\Services::email();

    $emailService->setFrom('naoresponda@cantina.com', 'Cantina');
    $emailService->setTo($usuario['email']);
    $emailService->setSubject('Recuperação de senha');

    $mensagem = "
        <h2>Recuperação de senha</h2>
        <p>Recebemos uma solicitação para redefinir sua senha.</p>
        <p>Clique no link abaixo para criar uma nova senha:</p>
        <p><a href='{$link}'>{$link}</a></p>
        <p>Este link expira em 1 hora.</p>
    ";

    $emailService->setMessage($mensagem);

    if (!$emailService->send()) {
        return redirect()->back()->with('erros', $emailService->printDebugger(['headers', 'subject', 'body']));
    }

    return redirect()->to('login')->with('sucesso', 'Enviamos um link de recuperação para o seu e-mail.');
}

public function abrirRedefinicao($token)
{
    // Procura usuário com esse token
    $usuario = $this->model->where('reset_token', $token)->first();

    if (!$usuario) {
        return redirect()->to('login')->with('erros', 'Token inválido.');
    }

    // Verifica se o token expirou
    if (strtotime($usuario['reset_token_date']) < time()) {
        return redirect()->to('login')->with('erros', 'Token expirado. Solicite uma nova recuperação de senha.');
    }

    return view('pages/auth/redefinir_senha', [
        'token' => $token
    ]);
}

public function salvarNovaSenha()
{
    $token = $this->request->getPost('token');
    $senha = $this->request->getPost('senha');
    $confirmarSenha = $this->request->getPost('confirmar_senha');

    if (!$senha || !$confirmarSenha) {
        return redirect()->back()->with('erros', 'Preencha todos os campos.');
    }

    if ($senha !== $confirmarSenha) {
        return redirect()->back()->with('erros', 'As senhas não conferem.');
    }

    if (strlen($senha) < 6) {
        return redirect()->back()->with('erros', 'A senha deve ter pelo menos 6 caracteres.');
    }

    // Procura usuário pelo token
    $usuario = $this->model->where('reset_token', $token)->first();

    if (!$usuario) {
        return redirect()->to('login')->with('erros', 'Token inválido.');
    }

    // Confere novamente se o token não expirou
    if (strtotime($usuario['reset_token_date']) < time()) {
        return redirect()->to('login')->with('erros', 'Token expirado. Solicite uma nova recuperação de senha.');
    }

    // Atualiza a senha e limpa o token
    $this->model->update($usuario['id'], [
        'senha_hash' => password_hash($senha, PASSWORD_DEFAULT),
        'reset_token' => null,
        'reset_token_date' => null
    ]);

    return redirect()->to('login')->with('sucesso', 'Senha alterada com sucesso. Faça login novamente.');
}



   
}
