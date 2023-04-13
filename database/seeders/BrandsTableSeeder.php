<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['1','BENTLEY'],
            ['2','BMW'],
            ['3','CADILLAC'],
            ['4','CHEVROLET'],
            ['5','FERRARI'],
            ['6','FORD'],
            ['7','GMC'],
            ['8','HINO'],
            ['9','HYUNDAI'],
            ['10','ISUZU'],
            ['11','JEEP'],
            ['12','KIA'],
            ['13','LAMBORGHINI'],
            ['14','LAND ROVER'],
            ['15','LEXUS'],
            ['16','MERCEDES'],
            ['17','MITSUBISHI'],
            ['18','NISSAN'],
            ['19','PEUGEOT'],
            ['20','PORSCHE'],
            ['21','ROLLS ROYCE'],
            ['22','SUZUKI'],
            ['23','TATA'],
            ['24','TESLA'],
            ['25','TOYOTA'],
            ['26','VOLKSWAGEN']
            ];
        foreach ($brands as $key => $value):
        $brand[] = [
            'id'       => $value[0],
            'brand_name' => $value[1]
        ];
        endforeach ;
        DB::table('brands')->insert($brand);

        $modelLines = [
            // BENTLEY
            ['1','Bentayga'],
            ['1','Continental GT'],
            // BMW
            ['2','X7'],
            ['2','X6'],
            ['2','X5'],
            // CADILLAC
            ['3','Escalade'],
            // CHEVROLET
            ['4','Captive'],
            ['4','Tahoe'],
            // FERRARI
            ['5','SF90 SPIDER'],
            ['5','296 GTB'],
            ['5','296 GTB'],
            ['5','SF90 SPIDER'],
            // FORD
            ['6','Ranger'],
            ['6','Raptor'],
            // GMC
            ['7','Yukon'],
            // HINO
            ['8','300'],
            // HYUNDAI
            ['9','Accent'],
            ['9','Creta'],
            ['9','Elantra'],
            ['9','Grand i10'],
            ['9','H1'],
            ['9','Santa Fe'],
            ['9','Staria'],
            ['9','Tucson'],
            ['9','Venue'],
            // ISUZU
            ['10','D-Max'],
            ['10','NPR Trucks'],
            // JEEP
            ['11','Wrangler'],
            ['11','Grand Cherokee'], 
            // KIA
            ['12','K5'],
            ['12','Picanto'],
            ['12','Seltos'],
            ['12','Sorento'],
            ['12','Sportage'],
            // LAMBORGHINI
            ['13','Urus'],
            // LAND ROVER
            ['14','Range Rover'],
            ['14','Defender'],
            // LEXUS
            ['15','ES-Series'],
            ['15','GX-Series'],
            ['15','IS-Series'],
            ['15','LX-Series'],
            ['15','NX-Series'],
            ['15','RX-Series'],
            ['15','LEUX-SeriesXUS'],
            // MERCEDES
            ['16','AMG GT'],
            ['16','C-Class'],
            ['16','E-Class'],
            ['16','GLE-Class'],
            ['16','G-Class'],
            ['16','GLS-Maybach'],
            ['16','S-Class'],
            ['16','V-Class'],
            ['16','V-Class Maybach'],
            // MITSUBISHI
            ['17','L200'],
            // NISSAN
            ['18','Kicks'],
            ['18','Patrol'],
            // PEUGEOT
            ['19','2008'],
            // PORSCHE
            ['20','Macan'],
            // ROLLS ROYCE
            ['21','Ghost'],
            // SUZUKI
            ['22','Alto'],
            ['22','Baleno'],
            ['22','Celerio'],
            ['22','Ciaz'],
            ['22','DZIRE'],
            ['22','Ertiga'],
            ['22','Jimny'],
            ['22','Super Carry'],
            ['22','Swift'],
            ['22','S Presso'],
            // TATA
            ['23','Ultra 814'],
            // TESLA
            ['24','Model Y'],
            // TOYOTA
            ['25','Avalon'],
            ['25','BZ4X'],
            ['25','Belta'],
            ['25','Corolla Cross'],
            ['25','Camry'],
            ['25','Corolla'],
            ['25','Coaster'],
            ['25','Fortuner'],
            ['25','Granvia'],
            ['25','Hilux'],
            ['25','HiAce'],
            ['25','Highlander'],
            ['25','Land Cruiser'],
            ['25','Land Cruiser 70 Series'],
            ['25','Prado'],
            ['25','Rumion'],
            ['25','RAV 4'],
            ['25','RAIZE'],
            ['25','Rush'],
            ['25','Starlet'],
            ['25','Tundra'],
            ['25','Yaris'],
            // VOLKSWAGEN
            ['26','Touareg'],
            ['26','ID.4'],
            ['26','ID.6']
            ];
        foreach ($modelLines as $key => $value):
        $modelLine[] = [
            'brand_id'       => $value[0],
            'model_line' => $value[1]
        ];
        endforeach ;
        DB::table('master_model_lines')->insert($modelLine);
    }
}
