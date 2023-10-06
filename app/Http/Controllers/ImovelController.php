<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Imovel;

class ImovelController extends Controller
{
    /**
     * Lista todos imoveis ou filtra por pesquisa (opcional).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $search = $request->query('search');

        $query = Imovel::query();
    
        if ($search) {
            $query->where('endereco', 'like', '%' . $search . '%');
        }
    
        $imoveis = $query->get();
    
        return response()->json($imoveis);
    }

    /**
     * Cria um novo imovel ou atualiza um existente.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'endereco'  => 'string|min:1',
            'preco'     => 'numeric|min:0',
            'tipo'      => [
                'required',
                Rule::in(Imovel::getTipos()),
            ],
            'status'    => [
                'required',
                Rule::in(Imovel::getStatus()),
            ],
        ]);

        $dadosImovel = [
            'endereco'  => $request->endereco,
            'preco'     => $request->preco,
            'tipo'      => $request->tipo,
            'status'    => $request->status,
        ];
    
        if ($request->id) {
            // Atualize o imóvel existente com base no ID
            $imovel = Imovel::find($request->id);
    
            if (!$imovel) {
                return response()->json(['error' => 'Imóvel não encontrado'], 404);
            }
    
            $imovel->update($dadosImovel);
        } else {
            // Crie um novo imóvel
            $imovel = new Imovel($dadosImovel);
            $imovel->save();
        }
    
        return response()->json([
            'id'     => $imovel->id,
            'mensagem' => $request->id ? 'Imóvel atualizado com sucesso' : 'Imóvel criado com sucesso',
        ], $request->id ? 200 : 201);
    }

    /**
     * Obtém informações de um Imovel pelo ID.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1', // Certifica-se de que o ID seja um número inteiro positivo
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'ID inválido'], 400); // Retorna um erro de solicitação inválida (Bad Request)
        }

        $imovel = Imovel::find($id);

        if (!$imovel) {
            return response()->json(['error' => 'Imóvel não encontrado'], 404);
        }

        return response()->json($imovel, 200);
    }

     /**
     * Deleta Imovel pelo ID.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1', // Certifica-se de que o ID seja um número inteiro positivo
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'ID inválido'], 400); // Retorna um erro de solicitação inválida (Bad Request)
        }

        $imovel = Imovel::find($id);

        if (!$imovel) {
            return response()->json(['error' => 'Imóvel não encontrado'], 404);
        }

        $imovel->delete();

        return response()->json(['mensagem' => 'Imóvel excluído com sucesso'], 200);
    }

}
