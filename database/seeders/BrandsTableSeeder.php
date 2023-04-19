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
            ['16','MERCEDES BENZ'],
            ['17','MITSUBISHI'],
            ['18','NISSAN'],
            ['19','PEUGEOT'],
            ['20','PORSCHE'],
            ['21','ROLLS ROYCE'],
            ['22','SUZUKI'],
            ['23','TATA'],
            ['24','TESLA'],
            ['25','TOYOTA'],
            ['26','VOLKSWAGEN'],
            ['27','GREATWALL']
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
            ['1','Flying Spur'],
            // BMW
            ['2','760i'],
            ['2','760i xDrive'],
            ['2','X7'],
            ['2','X6'],
            ['2','X5'],
            // CADILLAC
            ['3','Escalade'],
            // CHEVROLET
            ['4','Captiva'],
            ['4','Menlo'],
            ['4','Tahoe'],
            // FERRARI
            ['5','296 GTB'],
            ['5','SF90 SPIDER'],
            // FORD
            ['6','FORD RANGE RAPTOR'],
            ['6','Ranger'],
            ['6','RANGER RAPTOR'],
            // GMC
            ['7','Yukon'],
            // HINO
            ['8','300'],
            // HYUNDAI
            ['9','Accent'],
            ['9','Creta'],
            ['9','Elantra'],
            ['9','GRAND i10 HB'],
            ['9','GRAND i10 SEDAN'],
            ['9','H1'],
            ['9','H100'],
            ['9','Palisade'],
            ['9','Santa Fe'],
            ['9','Staria'],
            ['9','Tucson'],
            ['9','Venue'],
            // ISUZU
            // ['10','D-Max'],
            ['10','DMAX DC'],
            ['10','DMAX SC'],
            // ['10','NPR Trucks'],
            ['10','NPR'],
            ['10','NPR71'],
            ['10','NPR85'],
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
            ['14','DEFENDER 110'],
            ['14','Discovery Sport'],
            ['14','Range Rover'],
            ['14','Range Rover Sport'],
            ['14','Vogue Autobiography'],
            // LEXUS
            ['15','ES'],
            ['15','GX'],
            ['15','IS'],
            ['15','IS250'],
            ['15','Lexus GX640 4.6P'],
            ['15','LX450D'],
            ['15','LX570'],
            ['15','LX570S'],
            ['15','LX600'],
            ['15','NX'],
            ['15','RX'],
            ['15','UX'],
            // MERCEDES
            ['16','AMG GT'],
            ['16','C200'],
            ['16','E Class'],
            ['16','E350'],
            ['16','EQS SUV'],
            ['16','G Class'],
            ['16','G63'],
            ['16','GLC'],
            ['16','GLC300'],
            ['16','GLE Coupe'],
            ['16','GLE SUV'],
            ['16','GLE53'],
            ['16','GLE63'],
            ['16','GLS'],
            ['16','GLS Maybach'],
            ['16','GLS580'],
            ['16','GLS600'],
            ['16','LHD G 63 4x4 4.0P AT'],
            ['16','S Class'],
            ['16','S Coupe'],
            ['16','S550'],
            ['16','V CLASS'],
            ['16','V250'],
            // MITSUBISHI
            ['17','L200 DC'],
            ['17','L200 Sportero DC'],
            ['17','Montero'],
            ['17','PAJERO'],
            // NISSAN
            ['18','Kicks'],
            ['18','Murano'],
            ['18','Patrol Y62'],
            ['18','Sunny'],
            // PEUGEOT
            ['19','2008'],
            ['19','LandTrek'],
            ['19','Landtrek DC'],   
            // PORSCHE
            ['20','911 GT3'],
            ['20','Macan'],
            // ROLLS ROYCE
            ['21','Cullinan'],
            ['21','Ghost'],
            // SUZUKI
            ['22','ALTO'],
            ['22','BALENO'],
            ['22','CELERIO'],
            ['22','CIAZ'],
            ['22','Dzire'],
            ['22','Dzire GLX AT'],
            ['22','Ertiga'],
            ['22','Jimny'],
            ['22','S-Presso'],
            ['22','Super Carry'],
            ['22','SWIFT'],
            // TATA
            ['23','Ultra 814'],
            // TESLA
            ['24','Model Y'],
            // TOYOTA
             ['25','Alphard'],
             ['25','AVALON'],
             ['25','Belta'],
             ['25','BZ4X'],
             ['25','Camry'],
             ['25','Coaster'],
             ['25','Corolla'],
             ['25','Corolla Cross'],
             ['25','Fortuner'],
             ['25','Granvia'],
             ['25','Hardtop'],
             ['25','Hiace HR'],
             ['25','HIACE LR'],
             ['25','Hiace STD'],
             ['25','Highlander'],
             ['25','Hilux DC'],
             ['25','Hilux EC'],
             ['25','Hilux SC'],
             ['25','Land Cruiser'],
             ['25','LAND CRUISER PRADO'],
             ['25','LC 300 GXR 3.5'],
             ['25','LC200'],
             ['25','LC300'],
             ['25','LC300 VX 3.5'],
             ['25','LC71'],
             ['25','LC76'],
             ['25','LC78'],
             ['25','LC79DC'],
             ['25','LC79SC'],
             ['25','LHD RAIZE LIMITED 1.0P AT'],
             ['25','Model Line Feroz'],
             ['25','Prado'],
             ['25','PRADO 2.7L GCC '],
             ['25','PRADO 4.0 VX - 360 CAM'],
             ['25','RAIZE'],
             ['25','RAV4'],
             ['25','Rumion'],
             ['25','Rush'],
             ['25','Starlet'],
             ['25','TOYOTA HIGHLANDER'],
             ['25','TUNDRA'],
             ['25','Yaris'],
            // VOLKSWAGEN
            ['26','Touareg'],
            ['26','ID.4'],
            ['26','ID.6'],
            // GREATWALL
            ['27','INDIVIDUAL POER'],
            ['27','WINGLE 5'], 
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
