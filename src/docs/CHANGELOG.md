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

## [1.4.0] - 2026-01-09
TAGELÉS REWORK

### Added
#### •findtag.php, kereso_tag.php
-adatbázisban lévő tagek megmutatására szolgál mindkettő php fájl

### Changed
#### •upload.php
-beraktam egy textarea-t amibe az applikált tagek kerülnek be. ennek a tartalma töltödik fel a db-be


Changelog by: Norbi

---


## [1.3.6] - 2025-12-16

### Added
#### • Jelentés / Report rendszer
- Új “Report” gomb a felhasználók és jegyzetek jelentéséhez
- Jelentések továbbítása az admin felé (admin tud intézkedni)

### Changed
#### • UI / Design finomhangolás
- Kevesebb gradient: letisztultabb, kevésbé “túltolt” megjelenés
- A felületek (kártyák, navbar, gombok) inkább “soft”/egyszínű alapot kaptak, a gradient inkább csak accent maradt

### Fixed
#### • Főoldali értékelés
- Javítva: a főoldalon az értékelés (csillagok / beküldés) most már működik

### Security
#### • Report funkció
- Csak bejelentkezett felhasználó tud jelentést küldeni (vendég nem)

Changelog by: Csontos Kincső Anasztázia

---

## [1.3.5] - 2025-12-14

### Added
#### •mail-regver.php
-Regisztráció után küld egy linket e-mailbe amivel belehet aktiválni a fiókot

#### •reg-ver.php
-Ide visz az e-mail-ben lévő link

### Changed
#### •jegyzetar.sql
-új tábla: tokens (id, user_id, token), itt találhatóak a generált tokenek az aktivációhoz

#### •reglog.php
-nem lehet mostmár belépni aktiválatlan fiókkal (email_verified == 0)
-email küldés sikeres regisztráció után

Changelog by: Norbi


## [1.3.4] - 2025-12-06

### Added
#### • Database helper usage
- Egységesen bevezetésre került a `db_stmt()` és `db_query()` használata az auth, profil és keresés funkciókban, hogy mindenhol ugyanazt a prepared statement réteget használja az alkalmazás.
- Integrálva lett a `db_log_error()` logolás a DB-hívások mögötti helper funkciókba (közvetve ezekre a nézetekre is kiterjesztve a részletes hibanaplózást).

### Changed
#### • Profile handling
- A `profile.php` minden korábbi `db_prepared` és nyers `$conn->query()` hívása lecserélésre került `db_query()` / `db_stmt()`-re.
- A profil frissítési, badge-lekérdezési, CSS-request és értesítés-lekérdezési logika most már egységesen prepared statementeken fut.

#### • Registration & login
- A `reglog.php` fájlban az összes SQL-lekérdezés (felhasználónév/email ellenőrzés, regisztráció, bejelentkezés) átállt a `db_query()` / `db_stmt()` használatára.
- A regisztrációs INSERT most már paraméterezett lekérdezéssel írja az új felhasználókat az `users` táblába, a jelszó továbbra is `password_hash()`-szal kerül mentésre.

#### • Search & group invites
- A keresőoldal (search) korábbi, escape-elt string-összefűzős SQL-jei teljesen átírva prepared statement alapúra (fájl- és user-keresés, sorrendezés, rating szerinti listázás).
- A csoportmeghívás (group invite) logikája most már a `notifys` és `group_members` táblák felé is prepared statementeket használ.

### Fixed
#### • Input handling & robustness
- Javítva lett több helyen az ID-k (user, group) kezelése: minden numeric inputot típusosítva/intre castolva kap meg az SQL-réteg.
- Keresés közben speciális karaktereket tartalmazó kulcsszavak már nem tudják “szétütni” a lekérdezéseket, mert nem string-összefűzéssel, hanem bindolt paraméterekkel mennek.

### Removed
#### • Direct SQL usage
- Eltávolításra kerültek a közvetlen `$conn->query()` hívások a profil-, auth- és keresés logikából ott, ahol már `db_stmt()` / `db_query()` áll rendelkezésre.
- Eltávolításra került a `real_escape_string()`-re épülő ad-hoc escaping ezekben a fájlokban.

### Security
#### • SQL injection hardening
- A regisztrációs és bejelentkezési folyamat (felhasználónév/email ellenőrzés, login) most már teljesen prepared statement alapú, csökkentve az SQL injection kockázatát.
- A keresőoldal minden dinamikus WHERE és ORDER BY feltétele paraméterezve kerül az adatbázisba, így a keresési inputok nem tudnak direktben SQL-t “befecskendezni”.
- A csoportmeghívásoknál (`group_members`, `notifys`) megszűnt a nyers ID-beillesztés, minden user- és group-azonosító bindolt paraméterként megy tovább.

Changelog by: Csontos Kincső Anasztázia


---

## [1.3.3] – 2025-12-02

### Added

#### • group.php

- Teljes csoportnézet implementálva: tagsági állapotok (owner / accepted / pending) kezelése.
- Csoporton belüli jegyzetlista (elfogadott + függőben lévő) megjelenítése.
- Tulajdonosi moderációs műveletek: csatlakozási kérelmek elfogadása / elutasítása, tagkezelés.
- Csoportleírás, privát állapot és taglista megjelenítése.
- Upload funkció csoportjegyzetekhez, jogosultságkezeléssel.

#### • groups.php

- Összes csoport listázása grid nézetben.
- Privát / nyilvános csoport státusz badge-ek.
- Navigáció az egyes csoportok részleteihez.
- "Új csoport létrehozása" CTA beépítése.

#### • create_group.php

- Új tanulócsoport létrehozása (név + leírás + privát állapot).
- Automatikus tulajdonosi jogosultság beállítása a létrehozó usernek.
- Backend validáció + adatbázisba írás.

#### • group_init.php

- Csoportbetöltés központi inicializációja.
- Felhasználó tagsági státuszának (pending / accepted / owner) felismerése.
- Jogosultsági flag-ek: `$aktualis_felhasznalo_tag`, `$aktualis_felhasznalo_pending`, `$aktualis_felhasznalo_tulaj`.

### Changed

#### • search.php

- Csoportos jegyzetek integrálása a keresési eredményekbe.
- Privát csoport tartalmainak elrejtése nem tagok elől.

#### • notify.php

- Értesítési rendszer bővítése csoportos eseményekkel (csatlakozási kérelem, elfogadás).
- Olvasatlan értesítések számának pontosabb lekérése.

#### • navbar.php

- Csoport funkció integrálása a navigációba.
- Értesítési ikon frissítése a csoportműveletekhez tartozó értesítések miatt.
- Reszponzív viselkedés javítása mobil nézetben.

Changelog by: Szekeres Levente

---


## [1.3.2] - 2025-12-01

### Added
#### • Új SQL táblák
- `badges` tábla
  - A kítűzőknek külön tábla
- `user_badges` tábla
  - Ha a felhasználó kap egy kítűzőt ide fog elmentődni
- `user_custom_css_archive` tábla
  - archivált css profil kinézetek jönnek ide
- `user_custom_css_requests` tábla
  - az egyedi profil css-k ide érkeznek, ez a tábla az admin panelen fog látsznai és ott is lehet kezelni

### Changed
#### • Az összes oldal
- a 3 behívott fájl sorrendisége változott a megfelelő működés érdekében
  -> (functions.php, db.php, lang.php)
#### • Profil.php
- Új funkciók:
  - előre beállított témák közül lehet válogatni
  - az adatok kezdetleges változtatására való lehetőség
  - Bemutatkozás írása, mely meg is jelenik
  - Egyedi CSS írás a profil oldalhoz, melyet előszőr egy admin fog elfogadni
  - Segítség a css-hez
  - Egyedi css visszavonása
  - A kítűző megjelenik a profilon

### Fixed
#### • CHANGELOG.md
- `[1.3.1] - 2025-01-12` helyett `[1.3.1] - 2025-12-1`

### Removed
#### • User mappák
- felesleges user mappák törlésre kerültek
#### • Adatbázis
- A szükséges INSERT-ken kívűl nincs benne semmi

### Security
#### `db_prepared()` helper függvény

- Bevezetésre került egy segédfüggvényt, ami **biztonságosan futtat előkészített SQL lekérdezéseket** (prepared statement).
- A függvény:
  * előkészíti a lekérdezést (`prepare`)
  * hozzáköti a paramétereket (`bind_param`)
  * lefuttatja a lekérdezést (`execute`)
  * hibánál kivételt dob, hogy ne legyen csendes fail

**Mi az a prepared statement?**
Olyan SQL-lekérdezés, ahol a változókat *nem* simán belefűzzük a stringbe, hanem külön adjuk át. Ez gyorsabb és extra biztonságot ad SQL injection ellen.
Magyarul: *"nem hagyjuk, hogy a user bemászza a query-be"*.

Changelog by: Csontos Kincső Anasztázia

## [1.3.1] - 2025-12-1

### Added
#### • mail-2fa.php
- Sikeres bejelentkezés után ide vezet át,
- Küld egy emailt a felhasználónak egy kóddal.
- Ha ez sikeresen megtörtént akkor a 2fa.php-ra vezet át
#### • 2fa.php
- Input field ahova kódot kell irni
- Sikeres kétlépcsős azonosito kód beirására után index.php-ra vezet át
#### • jegyzetar.sql
- uj tabla: 2fa_codes (id, userid, code)
- itt tarolja a mail-2fa-ba generalt kodokat, userid-val egyutt

### Changed
#### • reglog.php
- Sikeres bejelentkezes után a mail-2fa.php-ra vezet át az index.php helyett

Changelog by: Norbi

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