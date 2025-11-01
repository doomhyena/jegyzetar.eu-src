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

Ezzel megszűntek a „mindig saját profilt látom” hibák.

#### • Értékelés javítása
- Az értékelés feltöltödik az adatbázisba
- A felhasználó csak egyszer tud értékelni egy jegyzetet
- Értékelés tábla bővítve

Changelog by: Csontos Kincső Anasztázia
