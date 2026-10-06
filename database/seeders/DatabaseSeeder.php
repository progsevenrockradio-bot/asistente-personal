<?php

namespace Database\Seeders;

use App\Models\PendingQuestion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@asistente.local'],
            [
                'name' => 'José Font',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Eventos de ejemplo para hoy y próximos días
        if ($user->events()->count() === 0) {
            $user->events()->createMany([
                [
                    'titulo' => 'Cita Médica - Revisión Anual',
                    'descripcion' => 'Revisión periódica y analítica en medicina interna',
                    'fecha' => Carbon::today(),
                    'hora_inicio' => '10:00:00',
                    'hora_fin' => '11:00:00',
                    'duracion' => 60,
                    'ubicacion' => 'Centro Médico Sanitas, Consulta 14',
                    'prioridad' => 'IMPORTANTE',
                    'estado' => 'confirmado',
                    'origen' => 'manual',
                    'notas' => 'Llevar carnet y resultados previos',
                ],
                [
                    'titulo' => 'Concierto en WiZink Center',
                    'descripcion' => 'Entradas en pista',
                    'fecha' => Carbon::today()->addDays(2),
                    'hora_inicio' => '21:00:00',
                    'hora_fin' => '23:30:00',
                    'duracion' => 150,
                    'ubicacion' => 'WiZink Center, Acceso Puerta 12',
                    'prioridad' => 'NORMAL',
                    'estado' => 'confirmado',
                    'origen' => 'manual',
                ],
            ]);
        }

        // Tareas de ejemplo
        if ($user->tasks()->count() === 0) {
            $user->tasks()->createMany([
                [
                    'titulo' => 'Comprar billetes de tren',
                    'prioridad' => 'URGENTE',
                    'fecha_limite' => Carbon::today()->addDays(1),
                    'estado' => 'pendiente',
                ],
                [
                    'titulo' => 'Descargar informes médicos en PDF',
                    'prioridad' => 'IMPORTANTE',
                    'fecha_limite' => Carbon::today(),
                    'estado' => 'pendiente',
                ],
                [
                    'titulo' => 'Revisar notas exportadas de NotebookLM',
                    'prioridad' => 'NORMAL',
                    'fecha_limite' => Carbon::today()->addDays(3),
                    'estado' => 'pendiente',
                ],
            ]);
        }

        // Elemento de bandeja con pregunta pendiente
        if ($user->inboxItems()->count() === 0) {
            $inbox = $user->inboxItems()->create([
                'content' => 'El viernes tengo médico a las 10 y el sábado tengo un concierto.',
                'source' => 'texto',
                'status' => 'necesita_informacion',
            ]);

            PendingQuestion::create([
                'user_id' => $user->id,
                'inbox_item_id' => $inbox->id,
                'entidad_tipo' => 'evento',
                'campo_faltante' => 'ubicación y duración',
                'pregunta' => '¿Dónde es la cita médica y cuánto durará aproximadamente?',
                'estado' => 'pendiente',
            ]);
        }
    }
}
