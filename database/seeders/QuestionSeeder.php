<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el usuario con ID 1
        $user = User::find(1);

        if (!$user) {
            $this->command->error('El usuario con ID 1 no existe. Ejecuta primero el UserSeeder.');
            return;
        }

        // Preguntas de seguridad predefinidas con sus respuestas
        $questionsData = [
            [
                'question_text' => '¿Cuál es el nombre de tu primera mascota?',
                'answer_text' => 'Firulais'
            ],
            [
                'question_text' => '¿En qué ciudad naciste?',
                'answer_text' => 'Quito'
            ],
            [
                'question_text' => '¿Cuál es el nombre de tu mejor amigo de la infancia?',
                'answer_text' => 'Carlos'
            ]
        ];

        // Crear las preguntas para el usuario
        foreach ($questionsData as $questionData) {
            Question::create([
                'user_id' => $user->id,
                'question_text' => Crypt::encryptString($questionData['question_text']), // Encriptar la pregunta
                'answer_text' => Crypt::encryptString($questionData['answer_text']) // Encriptar la respuesta
            ]);
        }

    }
}
