<?php

namespace Tests;

class ImovelTest extends TestCase
{
    public function test_criar_imovel()
    {
        $dados = [
            'endereco'  => 'Teste',
            'preco'     => 120,
            'tipo'      => 'A',
            'status'    => 'A',
        ];

        $response = $this->json('POST', '/create', $dados);

        $response->seeStatusCode(201);

        $response->seeJson([
            'preco' => 120
        ]);
    }
}