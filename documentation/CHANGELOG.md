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

## [1.X.X-beta] - 2026-0X-0X

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

## [1.X.X-beta] - 2026-0X-0X

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

## [1.5.6-beta] - 2026-02-11

### Added

#### • Hitelesítési fejlesztések

* Jelszó megjelenítés lehetősége a bejelentkezési és regisztrációs űrlapon
* Valós idejű jelszóerősség-mutató regisztráció során
* Jelszóegyezés visszajelzés regisztráció közben
* Kliensoldali 13+ életkor-ellenőrzés (szerveroldali ellenőrzéssel kiegészítve)
* CSRF token védelem a bejelentkezési és regisztrációs űrlapokon
* Alap szintű próbálkozás-korlátozás (rate limiting) bejelentkezésnél és regisztrációnál (session + IP alapú)

### Changed

#### • Regisztrációs űrlap

* Kompaktabb, reszponzív grid alapú elrendezés (közepes képernyőtől 2 oszlopos nézet)
* Javított űrlapszerkezet és térközök a jobb felhasználói élmény érdekében
* Egységesített, biztonságos cookie beállítások (Secure, HttpOnly, SameSite=Lax)

#### • Validáció

* Kibővített szerveroldali validáció (felhasználónév formátum, email ellenőrzés, jelszóházirend)
* Egységesített hibaüzenetek bejelentkezéskor a felhasználónév-felderítés (user enumeration) csökkentése érdekében

### Fixed

#### • Hitelesítési logika

* Nem egységes cookie biztonsági beállítás regisztráció után
* Jövőbeli dátum elfogadása születési dátumnál
* Gyenge jelszavak elfogadása házirend nélküli ellenőrzés esetén

#### • Frontend működés

* Jelszó megjelenítés és jelszóerősség-mutató hibás inicializálása (DOMContentLoaded-ba helyezve a stabil működésért)
* Eseménykezelési problémák, amikor az elemek még nem voltak a DOM-ban

### Removed

#### • Elavult / duplikált megoldások

* Duplikált jelszó-megjelenítési logika
* Kevésbé biztonságos cookie-beállítási megoldás

### Security

#### • Biztonsági erősítések

* Alap Content Security Policy hozzáadása
* CSRF védelem bevezetése
* Bejelentkezési és regisztrációs próbálkozások korlátozása
* Szigorúbb jelszókövetelmények
* Információszivárgás csökkentése bejelentkezéskor

Changelog by: Csontos Kincső Anasztázia

---

## [1.5.5-beta] - 2026-02-10

### Added
#### • Csoportok moderálása / admin jóváhagyás
- Új csoportok létrehozásakor a csoport státusza alapból `pending`, és csak admin jóváhagyás után jelenik meg teljes értékűen.
- Admin panelen bekerült a „Csoportok jóváhagyása” rész (jóváhagyás / elutasítás + reviewer adatok).

#### • Birthday validation
- Regisztrációnál születési dátum validáció: hibás dátum kezelése + 13 év alatti regisztráció tiltása.

#### • Privát jegyzet (prémium tagoknak)
- Prémium felhasználók számára elérhető privát jegyzet funkció.

#### • Prémium badge
- Prémium előfizetés aktiválásakor automatikusan hozzárendelődik a „premium” badge (ha még nincs meg).

### Changed
#### • Csoportok megjelenítése
- Nem jóváhagyott (`pending`) csoportok esetén a megnyitás korlátozva van (admin / tulaj kivételével).

Changelog by: Szekeres Levente

---

## [1.5.4-beta] - 2026-02-09

### Added
#### • Contact Form (contact.php)
- Teljes kontakt forma implementálása
- Email küldés az adminoknak (admin@jegyzetar.eu)
- Automatikus megerősítő email a feladónak
- Contact messages naplózása adatbázisban (contact_messages tábla)
- Bejelentkezett felhasználók pre-fill (név, email)
- Validálás minden mezőre (név, email format, min. 10 char üzenet)
- Inline magyar szövegek (lang.php-re nem szükséges)

#### • Info & Legal Oldalak
- **team.php** - Csapattagok profiljai (Baranyi Norbert, Csontos Kincső Anasztázia, Szekeres Levente)
- **about.php** - Rólunk oldal: projekt célja, alapelvek, fő funkciók, tech stack, NoteForge Development info, jogi nyilatkozat
- **faq.php** - Gyakran ismételt kérdések (6 kérdés-válasz pár accordion stílusban)
- **rules.php** - Használati szabályzat (korhatár=13 év, fiókbiztonság, tartalom szabályok, szerzői jog, közösségi viselkedés, pontok/badge-ek, moderáció, prémium, adatvédelem)
- **partners.php** - Partnereink oldal (RangerBot Discord bot história és linkek)
- **privacy.php** - Adatkezelési tájékoztató (GDPR compliant, adatkezelő info, adatfeldolgozók, jogok, megőrzés)

### Changed
#### • UI/CSS Konverzió
- **contact.php**: Tailwind CDN → styles.css (.card, .form-grid, .input, .btn-primary, .toast osztályok)
- **contact.php**: Max-width 700px wrapper, középre igazítás
- **contact.php**: Közvetlenül magyar szövegek (lang.php hívások eltávolítva)
- Forms és validációs üzenetek összhangban a report.php stílusával

### Fixed
#### • Contact Form Validation
- Email validálása (filter_var FILTER_VALIDATE_EMAIL)
- Üzenet hossz validálása (min 10, max 5000 karakter)
- Tárgy megadása kötelezővé tétele
- Input sanitizing a többi oldalhoz hasonlóan

### Removed
#### • Tailwind CDN hivatkozások
- Eltávolítva a team.php, about.php, faq.php-ből (nem szükséges, mivel styles.css fedezi a szükséges stílusokat)
- "Flex", "gap", "grid-cols-*" Tailwind classok NEM törlve (még szükségesek ezekhez!)

### Security
#### • Contact Form
- Email formátum validálása (filter_var FILTER_VALIDATE_EMAIL)
- Üzenet hossz validálása (min 10 karakter)
- Input sanitizing (htmlspecialchars, trim)
- XSS védelmi kódok (htmlspecialchars ENT_QUOTES)
- CSRF token előkészítés (bejelentkezés nem kötelező, de lehetséges)
- IP cím naplózása az adatbázisban (anonymize_ip())
- User-Agent naplózása az adatbázisban
- Privacy.php: GDPR megfelelőség (adatkezelő info, jogok, megőrzés)
- Rules.php: Korhatár bekéri (13 év) & tiltott tartalmak listázása

Changelog by: Csontos Kincső Anasztázia

---

## [1.5.3-beta] – 2026-01-26

### Added

#### Payment Demo
- Fizetési folyamat demó implementálása tesztelési célokra
#### Premium
-Prémium funkciók részletes ismertetése és előkészítése
#### assets/php/premium
- A prémium funkciók kezeléséhez szükséges PHP függvények hozzáadása

### Changed
#### Database
-`premium_users` tábla hozzáadása a prémium jogosultságok kezeléséhez

Changelog by: Szekeres Levente

---

## [1.5.2-beta] - 2026-01-25

### Added
#### • Profanity filter
-Fájl és csoport létrehozás előtt eldurran a profanity filteres kódrész
-Visszadob hogyha trágár szavat talál

!! EGYELŐRE CSAK A FÁJL ÉS CSOPORT LÉTREHOZÁSNÁL VAN ÉLESITVE! (név és descriptionnal pontossabban)
!!! HA NEM AKARJUK HOGY TELE LEGYEN A DB KÁROMKODÁSSAL, AKKOR KÉSŐBBIEKBEN A SZAVAKAT LEHASHELEM SZIVESEN.

Changelog by: Norbi

---

## [1.5.1-beta] – 2026-01-25

### Added
#### • Profil & UI
- Felhasználói badge-ek megjelenítése "pill" stílusban (lekerekített sarkok, vastagabb körvonal)
- Üres bio esetén automatikus, vicces fallback szöveg megjelenítése
- Profilon „Kedvenceim” és „Feltöltés” gombok egységes stílusban
- Felhasználási feltételek (Terms of Service) oldal hozzáadása
- Adatkezelési tájékoztató link elhelyezése a footerben

#### • Favorites
- Új, egységes Favorites UI
- Thumbnail nélküli fájloknál fájltípus ikon (PDF, DOCX, stb.) megjelenítése
- Ikonok középre igazítása fallback esetén
- Favorites oldal leválasztása a felhasználói profil témájáról

### Changed
#### • Profil oldal
- Profil fejléc layout újrarendezése (név, username, gombok elhelyezése)
- Profil témák kiterjesztése navbarra és footerre
- Checkbox alapú beállítások toggle-kapcsolóra cserélése (2FA)
- Toggle megjelenítés javítása világos és pastel témákban

#### • Footer
- Footer teljes újratervezése (brand blokk, jogi linkek, reszponzív layout)
- GitHub, Adatkezelés és Felhasználási feltételek elkülönített megjelenítése

### Fixed
#### • Admin panel
- Admin oldal középre igazítása reklám nélküli layout esetén
- Táblázatok betűnkénti tördelésének megszüntetése
- „Meglévő badge-ek” táblázat vízszintes görgetősávjának eltávolítása
- Badge preview cellák szétesésének javítása

#### • Backend / DB
- `messages` tábla PRIMARY KEY ütközés javítása (`AUTO_INCREMENT`)
- Duplikált `id = 0` beszúrási hiba megszüntetése

### Removed
- Felesleges, üres reklám oszlopok helyfoglalása admin és belső oldalakon

### Security
- Üzenetküldés adatbázis-integritási hibáinak javítása
- Felhasználói adatkezelési és jogi oldalak publikálása
- Inputok és layoutok stabilizálása admin felületen

Changelog by: Csontos Kincső Anasztázia

---

## [1.5.0-beta] – 2026-01-25

### Added
#### • Keresés
- Évfolyam / félév alapú szűrés (középiskola 9–13, egyetem 1–7)
- Tag alapú szűrés és keresés
- Relevancia alapú rendezés
- „Browse” mód keresőkifejezés nélkül

#### • Fájlfeltöltés
- Kereséssel kompatibilis feltöltési mezők (tantárgy, tagek, évfolyam/félév)
- Edu stage + level támogatás (`hs`, `uni`)
- Metaadatok egységesítése feltöltés és keresés között

### Changed
- `year` mező leváltása `edu_stage` + `edu_level` struktúrára
- Search és upload logika közös adatmodellre igazítása


### Fixed
- Keresési szűrők együttes használatából adódó hibák
- Upload oldali validációk és fallbackek


### Removed
- Elavult évfolyam-kezelési logika (`year` oszlop)

### Security
- Prepared statementek egységes használata keresésnél és feltöltésnél
- Feltöltési inputok szigorúbb validálása

Changelog by: Csontos Kincső Anasztázia

---

## [v1.4.5-beta] - 2026-01-17

### Added
#### • Tailwind CSS CDN integráció
- Tailwind CSS 4 Browser CDN hozzáadva az összes oldalhoz
- Főoldalak: index.php, note.php, search.php, upload.php, groups.php, messages.php, reglog.php
- További oldalak: favorites.php, 2fa.php, group.php, create_group.php, forgotpass.php, admin_panel.php
- Egységes reszponzív utility class rendszer bevezetése a teljes projektben

### Changed
#### • Teljes UI reszponzív refaktorálás Tailwind utility class-okkal
- **index.php**: Mobil-first grid rendszer (1/2/3 oszlop), egységes padding/spacing, reszponzív tipográfia
- **navbar.php**: Flexbox-alapú layout, reszponzív szövegméretek, dropdown pozicionálás javítva
- **note.php**: Reszponzív tartalom wrapper (max-w-4xl), flexbox gombok/űrlapok, tördelés javítva (break-words)
- **search.php**: Grid keresési űrlap (1/2/4 oszlop breakpointok), reszponzív eredménylista, kártya layout
- **upload.php**: Függőleges form layout gap-el, egységes input/button méretek
- **groups.php**: Grid csoport kártyák (1/2/3 oszlop), flex header mobile/desktop módban
- **messages.php**: Sidebar/main flex layout (mobil: stack, desktop: side-by-side), üzenet UI javítva
- **reglog.php**: Auth formok grid layout (1/2 oszlop), flexbox gombok, mobil-first spacing
- **favorites.php**: Grid layout (1/2/3 oszlop), reszponzív kártya rendszer, egységes gombok és spacing
- **2fa.php**: Reszponzív auth form (max-w-lg), vertikális gap-alapú layout, mobilbarát gombok
- **group.php**: Teljes újraformázás: tagok listája flex-alapú, jegyzetek flexbox card-ok, pending kérések responsive
- **create_group.php**: Reszponzív form layout (max-w-3xl), függőleges űrlap mezők, checkbox javítva
- **forgotpass.php**: Auth formok (max-w-lg), responsive button layout, flex container három állapothoz
- **admin_panel.php**: Teljes admin UI refaktorálás (max-w-7xl), grid formok (2/3/4 oszlop), overflow-x táblázatokhoz

#### • Egységes konténer logika
- Minden oldal: `w-full max-w-{size} mx-auto px-4 md:px-6 lg:px-8`
- Konzisztens max-width értékek (max-w-3xl, max-w-4xl, max-w-6xl) oldal típusonként

#### • Reszponzív tipográfia
- Mobil: text-sm/base, Desktop: md:text-base/lg
- Címsorok: text-2xl md:text-3xl lg:text-4xl skála
- Gombok/inputok: egységes text-sm md:text-base

### Fixed
#### • Layout és overflow hibák
- Vízszintes scroll megszüntetve minden oldalon
- Hosszú szövegek/URL-ek tördelése (break-words, truncate)
- Mobilon túlnyúló képek/videók/iframe-ek (w-full, max-w-full)
- Navbar elemek túlcsordulása mobilon (truncate, whitespace-nowrap, flex-shrink-0)
- Kártya tartalmak egységes elrendezése (flex-col, min-w-0, gap-*)
- a profil mostmár teljesen szimmetrikus / valid HTML struktúrára épül

#### • Spacing és elrendezés konzisztencia
- Egységes gap/padding rendszer (gap-2/3/4/6, p-4 md:p-6)
- Grid/flex rendszerek mobilon és desktopon
- Form elemek full-width mobilon, auto desktop

#### • Navbar mobilos működés egységesítése
- Mobilos navbar háttér sötétítve: rgba(15, 23, 42, 0.95) + blur(20px)
- Dropdown menü mobilon sötét háttér: rgba(30, 41, 59, 0.8)
- script.js importálva minden oldalon (search.php, note_stats.php)
- Navbar toggle működés azonos minden oldalon
- Z-index javítva a mobilos menü helyes megjelenéséhez

#### • jQuery függőségek javítása
- jQuery CDN hozzáadva hiányzó oldalakhoz (search.php, note_stats.php)
- script.js védve `typeof $ !== 'undefined'` ellenőrzéssel
- jQuery-függő kód blokkok (messages, search-box) biztonságosan betöltve
- "$ is not defined" konzol hiba megszüntetve minden oldalon

### Removed
#### • Inline style attribútumok
- style="margin-top:...", style="display:flex" helyett Tailwind utility classok
- Felesleges wrapper div-ek ahol Tailwind class elegendő

### Security
#### • Admin jogosultság ellenőrzés javítása
- **admin_panel.php**: Admin jogosultság ellenőrzés hozzáadva (403 Forbidden, ha nem admin)
- KRITIKUS: Korábban bárki hozzáférhetett az admin panelhez bejelentkezés után

#### • IDOR (Insecure Direct Object Reference) védelem auditálva
- **profile.php**: Minden UPDATE utasítás `$viewerId`-t használ (VÉDETT)
  - Basic profile, profile picture, bio, theme, 2FA - mind biztonságos
  - `$isOwner` ellenőrzés minden módosításnál
- **note_stats.php**: Owner ellenőrzés implementálva (VÉDETT)
  - Csak a jegyzet tulajdonosa láthatja a statisztikákat
- **Egyéb oldalak**: Nincs közvetlen UPDATE/DELETE `$_GET`/`$_POST` paraméterekből

#### • Biztonsági konklúzió
- Profil módosítás: NEM lehet más felhasználók adatait megváltoztatni
- URL paraméterek (`?userid=`) figyelmen kívül vannak hagyva
- Session-alapú autentikáció mindenhol következetes
- ⚠CSRF védelem még nincs implementálva (későbbi feladat)

Changelog by: **Csontos Kincső Anasztázia**


## [v1.4.4-beta] – 2026-01-17

### Added

#### • Jegyzet statisztika

* Új **note_stats.php** oldal jegyzetenkénti statisztikákhoz
* Összesített adatok megjelenítése (*views, downloads, favorites, ratings, flashcards*)
* **14 napos trend grafikon** (views / downloads / favorites / ratings_count)
* Olvasható **esemény feed** a `file_events` táblából (nem táblázatos, hanem kártyás megjelenítés)
* Admin-only megjelenítés teljes IP-vel és User-Agenttel
* Jogosultság alapú hozzáférés (csak a feltöltő látja)

### Changed

#### • Jogosultságkezelés

* `isOwner` logika egységesítve (`uploaded_by === currentUserId`)
* Admin felismerés egyszerűsítve (`users.admin = 1`)

#### • Frontend

* Táblázatos eseménylista lecserélve **reszponzív feed nézetre**
* Grafikon canvas HiDPI-támogatással
* Legend és színek egységesítése
* User-Agent szöveg vágása nem admin felhasználóknál

#### • Backend

* `file_stats_daily` és `file_events` adatok strukturáltabb felhasználása
* Grafikon adatainak normalizált előkészítése PHP oldalon

### Fixed

#### • Frontend

* Hiányzó JS változók miatti grafikonhiba (`favorites`, `ratings`)
* Hibás admin ellenőrzés (`is_admin` / `role` → `admin`)
* Olvashatatlan, vízszintesen görgethető eseménytáblázat
* Nem definiált grafikon-vonalak miatti runtime error

### Removed

#### • Frontend

* Horizontálisan scrollozható `file_events` táblázat
* Felesleges admin/role fallback logika

### Security

#### • IP-címek 

* IP-címek **anonimizálása nem admin felhasználóknak**
* Teljes User-Agent csak admin jogosultsággal érhető el
* Jegyzet statisztika kizárólag a feltöltő számára elérhető
* IPv6 és IPv4 IP-kezelés egységesítése (`INET6_NTOA`)

Changelog by: **Csontos Kincső Anasztázia**

---

## [v1.4.3-beta] - 2026-01-14

### Changed
#### • Dokumentáció
- Fejezetekkel kibővítve, pontosabb & részletesebb leírás

#### • CHANGELOG
- A verziók pontosítása `v`-vel & `-beta`-val egészen majd a vizsgáig, vizsga napján is hivatalosan is [v1.0.0] lesz

Changelog by: Csontos Kincső Anasztázia

---

## [v1.4.2-beta] - 2025-01-13

### Added

#### • Biztonság és fiókkezelés

* Kétlépcsős azonosítás (2FA) be- és kikapcsolásának lehetősége a profil oldalon
* Biztonsági kérdések hash-elése az adatbiztonság növelése érdekében

### Changed

#### • Felhasználói felület

* Navbar újratervezése (UI redesign)
* `messages.php` felhasználói felületének átdolgozása
* Gradientek mennyiségének csökkentése a letisztultabb megjelenés érdekében

### Fixed

#### • Jogosultság és adatvédelem

* Más felhasználók adatainak szerkesztési lehetőségének megszüntetése (pl. jelszó módosítás adatbázison keresztül)
* Profil bemutatkozás karakterlimitjének bevezetése (maximum 1500 karakter)

### Security

#### • Adatbiztonság

* Jogosulatlan adatmanipuláció lehetőségének megszüntetése más felhasználók esetén

Changelog by: Csontos Kincső Anasztázia


## [v1.4.1-beta] - 2025-01-13

### Added
#### • Reklámok
- Egyes oldalakon reklámok jelennek meg a baloldalt

### Fixed
#### • mobilos hamburger menü
- Mostmár lenyílik és rendesen lehet használni a mobilon

Changelog by: Szekeres Levente

---

## [v1.4.0-beta] - 2026-01-09
TAGELÉS REWORK

### Added
#### •findtag.php, kereso_tag.php
-adatbázisban lévő tagek megmutatására szolgál mindkettő php fájl

### Changed
#### •upload.php
-beraktam egy textarea-t amibe az applikált tagek kerülnek be. ennek a tartalma töltödik fel a db-be


Changelog by: Norbi

---


## [v1.3.6-beta] - 2025-12-16

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

## [v1.3.5-beta] - 2025-12-14

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


## [v1.3.4-beta] - 2025-12-06

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

## [v1.3.3-beta] – 2025-12-02

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


## [v1.3.2-beta] - 2025-12-01

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

## [v1.3.1-beta] - 2025-12-1

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

## [v1.3.0-beta]  - 2025-11-25

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

## [v1.2.2-beta] - 2025-11-23

### Added
#### •favorites.php
-megjelennek a felhasználó kedvenc jegyzetei, részletek és letöltés gombbal

### Changed
#### footer.php
-elvolt irva a nevem (Baranyai -> Baranyi)

Changelog by: Norbi

---

## [v1.2.1-beta] - 2025-11-23

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

## [v1.2.0-beta] - 2025-11-21

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

## [v1.1.1-beta] - 2025-11-12

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

## [v1.1.0-beta] - 2025-11-09

### Added
#### • Discord login button
- Discord bejelentkezés gomb
- Hozzájuk tartozó fájlok 

### Removed
#### • Google login button
- Az idő haladtával törlésre került, bonyolultabb mint a dc login

Changelog by: Csontos Kincső Anasztázia

---

## [v1.0.3-beta] - 2025-10-02

### Fixed
#### • Születésnapi profil keret
- A keret mostmár nem a profilkép alatt van, hanem rajta mint egy keret, külön animációval
- A hozzá tartozó születésnapi szöveg színe megváltozott, az olvashatóság érdekében

Changelog by: Csontos Kincső Anasztázia

---

## [v1.0.2-beta] - 2025-10-02


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

## [v1.0.1-beta] - 2025-11-02

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

## [v1.0.1-beta] - 2025-11-02

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

## [v1.0.0-beta] - 2025-10-01

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