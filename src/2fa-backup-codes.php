<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require_once "assets/php/db.php";
    require_once "assets/php/lang.php";
    require_once "assets/php/functions.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $userId = (int)$_COOKIE['id'];

    // Csak saját backup kódok megtekintése
    $userRes = db_query($conn, "SELECT id, username, twofa_enabled FROM users WHERE id = ? LIMIT 1", "i", [$userId]);
    if (!$userRes || $userRes->num_rows === 0) {
        http_response_code(404);
        exit(t('msg_user_not_found'));
    }

    $user = $userRes->fetch_assoc();
    
    if (!$user['twofa_enabled']) {
        header("Location: profile.php?user=" . urlencode($user['username']));
        exit;
    }

    $codesRes = db_query($conn, "SELECT id, used, created_at, used_at FROM 2fa_backup_codes WHERE userid = ? ORDER BY created_at DESC", "i", [$userId]);
    $codes = [];
    $usedCount = 0;
    $unusedCount = 0;
    
    if ($codesRes) {
        while ($row = $codesRes->fetch_assoc()) {
            $codes[] = $row;
            if ($row['used']) {
                $usedCount++;
            } else {
                $unusedCount++;
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <title>2FA Backup Kódok Státusz</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body class="no-ads">
    <?php include 'assets/php/navbar.php'; ?>
    <div class="content-wrapper">
        <div class="main max-w-2xl mx-auto">
            <div class="card">
                <h1>2FA Backup Kódok Státusz</h1>
                <p class="entry-meta">Ezek a kódok segítségével tudsz bejelentkezni, ha nem fér hozzá az emailedhez.</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin: 20px 0;">
                    <div style="padding: 16px; border-radius: 12px; background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.35);">
                        <div style="font-size: 24px; font-weight: bold; color: #86efac;"><?= htmlspecialchars($unusedCount) ?></div>
                        <p style="margin: 4px 0 0 0; color: #86efac; font-size: 13px;">Felhasználatlan</p>
                    </div>
                    <div style="padding: 16px; border-radius: 12px; background: rgba(107, 114, 128, 0.12); border: 1px solid rgba(107, 114, 128, 0.35);">
                        <div style="font-size: 24px; font-weight: bold; color: #9ca3af;"><?= htmlspecialchars($usedCount) ?></div>
                        <p style="margin: 4px 0 0 0; color: #9ca3af; font-size: 13px;">Felhasznált</p>
                    </div>
                </div>

                <h3 style="margin-top: 24px;">Kódok státusza</h3>
                <div style="margin-top: 12px;">
                    <?php if (!empty($codes)): ?>
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                    <th style="padding: 8px; text-align: left; color: #cbd5e1;">Kód ID</th>
                                    <th style="padding: 8px; text-align: left; color: #cbd5e1;">Státusz</th>
                                    <th style="padding: 8px; text-align: left; color: #cbd5e1;">Létrehozva</th>
                                    <th style="padding: 8px; text-align: left; color: #cbd5e1;">Használva</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($codes as $code): ?>
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                        <td style="padding: 8px; color: #e5e7eb;"><code>BC-<?= str_pad($code['id'], 5, '0', STR_PAD_LEFT) ?></code></td>
                                        <td style="padding: 8px;">
                                            <?php if ($code['used']): ?>
                                                <span style="display: inline-block; padding: 2px 8px; background: rgba(245,158,11,0.2); border: 1px solid rgba(245,158,11,0.5); border-radius: 4px; color: #fde68a; font-size: 11px;">FELHASZNÁLT</span>
                                            <?php else: ?>
                                                <span style="display: inline-block; padding: 2px 8px; background: rgba(34,197,94,0.2); border: 1px solid rgba(34,197,94,0.5); border-radius: 4px; color: #86efac; font-size: 11px;">AKTÍV</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 8px; color: #cbd5e1; font-size: 12px;">
                                            <?= date('Y-m-d H:i', strtotime($code['created_at'])) ?>
                                        </td>
                                        <td style="padding: 8px; color: #cbd5e1; font-size: 12px;">
                                            <?= $code['used'] && $code['used_at'] ? date('Y-m-d H:i', strtotime($code['used_at'])) : '—' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="entry-meta">Nincsenek backup kódok. Engedélyezd a 2FA-t a profil beállításaidban.</p>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 24px; padding: 16px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.35);">
                    <p style="margin: 0; color: #fde68a; font-size: 13px;">
                        ⚠️ <strong>Fontos:</strong> Ha kevés az aktív kódod (&lt; 3), új kódokat generálhatsz a profil beállításaidban a 2FA letiltása után ismételt engedélyezéshez.
                    </p>
                </div>

                <div style="margin-top: 20px; display: flex; gap: 12px;">
                    <a href="profile.php?user=<?= urlencode($user['username']) ?>" class="btn-cta" style="text-decoration: none; display: inline-block;">
                        ← Vissza a profilra
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>
