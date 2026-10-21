# READALOT

> Tīmekļa bibliotēkas sistēma grāmatu pārlūkošanai, vērtēšanai, saglabāšanai un kolekciju veidošanai.

Mūsdienās arvien vairāk cilvēku izmanto digitālās platformas grāmatu meklēšanai, lasīšanai un novērtēšanai. Tomēr daudzas sistēmas nepiedāvā iespēju vienuviet pārvaldīt savu lasīšanas pieredzi, saglabāt interesējošās grāmatas, veidot tematiskas kolekcijas un dalīties viedokļos ar citiem lietotājiem.

READALOT ir tīmekļa sistēma, kas ļauj lietotājiem pārlūkot grāmatas, lasīt aprakstus, pievienot vērtējumus un atsauksmes, saglabāt grāmatas savā profilā, veidot kolekcijas un pārvaldīt savu lasīšanas pieredzi. Sistēmā ir pieejama arī administratora daļa grāmatu un lietotāju satura pārvaldībai.

---

## Sistēmas funkcionalitāte

### Lietotājs var:

* Reģistrēties un autorizēties sistēmā;
* Pārlūkot grāmatu katalogu;
* Meklēt grāmatas pēc nosaukuma/autora;
* Filtrēt grāmatas pēc žanra;
* Kārtot grāmatas pēc:

  * vērtējuma;
  * alfabētiskā secībā;
  * pievienošanas datuma;
* Apskatīt detalizētu informāciju par grāmatu;
* Pievienot vērtējumus un atsauksmes;
* Dzēst savas atsauksmes;
* Saglabāt grāmatas;
* Veidot tematiskas kolekcijas;
* Pievienot grāmatas kolekcijām;
* Apskatīt savas atsauksmes;
* Lasīt grāmatas tiešsaistē.

### Administrators var:

* Pievienot jaunas grāmatas;
* Rediģēt grāmatu informāciju;
* Dzēst grāmatas;
* Dzēst lietotāju atsauksmes;
* Apskatīt sistēmas statistiku;
* Analizēt grafikus par:

  * grāmatu vērtējumiem;
  * sistēmas aktivitāti.

## Tehnoloģijas

### Frontend

* Vue 3
* Vuetify 3
* CSS
* HTML
* Chart.js

### Backend

* PHP 8
* Laravel 11
* Laravel Sanctum
* MySQL

## Datu bāzes galvenās tabulas

* users
* books
* reviews
* boards
* board_books

## Aplikācijas startēšana

### Backend
```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### Frontend
```sh
npm install
npm run dev
```

## Testa piemēri

### Testa dati – Reģistrācija

| Testa ID | Ieejas dati         | Izejas dati                |
| -------- | ------------------- | -------------------------- |
| 1        | Tukši lauki         | Visi lauki ir obligāti     |
| 2        | Korekti dati        | Lietotājs tiek reģistrēts  |
| 3        | Eksistējošs e-pasts | E-pasts jau tiek izmantots |


### Testa dati – Atsauksmes pievienošana

| Testa ID | Ieejas dati            | Izejas dati           |
| -------- | ---------------------- | --------------------- |
| 1        | Tukšs komentārs        | Komentārs ir obligāts |
| 2        | Vērtējums 5, komentārs | Atsauksme pievienota  |
| 3        | Vērtējums 0            | Nepareizs vērtējums   |


### Testa dati – Grāmatas saglabāšana

| Testa ID | Ieejas dati            | Izejas dati       |
| -------- | ---------------------- | ----------------- |
| 1        | Autorizēts lietotājs   | Grāmata saglabāta |
| 2        | Neautorizēts lietotājs | Piekļuve liegta   |


### Testa dati – Kolekcijas izveide

| Testa ID | Ieejas dati     | Izejas dati           |
| -------- | --------------- | --------------------- |
| 1        | Tukšs nosaukums | Nosaukums ir obligāts |
| 2        | Korekti dati    | Kolekcija izveidota   |


### Testa dati – Administratora funkcijas

| Testa ID | Ieejas dati         | Izejas dati         |
| -------- | ------------------- | ------------------- |
| 1        | Jauna grāmata       | Grāmata pievienota  |
| 2        | Grāmatas rediģēšana | Izmaiņas saglabātas |
| 3        | Grāmatas dzēšana    | Grāmata dzēsta      |
| 4        | Atsauksmes dzēšana  | Atsauksme dzēsta    |


## Sistēmas statistika

Administratoram ir pieejama statistikas sadaļa, kur iespējams apskatīt:

* Kopējo grāmatu skaitu;
* Reģistrēto lietotāju skaitu;
* Atsauksmju skaitu;
* Vidējo vērtējumu sistēmā;
* Populārāko grāmatu;
* Vērtējumu sadalījuma grafikus.



