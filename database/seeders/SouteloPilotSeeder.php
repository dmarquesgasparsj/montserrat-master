<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Location;
use App\Models\Permission;
use App\Models\Registration;
use App\Models\Retreat;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class SouteloPilotSeeder extends Seeder
{
    public function run(): void
    {
        if (! config('pilot.enabled') || DB::connection()->getDatabaseName() !== 'soutelo_pilot') {
            throw new RuntimeException('Use the separate soutelo_pilot database with SOUTELO_PILOT enabled.');
        }

        $email = config('pilot.admin_email');
        $password = config('pilot.admin_password');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || ! is_string($password) || strlen($password) < 14) {
            throw new RuntimeException('Set a valid pilot email and a password of at least 14 characters.');
        }

        DB::transaction(function () use ($email, $password) {
            if (Contact::query()->exists() || User::query()->exists() || Permission::query()->exists()) {
                throw new RuntimeException('The pilot seeder requires an empty database.');
            }

            $this->call(DatabaseSeeder::class);

            $user = User::create([
                'name' => 'Administração de teste',
                'username' => $email,
                'email' => $email,
                'password' => Hash::make($password),
                'provider' => 'soutelo-pilot',
                'provider_id' => $email,
                'avatar' => '',
            ]);
            $role = Permission::whereName('superuser')->firstOrFail()->roles()->firstOrFail();
            $user->roles()->syncWithoutDetaching([$role->id]);

            $location = new Location;
            $location->forceFill([
                'name' => 'Alojamento de teste',
                'label' => 'Alojamento de teste',
                'description' => 'Edifício fictício',
                'notes' => '',
                'occupancy' => 3,
                'type' => 'B',
            ])->save();

            $rooms = [];
            foreach (['T01' => 1, 'T02' => 2] as $name => $capacity) {
                $room = new Room;
                $room->forceFill([
                    'location_id' => $location->id,
                    'floor' => 0,
                    'name' => $name,
                    'description' => 'Quarto fictício',
                    'notes' => '',
                    'access' => 'N/A',
                    'type' => 'Bedroom',
                    'occupancy' => (string) $capacity,
                    'status' => 'A',
                ])->save();
                $rooms[] = $room;
            }

            $friday = now()->next('Friday');
            $sunday = $friday->copy()->addDays(2);

            foreach ([1, 2] as $number) {
                $retreat = Retreat::create([
                    'title' => 'Grupo de teste '.$number,
                    'idnumber' => 'PILOT-'.$number,
                    'event_type_id' => 1,
                    'start_date' => $friday->copy()->setTime($number === 1 ? 18 : 12, 0),
                    'end_date' => $sunday->copy()->setTime($number === 1 ? 11 : 15, 0),
                    'is_active' => 1,
                    'max_participants' => 8,
                ]);

                $contact = Contact::create([
                    'contact_type' => config('polanco.contact_type.individual'),
                    'first_name' => 'Participante',
                    'last_name' => 'de teste '.$number,
                    'display_name' => 'Participante de teste '.$number,
                    'sort_name' => 'Participante de teste '.$number,
                ]);

                $registration = new Registration;
                $registration->forceFill([
                    'contact_id' => $contact->id,
                    'event_id' => $retreat->id,
                    'room_id' => $rooms[$number - 1]->id,
                    'status_id' => config('polanco.registration_status_id.registered'),
                    'role_id' => config('polanco.participant_role_id.retreatant'),
                    'register_date' => now(),
                ])->save();
            }
        });
    }
}
