<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ModelDescriptionMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modelDescription = [
        ['19','LHD 3711 SC CHASIS 4.0D MT'], // 300
        ['93','LHD 2008 ALLURE 1.2P AT '], // 2008
        ['93','LHD 2008 GT 1.2P AT'],
        ['13','LHD 296 GTB 2.9P AT'], // 296 GTB
        ['4','LHD 760i 4.4P AT'], // 760i
        ['20','LHD ACCENT 1.4P AT'], // ACCENT
        ['20','LHD ACCENT 1.6P AT'],
        ['113','RHD ALPHARD 2.5P HEV AT'], // Alphard
        ['100','LHD ALTO GLX MT 0.8P MT'], // ALTO
        ['62','LHD AMG GT 53 3.0P AT'], // AMG GT
        ['114','LHD Avalon Limited 3.5P AT'], // AVALON
        ['101','LHD BALENO GLX 1.4P AT'], // BALENO
        ['115','LHD BELTA 1.5P AT'], // Belta
        ['1','LHD BENTAYGA 4.0P AT'], // BENTAYGA
        ['116','LHD BZ4X EV AT'], // BZ4X
        ['63','LHD C200 1.5P AT'], // C200
        ['117','RHD CAMRY 2.5P AT '], // Camry
        ['117','LHD CAMRY 2.5P AT '],
        ['117','LHD CAMRY 2.5P AT'],
        ['117','LHD CAMRY GLE 2.5P HEV AT'],
        ['117','LHD CAMRY GRANDE 2.5P HEV AT'],
        ['117','LHD CAMRY LUMIERE 2.5P HEV AT'],
        ['117','LHD CAMRY SE 3.5P AT'],
        ['117','LHD CAMRY GRANDE 3.5P AT'],
        ['117','LHD CAMRY LIMITED 3.5P AT'],
        ['117','LHD CAMRY 40TH ANNIVERSARY 3.5P AT'],
        ['117','LHD CAMRY SE 2.5P HEV AT'],
        ['117','LHD CAMRY XSE 2.5P HEV AT'],
        ['117','LHD CAMRY 2.5P HEV AT'],
        ['117','LHD CAMRY LE 2.5P AT'],
        ['10','LHD CAPTIVA PREMIER 1.5P AT '], // Captiva
        ['10','LHD CAPTIVA PREMIER 1.5P AT'],
        ['102','LHD CELERIO GL 1.0P AT '], // CELERIO
        ['103','LHD CIAZ GLX 1.5P AT'], // CIAZ
        ['118','RHD COASTER 4.2D MT 30 SEATER'], // Coaster
        ['118','LHD COASTER 4.2D MT 30 SEATER'],
        ['118','LHD COASTER 4.2D MT HR 23 SEATER'],
        ['2','LHD CONTINENTAL GT 6.0P AT'], // Continental GT
        ['119','LHD Corolla XLI 2.0P AT'], // Corolla
        ['119','LHD COROLLA 1.6P AT'],
        ['119','LHD COROLLA 1.2P AT'],
    ];
    foreach ($modelDescription as $key => $value):
    $modelDescriptionDate[] = [
        'model_line_id'       => $value[0],
        'model_description' => $value[1]
    ];
    endforeach ;
    DB::table('master_model_descriptions')->insert($modelDescriptionDate);
    }
}
