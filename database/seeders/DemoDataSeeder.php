<?php

namespace Database\Seeders;

use App\Models\AdoptionRequest;
use App\Models\Developer;
use App\Models\Organization;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    protected array $descriptions = [
        'Es un perrito muy cariñoso y juguetón al que le encanta correr al aire libre. Se lleva bien con otros perros y disfruta mucho de los mimos. Busca una familia activa que pueda darle largas caminatas y mucho amor.',
        'Una perrita dulce y tranquila que disfruta de la compañía humana. Es ideal para un hogar con espacio al aire libre. Le encanta dormir al sol y recibir caricias en la panza. Muy obediente y aprende rápido.',
        'Este peludito llegó al refugio después de vivir en la calle, pero su espíritu es increíble. Es agradecido, noble y está aprendiendo a confiar en las personas. Necesita una familia paciente que le brinde seguridad y cariño.',
        'Una perrita muy inteligente y llena de energía. Le fascina jugar con la pelota y aprender trucos nuevos. Es ideal para una familia que quiera compartir aventuras al aire libre. Conoce órdenes básicas y es muy atenta.',
        'Un perro de tamaño mediano con un corazón enorme. Es compañero fiel y le encanta participar en las actividades de la casa. Se adapta fácilmente a nuevos entornos. Bueno con niños y otros animales.',
        'Tierno, juguetón y muy sociable. Este cachorro busca un hogar donde reciba la atención y el cuidado que merece. Está lleno de energía y ganas de explorar el mundo. Ideal para familias que tengan tiempo para dedicarle.',
        'Este perro adulto es la definición de la lealtad. Es calmado, educado y agradece cada muestra de afecto. Se merece un hogar donde pueda descansar tranquilo y recibir el amor que nunca tuvo.',
        'Una perrita rescatada de una situación difícil, pero que nunca perdió su alegría. Es juguetona, curiosa y muy cariñosa con las personas que conoce. Con paciencia y amor, se convierte en la compañera más fiel.',
        'Un compañero de四 patas único. Es tranquilo en casa pero le encanta jugar en el parque. Tiene un carácter equilibrado y se adapta bien a la vida en departamento. Recomendado para dueños primerizos.',
        'Perro de gran tamaño pero de temperamento dócil y protector. Ideal para una casa con patio amplio. Es leal a su familia y desconfía con extraños, lo que lo convierte en un excelente guardián.',
        'Esta gata elegante y cariñosa busca un hogar donde reinar. Le encanta dormir en lugares altos y recibir mimos en la mañana. Es independiente pero siempre vuelve buscando caricias.',
        'Un gato juguetón al que le fascinan los juguetes con plumas y las cajas de cartón. Es muy activo durante la noche y ronronea fuerte cuando está contento. Ideal para quienes disfrutan de la compañía felina.',
        'Una gata dulce y tímida que necesita un hogar tranquilo sin otros animales. Con paciencia se convierte en la compañera más dulce. Le encantan las siestas largas y las comidas sabrosas.',
        'Este gato adulto es la compañía perfecta para días de lluvia y mantas calentitas. Es sereno, limpio y muy agradecido. Ya está castrado y acostumbrado a usar su arenero a la perfección.',
        'Una gata rescatada de la calle que ha demostrado una fortaleza increíble. Ahora busca un hogar donde la mimen y la consientan. Es cariñosa, curiosa y le encanta explorar cada rincón.',
        'Este gato joven tiene una personalidad carismática que conquista a todos. Es sociable, juguetón y se lleva bien con otros gatos. Busca una familia que entienda que un rascador nuevo es siempre bienvenido.',
        'Una pareja inseparable de perros que buscan un hogar juntos. Se complementan perfectamente: ella es la protectora y él es el divertido. Adoptarlos juntos es darles la felicidad que merecen.',
        'Esta perrita mayor merece disfrutar de sus años dorados en un hogar cálido. Es tranquila, agradecida y sabe exactamente lo que quiere: una cama cómoda, comida rica y alguien que la quiera.',
        'Un gato rescatado de bebé que creció sano y fuerte gracias a los cuidados del refugio. Es juguetón, cariñoso y muy comunicativo. Maúlla para pedir comida, caricias o simplemente para conversar.',
        'Perro mediano de pelaje suave y mirada expresiva. Es tímido al principio, pero una vez que toma confianza se vuelve muy cariñoso. Le encanta acompañar a su humano a cualquier lado.',
    ];

    public function run(): void
    {
        $adopters = [
            ['name' => 'Sixto Servian', 'email' => 'sixto@gmail.com'],
            ['name' => 'Abraham Fernandez', 'email' => 'abraham@gmail.com'],
            ['name' => 'Fabian Blanco Wuest', 'email' => 'fabian@gmail.com'],
        ];

        foreach ($adopters as $adopter) {
            User::factory()->create([
                ...$adopter,
                'role' => 'adopter',
                'password' => Hash::make('password1234'),
            ]);
        }

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $refugios = [
            [
                'name' => 'Huellitas Formosa',
                'email' => 'huellitasfsa@gmail.com',
                'org' => 'Huellitas Formosa',
                'slug' => 'huellitas-formosa',
                'description' => 'En Huellitas Formosa trabajamos día a día para rescatar, rehabilitar y encontrar hogares responsables para perros y gatos en situación de calle. Contamos con un equipo de voluntarios comprometidos y un espacio de tránsito donde brindamos amor y cuidados veterinarios a cada animalito que llega a nosotros.',
                'phone' => '+54370 4' . fake()->randomNumber(6, true),
                'address' => 'Córdoba 2030',
            ],
            [
                'name' => 'Patitas Formosa',
                'email' => 'patitasfsa@gmail.com',
                'org' => 'Patitas Formosa',
                'slug' => 'patitas-formosa',
                'description' => 'Patitas Formosa es una organización sin fines de lucro dedicada a la protección animal. Realizamos jornadas de castración, vacunación y concientización sobre la tenencia responsable. Nuestro refugio temporal alberga mascotas rescatadas hasta que encuentran una familia que les brinde el amor que merecen.',
                'phone' => '+54370 4' . fake()->randomNumber(6, true),
                'address' => 'Salta 316',
            ],
            [
                'name' => 'Narices Frías',
                'email' => 'naricesfrias@gmail.com',
                'org' => 'Narices Frías',
                'slug' => 'narices-frias',
                'description' => 'Narices Frías nació del amor por los animales y las ganas de cambiar realidades. Rescatamos animales abandonados, maltratados o en riesgo, los rehabilitamos física y emocionalmente, y promovemos su adopción responsable. Creemos en un mundo donde cada mascota tenga un hogar lleno de cariño.',
                'phone' => '+54370 4' . fake()->randomNumber(6, true),
                'address' => 'Moreno 215',
            ],
        ];

        $petIndex = 0;
        $firstOrg = null;

        foreach ($refugios as $data) {
            $rescuer = User::factory()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => 'rescuer',
                'password' => Hash::make('refugio1234'),
            ]);

            $org = Organization::factory()->create([
                'user_id' => $rescuer->id,
                'name' => $data['org'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => 'Formosa',
                'province' => 'Formosa',
            ]);

            if ($firstOrg === null) {
                $firstOrg = $org;
            }

            $dogSmall = Pet::factory()->dog()->small()->withImages(3)->count(2)->create([
                'organization_id' => $org->id,
                'user_id' => $rescuer->id,
            ]);

            $dogMedium = Pet::factory()->dog()->medium()->withImages(3)->count(2)->create([
                'organization_id' => $org->id,
                'user_id' => $rescuer->id,
            ]);

            $dogLarge = Pet::factory()->dog()->large()->withImages(3)->count(1)->create([
                'organization_id' => $org->id,
                'user_id' => $rescuer->id,
            ]);

            $catSmall = Pet::factory()->cat()->small()->withImages(3)->count(1)->create([
                'organization_id' => $org->id,
                'user_id' => $rescuer->id,
            ]);

            $pets = $dogSmall->merge($dogMedium)->merge($dogLarge)->merge($catSmall);

            foreach ($pets as $pet) {
                $pet->update(['description' => $this->descriptions[$petIndex % count($this->descriptions)]]);
                $petIndex++;
            }
        }

        Developer::create([
            'name' => 'Fabián Blanco Wuest',
            'role' => 'Backend Developer',
            'description' => 'Responsable del desarrollo del backend, la lógica de negocio, la base de datos y la integración de los servicios de la plataforma.',
            'sort_order' => 1,
        ]);

        Developer::create([
            'name' => 'Sixto Servián',
            'role' => 'DBA',
            'description' => 'Encargado del diseño, la administración y la optimización de la base de datos, garantizando la integridad y el rendimiento de los datos.',
            'sort_order' => 2,
        ]);

        Developer::create([
            'name' => 'Abraham Fernandez',
            'role' => 'Frontend Developer',
            'description' => 'Responsable de la interfaz de usuario, la experiencia de navegación y el diseño visual de la plataforma.',
            'sort_order' => 3,
        ]);

        $adopter = User::where('email', 'sixto@gmail.com')->first();
        $availablePets = Pet::where('organization_id', $firstOrg->id)->where('status', 'available')->take(2)->get();

        foreach ($availablePets as $pet) {
            AdoptionRequest::create([
                'pet_id' => $pet->id,
                'user_id' => $adopter->id,
                'organization_id' => $firstOrg->id,
                'status' => AdoptionRequest::STATUS_PENDING,
                'phone' => '+54 370 123-4567',
                'birth_date' => '1995-03-15',
                'address' => 'Av. Principal 123, Formosa',
                'housing_type' => 'house',
            ]);
        }
    }
}