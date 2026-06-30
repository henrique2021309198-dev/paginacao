<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthMiddleware implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if(!session()->get('logado')){
            return redirect()->to('login')->with('erros', 'Sem permissão');
        }
        //se não tiver return, ele vai para o controller.
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {    
    }
}
