<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Imovel extends Model
{
    use HasFactory;

    // Dicionário para tipos
    const TIPO_CASA = 'C';
    const TIPO_APARTAMENTO = 'A';
    const TIPO_TERRENO = 'T';

    // Dicionário para status
    const STATUS_DISPONIVEL = 'D';
    const STATUS_ALUGADO = 'A';
    const STATUS_VENDIDO = 'V';

    protected $table = 'imoveis';

    protected $fillable = [
        'tipo', 'endereco', 'preco', 'status'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!empty($attributes)) {
            $validator = Validator::make($attributes, self::getRules());
    
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
    }

    public static function getRules()
    {
        return [
            'preco'    => 'required|numeric',
            'endereco' => 'required|string',
            'tipo'      => [
                'required',
                Rule::in(Imovel::getTipos()),
            ],
            'status'    => [
                'required',
                Rule::in(Imovel::getStatus()),
            ],
        ];
    }

    /**
     * Retorna uma lista de tipos suportados.
     *
     * @return array Uma lista das tipos suportados.
     */
    public static function getTipos()
    {
        return [self::TIPO_APARTAMENTO, self::TIPO_CASA, self::TIPO_TERRENO];
    }

    /**
     * Retorna uma lista de status suportados.
     *
     * @return array Uma lista das status suportados.
     */
    public static function getStatus()
    {
        return [self::STATUS_ALUGADO, self::STATUS_DISPONIVEL, self::STATUS_VENDIDO];
    }

}
