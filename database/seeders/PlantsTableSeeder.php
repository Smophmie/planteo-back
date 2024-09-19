<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plants')->insert([
            [
                'name' => 'Haricots verts',
                'type' => 'Légume vert',
                'sowing_period' => '04, 05, 06',
                'planting_period' => '05, 06, 07',
                'harvest_period' => '07, 08, 09',
                'soil' => 'Sol léger, bien drainé, riche en matière organique',
                'watering' => 'Arrosage régulier, sans excès, surtout pendant la floraison',
                'exposure' => 'Ensoleillée',
                'maintenance' => 'Biner régulièrement, pailler pour maintenir l\'humidité, et butter les plants lorsqu\'ils atteignent 10-15 cm',
                'description' => 'Les haricots verts sont des légumes savoureux et croquants, parfaits pour des salades ou des plats cuisinés.',
                'image' => 'images/haricots_verts.jpg'
            ],
            [
                'name' => 'Courgettes',
                'type' => 'Courge',
                'sowing_period' => '04, 05',
                'planting_period' => '05, 06',
                'harvest_period' => '07, 08, 09, 10',
                'soil' => 'Sol riche en humus, profond et bien drainé',
                'watering' => 'Arrosage régulier, éviter de mouiller les feuilles',
                'exposure' => 'Ensoleillée',
                'maintenance' => 'Pailler pour maintenir l\'humidité et biner régulièrement',
                'description' => 'Les courgettes sont des légumes polyvalents qui peuvent être utilisés dans une variété de plats, des soupes aux grillades.',
                'image' => 'images/courgettes.webp'
            ],
            [
                'name' => 'Fraisier',
                'type' => 'Fruit rouge',
                'sowing_period' => '02, 03',
                'planting_period' => '03, 04',
                'harvest_period' => '06, 07',
                'soil' => 'Sol riche, frais, bien drainé',
                'watering' => 'Arrosage régulier, maintenir le sol humide mais pas détrempé',
                'exposure' => 'Ensoleillée à mi-ombre',
                'maintenance' => 'Pailler, désherber régulièrement et retirer les stolons pour favoriser la production',
                'description' => 'Les fraisiers produisent des fraises juteuses et sucrées, idéales pour les desserts et les confitures.',
                'image' => 'images/fraisiers.jpg'
            ],
            [
                'name' => 'Framboisier',
                'type' => 'Fruit rouge',
                'sowing_period' => '03, 04',
                'planting_period' => '04, 05',
                'harvest_period' => '06, 07, 08, 09',
                'soil' => 'Sol riche en humus, bien drainé',
                'watering' => 'Arrosage régulier, maintenir le sol humide',
                'exposure' => 'Ensoleillée à mi-ombre',
                'maintenance' => 'Tailler après la récolte et attacher les nouvelles pousses',
                'description' => 'Les framboisiers offrent des framboises délicieuses et nutritives, parfaites pour les collations et les desserts.',
                'image' => 'images/framboisiers.webp'
            ],
            [
                'name' => 'Aubergines',
                'type' => 'Légume',
                'sowing_period' => '02, 03',
                'planting_period' => '05, 06',
                'harvest_period' => '07, 08, 09',
                'soil' => 'Sol riche, profond, bien drainé',
                'watering' => 'Arrosage régulier, éviter de mouiller les feuilles',
                'exposure' => 'Ensoleillée',
                'maintenance' => 'Pailler pour maintenir l\'humidité et biner régulièrement',
                'description' => 'Les aubergines sont des légumes polyvalents, excellents pour les plats rôtis, grillés ou mijotés.',
                'image' => 'images/aubergines.jpg'
            ],
            [
                'name' => 'Butternut',
                'type' => "Courge",
                'sowing_period' => '04, 05',
                'planting_period' => '05, 06',
                'harvest_period' => '09, 10, 11',
                'soil' => 'Sol riche, profond, bien drainé',
                'watering' => 'Arrosage régulier, éviter de mouiller les feuilles',
                'exposure' => 'Ensoleillée',
                'maintenance' => 'Pailler pour maintenir l\'humidité et biner régulièrement',
                'description' => 'Les courges butternut sont sucrées et crémeuses, idéales pour les soupes et les purées.',
                'image' => 'images/butternut.jpg'
            ],
            [
                'name' => 'Carottes',
                'type' => 'Légume',
                'sowing_period' => '03, 04, 05',
                'planting_period' => '04, 05, 06',
                'harvest_period' => '07, 08, 09, 10',
                'soil' => 'Sol léger, profond, sableux, bien drainé',
                'watering' => 'Arrosage régulier, maintenir le sol humide',
                'exposure' => 'Ensoleillée',
                'maintenance' => 'Biner régulièrement, éclaircir les plants si nécessaire',
                'description' => 'Les carottes sont des légumes racines croquants, excellents crus ou cuits.',
                'image' => 'images/carottes.webp'
            ],
            [
                'name' => 'Endives',
                'type' => "Légume d'hiver",
                'sowing_period' => '05, 06',
                'planting_period' => '06, 07',
                'harvest_period' => '10, 11, 12',
                'soil' => 'Sol léger, riche en humus, bien drainé',
                'watering' => 'Arrosage régulier, maintenir le sol humide',
                'exposure' => 'Ensoleillée',
                'maintenance' => 'Pailler pour maintenir l\'humidité et biner régulièrement',
                'description' => 'Les endives, ou chicons, sont parfaites pour des salades fraîches ou cuites.',
                'image' => 'images/endives.jpg'
            ],
            [
                'name' => 'Laitues',
                'type' => 'Salade',
                'sowing_period' => '02, 03, 04',
                'planting_period' => '03, 04, 05',
                'harvest_period' => '05, 06, 07, 08',
                'soil' => 'Sol léger, riche en humus, bien drainé',
                'watering' => 'Arrosage régulier, maintenir le sol humide',
                'exposure' => 'Ensoleillée à mi-ombre',
                'maintenance' => 'Pailler pour maintenir l\'humidité et biner régulièrement',
                'description' => 'Les laitues sont des légumes-feuilles croquants, idéals pour les salades.',
                'image' => 'images/laitues.jpg'
            ],
            [
                'name' => 'Mâche',
                'type' => 'Salade',
                'sowing_period' => '08, 09',
                'planting_period' => '09, 10',
                'harvest_period' => '11, 12, 01',
                'soil' => 'Sol léger, riche en humus, bien drainé',
                'watering' => 'Arrosage régulier, maintenir le sol humide',
                'exposure' => 'Mi-ombre',
                'maintenance' => 'Pailler pour maintenir l\'humidité et biner régulièrement',
                'description' => 'La mâche est une salade d\'hiver douce et savoureuse.',
                'image' => 'images/mache.webp'
            ],
            [
                'name' => 'Poivrons',
                'type' => 'Légume',
                'sowing_period' => '02, 03',
                'planting_period' => '05, 06',
                'harvest_period' => '07, 08, 09',
                'soil' => 'Sol riche, profond, bien drainé',
                'watering' => 'Arrosage régulier, éviter de mouiller les feuilles',
                'exposure' => 'Ensoleillée',
                'maintenance' => 'Pailler pour maintenir l\'humidité et biner régulièrement',
                'description' => 'Les poivrons sont des légumes colorés et savoureux, parfaits pour des salades, des grillades ou des plats cuisinés.',
                'image' => 'images/poivrons.jpg'
            ],
            [
                'name' => 'Tomates',
                'type' => 'Légume',
                'sowing_period' => '02, 03, 04',
                'planting_period' => '05, 06',
                'harvest_period' => '07, 08, 09, 10',
                'soil' => 'Sol riche, profond, bien drainé',
                'watering' => 'Arrosage régulier, éviter de mouiller les feuilles',
                'exposure' => 'Ensoleillée',
                'maintenance' => 'Pailler pour maintenir l\'humidité, tuteurer les plants et enlever les gourmands',
                'description' => 'Les tomates sont des légumes incontournables, idéals pour les salades, les sauces et les plats cuisinés.',
                'image' => 'images/tomates.jpg'
            ],
            [
                'name' => 'Radis',
                'type' => 'Légume-racine',
                'sowing_period' => '03, 04, 05',
                'planting_period' => '04, 05, 06',
                'harvest_period' => '05, 06, 07, 08',
                'soil' => 'Sol léger, bien drainé',
                'watering' => 'Arrosage régulier, maintenir le sol humide',
                'exposure' => 'Ensoleillée',
                'maintenance' => 'Biner régulièrement, éclaircir les plants si nécessaire',
                'description' => 'Les radis sont des légumes-racines croquants et piquants, parfaits pour des salades ou en apéritif.',
                'image' => 'images/radis.jpg'
            ],
        ]);
    }
}
