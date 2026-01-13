<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require "assets/php/db.php";
    require "assets/php/lang.php";
    require_once "assets/php/functions.php";
    require "assets/php/group_init.php";
    require "assets/php/group_actions.php";
    include "assets/php/ads.php";

$hiba_uzenet = "";
    if ($privat == 1 && !$aktualis_felhasznalo_tag && !$aktualis_felhasznalo_tulaj) {
        $hiba_uzenet = "Ez egy privát csoport. A tartalom csak tagoknak látható.";
    }
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <title><?= htmlspecialchars($csoport_nev) ?> – Jegyzetár csoport</title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= t('meta_description_home') ?>">
    <meta name="keywords" content="<?= t('meta_keywords_home') ?>">
    <meta name='author' content='Baranyi Norbert, Csontos Kincső, Szekeres Levente'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="content-wrapper">
    <?php include "assets/php/ads.php"; ?>
    <div class="main">
        <div class="section-titlebar">
            <h1><?= htmlspecialchars($csoport_nev) ?></h1>
                <span class="entry-meta">
                    <?php if ($privat == 1): ?>
                        Privát csoport
                    <?php else: ?>
                        Nyilvános csoport
                    <?php endif; ?>
                </span>
            </div>
        <section class="card">
            <?php if ($csoport_leiras != ""): ?>
                <p><?= nl2br(htmlspecialchars($csoport_leiras)) ?></p>
            <?php else: ?>
                <p class="entry-meta">Ehhez a csoporthoz még nincs leírás megadva.</p>
            <?php endif; ?>

            <?php if ($hiba_uzenet != ""): ?>
                <p class="entry-meta" style="color:var(--danger); margin-top:8px;">
                    <?= htmlspecialchars($hiba_uzenet) ?>
                </p>
            <?php endif; ?>
            <div class="profile-actions" style="margin-top:16px; display:flex; gap:8px; flex-wrap:wrap;">
                <?php if (!$aktualis_felhasznalo_tag && !$aktualis_felhasznalo_pending): ?>
                    <form method="post">
                        <button type="submit" name="join_group" class="btn-cta">
                            Csatlakozás a csoporthoz
                        </button>
                    </form>
                <?php endif; ?>
                <?php if ($aktualis_felhasznalo_pending): ?>
                    <span class="entry-meta">
                        Csatlakozási kérelmed függőben van.
                    </span>
                <?php endif; ?>
                <?php if ($aktualis_felhasznalo_tag && !$aktualis_felhasznalo_tulaj): ?>
                    <form method="post">
                        <button type="submit" name="kilepes" class="btn-ghost">
                            Kilépés a csoportból
                        </button>
                    </form>
                    <form method="post" action="assets/php/report.php" style="display:inline-block; max-width: 240px;">
                        <input type="hidden" name="type" value="group">
                        <input type="hidden" name="target_id" value="<?= (int)$csoport_id ?>">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') ?>">
                        <textarea name="reason" rows="2" placeholder="Miért jelented a csoportot? (nem kötelező)" style="width: 100%; resize: vertical; margin-bottom: 4px;"></textarea>
                        <button type="submit" class="btn-ghost" onclick="return confirm('Biztosan jelenteni szeretnéd ezt a csoportot?');">
                            ⚠ Csoport jelentése
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
        <section class="card">
            <h2>Tagok</h2>
            <?php if ($hiba_uzenet == "" && ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj)): ?>
                <?php
                    $tagok_lekerdezes = db_query($conn, "SELECT group_members.*, users.username FROM group_members, users WHERE group_members.user_id = users.id AND group_members.group_id = ?  AND group_members.status = 'accepted'", "i", [$csoport_id]);
                ?>
                <?php if ($tagok_lekerdezes && $tagok_lekerdezes->num_rows > 0): ?>
                    <div class="list-compact">
                        <?php while ($egy_tag = $tagok_lekerdezes->fetch_assoc()):
                                $tag_id = $egy_tag['user_id'];
                                $tag_nev = $egy_tag['username'];
                                $tag_szerep = $egy_tag['role'];

                                if ($tag_szerep == "owner") {
                                    $szerep_kiir = "tulajdonos";
                                } else {
                                    $szerep_kiir = "tag";
                                }
                            ?>
                            <article class="mini-card">
                                <div class="mini-main">
                                    <h4 class="mini-title">@<?= htmlspecialchars($tag_nev) ?></h4>
                                    <p class="mini-meta"><?= htmlspecialchars($szerep_kiir) ?></p>
                                </div>
                                <?php if ($aktualis_felhasznalo_tulaj && $tag_id != $tulaj_id): ?>
                                    <form method="post">
                                        <input type="hidden" name="remove_user_id" value="<?= (int)$tag_id ?>">
                                        <button type="submit" name="remove_member" class="btn-ghost">
                                            Eltávolítás
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="entry-meta">Még nincsenek tagok ebben a csoportban.</p>
                <?php endif; ?>
                <?php if ($aktualis_felhasznalo_tulaj): ?>
                    <hr style="margin:16px 0;">
                    <h3>Függőben lévő jelentkezések</h3>
                    <?php
                        $pending_tagok_lekerdezes = db_query($conn, "SELECT group_members.*, users.username FROM group_members, users WHERE group_members.user_id = users.id AND group_members.group_id = ? AND group_members.status = 'pending'", "i", [$csoport_id]);
                    ?>
                    <?php if ($pending_tagok_lekerdezes && $pending_tagok_lekerdezes->num_rows > 0): ?>
                        <div class="list-compact">
                            <?php while ($egy_pending = $pending_tagok_lekerdezes->fetch_assoc()):
                                $pending_tag_id  = $egy_pending['user_id'];
                                $pending_tag_nev = $egy_pending['username'];
                            ?>
                                <article class="mini-card">
                                    <div class="mini-main">
                                        <h4 class="mini-title">@<?= htmlspecialchars($pending_tag_nev) ?></h4>
                                        <p class="mini-meta">függőben</p>
                                    </div>
                                    <div style="display:flex;gap:6px;">
                                        <form method="post">
                                            <input type="hidden" name="kezelt_user_id" value="<?= (int)$pending_tag_id ?>">
                                            <button type="submit" name="elfogadas" class="btn-cta">
                                                Elfogadás
                                            </button>
                                        </form>
                                        <form method="post">
                                            <input type="hidden" name="kezelt_user_id" value="<?= (int)$pending_tag_id ?>">
                                            <button type="submit" name="elutasitas" class="btn-ghost">
                                                Elutasítás
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="entry-meta">Nincsenek függőben lévő jelentkezések.</p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <p class="entry-meta">A tagok listája csak tagok és a tulajdonos számára elérhető.</p>
            <?php endif; ?>
            <?php if ($hiba_uzenet == "" && ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj)): ?>
            <section class="card">
                <h2>Csoport jegyzetek</h2>
                <?php if ($aktualis_felhasznalo_tag): ?>
                    <h3>Új jegyzet feltöltése</h3>
                    <form method="post" enctype="multipart/form-data" class="auth-card compact" style="padding:16px; margin-top:8px;">
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Jegyzet neve</label>
                                <input type="text" name="jegyzet_nev" class="input">
                            </div>
                            <div class="form-field">
                                <label>Fájl</label>
                                <input type="file" name="jegyzet_fajl" class="input">
                            </div>
                        </div>
                        <div class="form-field" style="margin-top:10px;">
                            <label>Leírás (nem kötelező)</label>
                            <textarea name="jegyzet_leiras" rows="3" class="input"></textarea>
                        </div>
                        <div class="auth-actions" style="margin-top:12px;">
                            <button type="submit" name="uj_jegyzet" class="btn-cta">
                                Feltöltés a csoportba
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
                <h3 style="margin-top:18px;">Feltöltött jegyzetek</h3>
                <?php
                    $group_files_lekerdezes = db_query($conn, "SELECT group_files.*, users.username FROM group_files, users WHERE group_files.uploaded_by = users.id AND group_files.group_id = ? AND group_files.is_approved = 1 ORDER BY group_files.id DESC", "i", [$csoport_id]);
                ?>
                <?php if ($group_files_lekerdezes && $group_files_lekerdezes->num_rows > 0): ?>
                    <div class="list-compact">
                        <?php while ($egy_jegyzet = $group_files_lekerdezes->fetch_assoc()):
                            $jegyzet_id = $egy_jegyzet['id'];
                            $jegyzet_nev = $egy_jegyzet['name'];
                            $jegyzet_leiras = $egy_jegyzet['description'];
                            $jegyzet_fajlnev = $egy_jegyzet['file_name'];
                            $jegyzet_feltolto = $egy_jegyzet['username'];
                            $fajl_elercim = "users/".$jegyzet_feltolto."/".$jegyzet_fajlnev;
                        ?>
                            <article class="mini-card">
                                <div class="mini-main">
                                    <h4 class="mini-title"><?= htmlspecialchars($jegyzet_nev) ?></h4>
                                    <p class="mini-meta">
                                        @<?= htmlspecialchars($jegyzet_feltolto) ?>
                                    </p>
                                    <?php if ($jegyzet_leiras != ""): ?>
                                        <p class="entry-meta"><?= nl2br(htmlspecialchars($jegyzet_leiras)) ?></p>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= htmlspecialchars($fajl_elercim) ?>" target="_blank" class="mini-download" title="Fájl megnyitása / letöltése">
                                    <svg class="icon icon-download" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="entry-meta">Még nincsenek elfogadott csoport jegyzetek.</p>
                <?php endif; ?>
                <?php if ($aktualis_felhasznalo_tulaj): ?>
                    <hr style="margin:16px 0;">
                    <h3>Elfogadásra váró jegyzetek</h3>
                    <?php
                        $varakozo_jegyzetek = db_query($conn, "SELECT group_files.*, users.username FROM group_files, users WHERE group_files.uploaded_by = users.id AND group_files.group_id = ? AND group_files.is_approved = 0 ORDER BY group_files.id DESC", "i", [$csoport_id]);
                    ?>
                    <?php if ($varakozo_jegyzetek && $varakozo_jegyzetek->num_rows > 0): ?>
                        <div class="list-compact">
                            <?php while ($egy_varakozo = $varakozo_jegyzetek->fetch_assoc()):
                                    $v_jegyzet_id = $egy_varakozo['id'];
                                    $v_jegyzet_nev = $egy_varakozo['name'];
                                    $v_jegyzet_leiras = $egy_varakozo['description'];
                                    $v_jegyzet_fajlnev = $egy_varakozo['file_name'];
                                    $v_jegyzet_feltolto = $egy_varakozo['username'];
                                    $v_fajl_elercim = "users/".$v_jegyzet_feltolto."/".$v_jegyzet_fajlnev;
                                ?>
                                <article class="mini-card">
                                    <div class="mini-main">
                                        <h4 class="mini-title"><?= htmlspecialchars($v_jegyzet_nev) ?></h4>
                                        <p class="mini-meta">
                                            @<?= htmlspecialchars($v_jegyzet_feltolto) ?> – jóváhagyásra vár
                                        </p>
                                        <?php if ($v_jegyzet_leiras != ""): ?>
                                            <p class="entry-meta"><?= nl2br(htmlspecialchars($v_jegyzet_leiras)) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div style="display:flex;gap:6px;align-items:center;">
                                        <a href="<?= htmlspecialchars($v_fajl_elercim) ?>" target="_blank" class="mini-download" title="Fájl megnyitása / letöltése">
                                            <svg class="icon icon-download" viewBox="0 0 24 24" fill="none">
                                                <path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                        <form method="post">
                                            <input type="hidden" name="jegyzet_id" value="<?= (int)$v_jegyzet_id ?>">
                                            <button type="submit" name="jegyzet_elfogadas" class="btn-cta">
                                                Elfogadás
                                            </button>
                                        </form>
                                        <form method="post" onsubmit="return confirm('Biztos elutasítod / törlöd ezt a jegyzetet?');">
                                            <input type="hidden" name="jegyzet_id" value="<?= (int)$v_jegyzet_id ?>">
                                            <button type="submit" name="jegyzet_elutasitas" class="btn-ghost">
                                                Elutasítás
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="entry-meta">Nincs elfogadásra váró jegyzet.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
            <?php endif; ?>
        </section>
        <div style="margin-top:16px;">
            <a href="groups.php" class="btn-ghost">Vissza a csoportokhoz</a>
        </div>
        <?php if ($aktualis_felhasznalo_tulaj): ?>
        <form method="post" onsubmit="return confirm('Biztosan törölni szeretnéd a csoportot? Ez a művelet nem visszavonható.');">
            <button type="submit" name="csoport_torles" class="btn-ghost">
                Csoport törlése
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
