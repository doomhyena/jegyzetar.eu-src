# Jegyzetár – Fejlesztői dokumentáció

> Online jegyzetmegosztó platform diákoknak.

## Áttekintés

A **Jegyzetár** egy webalapú platform, amely lehetővé teszi a felhasználók számára jegyzetek megosztását és letöltését. A projekt célja, hogy egyszerű és hatékony eszközt biztosítson a tanulók és szakemberek számára a tudásmegosztáshoz.

---

## Használt technológiák

| Rész              | Technológia  |
| ----------------- | ------------ |
| **Frontend**      | React.js     |
| **Backend**       | PHP          |
| **Adatbázis**     | MySQL        |
| **Verziókezelés** | Git + GitHub |
| **Hosting**       | Rackhost     |

---

## Telepítés és Használat

A részletes telepítési és használati útmutató megtalálható a projekt dokumentációjában.

---

## Készítette

**Jegyzetár fejlesztőcsapat**

- Baranyi Norbert
- Csontos Kincső Anasztázia
- Szekeres Levente

---

## Licenc

Ez a projekt saját projektmunkás licensz alatt áll. A forráskód és a dokumentáció kizárólag oktatási célokra használható fel, kereskedelmi felhasználása nem engedélyezett.

#### 8.6.x. x.php

**Oldal neve:** `X.php`
**Cél:** röviden leírni, mire való az oldal.

**Elérés / route:**

* URL: `.../X.php`
* Belépés szükséges? (Guest/User/Admin)

**Bemenetek (inputok):**

* GET: pl. `id`, `q`, `page`
* POST: pl. `title`, `description`, `file`
* Session: pl. `user_id`, `admin`

**Folyamat (lépések):**

1. Jogosultság ellenőrzés (ha kell: redirect + üzenet).
2. Input validálás (üres mezők, típusok, határok).
3. DB művelet(ek) `db_prepared()` használatával.
4. Kimenet:

   * siker: render / redirect + success message
   * hiba: hibaüzenet + állapot visszajelzés

**UI állapotok / felhasználói visszajelzés:**

* Loading (ha van)
* Empty state (pl. nincs találat)
* Error state (pl. DB hiba, tiltott fájl)
* Success state (pl. feltöltés kész)

**Biztonság:**

* SQL injection védelem: `db_prepared()`
* XSS védelem: `htmlspecialchars()`
* Fájl esetén: whitelist, max méret, path traversal védelem
* (Ha van) CSRF token

**Elfogadási kritériumok (tesztelhető):**

* „Hibás inputra nem indul DB írás”
* „Siker esetén pontos redirect történik”
* „Jogosulatlan user nem látja a tartalmat”