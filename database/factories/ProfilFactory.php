<?php

namespace Database\Factories;

use App\Models\Profil;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfilFactory extends Factory
{
    protected $model = Profil::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->name,
            'prénom' => $this->faker->firstName,
            'image' => $this->faker->imageUrl,
            'statut' => 'actif',
        ];
    }
}
