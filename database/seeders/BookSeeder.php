<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [

/* ================= FANTĀZIJAS ================= */

['title'=>'Hobits','author'=>'Dž. R. R. Tolkins','genre'=>'Fantāzijas','description'=>'Bilbo Baginss dodas piedzīvojumā kopā ar rūķiem.','year'=>1937,'cover'=>'/image/hobit.jpg'],
['title'=>'Gredzenu pavēlnieks','author'=>'Dž. R. R. Tolkins','genre'=>'Fantāzijas','description'=>'Episks stāsts par gredzena iznīcināšanu.','year'=>1954,'cover'=>'/image/Gredzenu-pavelnieks.jpg'],
['title'=>'Harijs Poters un Filozofu akmens','author'=>'Dž. K. Roulinga','genre'=>'Fantāzijas','description'=>'Zēns atklāj, ka ir burvis.','year'=>1997,'cover'=>'/image/harijspotersunfilozofuakmens.jpg.jpg'],
['title'=>'Harijs Poters un Noslēpumu kambaris','author'=>'Dž. K. Roulinga','genre'=>'Fantāzijas','description'=>'Harijs atgriežas Cūkkārpā otrajā gadā.','year'=>1998,'cover'=>'/image/harijs_poters_un_noslepumu_kamb.jpg'],
['title'=>'Vēja vārds','author'=>'Patriks Rotfuss','genre'=>'Fantāzijas','description'=>'Leģendārā burvja Kvota stāsts.','year'=>2007,'cover'=>'/image/veja-vards.jpeg'],

/* ================= KLASIKA ================= */

['title'=>'1984','author'=>'Džordžs Orvels','genre'=>'Klasika','description'=>'Stāsts par totalitāru sabiedrību.','year'=>1949,'cover'=>'/image/1984.jpg'],
['title'=>'Dzīvnieku ferma','author'=>'Džordžs Orvels','genre'=>'Klasika','description'=>'Dzīvnieki sacēlās pret cilvēkiem.','year'=>1945,'cover'=>'/image/dzivniekuferma.jpg'],
['title'=>'Lepnums un aizspriedumi','author'=>'Džeina Ostina','genre'=>'Klasika','description'=>'Romāns par mīlestību un sabiedrību.','year'=>1813,'cover'=>'/image/lepnums-un-aizspriedumi.jpg '],
['title'=>'Karš un miers','author'=>'Ļevs Tolstojs','genre'=>'Klasika','description'=>'Plašs romāns par Krievijas sabiedrību.','year'=>1869,'cover'=>'/image/karsunmiers.jpg'],
['title'=>'Anna Kareņina','author'=>'Ļevs Tolstojs','genre'=>'Klasika','description'=>'Traģisks stāsts par mīlestību.','year'=>1877,'cover'=>'/image/anna_karenina.jpg'],

/* ================= TRILLERI ================= */

['title'=>'Da Vinči kods','author'=>'Dens Brauns','genre'=>'Trilleri','description'=>'Simbolu un slepenu organizāciju noslēpumi.','year'=>2003,'cover'=>'/image/daVincikods.jpg'],
['title'=>'Meitene vilcienā','author'=>'Paula Hokinsa','genre'=>'Trilleri','description'=>'Sieviete kļūst apsēsta ar kādu pāri.','year'=>2015,'cover'=>'/image/meitene-vilciena.jpg'],
['title'=>'Neatrodamā','author'=>'Giliāna Flinna','genre'=>'Trilleri','description'=>'Noslēpumaina sievietes pazušana.','year'=>2012,'cover'=>'/image/flinna_neatrodama.jpg'],
['title'=>'Mājkalpotāja','author'=>'Makfadena Frīda','genre'=>'Trilleri','description'=>'Noslēpumaina sievietes pazušana.','year'=>2023,'cover'=>'/image/majkalpotaja.webp'],

/* ================= ŠAUSMAS ================= */

['title'=>'Drakula','author'=>'Bram Stoker','genre'=>'Šausmas','description'=>'Leģendārais stāsts par vampīru.','year'=>1897,'cover'=>'/image/drakula.webp'],
['title'=>'Frankenšteins','author'=>'Mērija Šellija','genre'=>'Šausmas','description'=>'Zinātnieks rada dzīvību.','year'=>1818,'cover'=>'/image/frankenstein.jpg'],
['title'=>'Tas','author'=>'Stīvens Kings','genre'=>'Šausmas','description'=>'Briesmonis terorizē mazpilsētu.','year'=>1986,'cover'=>'/image/tas.jpg'],
['title'=>'Spīdēšana','author'=>'Stīvens Kings','genre'=>'Šausmas','description'=>'Viesnīca ar tumšiem noslēpumiem.','year'=>1977,'cover'=>'/image/spidesana.jpg '],

/* ================= BIZNESS ================= */

['title'=>'Sāc ar kāpēc','author'=>'Saimons Sineks','genre'=>'Bizness','description'=>'Kāpēc veiksmīgi līderi sāk ar jautājumu kāpēc.','year'=>2009],
['title'=>'No laba uz izcilu','author'=>'Džims Kolinss','genre'=>'Bizness','description'=>'Kā uzņēmumi kļūst izcili.','year'=>2001],
['title'=>'Lean Startup','author'=>'Ēriks Rīss','genre'=>'Bizness','description'=>'Jauna pieeja uzņēmējdarbībai.','year'=>2011],
['title'=>'4 stundu darba nedēļa','author'=>'Tims Feriss','genre'=>'Bizness','description'=>'Kā strādāt gudrāk.','year'=>2007],
['title'=>'Zilā okeāna stratēģija','author'=>'V. Čans Kims','genre'=>'Bizness','description'=>'Jaunas tirgus telpas radīšana.','year'=>2005],

/* ================= FINANSES ================= */

['title'=>'Bagātais tētis, nabagais tētis','author'=>'Roberts Kijosaki','genre'=>'Finanses','description'=>'Domāšana par naudu.','year'=>1997],
['title'=>'Naudas psiholoģija','author'=>'Morgans Housels','genre'=>'Finanses','description'=>'Kā cilvēki domā par naudu.','year'=>2020],
['title'=>'The Intelligent Investor','author'=>'Bendžamins Grehems','genre'=>'Finanses','description'=>'Investēšanas klasika.','year'=>1949],
['title'=>'I Will Teach You to Be Rich','author'=>'Ramits Sethi','genre'=>'Finanses','description'=>'Personīgo finanšu pārvaldība.','year'=>2009],
['title'=>'Your Money or Your Life','author'=>'Viki Robina','genre'=>'Finanses','description'=>'Attiecības ar naudu.','year'=>1992]

        ];

        foreach ($books as $book) {

            Book::create([
                'title'=>$book['title'],
                'author'=>$book['author'],
                'genre'=>$book['genre'],
                'description'=>$book['description'].' '.fake()->paragraph(7),
                'publishing_year'=>$book['year'],
                'price'=>fake()->randomFloat(2,2,11),
                'cover' => $book['cover'] ?? '/image/book'.rand(1,30).'.jpg'
            ]);

        }

    }
}
