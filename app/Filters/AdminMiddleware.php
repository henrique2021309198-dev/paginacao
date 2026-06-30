<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminMiddleware implements FilterInterface
{
   
    public function before(RequestInterface $request, $arguments = null)
    { 
        $usuario = session()->get('usuario');
        $allowedTypes = is_array($arguments) ? $arguments : ['admin'];

        if (!$usuario || !in_array($usuario['tipo'], $allowedTypes, true)) {
            return redirect()->to('erro/401')->with('erros', 'Acesso negado.');
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    { }
}