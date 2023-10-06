<?php

namespace Database\Factories;

use App\Models\Imovel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImovelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Imovel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'endereco' => $this->faker->randomFloat(2, 1, 10000),
            'preco' => $this->faker->randomFloat(2, 1, 10000),
            'tipo' => $this->faker->randomElement(Imovel::getTipos()),
            'status' => $this->faker->randomElement(Imovel::getStatus()),
        ];
    }
}
