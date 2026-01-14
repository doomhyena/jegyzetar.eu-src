<div align="center">
<h1> "Schola Europa Akadémia" Technikum, Gimnázium és Alapfokú Művészeti Iskola a  Magyarországi Metodista Egyház fenntartásában</h1>

<br> ![Schola Europa Akadémia logó](img/scholalogo.png)<br>

**SZOFTVERFEJLESZTŐ ÉS -TESZTELŐ**<br>
5 0613 12 03

Dokumentáció

Készítette:<br>
Baranyi Norbert 14/B<br>
Csontos Kincső 14/A<br>
Szekeres Levente 14/A<br>

**2026**

</div>

<div style="page-break-before: always;"></div>

# Jegyzetár - Online Jegyzetmegosztós Platform Dokumentáció

1. [Bevezetés](#1-bevezetés)
   - 1.1. [A Projekt Célja](#11-a-projekt-célja)
   - 1.2. [Főbb Funkciók](#12-főbb-funkciók)
   - 1.3. [Technológiai Stack](#13-technológiai-stack)

2. [Rendszerarchitektúra](#2-rendszerarchitektúra)
   - 2.1. [Magas Szintű Architektúra](#21-magas-szintű-architektúra)
   - 2.2. [Komponensek](#22-komponensek)
   - 2.3. [Adatbázis Séma](#23-adatbázis-séma)

3. [Frontend Architektúra](#3-frontend-architektúra)
   - 3.1. [UI/UX Design](#31-uiux-design)

4. [Backend Architektúra](#4-backend-architektúra)
   - 4.1. [Szolgáltatások](#41-szolgáltatások)
   - 4.2. [Adatbázis Kapcsolat](#42-adatbázis-kapcsolat)
   - 4.3. [Fájlkezelés](#43-fájlkezelés)

5. [Deployment](#5-deployment)
   - 4.1. [Környezetek](#51-környezetek)
   - 4.2. [Fejlesztői környezet](#52-fejlesztői-környezet-development)
   - 4.3. [Kód commit és push](#53-kód-commit-és-push)
   - 4.4. [Code review](#54-pull-request-és-code-review)
   - 4.5. [Hibaelhárítás](#55-hibaelhárítás)

6. [Tesztelés](#6-tesztelés) (Terv alatt)

6. [Felhasználói Dokumentáció](#6-felhasználói-dokumentáció)
   - 6.1. [Telepítési Útmutató](#61-telepítési-útmutató)
   - 6.2. [Használat](#62-használat)
   - 6.3. [Weben Belüli Navigáicó](#63-weben-belüli-navigáció)
   - 6.4. [Biztonsági Tippek](#64-biztonsági-tippek)

7. [Fejlesztői Dokumentáció](#7-fejlesztői-dokumentáció)
    - 7.1. [Fejlesztői Környezet Beállítása](#71-fejlesztői-környezet-beállítása)
    - 7.2. [Verziókezelési Stratégia](#73-verziókezelési-stratégia)
    - 7.3. [FájlStruktúra](#73-fájlstruktúra)

8. [API Dokumentáció](#8-api-dokumentáció) (Terv alatt)

8. [Jövőbeli Tervek](#8-jövőbeli-tervek)
    - 8.1. [Kollaboratív tanulási eszközök](#81-kollaboratív-tanulási-eszközök)
    - 8.2. [API és harmadik fél integrációk](#82-api-és-harmadik-fél-integrációk)
    - 8.3. [Big data analitika](#83-big-data-analitika)
    - 8.4. [AI-alapú keresés és javaslatok](#84-ai-alapú-keresés-és-javaslatok)
    - 8.5. [Gamifikáció](#85-gamifikáció)
    - 8.6. [Mobil Applikáció](#86-mobil-applikáció)

9. [Ki mit készített?](#9-ki-mit-készített?)
    - 9.1. [Baranyi Norbert](#91-baranyi-norbert)
    - 9.2. [Csontos Kincső Anasztázia](#92-csontos-kincső-anasztázia)
    - 9.2. [Szekeres Levente](#92-szekeres-levente)

10. [Licensz](#10-licensz)

<div style="page-break-before: always;"></div>

## 1. Bevezetés

### 1.1. A Projekt Célja

A **Jegyzetár** egy modern, közösségi alapú, oktatássegítő platform, amely lehetővé teszi a diákok számára, hogy egyszerűen és biztonságosan megosszák egymással jegyzeteiket, segédanyagaikat és tanulást támogató dokumentumaikat. A projekt célja, hogy:

* **Kik használják?**
Diákok, tanárok és oktatók, akik megosztanák egymással az oktatási anyagokat.

* **Gyors és kényelmes fájlmegosztást biztosítson tanulóknak**

  - A felhasználók könnyedén feltölthetnek, kereshetnek és letölthetnek jegyzeteket
  - A platform reszponzív kialakítása biztosítja a mobilbarát használatot
  - Az egyszerű kezelőfelület csökkenti az informatikai tudás iránti igényt

* **Rendszerezett és kereshető tananyagbázist hozzon létre**

  - Tantárgyak, évfolyamok és dokumentumtípusok szerint kategorizál
  - Kulcsszavas keresés és szűrés segíti a gyors anyagkeresést
  - Előnézeti kép vagy rövid leírás segít a tartalom gyors azonosításában

* **Támogassa a közösségi tanulást**

  - A felhasználók értékelhetik, kommentelhetik a jegyzeteket
  - A rendszer kiemeli a legnépszerűbb vagy legjobbra értékelt anyagokat


---

### 1.2. Főbb Funkciók

A Jegyzetár rendszer az alábbi kulcsfunkciókat biztosítja:

### Fájlfeltöltés és -kezelés

- Jegyzetek és segédanyagok feltöltése PDF, DOCX, MP4 formátumban
- Automatikus kategorizálás tantárgy és dokumentumtípus szerint
- Előnézeti kép generálása (PDF első oldal, képek)

### Felhasználói felület

- Regisztráció és bejelentkezés (alap és "OAuth")
- Egyéni irányítópult a feltöltések, letöltések, értékelések követésére
- Mobilbarát és reszponzív dizájn

### Keresés és közösségi funkciók

- Kulcsszavas kereső és szűrő (tantárgy, értékelés alapján)
- Kommentelés, csillagozás
- Legnépszerűbb jegyzetek és új feltöltések kiemelése

### Adminisztráció és jogosultságok

- Admin felület a tartalmak, felhasználók és kategóriák kezeléséhez
- Feltöltések jelentése és moderálása
    - Admin eszközök kiterjesztése: Külön felület az egyedi profil CSS kérések kezelésére (jóváhagyás / elutasítás / archiválás), valamint a felhasználók és feltöltések részletes kezelése (törlés, szerkesztés, moderation logs).

### Profil egyedi CSS kérések
Bevezetésre került a felhasználói CSS kérelmek kezelése, amely a profil felületén megadható, de csak admin jóváhagyása után érvényesül. Az előnézet a felhasználói élményt javítja és nem ment a szerverre automatikusan.

### Admin jóváhagyási UI
Az admin panelben külön táblában listázhatók és jóváhagyhatók a CSS kérések; az elfogadott CSS archiválható és az előző állapot is visszaállítható.

### Frontend preview & safe background rules
A preview kliens oldali (style tag injektálás), a SAFE_BG_RULE szabály megakadályozza a background képek kellemetlen ismétlődését, és a preview idején a jobboldali panel rejtésre kerül, hogy az előnézet ne rontsa el az oldalt.

### Lokalizációs módosítások
A `t()` segédfüggvény és `lang.php` központi megoldással egy adatbázis-alapú fordítási rendszer lett bevezetve. A fordítások hiányzó kulcsait automatikusan lehet seed-elni. Fordítási kulcsok a `profiles`-hoz a 1543-as ID-tól készültek.

### SQL dump & seed eszközök
Hozzáadtuk a `clean_translations.py`, `translations_clean.sql`, `replace_translations_in_dump.py`, `repair_translations.sql` eszközöket, amelyek a dump-ok importjának biztonságát növelik (duplikált bejegyzések eltávolítása, ON DUPLICATE használata, táblák import sorrendje). A `repair_translations.sql` script automatikus backup-ot készít, majd törli a duplikátumokat és létrehozza a megfelelő UNIQUE indexet.

### Biztonsági javítások
A kód refaktorálásával `db_prepared()` használata javasolt a lekérdezésekhez, `require_once` és `function_exists` védelem bevezetésre került, a `Message()` segédfüggvény javítja az értesítések konzisztenciáját.

### 1.3. Technológiai Stack

A Jegyzetár fejlesztése során a következő technológiákat és eszközöket használtuk, amelyek mindegyike szabadon elérhető és bármilyen modern számítógépen telepíthető:

### Frontend
- **HTML5, CSS3, JavaScript**: Az alapvető webes technológiák a felhasználói felület kialakításához.
- **Bootstrap**: Reszponzív és mobilbarát dizájn gyors fejlesztéséhez.
- **jQuery**: Egyszerű DOM-manipulációk és AJAX-hívások kezelésére.

### Backend
- **PHP (8.2+)**: A szerveroldali logika és API-k implementálásához.

### Adatbázis
- **MySQL**: Relációs adatbázis-kezelő a felhasználói adatok, fájlok és egyéb információk tárolására.
- **phpMyAdmin**: Az adatbázis adminisztrációjához.

### Verziókezelés
- **Git**: Verziókövetés és csapatmunka támogatása.
- **GitHub**: Távoli repository a kód tárolására és megosztására.

### Egyéb eszközök
- **XAMPP**: Lokális fejlesztői környezet (Apache, MySQL, PHP) - ingyenesen letölthető bármely operációs rendszerre.
- **Visual Studio Code**: Kódszerkesztő a fejlesztéshez - ingyenes és cross-platform.
- **PHPStorm**: Integrált Fejlesztői Környezet a fejlesztéshez - fizetős verzió elérhető bármeéy operációs rendszerre.

### Felhasznált hardverek

#### Baranyi Norbert

-

#### Csontos Kincső Anasztázia

- **Laptop**: Lenovo LOQ 15A
- **CPU**: AMD Ryzen 5 7235HS
- **GPU**: NVIDIA GeForce RTX 3050 6GB Laptop GPU
- **RAM**: 16GB DDR5 4800 MHz
- **SSD**: 512GB NVMe SSD
- **OS**: Windows 11 Pro 64-bit

#### Szekeres Levent

-

### Hardver követelmények
A fejlesztési környezet bármilyen modern számítógépen futtatható. Minimális követelmények:
- **Operációs rendszer**: Windows 10/11, macOS 10.14+ vagy Linux (Ubuntu/Debian)
- **Processzor**: Modern többmagos CPU (pl. Intel i5, AMD Ryzen 5 vagy hasonló)
- **Memória**: Legalább 8GB RAM (ajánlott 16GB a jobb teljesítmény érdekében)
- **Tárhely**: Legalább 50GB szabad hely SSD-n a projekt fájlok és virtuális környezet számára
- **Grafikus kártya**: Integrált vagy dedikált GPU (nem kritikus, mivel webfejlesztésről van szó)

## 2. Rendszerarchitektúra

### 2.1. Magas Szintű Architektúra
A Jegyzetár egy háromrétegű architektúrát követ:
1. **Prezentációs réteg**: A felhasználói felület, amely a frontend technológiákra épül.
2. **Alkalmazásréteg**: A backend logika, amely PHP segítségével valósul meg.
3. **Adatbázis réteg**: A MySQL adatbázis, amely az összes adatot tárolja.

### 2.2. Komponensek
- **Frontend**: A felhasználói interakciók kezelése és az adatok megjelenítése.
- **Backend**: Az logika és az adatbázis műveletek végrehajtása.
- **Adatbázis**: A felhasználói adatok, fájlok és metaadatok tárolása.

### 2.3. Adatbázis Séma

#### Táblák

1. 2fa_codes
   - `id` (INT, PK, AUTO_INCREMENT)
   - `userid` (INT, FK → users.id)
   - `code` (INT)

2. badges
   - `id` (INT, PK, AUTO_INCREMENT)
   - `name` (VARCHAR(64))
   - `slug` (VARCHAR(64), UNIQUE)
   - `description` (VARCHAR(255))
   - `icon` (VARCHAR(16))

3. comments
   - `id` (INT, PK, AUTO_INCREMENT)
   - `userid` (INT, FK → users.id)
   - `postid` (INT)
   - `text` (VARCHAR(1000))

4. favorites
   - `id` (INT, PK, AUTO_INCREMENT)
   - `user_id` (INT, FK → users.id)
   - `file_id` (INT, FK → files.id)
   - `created_at` (DATETIME DEFAULT current_timestamp())

5. files
   - `id` (INT, PK, AUTO_INCREMENT)
   - `uploaded_by` (INT, FK → users.id)
   - `name` (VARCHAR(255))
   - `file_name` (VARCHAR(255))
   - `description` (TEXT)
   - `file_path` (VARCHAR(255))
   - `subject` (VARCHAR(100))
   - `tags` (VARCHAR(255))
   - `tn_name` (VARCHAR(255))
   - `file_size` (BIGINT UNSIGNED)

6. friends
   - `id` (INT, PK, AUTO_INCREMENT)
   - `fromid` (INT, FK → users.id)
   - `toid` (INT, FK → users.id)
   - `status` (TINYINT DEFAULT 0)

7. groups
   - `id` (INT, PK, AUTO_INCREMENT)
   - `name` (VARCHAR(100))
   - `description` (TEXT)
   - `owner_id` (INT, FK → users.id)
   - `is_private` (TINYINT DEFAULT 0)
   - `created_at` (DATETIME DEFAULT current_timestamp())

8. group_files
   - `id` (INT, PK, AUTO_INCREMENT)
   - `group_id` (INT, FK → groups.id)
   - `uploaded_by` (INT, FK → users.id)
   - `name` (VARCHAR(255))
   - `description` (TEXT)
   - `file_name` (VARCHAR(255))
   - `created_at` (DATETIME)
   - `is_approved` (TINYINT DEFAULT 0)

9. group_members
   - `id` (INT, PK, AUTO_INCREMENT)
   - `group_id` (INT, FK → groups.id)
   - `user_id` (INT, FK → users.id)
   - `role` (ENUM('owner','member') DEFAULT 'member')
   - `status` (ENUM('accepted','pending') DEFAULT 'accepted')
   - `joined_at` (DATETIME DEFAULT current_timestamp())

10. languages
    - `id` (INT, PK, AUTO_INCREMENT)
    - `code` (VARCHAR(5), UNIQUE)
    - `name` (VARCHAR(50))

11. messages
    - `id` (INT, PK, AUTO_INCREMENT)
    - `fromid` (INT, FK → users.id)
    - `toid` (INT, FK → users.id)
    - `content` (TEXT)
    - `sent_at` (DATE DEFAULT current_timestamp())

12. namedays
    - `id` (INT, PK, AUTO_INCREMENT)
    - `datum` (VARCHAR(5))
    - `nevek` (VARCHAR(255))

13. notifys
    - `id` (INT, PK, AUTO_INCREMENT)
    - `fromid` (INT, FK → users.id)
    - `toid` (INT, FK → users.id)
    - `notifytype` (VARCHAR(100))
    - `readed` (TINYINT DEFAULT 0)

14. ratings
    - `id` (INT, PK, AUTO_INCREMENT)
    - `file_id` (INT, FK → files.id)
    - `user_id` (INT, FK → users.id)
    - `rating` (TINYINT)
    - `created_at` (DATETIME DEFAULT current_timestamp())
    - `updated_at` (DATETIME DEFAULT current_timestamp() ON UPDATE current_timestamp())

15. reg_codes
    - `id` (INT, PK, AUTO_INCREMENT)
    - `code` (VARCHAR(50), UNIQUE)
    - `description` (VARCHAR(255))
    - `max_uses` (INT)
    - `used` (INT DEFAULT 0)
    - `expires_at` (DATETIME)
    - `active` (TINYINT DEFAULT 1)
    - `created_at` (DATETIME DEFAULT current_timestamp())

16. reports
    - `id` (INT, PK, AUTO_INCREMENT)
    - `reporter_id` (INT, FK → users.id)
    - `target_type` (VARCHAR(50))
    - `target_id` (INT)
    - `reason` (TEXT)
    - `status` (ENUM('open','dismissed','resolved') DEFAULT 'open')
    - `created_at` (DATETIME DEFAULT current_timestamp())
    - `handled_by` (INT, FK → users.id)
    - `handled_at` (DATETIME)

17. tags
    - `id` (INT, PK, AUTO_INCREMENT)
    - `tags` (VARCHAR(100))

18. tokens
    - `id` (INT, PK, AUTO_INCREMENT)
    - `user_id` (INT, FK → users.id)
    - `token` (VARCHAR(255))
    - `created_at` (TIMESTAMP DEFAULT current_timestamp())

19. translations
    - `id` (INT, PK, AUTO_INCREMENT)
    - `t_key` (VARCHAR(100))
    - `lang_code` (VARCHAR(5))
    - `text` (VARCHAR(255))

20. users
    - `id` (INT, PK, AUTO_INCREMENT)
    - `lastname` (VARCHAR(100))
    - `firstname` (VARCHAR(100))
    - `username` (VARCHAR(50), UNIQUE)
    - `birthdate` (DATE)
    - `gender` (ENUM('male','female','other'))
    - `email` (VARCHAR(100), UNIQUE)
    - `profile_picture` (VARCHAR(255))
    - `password` (VARCHAR(255))
    - `security_question` (VARCHAR(255))
    - `security_answer` (VARCHAR(255))
    - `admin` (TINYINT DEFAULT 0)
    - `registration_date` (DATETIME DEFAULT current_timestamp())
    - `language` (VARCHAR(5) DEFAULT 'hu')
    - `oauth_provider` (VARCHAR(50))
    - `oauth_sub` (VARCHAR(255))
    - `email_verified` (TINYINT DEFAULT 0)
    - `bio` (TEXT)
    - `profile_theme` (VARCHAR(32) DEFAULT 'default')

21. user_badges
    - `id` (INT, PK, AUTO_INCREMENT)
    - `user_id` (INT, FK → users.id)
    - `badge_id` (INT, FK → badges.id)
    - `granted_by` (INT, FK → users.id)
    - `granted_at` (DATETIME DEFAULT current_timestamp())

22. user_custom_css_archive
    - `id` (INT, PK, AUTO_INCREMENT)
    - `original_request_id` (INT)
    - `user_id` (INT, FK → users.id)
    - `css` (MEDIUMTEXT)
    - `status` (ENUM('pending','approved','rejected'))
    - `created_at` (DATETIME)
    - `reviewed_at` (DATETIME)
    - `reviewed_by` (INT, FK → users.id)
    - `archived_at` (DATETIME DEFAULT current_timestamp())

23. user_custom_css_requests
    - `id` (INT, PK, AUTO_INCREMENT)
    - `user_id` (INT, FK → users.id)
    - `css` (MEDIUMTEXT)
    - `status` (ENUM('pending','approved','rejected') DEFAULT 'pending')
    - `created_at` (DATETIME DEFAULT current_timestamp())
    - `reviewed_at` (DATETIME)
    - `reviewed_by` (INT, FK → users.id)


## 3. Frontend Architektúra

### 3.1. UI/UX Design
A Jegyzetár felhasználói felülete egyszerű és intuitív, a következő szempontokat figyelembe véve:
- **Reszponzív dizájn**: A platform minden eszközön jól használható.
- **Egyszerű navigáció**: Könnyen elérhető funkciók és tiszta menürendszer.
- **Konzisztens stílus**: Azonos színpaletta és tipográfia az egész alkalmazásban.

#### Frontend kiegészítések 

- A profiloldalhoz egy kliensoldali CSS előnézeti funkció került be: a felhasználó `profile.php` oldalon megadhat, és előnézhet egyedi CSS beállításokat. A preview csak kliensoldali stílus-injektálást használ és nem menti el automatikusan az adatbázisba.
- Biztonsági frontend szabályok: a preview megakadályozza a kedvezőtlen elrendezés-befolyásolást (jobboldali oszlop eltakarása preview alatt), illetve van egy `SAFE_BG_RULE` ami megakadályozza a háttér pattern (tiling) fokozott felvillanását.
- Üres CSS beküldésének megelőzése: kliens-oldali validáció, toast üzenet és szerveroldali fallback letakarítás, visszaállítási jelzés a felhasználó számára.

## 4. Backend Architektúra

### 4.1. Szolgáltatások
- Felhasználói regisztráció.
- Fájlok feltöltése, letöltése és kezelése.
- Kommentek és értékelések kezelése.

### 4.2. Adatbázis Kapcsolat
A PHP mysqli-t használjuk az adatbázis műveletek végrehajtására.

- Security & prepared statements: A kód nagy részét átdolgoztuk, hogy a `db_prepared($conn, $sql, $types, $params)` wrapper-t használjuk, amely a mysqli prepared statements használatát biztosítja. Ezzel jelentősen csökkent a kockázata az SQL injekcióknak, és egységesebbé vált a lekérdezések kezelése.

### 4.3. Fájlkezelés
A feltöltött fájlokat a szerveren tároljuk, és a fájlokhoz tartozó metaadatokat az adatbázisban rögzítjük.

## 5. Deployment

### 5.1. Környezetek
- **Fejlesztői környezet**: Lokális XAMPP szerver.
- **Éles környezet**: Apache szerver MySQL adatbázissal.

### 5.2. Fejlesztői környezet
A fejlesztéshez szükséges összes függőség telepítése Composer és npm segítségével történik.

### 5.3. Kód commit és push
A kódot Git segítségével kezeljük, és minden változtatást a GitHub repository-ba push-olunk.

### 5.4. Code review
Minden új funkciót pull request formájában integrálunk, amelyet code review előz meg.

### 5.5. Hibaelhárítás
A hibák nyomon követésére és kezelésére a GitHub Issues funkcióját használjuk.

## 6. Felhasználói Dokumentáció

### 6.1. Telepítési Útmutató

##### Előfeltételek
- XAMPP vagy más helyi szerver PHP és MySQL támogatással.
- Egy webböngésző.

```bash
1. Klónozd le a projekt fájljait (pl. `git clone https://github.com/doomhyena/jegyzetar.eu-src.git`) a helyi szerver gyökérkönyvtárába (pl. `c:/xampp/htdocs/Jegyzetár-Dev`).
2. Importáld az adatbázist:
- Nyisd meg a phpMyAdmin-t.
- Importáld a `jegyzetar.sql` fájlt az `assets/sql/` mappából. (Ajánlott: ha fennáll a duplikált fordítások vagy import hibák kockázata, használd a `jegyzetar_clean.sql` fájlt, amely előre tisztítja a translations INSERT blokkot, vagy futtasd előbb a `repair_translations.sql`-t, majd importáld a seed adatokat.)
  
    - Ha a dump nagy és összetett, akkor javasolt először importálni a séma CREATE TABLE részeket és az ALTER TABLE kulcsokat, majd a seed INSERT blokkokat, így elkerülve a `UNIQUE` index beállítása előtti duplikált sorok beszúrását.
3. Konfiguráld az adatbázis kapcsolatot:
- Nyisd meg a `db.php` fájlt.
- Győződj meg róla, hogy az adatbázis hitelesítési adatok megfelelnek a helyi szerver beállításainak.
4. Indítsd el a helyi szervert, és navigálj a `http://localhost/jegyzetar.eu-src/src`  címre a böngésződben.
```

### 6.2. Használat 

1. Felhasználói Regisztráció
    1. Navigálj a `reglog.php` oldalra.
    2. Töltsd ki a szükséges mezőket (vezetéknév, keresztnév, felhasználónév, email cím. jelszó, biztonsági válasz).
    3. Kattints a `Regisztráció` gombra.

2. Bejelentkezés
    1. Navigálj a `reglog.php` oldalra.
    2. Kattints a `Lépj be!` linkre
    3. Add meg a felhasználónevedet és jelszavadat.
    4. Kattints a `Bejelentkezés` gombra.

3. Fájlok Feltöltése
    1. Navigálj az `upload.php` oldalra.
    2. Add meg a fájl nevét, és válaszd ki a feltöltendő fájlt.
    3. Küldd el az űrlapot a fájl feltöltéséhez.

4. Profilkezelés
    1. Navigálj a `profile.php` oldalra.
    2. Profil testreszabása — Egyedi CSS:
        1. A profilbeállításoknál található egy `CSS kód` mező, ahova saját profil CSS-t adhatsz meg.
        2. Használhatod a `preview` gombot, amely kliens oldali élő előnézetet biztosít (a böngészőbe beágyazott stílus kerül alkalmazásra, a változás csak előnézet, és nem mentődik a szerverre automatikusan).
        3. A szerkesztett CSS beküldéséhez használd a `Egyedi CSS elküldése` gombot — ez a kérés az admin felületre kerül jóváhagyásra.
        4. Ha üresen küldöd be (nem ajánlott), a rendszer figyelmeztet (client-side validáció), valamint szerver oldali ellenőrzés is megakadályozhatja az üres kérést, és visszaállítja az előző állapotot.
        5. Az admin jóváhagyást követően az egyedi CSS inline jellegű bejegyzésként fog megjelenni a profilnál — a rendszer archiválja az előző kérelmeket.
    2. Tekintsd meg feltöltött fájljaidat, és tölts fel profilképet.

4. Fájlok Letöltése
    1. Navigálj az `index.php` oldalra.
    2. Böngészd az elérhető fájlok listáját.
    3. Kattints a "Letöltés" linkre egy fájl letöltéséhez.

5. Jelszó Visszaállítása
    1. Navigálj a `forgotpass.php` oldalra.
    2. Add meg a felhasználónevedet, és kövesd az utasításokat a jelszó visszaállításához.

6. Barátok hozzáadása: A felhasználók barátokat adhatnak hozzá, és értesítéseket kapnak a barátok státuszáról.
    1. Navigálj a `profile.php` vagy keress egy felhasználót a `search.php`-n.
    2. Jelöld be a gomb segítségével.
    3. Várd meg amíg visszaigazol.

7. Üzenetküldés
    1. Navigálj a `messages.php` oldalra.
    2. Válaszd ki, hogy kinek szeretnél üzenetet küldeni.
    3. Küldd el az üzenetedet.

### 6.3. Weben belüli navigáció

### 6.4. Biztonsági Tippek

1. **Fiók biztonsága:**
   - Használjon erős jelszót
   - Ne ossza meg a bejelentkezési adatait

2. **Adatok biztonsága:**
   - Rendszeresen készítsen biztonsági másolatot
   - Ne küldjön bizalmas adatokat emailben
   - Használjon biztonságos kapcsolatot

3. **Rendszer biztonsága:**
   - Tartsa naprakészen a szoftvert
   - Használjon vírusirtót
   - Kerülje a nyilvános hálózatokat

4. **Jogosultságok kezelése:**
   - Csak a szükséges jogosultságokat adja meg
   - Rendszeresen ellenőrizze a jogosultságokat
   - Azonnal vonja vissza a nem használt jogosultságokat

<div style="page-break-before: always;"></div>

## 7. Fejlesztői Dokumentáció

### 7.1. Fejlesztői Környezet Beállítása
```bash
1. Klónozd le a projekt fájljait (pl. `git clone https://github.com/doomhyena/jegyzetar.eu-src.git`) a helyi szerver gyökérkönyvtárába (pl. `c:/xampp/htdocs/jegyzetar.eu-src`).
2. Importáld az adatbázist:
- Nyisd meg a phpMyAdmin-t.
- Importáld a `Jegyzetár.sql` fájlt az `assets/sql/` mappából.
3. Konfiguráld az adatbázis kapcsolatot:
- Nyisd meg a `db.php` fájlt.
- Győződj meg róla, hogy az adatbázis hitelesítési adatok megfelelnek a helyi szerver beállításainak.
4. Indítsd el a helyi szervert, és navigálj a `http://localhost/jegyzetar.eu-src/src/` címre a böngésződben.
```
### 7.2. Verziókezelési Stratégia
- **Main branch**: Stabil, éles verzió.
- **Feature branch-ek**: Új funkciók fejlesztésére.

### 7.3. FájlStruktúra

```bash
jegyzetar.eu-src/
├── src/
│   ├── 2fa.php                              # Kétlépcsős hitelesítés oldal
│   ├── admin_panel.php                      # Adminisztrációs panel
│   ├── create_group.php                     # Csoport létrehozása oldal
│   ├── favorites.php                        # Kedvenc jegyzetek oldal
│   ├── forgotpass.php                       # Jelszó visszaállítás oldal
│   ├── group.php                            # Csoport részletek oldal
│   ├── groups.php                           # Csoportok listája oldal
│   ├── index.php                            # Főoldal
│   ├── messages.php                         # Üzenetek oldal
│   ├── note.php                             # Jegyzet részletek oldal
│   ├── notify.php                           # Értesítések oldal
│   ├── profile.php                          # Profil oldal
│   ├── reg-ver.php                          # E-mail aktivációs oldal
│   ├── reglog.php                           # Regisztráció és bejelentkezés oldal
│   ├── search.php                           # Keresés oldal
│   ├── upload.php                           # Feltöltés oldal
│   ├── .idea/                               # IDE konfigurációs fájlok
│   ├── assets/
│   │   ├── composer.json                    # Composer függőségek
│   │   ├── ads/                             # Hirdetések mappája
│   │   ├── css/
│   │   │   └── styles.css                   # Fő stíluslap
│   │   ├── img/                             # Képek mappája
│   │   ├── js/
│   │   │   └── script.js                    # JavaScript fájl
│   │   ├── logs/                            # Log fájlok
│   │   ├── oauth/
│   │   │   ├── discord-callback.php         # Discord OAuth callback
│   │   │   └── discord-login.php            # Discord OAuth login
│   │   ├── php/
│   │   │   ├── accept_friend.php            # Barát elfogadása
│   │   │   ├── add_friend.php               # Barát hozzáadása
│   │   │   ├── ads.php                      # Hirdetések kezelése
│   │   │   ├── db.php                       # Adatbázis kapcsolat
│   │   │   ├── delete.php                   # Fájlok törlése
│   │   │   ├── download.php                 # Fájlok letöltése
│   │   │   ├── findanything.php             # Keresési funkció
│   │   │   ├── footer.php                   # Footer megjelenítés
│   │   │   ├── functions.php                # Közös függvények
│   │   │   ├── group_actions.php            # Csoport műveletek
│   │   │   ├── group_init.php               # Csoport inicializálás
│   │   │   ├── lang.php                     # Nyelvi kezelés
│   │   │   ├── loadmessages.php             # Üzenetek betöltése
│   │   │   ├── logout.php                   # Kijelentkezés
│   │   │   ├── mail-2fa.php                 # 2FA e-mail küldés
│   │   │   ├── mail-regver.php              # Regisztrációs e-mail
│   │   │   ├── navbar.php                   # Navigációs sáv
│   │   │   └── report.php                   # Jelentések kezelése
│   │   ├── sql/
│   │   │   └── jegyzetar.sql                # Adatbázis dump
│   │   └── vendor/                          # Composer vendor könyvtár
│   ├── docs/
│   │   ├── CHANGELOG.md                     # Változásnapló
│   │   ├── dokumentáció.md                  # Ez a dokumentáció
│   │   └── img/                             # Dokumentáció képei
│   └── users/                               # Felhasználói fájlok
└── LICENSE                                  # Licensz fájl
```

### 7.4. Fejlesztői eszközök, script-ek és refaktorok

- `assets/php/functions.php` - Központi helyre került a `db_prepared()` segédfüggvény, ami a mysqli prepared statements használatát segíti és csökkenti az SQL injection kockázatát. A fájlban `function_exists` ellenőrzésekkel védjük a többszörös deklarációt.
 - Include és duplikációs védelmek: Az include/require helyeken mostantól `require_once` használata ajánlott, és a központi függvényeknél `function_exists`-es feltételek alkalmazása segít elkerülni fatal hibákat többszörös include esetén.
- `assets/php/lang.php` - Feltölti és betölti az adatbázisban tárolt fordításokat (i18n). Egy beépített rutin gondoskodik arról, hogy a hiányzó kulcsok bekerüljenek az adatbázisba a támogatott nyelvekhez, így a `t()` segédfüggvény mindig vissza tud adni fordítást a kulcshoz.
- `assets/js/script.js` - Új kliensoldali funkciók: a profil egyedi CSS előnézete, preview bekapcsolás/letiltás, jobb oszlop rejtése preview közben, és kliens oldali validálás (pl. üres CSS megelőzése).
 - `assets/php/functions.php` - A fejlesztés során bevezetett `Message()` segédfüggvény a felhasználói üzenetek központi kiírására szolgál; a korábbi `echo "<script>alert('...')";` szerkezeteket érdemes ezzel kiváltani a konzisztens, könnyebben tesztelhető és lokalizálható felhasználói értesítésekhez.

Fejlesztői script-ek az `assets/sql/scripts/` mappában:

- `clean_translations.py` — Python script, ami a nagy `translations` INSERT tömbből kiszedi a duplikált (t_key, lang_code) bejegyzéseket, és előállít egy `translations_clean.sql` fájlt, amely csak egyedi fordításokat tartalmaz.
- `replace_translations_in_dump.py` — Segédfájl, amellyel a `jegyzetar.sql` dumpból lecseréljük a translations beszúrási blokkot a `translations_clean.sql` tartalmára, és előállítjuk a `jegyzetar_clean.sql` fájlt.
- `repair_translations.sql` — SQL script, amely a már létező adatbázison belül végrehajtható módon:
    1) biztonsági mentést készít a `translations` tábláról (`translations_backup`),
    2) törli a duplikált bejegyzéseket (például megtartja a `MIN(id)` vagy `MAX(id)` bejegyzéseket),
    3) létrehozza a `UNIQUE` indexet `t_key,lang_code` mezőkre.

- Import javaslat: Ha gyakran importálnak db dump-ot, importálják először a séma CREATE TABLE részt, majd az `ALTER TABLE`-okat (kulcsokkal), és végül a seed adatokat (INSERT), vagy használják a `jegyzetar_clean.sql` fájlt amely előre eltávolítja a duplikátumokat.

- A `profile.php` oldalhoz kapcsolódó fordítási kulcsok (HU/EN/DE) seeding-je `ID = 1543`-tól készült el. Amennyiben a roll-out során seed fájlokat használunk, ezek az értékek biztosítják a következetes fordítási kulcstartományt.

Megjegyzés: A `db_prepared` használata erősen ajánlott az SQL lekérdezésekhez; ahol van rá lehetőség, a 2025-es refaktor során a fájlok és komponensek `db_prepared()`-et használnak.

## 8. Jövőbeli Tervek

### 8.1. Kollaboratív tanulási eszközök

A Jegyzetár közösségi aspektusának bővítése érdekében a cél, hogy a felhasználók együtt dolgozhassanak jegyzeteken és tanulási anyagokon.

**Lehetséges fejlesztések:**

* **Valós idejű jegyzet szerkesztés:** Több felhasználó egyszerre szerkeszthet egy dokumentumot, hasonlóan a Google Docs-hoz.
* **Csoportos tanulási szobák:** Felhasználók létrehozhatnak virtuális tanulócsoportokat, ahol fájlok megosztása, chat és feladatok követése egyszerre történhet.
* **Komment és annotáció rendszer:** A jegyzeteken belül jelölhetnek részeket, és hozzáfűzhetnek személyes megjegyzéseket vagy kérdéseket.
* **Közös naptár és feladatlista:** Tanulócsoportok eseményeket, határidőket és feladatokat oszthatnak meg egymással.

### 8.2. API és harmadik fél integrációk

A Jegyzetár platformot érdemes külső szolgáltatásokkal összekapcsolni, hogy a felhasználók minél több eszközt használhassanak egy helyen.

**Lehetséges fejlesztések:**

* **OAuth integrációk:** Google, Microsoft, Discord vagy EduID fiókokkal történő bejelentkezés támogatása.
* **Fájlmegosztó szolgáltatások integrálása:** Dropbox, Google Drive, OneDrive feltöltés és letöltés közvetlenül a Jegyzetárból.
* **Tanulási eszközök API kapcsolatai:** Kahoot, Quizlet vagy egyéb oktatási platformokkal való integráció a könnyebb feladat és teszt megosztás érdekében.
* **Push értesítések külső csatornákra:** Discord bot vagy email értesítések, ha valaki új jegyzetet tölt fel, vagy kommentel a fájlodra.

### 8.3. Big data analitika

A platform folyamatos adatgyűjtésével és elemzésével javítható a felhasználói élmény és a tartalom minősége.

**Lehetséges fejlesztések:**

* **Felhasználói aktivitás elemzése:** Kinek milyen típusú jegyzetek tetszenek, mennyi időt töltenek az oldalon, mely tantárgyak a legnépszerűbbek.
* **Tartalom minőség értékelése:** Legjobbra értékelt és leggyakrabban letöltött anyagok kiemelése.
* **Tanulási szokások feltérképezése:** Időszakos jelentések készítése, hogy mikor aktívak a diákok, milyen tantárgyakhoz kell több támogatás.
* **Prediktív ajánlások:** A felhasználók korábbi aktivitása alapján automatikus jegyzet- és csoport-ajánlások.
* **Admin riportok:** Statisztikák a feltöltött tartalmakról, felhasználói aktivitásról és a csoportok működéséről a hatékonyabb menedzsmenthez.

### 8.4. AI-alapú keresés és javaslatok

A Jegyzetár fejlesztése során gépi tanulási és mesterséges intelligencia (AI) megoldások is bevezetésre kerülnek, hogy a felhasználók gyorsabban és hatékonyabban találják meg a számukra releváns tartalmakat.

Főbb funkciók:

- **Személyre szabott ajánlások:** Az AI elemzi a felhasználó korábbi kereséseit, letöltéseit és érdeklődési köreit, majd ezek alapján ajánl új jegyzeteket vagy segédanyagokat.
- **Intelligens kereső:** A keresési találatok sorrendjét a rendszer a felhasználó szokásaihoz és preferenciáihoz igazítja.
- **Tartalom szűrés és kategorizálás:** Az AI segít a feltöltött anyagok automatikus címkézésében és kategorizálásában.
- **Tanulási útvonalak ajánlása:** A rendszer javaslatokat adhat, hogy milyen anyagokat érdemes elolvasni egy adott témakörben.

#### Előnyök:

- Gyorsabb és pontosabb keresés
- Személyre szabott tanulási élmény
- Nagyobb felhasználói elégedettség

### 8.5. Gamifikáció

A felhasználói aktivitás ösztönzése érdekében a Jegyzetár gamifikációs elemeket vezet be, amelyek játékosabbá és motiválóbbá teszik a platform használatát.

**Főbb funkciók:**
- **Pontgyűjtés:** A felhasználók pontokat szerezhetnek különböző tevékenységekért, például regisztrációért, jegyzetfeltöltésért, hozzászólásért vagy értékelésért.
- **Jelvények és rangok:** Különféle mérföldkövek eléréséért (pl. első feltöltés, 100. letöltés) digitális jelvényeket és rangokat kapnak a felhasználók.
- **Ranglisták:** A legaktívabb vagy legnépszerűbb felhasználók megjelennek a közösségi ranglistákon.
- **Kihívások és küldetések:** Időszakos vagy tematikus kihívások teljesítésével további jutalmak szerezhetők.

**Előnyök:**
- Felhasználói elköteleződés növelése
- Közösségi aktivitás ösztönzése
- Pozitív visszacsatolás a felhasználók számára


### 8.6. Mobil Applikáció

A Jegyzetár jövőbeli fejlesztési tervei között szerepel egy natív mobilalkalmazás elkészítése Android és iOS platformokra. A mobil app célja, hogy a felhasználók még kényelmesebben érhessék el a jegyzeteket, tölthessenek fel fájlokat, és kommunikálhassanak egymással útközben is.

**Főbb funkciók:**
- Jegyzetek böngészése, letöltése és feltöltése közvetlenül a mobilról
- Push értesítések új üzenetekről, barátkérésekről, kommentekről
- Felhasználói profil szerkesztése, profilkép módosítása
- Barátok kezelése, üzenetküldés
- Offline elérés a letöltött jegyzetekhez

**Technológiai lehetőségek:**
- **React Native** vagy **Flutter** a multiplatform fejlesztéshez
- REST API integráció a meglévő backenddel
- Biztonságos bejelentkezés és adatkezelés

## 9. Ki mit készített?

### 9.1. Baranyi Norbert

- E-mail aktivációs rendszer fejlesztése (mail-regver.php, reg-ver.php, tokens tábla hozzáadása)
- Kétlépcsős hitelesítés megvalósítása (mail-2fa.php, 2fa.php, 2fa_codes tábla)
- Kedvencek oldal létrehozása (favorites.php)
- Adatbázis séma javításai és új táblák (jegyzetar.sql frissítések, favorites tábla)
- Jegyzet részletek oldal (note.php) és kapcsolódó funkciók (kommentelés, értékelés)
- Footer és regisztrációs folyamat módosításai
- Feltöltési logika javításai (upload.php)
- UI/Design finomhangolások (gradient csökkentés, letisztult megjelenés)

### 9.2. Csontos Kincső Anasztázia

- Jelentés/report rendszer bevezetése (új gombok, admin kezelőfelület)
- UI/Design finomhangolások (gradient csökkentés, letisztult megjelenés)
- Adatbázis helper függvények (db_prepared, db_stmt, db_query) és biztonságos SQL kezelése
- Profil oldal bővítése (előre beállított témák, bemutatkozás, egyedi CSS kérelmek, kítűzők megjelenítése)
- Multilanguage rendszer (lang.php, languages és translations táblák)
- Discord OAuth integráció (vendor mappa, .env, login gomb)
- Születésnapi élmény funkciók (animált keret, üzenetek)
- Vendég mód engedélyezése (főoldal elérhető bejelentkezés nélkül)
- Teljes felület újradizájnolása (Aurora UI stílus, reszponzív layout)
- Kereső funkció bővítése (több mezős keresés, pontosabb találatok)
- Értékelés rendszer javításai (adatbázisba mentés, egyszeri értékelés)
- Biztonsági frissítések (SQL injection védelem, prepared statements)
- Dokumentáció

### 9.3. Szekeres Levente

- Csoport funkciók teljes implementációja (group.php, groups.php, create_group.php, group_init.php)
- Csoporttagság kezelése (tulajdonos, elfogadott, függőben lévő tagok)
- Csoporton belüli jegyzetfeltöltés és moderáció
- Csoport integráció a keresésbe, értesítésekbe és navigációba
- UI/Design finomhangolások (gradient csökkentés, letisztult megjelenés)

<div style="page-break-before: always;"></div>

## 10. Licensz
Ez a projekt saját projektmunkás licensz alatt áll. A forráskód és a dokumentáció kizárólag oktatási célokra használható fel, kereskedelmi felhasználásra nem engedélyezett.

A felhasználók vállalják, hogy nem töltenek fel jogvédett tartalmat.
