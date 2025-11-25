# Changelog

Ez a projekt hivatalos változásnaplója.

- Minden módosítást itt kell rögzíteni.
- A legújabb verzió mindig **felül** legyen.
- A módosításokat az alábbi kategóriák egyikébe sorold:
    - **Added** – új funkció
    - **Changed** – módosítás meglévő funkción
    - **Fixed** – hibajavítás
    - **Removed** – eltávolított funkció/elem
    - **Security** – biztonsági frissítés

Dátum formátum: `YYYY-MM-DD`

Verziózás: `major.minor.patch` (pl. 1.0.3)

Szerző megjelölése (kötelező), sor végén:

### Formátum útmutató 

## [1.X.X] - 2025-0X-0X

### Added
#### •
-

### Changed
#### •
-

### Fixed
#### •
-

### Removed
#### •
-

### Security
#### •
-

Changelog by: Neved

---


## [1.3.0]  - 2025-11-25

### Added
#### • vendor mappa
- A szükséges fájlokat tartalmazza a discord loginhoz
#### • .env
- A `.env` fájl nem került felöltésre révén, hogy érzékeny adatokat tartalmaz.

### Changed
#### • Discord login button
- A gomb újra elérhető

### Fixed
#### • reglog.php
- A discord fiókkal való bejelentkezés és regisztráció elérhető.

Changelog by: Csontos Kincső Anasztázia

---

## [1.2.2] - 2025-11-23

### Added
#### •favorites.php
-megjelennek a felhasználó kedvenc jegyzetei, részletek és letöltés gombbal

### Changed
#### footer.php
-elvolt irva a nevem (Baranyai -> Baranyi)

Changelog by: Norbi

---

## [1.2.1] - 2025-11-23

### Added
#### •favorites.php
-üres favorite.php oldal hozzáadva
#### profle.php
-kedvencek gomb ami átvezet a favorites.php-ra
#### •reglog.php
-navbar-t beraktam

### Changed
#### •reglog.php
-sikeres regisztáció után átvezet a reglog.php-ra

### Fixed
#### •jegyzetar.sql
-5. sor-ban varchar helyett archar volt irva...
-user táblában az id-nál nem volt engedélyezve az auto_increment (hogy akarunk igy egyedi userid-t???)
-files táblában az id-nál nem volt engedélyezve az auto_increment
-favorites táblában az id-nál nem volt engedélyezve az auto_increment
-favorites táblába kivettem a created_at mezőt.
#### upload.php
-visszaraktam a hiányzó feltöltési logikát de ezt még átkell nézni


Changelog by: Norbi

---

## [1.2.0] - 2025-11-21

### Added
#### • Multilanguage
- Mostantól az oldal 3 nyelven érhető el, az alapértelmezett nyelv a magyar.
  - Magyar
  - Angol
  - Német
  - Új fájl hozzáadva: `lang.php`, ez kezeli at oldal nyelvét

#### • Adatbázis
- Új adatbázisnév: `jegyzetar`
- `languages` tábla hozzáadva
- `translations` tábla hozzáadva

### Changed
#### • Több fájl is változott
- edit_email.php
- forgotpass.php
- index.php
- messages.php
- note.php
- notify.php
- profile.php
- reglog.php
- search.php
- upload.php
  - rendes szövegek helyett mostmár az adatbázisból kéri le az oldal a szövegeket, az adott nyelven

Changelog by: Csontos Kincső Anasztázia

---

## [1.1.1] - 2025-11-12

### Added
#### •Uj tábla: favorites
#### •Kedvencezés funkció KEZDETLEGES
-Adatbázisban tárolja hogy ki mit szivecskézett be
-Egyelőre kezdetleges, majd folyatatom (!!!)

### Changed
#### •Értékelés témájának egységesitése


### Removed
#### •Index.php-n nem müködö értékelés küldése kikommentelve
- nem merem eltávolitani de sztem fölös átlag értékelésnél

Changelog by: Norbi

---

## [1.1.0] - 2025-11-09

### Added
#### • Discord login button
- Discord bejelentkezés gomb
- Hozzájuk tartozó fájlok 

### Removed
#### • Google login button
- Az idő haladtával törlésre került, bonyolultabb mint a dc login

Changelog by: Csontos Kincső Anasztázia

---

## [1.0.3] - 2025-10-02

### Fixed
#### • Születésnapi profil keret
- A keret mostmár nem a profilkép alatt van, hanem rajta mint egy keret, külön animációval
- A hozzá tartozó születésnapi szöveg színe megváltozott, az olvashatóság érdekében

Changelog by: Csontos Kincső Anasztázia

---

## [1.0.2] - 2025-10-02


### Added
#### • "Részletek" gomb a fájlkártyákhoz
- Új "Részletek" CTA gomb került a jegyzetkártyákra
- A gomb a `note.php?id=` oldalra navigál, ahol teljes fájlinfó, kommentek és értékelések láthatók
- Illeszkedik az Aurora UI stílusához

### Changed
#### • Kritikus helyi módosítások biztonságba helyezése
- A korábban *lokálisan ragadt, majdnem elveszett* fontos fejlesztések végre fel lettek pusholva
- Verziókezelési workflow javítva, hogy ilyen ne forduljon elő még egyszer

### Fixed
#### • Jegyzet részletek elérhetősége
- Az egyedi jegyzetek (`note.php`) most már stabilan elérhetők ID alapján
- A részletek gomb hibátlanul átadja a fájl azonosítót

### Security
#### • Biztonságosabb munkafolyamat
- Külső adatvesztési rizikó minimalizálva
- Projekt snapshot mentések bevezetve

Changelog by: Csontos Kincső Anasztázia

---

## [1.0.1] - 2025-11-02

### Added
- note.php
- -->jegyzet neve
- -->jegyzet megtekintes/letoltes
- -->kommenteles
- -->ertekeles
- -->stb

- ugrás a jegyzetre gomb a keresési eredményekben (letöltés helyett)
- közvetlen navigáció jegyzet oldalakra (/Jegyzetar/note.php?id={file_id})

- css bővítés
- -->.search-card
- -->.note-link
- -->.comments-section
- -->.comment`
- -->.comment-form

### Changed
- Letöltés gomb helyett Ugrás a jegyzetre gomb lett

### Fixed
- index.php kidob a reglog.php-ba ha nincs bejelentkezve a felhasznalo

Changelog by: Norbi

---

## [1.0.1] - 2025-11-02

### Added
- note.php
- -->jegyzet neve
- -->jegyzet megtekintes/letoltes
- -->kommenteles
- -->ertekeles
- -->stb

- ugrás a jegyzetre gomb a keresési eredményekben (letöltés helyett)
- közvetlen navigáció jegyzet oldalakra (/Jegyzetar/note.php?id={file_id})

- css bővítés
- -->.search-card
- -->.note-link
- -->.comments-section
- -->.comment`
- -->.comment-form

### Changed
- Letöltés gomb helyett Ugrás a jegyzetre gomb lett

### Fixed
- index.php kidob a reglog.php-ba ha nincs bejelentkezve a felhasznalo

Changelog by: Norbi

---

## [1.0.0] - 2025-10-01

### Added

#### • Alap profilkép megjelenítése
- Ha egy felhasználó nem tölt fel saját képet, egy alapértelmezett profilkép jelenik meg.
- Megelőzi a törött képek és elcsúszott layoutek problémáját.

#### • Születésnapi élmény
- Szülinapos felhasználónál automatikusan:
    - animált körgyűrűs profilkép dísz
    - csillag animáció
    - személyes születésnapi üzenet, ha a saját profilját nézi
- Szülinap felismerése dátum alapján (`month-day` egyezés)

#### • Vendég mód: főoldal elérhető bejelentkezés nélkül
- A látogatók böngészhetik a főoldalt regisztráció nélkül
- Biztonságos hozzáférés-kezelés
- A profiloldalak továbbra is védettek

### Changed

#### • Teljes felület újradizájnolása
- Modern aurora-stílusú UI
- Reszponzív, mobilbarát megjelenés
- Átdolgozott layout: grid-alapú megoldások, egységes spacing és shadow-rendszer
- Letisztult ikonok, körkerekített formák, üveg-szerű elemek

#### • Kereső funkció bővítése
- Bővített keresési logika
- Több mezőn való keresés támogatása
- Pontosabb találatok, jobb adatkezelés


### Fixed

#### • Profil megjelenítés javítása
- A felhasználó nevére kattintva mostantól:
    - annak a profilja nyílik meg, akire kattintottak
    - nem a saját adatát tölti be véletlenül
- Teljesen különválasztva:
    - **$profile** = megtekintett felhasználó adatai
    - **$viewer** = belépett felhasználó

Ezzel megszűntek a "mindig saját profilt látom" hibák.

#### • Értékelés javítása
- Az értékelés feltöltödik az adatbázisba
- A felhasználó csak egyszer tud értékelni egy jegyzetet
- Értékelés tábla bővítve

Changelog by: Csontos Kincső Anasztázia