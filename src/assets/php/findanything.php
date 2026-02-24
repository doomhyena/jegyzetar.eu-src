<?php

	// Adatbázis kapcsolat betöltése
	require "db.php";
	
	// ADMIN USER KERESŐ MÓD (admin panelhez)
    if (isset($_GET['mode']) && $_GET['mode'] === 'admin_users') {

        $keresett_admin = isset($_GET['keresett']) ? trim((string)$_GET['keresett']) : '';

        $adminId = (int)$_COOKIE['id'];

        // admin jog check
        $adminRes = $conn->query("SELECT admin FROM users WHERE id = $adminId LIMIT 1");
        $adminRow = $adminRes ? $adminRes->fetch_assoc() : null;

        if (!$adminRow || (int)$adminRow['admin'] !== 1) {
            http_response_code(403);
            exit("Nincs jogosultság.");
        }

        if ($keresett_admin === '') {
            exit('<p class="search-text">Kezdj el gépelni...</p>');
        }

        $safe = $conn->real_escape_string($keresett_admin);

        $sql = "
            SELECT id, username, email, firstname, lastname, admin, teacher
            FROM users
            WHERE (username LIKE '%$safe%' OR email LIKE '%$safe%' OR firstname LIKE '%$safe%' OR lastname LIKE '%$safe%')
            ORDER BY id DESC
            LIMIT 20
        ";
        $res = $conn->query($sql);

        if (!$res || $res->num_rows === 0) {
            exit('<p class="search-text">Nincs találat.</p>');
        }

        while ($u = $res->fetch_assoc()) {

            $nev = trim(($u['lastname'] ?? '') . ' ' . ($u['firstname'] ?? ''));
            if ($nev === '') $nev = '—';

            $rang = 'Diák';
            if ((int)$u['admin'] === 1) $rang = 'Admin';
            else if (isset($u['teacher']) && (int)$u['teacher'] === 1) $rang = 'Tanár';

            echo '<div class="search-card search-user-card">';
            echo '<div class="search-content">';
            echo '<p><b>@' . htmlspecialchars($u['username']) . '</b> • ' . htmlspecialchars($nev) . '</p>';
            echo '<small>' . htmlspecialchars($u['email']) . ' • Jelenlegi rang: <b>' . $rang . '</b></small>';
            echo '</div>';

            echo '<form method="post" action="admin_panel.php" style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">';
            echo '<input type="hidden" name="role_action" value="set_role">';
            echo '<input type="hidden" name="target_user_id" value="' . (int)$u['id'] . '">';

            echo '<select name="new_role" class="input" required>';
            echo '<option value="diak">Diák</option>';
            echo '<option value="tanar">Tanár</option>';
            echo '<option value="admin">Admin</option>';
            echo '</select>';

            echo '<button type="submit" class="btn-cta">Rang mentése</button>';
            echo '</form>';

            echo '</div>';
        }

        exit();
    }

	// Keresett kifejezés lekérése, megtisztítása
	$keresett = isset($_GET['keresett']) ? htmlspecialchars(trim($_GET['keresett'])) : '';

	// Bejelentkezett felhasználó azonosítójának lekérése sütiből
	$loggedInUserId = $_COOKIE['id'] ?? 0;

	// Fájlok kereséséhez SQL lekérdezés alapja
	$sqlFiles = "SELECT * FROM files WHERE name LIKE '%$keresett%'";

	// Feltételek tömb inicializálása
	$conditions = [];

	// Ha van keresett kifejezés, feltétel hozzáadása név, tantárgy vagy címke alapján
	if (!empty($keresett)) {
		$safeKeresett = $conn->real_escape_string($keresett);
		$conditions[] = "(name LIKE '%$safeKeresett%' OR subject LIKE '%$safeKeresett%' OR tags LIKE '%$safeKeresett%')";
	}

	// Ha van értékelés szűrő, feltétel hozzáadása
	if (isset($_GET['rating']) && $_GET['rating'] !== '') {
		$rating = (int)$_GET['rating'];
		$conditions[] = "rating = $rating";
	}
	// Ha nincs keresés, ne adjon vissza semmit
	if (empty($conditions)) {
    exit('<p class="search-text">Kezdj el gépelni...</p>');
}

	// WHERE záradék összeállítása a feltételekből
	$whereClause = '';
	if (!empty($conditions)) {
		$whereClause = 'WHERE ' . implode(' AND ', $conditions);
	}

	// Fájlok lekérdezése a feltételek alapján
	$sqlFiles = "SELECT * FROM files $whereClause";
	$resultFiles = $conn->query($sqlFiles);

	// norbi: letöltés -> ugráss jegyzegre
	// Talált fájlok kilistázása, jegyzetre ugrás lehetőséggel
	while ($file = $resultFiles->fetch_assoc()) {
echo '
<div class="search-card">
    <div class="search-content">
        <p>' . htmlspecialchars($file['name']) . '</p>
    </div>
    <a class="note-link" href="/Jegyzetar/note.php?id=' . (int)$file['id'] . '" title="Ugrás a jegyzetre">
        <svg class="icon icon-external" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Ugrás a jegyzetre
    </a>
</div>';
	}

	// Felhasználók keresése a keresett név alapján, kivéve a bejelentkezett felhasználót
	$sqlUsers = "SELECT * FROM users WHERE username LIKE '%$keresett%' AND id != $loggedInUserId";
	$resultUsers = $conn->query($sqlUsers);

	while ($user = $resultUsers->fetch_assoc()) {
		$userId = (int)$user['id'];

		// Ellenőrzi, hogy már barátok-e
		$sqlFriendCheck = "SELECT * FROM friends WHERE (fromid = $loggedInUserId AND toid = $userId) OR (fromid = $userId AND toid = $loggedInUserId)";
		$friendCheck = $conn->query($sqlFriendCheck);

		echo '<form class="user search-card search-user-card" method="post" action="assets/php/add_friend.php">';
		echo '<p>' . htmlspecialchars($user['username']) . '</p>';

		// Ha még nem barátok, megjeleníti a jelölés gombot
		if ($friendCheck->num_rows === 0) {
			echo '<input type="hidden" name="toid" value="' . $userId . '">';
			echo '<button type="submit" class="friend-btn" name="add-friend-btn" onclick="this.classList.add(\'added\')">Jelölés</button>';
		} else {
			echo '<span style="color:green;">✔ Már barátok vagytok</span>';
		}

		echo '</form>';
	}
?>