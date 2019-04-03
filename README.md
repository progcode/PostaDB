# PostaDB 2.0
Magyarországi irányítószámok és települések adatbázisa

Adatbázis .csv formátumban, auto-importerrel, a Magyar Posta hivatalos adatbázis alapján   
http://posta.hu/ugyfelszolgalat/iranyitoszam_kereso   

> **v.2.0.0:**
>
> - Publikus stabil verzió

> **Függőségek:**
> - Composer

Telepítés:
```
composer install
```

- Hozzuk létre a táblát a table.sql alapján
- Az env/.env.sample file-ban állítsuk be a konfigurációs értékeket, majd:

```
cd env
cp .env.sample .env
```

Használat:
```
cd /var/www/host/
php importer.php
```

Frissitve: 2019.04.03
