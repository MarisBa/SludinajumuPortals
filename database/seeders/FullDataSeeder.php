<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FullDataSeeder extends Seeder
{
    public function run(): void
    {

        $u1 = DB::table('users')->insertGetId(['name'=>'Janis Berzins','email'=>'janis@example.com','password'=>Hash::make('password'),'created_at'=>now(),'updated_at'=>now()]);
        $u2 = DB::table('users')->insertGetId(['name'=>'Anna Kalnina','email'=>'anna@example.com','password'=>Hash::make('password'),'created_at'=>now(),'updated_at'=>now()]);
        $u3 = DB::table('users')->insertGetId(['name'=>'Peteris Ozols','email'=>'peteris@example.com','password'=>Hash::make('password'),'created_at'=>now(),'updated_at'=>now()]);
        $users = [$u1,$u2,$u3];

        $cats = ['Transports','Nekustamais ipasums','Elektronikas','Majai un darzam','Apgerbs','Sports un hobiji','Dzivnieki','Darbs'];
        $catIds = [];
        foreach($cats as $c){
            $catIds[$c] = DB::table('categories')->insertGetId(['name'=>$c,'slug'=>Str::slug($c),'image'=>'default.jpg','created_at'=>now(),'updated_at'=>now()]);
        }

        $subs = [
            'Transports'=>['Automasinas','Motocikli','Velosipedi','Udenstransports','Rezerves dalas'],
            'Nekustamais ipasums'=>['Dzivokli','Majas','Zemes gabali','Komercplatibas','Garazas'],
            'Elektronikas'=>['Telefoni','Datori','Televizori','Foto un video','Audio tehnika','Spelu konsoles'],
            'Majai un darzam'=>['Mebeles','Sadzives tehnika','Majas apdare','Darza tehnika','Instrumenti'],
            'Apgerbs'=>['Sieviesu apgerbs','Viriesu apgerbs','Bernu apgerbs','Apavi','Somas'],
            'Sports un hobiji'=>['Sporta inventars','Medibas','Kolekcionesana','Muzika','Gramatas'],
            'Dzivnieki'=>['Suni','Kaki','Grauzeji','Putni','Zivtvertnes'],
            'Darbs'=>['IT','Celtnieciba','Transports','Skaistums','Apmaciba'],
        ];

        $subIds = [];
        foreach($subs as $cat=>$subList){
            $subIds[$cat]=[];
            foreach($subList as $s){
                $subIds[$cat][] = DB::table('subcategories')->insertGetId(['category_id'=>$catIds[$cat],'name'=>$s,'slug'=>Str::slug($s),'created_at'=>now(),'updated_at'=>now()]);
            }
        }

        $imgs = [
            'Transports'=>['https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800','https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800','https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=800'],
            'Nekustamais ipasums'=>['https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800','https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800','https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800'],
            'Elektronikas'=>['https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800','https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800','https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800'],
            'Majai un darzam'=>['https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800','https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800','https://images.unsplash.com/photo-1484101403633-562f891dc89a?w=800'],
            'Apgerbs'=>['https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=800','https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=800','https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800'],
            'Sports un hobiji'=>['https://images.unsplash.com/photo-1517649763962-0c623066013b?w=800','https://images.unsplash.com/photo-1530549387789-4c1017266635?w=800','https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800'],
            'Dzivnieki'=>['https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800','https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?w=800','https://images.unsplash.com/photo-1548681528-6a5c45b66b42?w=800'],
            'Darbs'=>['https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?w=800','https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800','https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800'],
        ];

        $tpls = [
            'Transports'=>[['VW Golf TDI',12500],['Toyota Yaris',9900],['BMW 320d',18500],['Audi A4',15000],['Mercedes C220',21000],['Opel Astra',7500],['Ford Focus',8200],['Skoda Octavia',11000],['Honda CBR600',5500],['Trek velosipeds',580]],
            'Nekustamais ipasums'=>[['2-istabu dzivoklis Riga',450],['3-istabu dzivoklis centra',135000],['Maja Jurmala',320000],['Vasarnica',45000],['1-istabu dzivoklis',35000],['Birojs Riga',900],['Zemes gabals',35000],['Garaza',8500],['Maja Pieriga',185000],['Istaba ire',180]],
            'Elektronikas'=>[['iPhone 14 Pro',750],['Samsung Galaxy S23',820],['MacBook Pro M3',1800],['Dell XPS 15',1200],['Samsung 65 QLED',650],['Sony A7III',1400],['iPad Pro',950],['PlayStation 5',480],['AirPods Pro 2',180],['Gaming PC',1650]],
            'Majai un darzam'=>[['Adas divans',320],['IKEA virtuve',450],['Bosch velas masina',280],['Ledusskapis',380],['Zales plaveji',220],['Galds un kresli',490],['Perforators',120],['Trauku mazgajama',350],['Gulta ar matraci',280],['Skapis',160]],
            'Apgerbs'=>[['Ziemas metelis',45],['Nike sporta jaka',35],['Adidas kedas',60],['Bernu kombinezons',25],['Rokasspoma ada',85],['Zara kleita',18],['Uzvalks',80],['Timberland zabaki',55],['Bernu apavi',20],['Mugursoma',65]],
            'Sports un hobiji'=>[['Slepsanas komplekts',180],['Makskeresanas komplekts',85],['Gitara Fender',450],['Tenisa rakete',120],['Skrejcelins',350],['Antiks pulkstenis',280],['Elektroniskas klavieres',380],['Bernu slepes',90],['Futbola bumba',35],['Velosipeda kivere',30]],
            'Dzivnieki'=>[['Labradoru kuceni',800],['Vacu aitu suns',600],['Kakis mekle majas',0],['Britu shortheira kakeni',450],['Trusu paris',80],['Papagailis',350],['Akvarijs 200L',180],['Kaku majina',40],['Sunu buda',65],['Jauktenu kuceni',50]],
            'Darbs'=>[['Web izstradatejs',50],['Elektrikis',25],['Santehmikis',30],['Kravas parvadjumi',1200],['Anglu valoda',15],['Flizetajs',20],['Friziere majas',20],['Matematikas skolotajs',12],['Dzivoklu uzkopsana',60],['Datoru remonts',20]],
        ];

        $locs=['Riga','Riga Centrs','Riga Purvciems','Jurmala','Jelgava','Ogre','Liepaja','Ventspils','Valmiera','Daugavpils','Cesis','Tukums','Sigulda'];
        $conds=['Jauns','Lietots','Labi saglabajies','Apmierinosss','Renovets'];
        $pstat=['Fikseta','Runajama','Piedavajiet'];

        $cId = DB::table('countries')->first()?->id ?? 1;
        $sId = DB::table('states')->first()?->id ?? 1;
        $ctId= DB::table('cities')->first()?->id ?? 1;

        $n=0; $target=500;
        while($n<$target){
            foreach($tpls as $cat=>$list){
                if($n>=$target) break;
                $catId=$catIds[$cat];
                $catSubs=$subIds[$cat];
                $imgPool=$imgs[$cat];
                foreach($list as $i=>$t){
                    if($n>=$target) break;
                    $img=$imgPool[$n%count($imgPool)];
                    DB::table('advertisements')->insert([
                        'user_id'=>$users[$n%3],
                        'name'=>$t[0].' '.($n+1),
                        'description'=>'Sludinajums par: '.$t[0].'. Labas kvalitates, laba stavokli.',
                        'price'=>round($t[1]*(rand(88,112)/100)),
                        'price_status'=>$pstat[$n%3],
                        'category_id'=>$catId,
                        'subcategory_id'=>$catSubs[$i%count($catSubs)],
                        'childcategory_id'=>null,
                        'product_condition'=>$conds[$n%5],
                        'listing_location'=>$locs[$n%count($locs)],
                        'country_id'=>$cId,
                        'state_id'=>$sId,
                        'city_id'=>$ctId,
                        'phone_number'=>'+371260'.str_pad($n,5,'0',STR_PAD_LEFT),
                        'feature_image'=>$img,
                        'first_image'=>$imgPool[($n+1)%count($imgPool)],
                        'second_image'=>$imgPool[($n+2)%count($imgPool)],
                        'published'=>1,
                        'link'=>Str::slug($t[0]).'-'.$n,
                        'slug'=>Str::slug($t[0]).'-'.$n,
                        'created_at'=>now()->subDays(rand(0,90)),
                        'updated_at'=>now()->subDays(rand(0,5)),
                    ]);
                    $n++;
                }
            }
        }
        $this->command->info('Kategorijas: '.DB::table('categories')->count());
        $this->command->info('Apakskategorijas: '.DB::table('subcategories')->count());
        $this->command->info('Sludinajumi: '.$n);
        $this->command->info('Login: janis@example.com / parole: password');
    }
}
