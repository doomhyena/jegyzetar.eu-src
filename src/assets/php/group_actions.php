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