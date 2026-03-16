<?php
if (isset($_POST['join_group']) && !$aktualis_felhasznalo_tag) {

    if ($aktualis_felhasznalo_pending) {

        echo "<script>alert('Már van egy függőben lévő csatlakozási kérelmed.');</script>";

    } else {

        if ($privat == 1) {
            $status = 'pending';
        } else {
            $status = 'accepted';
        }

        $csatlakozas_sql = "
            INSERT INTO group_members (group_id, user_id, role, status)
            VALUES ('$csoport_id', '$aktualis_felhasznalo_id', 'member', '$status')
        ";

        if ($conn->query($csatlakozas_sql)) {

            if ($status == 'accepted') {
                $aktualis_felhasznalo_tag = true;
                echo "<script>alert('Sikeresen csatlakoztál a csoporthoz.');</script>";
            } else {
                $aktualis_felhasznalo_pending = true;
                echo "<script>alert('Csatlakozási kérelmed elküldve, jóváhagyásra vár.');</script>";
            }

        } else {
            echo "<script>alert('Hiba történt csatlakozáskor.');</script>";
        }
    }
}

// KOMMENT FAL
if (isset($_POST['uj_komment'])) {
    $komment_szoveg = trim($_POST['komment_szoveg'] ?? '');

    if (($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj) && $komment_szoveg !== '') {
        db_exec(
            $conn,
            "INSERT INTO group_comments (group_id, user_id, comment_text) VALUES (?, ?, ?)",
            "iis",
            [$csoport_id, $aktualis_felhasznalo_id, $komment_szoveg]
        );
    }

    header("Location: group.php?id=" . $csoport_id);
    exit;
}

// KOMMENT TÖRLÉS
if (isset($_POST['komment_torles'])) {

    $torlendo_komment_id = (int)($_POST['komment_id'] ?? 0);

    if ($torlendo_komment_id > 0) {

        $komment_lekerdezes = db_query(
            $conn,
            "SELECT * FROM group_comments WHERE id = ? AND group_id = ? LIMIT 1",
            "ii",
            [$torlendo_komment_id, $csoport_id]
        );

        if ($komment_lekerdezes && $komment_lekerdezes->num_rows > 0) {
            $komment_sor = $komment_lekerdezes->fetch_assoc();

            $komment_iro_id = (int)$komment_sor['user_id'];
            $aktualis_id = (int)$aktualis_felhasznalo_id;

            $sajat_komment = ($komment_iro_id === $aktualis_id);
            $tulaj_torolhet = $aktualis_felhasznalo_tulaj;
            $admin_torolhet = (isset($user['admin']) && (int)$user['admin'] === 1);

            if ($sajat_komment || $tulaj_torolhet || $admin_torolhet) {
                db_exec(
                    $conn,
                    "DELETE FROM group_comments WHERE id = ? AND group_id = ?",
                    "ii",
                    [$torlendo_komment_id, $csoport_id]
                );

                echo "<script>alert('A komment törölve lett.');</script>";
            } else {
                echo "<script>alert('Nincs jogosultságod ennek a kommentnek a törléséhez.');</script>";
            }
        }
    }

    header("Location: group.php?id=" . $csoport_id);
    exit;
}

// ESEMÉNY HOZZÁADÁS
if (isset($_POST['uj_esemeny']) && ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj)) {

    $title = trim($_POST['event_title'] ?? '');
    $description = trim($_POST['event_desc'] ?? '');
    $date = $_POST['event_date'] ?? '';

    if ($title == "" || $date == "") {
        echo "<script>alert('Az esemény neve és dátuma kötelező!');</script>";
    } else {

        db_exec(
            $conn,
            "INSERT INTO group_events (group_id, created_by, title, description, event_date) VALUES (?, ?, ?, ?, ?)",
            "iisss",
            [$csoport_id, $aktualis_felhasznalo_id, $title, $description, $date]
        );

        echo "<script>alert('Esemény hozzáadva!');</script>";
    }

    header("Location: group.php?id=".$csoport_id);
    exit;
}

// FLASHCARD HOZZÁADÁS
if (isset($_POST['flashcard_add']) && ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj)) {

    $kerdes = trim((string)($_POST['flash_q'] ?? ''));
    $valasz = trim((string)($_POST['flash_a'] ?? ''));

    if ($kerdes === '' || $valasz === '') {
        echo "<script>alert('A kérdés és a válasz kötelező!');</script>";
    } else {

        $maxRes = $conn->query("SELECT MAX(id) AS max_id FROM group_flashcards");
        $maxRow = $maxRes ? $maxRes->fetch_assoc() : null;
        $uj_id = (int)($maxRow['max_id'] ?? 0) + 1;

        $kerdes_safe = $conn->real_escape_string($kerdes);
        $valasz_safe = $conn->real_escape_string($valasz);

        $sql = "INSERT INTO group_flashcards (id, group_id, created_by, question, answer, created_at) VALUES ('$uj_id', '$csoport_id', '$aktualis_felhasznalo_id','$kerdes_safe', '$valasz_safe', NOW())";

        if ($conn->query($sql)) {
            echo "<script>alert('Flashcard hozzáadva!');</script>";
            echo "<script>location.href='group.php?id=".$csoport_id."';</script>";
            exit;
        } else {
            echo "<script>alert('Hiba történt a mentés során.');</script>";
        }
    }
}

// FLASHCARD TÖRLÉS
if (isset($_POST['flashcard_delete']) && $aktualis_felhasznalo_tulaj) {

    $delete_id = (int)$_POST['flashcard_id'];

    $conn->query("DELETE FROM group_flashcards WHERE id = '$delete_id' AND group_id = '$csoport_id'");

    echo "<script>location.href='group.php?id=".$csoport_id."';</script>";
    exit;
}

// FLASHCARD ÉRTÉKELÉS
if (isset($_POST['flashcard_mark'])) {

    $flashcard_id = (int)$_POST['flashcard_id'];
    $type = $_POST['flashcard_mark'];

    if ($type === 'correct') {
        $conn->query("UPDATE group_flashcards SET correct_count = correct_count + 1 WHERE id = '$flashcard_id'");
    }

    if ($type === 'wrong') {
        $conn->query("UPDATE group_flashcards SET wrong_count = wrong_count + 1 WHERE id = '$flashcard_id'");
    }

    header("Location: group.php?id=".$csoport_id."");
    exit;
}

// POLL LÉTREHOZÁS
if (isset($_POST['uj_poll']) && $aktualis_felhasznalo_tulaj) {

    $kerdes = trim($_POST['poll_question'] ?? '');
    $opt1 = trim($_POST['opt1'] ?? '');
    $opt2 = trim($_POST['opt2'] ?? '');
    $opt3 = trim($_POST['opt3'] ?? '');

    if ($kerdes == "" || $opt1 == "" || $opt2 == "") {
        echo "<script>alert('Legalább 2 opció kell!');</script>";
    } else {

        $conn->query("
            INSERT INTO group_polls (group_id, created_by, question)
            VALUES ('$csoport_id', '$aktualis_felhasznalo_id', '$kerdes')
        ");

        $poll_id = $conn->insert_id;

        $conn->query("INSERT INTO group_poll_options (poll_id, option_text) VALUES ('$poll_id','$opt1')");
        $conn->query("INSERT INTO group_poll_options (poll_id, option_text) VALUES ('$poll_id','$opt2')");

        if ($opt3 != "") {
            $conn->query("INSERT INTO group_poll_options (poll_id, option_text) VALUES ('$poll_id','$opt3')");
        }

        echo "<script>alert('Szavazás létrehozva!');</script>";
    }

    header("Location: group.php?id=".$csoport_id);
    exit;
}

// POLL LEZÁRÁSA
if (isset($_POST['poll_close']) && $aktualis_felhasznalo_tulaj) {

    $poll_id = (int)$_POST['poll_id'];

    $conn->query("
        UPDATE group_polls
        SET closed = 1
        WHERE id='$poll_id'
        AND group_id='$csoport_id'
    ");

    echo "<script>alert('Szavazás lezárva!');</script>";

    header("Location: group.php?id=".$csoport_id);
    exit;
}

// SZAVAZÁS
if (isset($_POST['poll_vote'])) {

    $option_id = (int)$_POST['option_id'];
    $poll_id = (int)$_POST['poll_id'];

    $ellenorzes = $conn->query("
        SELECT id FROM group_poll_votes
        WHERE poll_id='$poll_id'
        AND user_id='$aktualis_felhasznalo_id'
    ");

    if ($ellenorzes->num_rows == 0) {

        $conn->query("
            INSERT INTO group_poll_votes (poll_id, option_id, user_id)
            VALUES ('$poll_id','$option_id','$aktualis_felhasznalo_id')
        ");

        echo "<script>alert('Szavazat rögzítve!');</script>";
    } else {
        echo "<script>alert('Már szavaztál!');</script>";
    }

    header("Location: group.php?id=".$csoport_id);
    exit;
}

// Tag eltávolítása
if (isset($_POST['remove_member']) && $aktualis_felhasznalo_tulaj) {

    $torlendo_felhasznalo_id = $_POST['remove_user_id'];

    if ($torlendo_felhasznalo_id != $tulaj_id) {

        $torles_sql = "
            DELETE FROM group_members
            WHERE group_id='$csoport_id'
              AND user_id='$torlendo_felhasznalo_id'
        ";
        $conn->query($torles_sql);
    }
}


    // FÜGGŐBEN LÉVŐ JELENTKEZÉSEK (ELFOGADÁS / ELUTASÍTÁS)

if (isset($_POST['elfogadas']) && $aktualis_felhasznalo_tulaj) {

    $kezelt_felhasznalo_id = $_POST['kezelt_user_id'];

    $elfogadas_sql = "
        UPDATE group_members
        SET status='accepted'
        WHERE group_id='$csoport_id'
          AND user_id='$kezelt_felhasznalo_id'
          AND status='pending'
    ";

    if ($conn->query($elfogadas_sql)) {
        echo "<script>alert('Jelentkezés elfogadva.');</script>";
    } else {
        echo "<script>alert('Hiba történt az elfogadásnál.');</script>";
    }
}

if (isset($_POST['elutasitas']) && $aktualis_felhasznalo_tulaj) {

    $kezelt_felhasznalo_id = $_POST['kezelt_user_id'];

    $elutasitas_sql = "
        DELETE FROM group_members
        WHERE group_id='$csoport_id'
          AND user_id='$kezelt_felhasznalo_id'
          AND status='pending'
    ";

    if ($conn->query($elutasitas_sql)) {
        echo "<script>alert('Jelentkezés elutasítva.');</script>";
    } else {
        echo "<script>alert('Hiba történt az elutasításnál.');</script>";
    }
}


    // JEGYZET ELFOGADÁSA / ELUTASÍTÁSA

if ($aktualis_felhasznalo_tulaj && isset($_POST['jegyzet_elfogadas'])) {

    $kezelt_jegyzet_id = (int)$_POST['jegyzet_id'];

    $jegyzet_elfogadas_sql = "
        UPDATE group_files
        SET is_approved = 1
        WHERE id = '$kezelt_jegyzet_id'
          AND group_id = '$csoport_id'
    ";

    if ($conn->query($jegyzet_elfogadas_sql)) {
        echo "<script>alert('A jegyzet elfogadva.');</script>";
    } else {
        echo "<script>alert('Hiba történt a jegyzet elfogadásakor.');</script>";
    }
}

if ($aktualis_felhasznalo_tulaj && isset($_POST['jegyzet_elutasitas'])) {

    $kezelt_jegyzet_id = (int)$_POST['jegyzet_id'];

    $jegyzet_elutasitas_sql = "
        DELETE FROM group_files
        WHERE id = '$kezelt_jegyzet_id'
          AND group_id = '$csoport_id'
    ";

    if ($conn->query($jegyzet_elutasitas_sql)) {
        echo "<script>alert('A jegyzet elutasítva / törölve.');</script>";
    } else {
        echo "<script>alert('Hiba történt a jegyzet elutasításakor!');</script>";
    }
}

if (isset($_POST['uj_jegyzet']) && ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj)) {

    $jegyzet_nev    = $_POST['jegyzet_nev'];
    $jegyzet_leiras = $_POST['jegyzet_leiras'];

    if ($jegyzet_nev == "" || !isset($_FILES['jegyzet_fajl']) || $_FILES['jegyzet_fajl']['name'] == "") {

        echo "<script>alert('A jegyzet neve és a fájl kötelező.');</script>";

    } else {

        // aktuális user felhasználónév
        $user_lekerdezes = $conn->query("
            SELECT username 
            FROM users 
            WHERE id='$aktualis_felhasznalo_id' 
            LIMIT 1
        ");

        if ($user_lekerdezes && $user_lekerdezes->num_rows > 0) {
            $user_sor = $user_lekerdezes->fetch_assoc();
            $feltolto_felhasznalonev = $user_sor['username'];
        } else {
            $feltolto_felhasznalonev = "ismeretlen";
        }

        $cel_mappa = "users/".$feltolto_felhasznalonev."/";

        if (!is_dir($cel_mappa)) {
            mkdir($cel_mappa, 0777, true);
        }

        $eredeti_fajlnev = $_FILES['jegyzet_fajl']['name'];
        $atmeneti_nev    = $_FILES['jegyzet_fajl']['tmp_name'];

        $vegleges_utvonal = $cel_mappa.$eredeti_fajlnev;

        if (move_uploaded_file($atmeneti_nev, $vegleges_utvonal)) {

            if ($aktualis_felhasznalo_tulaj) {
                $jegyzet_allapot = 1; // azonnal elfogadott
            } else {
                $jegyzet_allapot = 0; // jóváhagyásra vár
            }

            $beszuras_sql = "
                INSERT INTO group_files (group_id, uploaded_by, name, description, file_name, created_at, is_approved)
                VALUES ('$csoport_id', '$aktualis_felhasznalo_id', '$jegyzet_nev', '$jegyzet_leiras', '$eredeti_fajlnev', NOW(), '$jegyzet_allapot')
            ";

            if ($conn->query($beszuras_sql)) {
                if ($aktualis_felhasznalo_tulaj) {
                    echo "<script>alert('Jegyzet sikeresen feltöltve a csoportba.');</script>";
                } else {
                    echo "<script>alert('Jegyzet feltöltve, jóváhagyásra vár a csoport tulajdonosánál.');</script>";
                }
            } else {
                echo "<script>alert('A jegyzet adatainak mentése nem sikerült.');</script>";
            }

        } else {
            echo "<script>alert('Hiba történt a fájl feltöltésekor.');</script>";
        }
    }
}

// Kilépés (nem tulaj)
if (isset($_POST['kilepes'])) {

    if ($aktualis_felhasznalo_id == $tulaj_id) {

        echo "<script>alert('A tulajdonos nem léphet ki a saját csoportjából.');</script>";

    } else {

        $kilepes_sql = "
            DELETE FROM group_members
            WHERE group_id='$csoport_id'
              AND user_id='$aktualis_felhasznalo_id'
        ";

        if ($conn->query($kilepes_sql)) {
            $aktualis_felhasznalo_tag = false;
            $aktualis_felhasznalo_pending = false;
            echo "<script>alert('Sikeresen kiléptél a csoportból.');</script>";
        } else {
            echo "<script>alert('Hiba történt a kilépés közben.');</script>";
        }
    }
}

// Csoport törlése
if (isset($_POST['csoport_torles']) && $aktualis_felhasznalo_tulaj) {

    $conn->query("DELETE FROM group_members WHERE group_id='$csoport_id'");
    $conn->query("DELETE FROM group_files   WHERE group_id='$csoport_id'");
    $conn->query("DELETE FROM groups WHERE id='$csoport_id'");

    echo "<script>alert('A csoport sikeresen törölve lett.'); window.location.href='groups.php';</script>";
    exit;
}