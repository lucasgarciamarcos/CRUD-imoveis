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
    }

    public function test_buscar_imovel()
    {
        $response = $this->json('GET', '/list');
        $json = json_decode($response->response->getContent(), true);
        $last = end($json);

        $this->assertArrayHasKey('id', $last);

        $id = $last['id'];

        $response = $this->json('GET', '/get/' . $id);

        $json = json_decode($response->response->getContent(), true);

        $this->assertArrayHasKey('id', $json);
    }

    public function test_listar_imoveis()
    {
        $response = $this->json('GET', '/list');

        $response->seeStatusCode(200);
        $json = json_decode($response->response->getContent(), true);
        $this->assertNotEmpty($json);
    }

    public function test_deletar_imovel()
    {
        $response = $this->json('GET', '/list');
        $json = json_decode($response->response->getContent(), true);
        $last = end($json);

        $this->assertArrayHasKey('id', $last);

        $id = $last['id'];

        $response = $this->json('GET', '/delete/' . $id);

        $response->seeStatusCode(200);
    }

    public function test_atualizar_imovel()
    {
        $dados = [
            'endereco'  => 'Teste',
            'preco'     => 120,
            'tipo'      => 'A',
            'status'    => 'A',
        ];

        $response = $this->json('POST', '/create', $dados);

        $json = json_decode($response->response->getContent(), true);

        $this->assertArrayHasKey('id', $json);

        $dados = [
            'id'        => $json['id'],
            'endereco'  => 'Teste1',
            'preco'     => 22.22,
            'tipo'      => 'A',
            'status'    => 'V',
        ];

        $response = $this->json('POST', '/create', $dados);

        $response->seeStatusCode(200);
    }
    
}