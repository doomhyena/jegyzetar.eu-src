<?php
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    require "assets/php/db.php";
    require "assets/php/lang.php";
    require_once "assets/php/functions.php";
    require "assets/php/group_init.php";
	
	$admin = isset($user['admin']) && (int)$user['admin'] === 1;
	$owner = isset($tulaj_id) && isset($_COOKIE['id']) && (int)$tulaj_id === (int)$_COOKIE['id'];
	if (($csoport_statusz ?? 'approved') !== 'approved' && !$admin && !$owner) {
		echo "<script>alert('Ez a csoport még nincs jóváhagyva az admin által.');</script>";
		header("Location: groups.php");
		exit;
	}
	
    require "assets/php/group_actions.php";
    
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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include 'assets/php/navbar.php'; ?>
<div class="content-wrapper w-full">
    <?php include "assets/php/ads.php"; ?>
    <div class="main w-full max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6">
        <div class="section-titlebar mb-6">
            <h1 class="text-2xl md:text-3xl lg:text-4xl mb-2"><?= htmlspecialchars($csoport_nev) ?></h1>
                <span class="entry-meta text-xs md:text-sm inline-block px-2 py-1 rounded bg-white/10">
                    <?php if ($privat == 1): ?>
                        Privát csoport
                    <?php else: ?>
                        Nyilvános csoport
                    <?php endif; ?>
                </span>
            </div>
        <section class="card p-4 md:p-6 mb-6">
            <?php if ($csoport_leiras != ""): ?>
                <p class="text-sm md:text-base break-words"><?= nl2br(htmlspecialchars($csoport_leiras)) ?></p>
            <?php else: ?>
                <p class="entry-meta text-sm md:text-base opacity-75">Ehhez a csoporthoz még nincs leírás megadva.</p>
            <?php endif; ?>

            <?php if ($hiba_uzenet != ""): ?>
                <p class="entry-meta text-sm md:text-base mt-3" style="color:var(--danger);">
                    <?= htmlspecialchars($hiba_uzenet) ?>
                </p>
            <?php endif; ?>
            <div class="profile-actions mt-4 flex flex-wrap gap-2 md:gap-3">
                <?php if (!$aktualis_felhasznalo_tag && !$aktualis_felhasznalo_pending): ?>
                    <form method="post">
                        <button type="submit" name="join_group" class="btn-cta text-sm md:text-base">
                            Csatlakozás a csoporthoz
                        </button>
                    </form>
                <?php endif; ?>
                <?php if ($aktualis_felhasznalo_pending): ?>
                    <span class="entry-meta text-sm md:text-base">
                        Csatlakozási kérelmed függőben van.
                    </span>
                <?php endif; ?>
                <?php if ($aktualis_felhasznalo_tag && !$aktualis_felhasznalo_tulaj): ?>
                    <form method="post">
                        <button type="submit" name="kilepes" class="btn-ghost text-sm md:text-base">
                            Kilépés a csoportból
                        </button>
                    </form>
                    <form method="post" action="assets/php/report.php" class="inline-block w-full md:w-auto max-w-xs">
                        <input type="hidden" name="type" value="group">
                        <input type="hidden" name="target_id" value="<?= (int)$csoport_id ?>">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') ?>">
                        <textarea name="reason" rows="2" placeholder="Miért jelented a csoportot? (nem kötelező)" class="input w-full text-sm md:text-base mb-2"></textarea>
                        <button type="submit" class="btn-ghost text-sm md:text-base w-full" onclick="return confirm('Biztosan jelenteni szeretnéd ezt a csoportot?');">
                            ⚠ Csoport jelentése
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
        <section class="card p-4 md:p-6 mb-6">
            <h2 class="text-xl md:text-2xl mb-4">Tagok</h2>
            <?php if ($hiba_uzenet == "" && ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj)): ?>
                <?php
                    $tagok_lekerdezes = db_query($conn, "SELECT group_members.*, users.username FROM group_members, users WHERE group_members.user_id = users.id AND group_members.group_id = ?  AND group_members.status = 'accepted'", "i", [$csoport_id]);
                ?>
                <?php if ($tagok_lekerdezes && $tagok_lekerdezes->num_rows > 0): ?>
                    <div class="list-compact flex flex-col gap-2 md:gap-3">
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
                            <article class="mini-card p-3 md:p-4 flex items-center justify-between gap-3">
                                <div class="mini-main">
                                    <h4 class="mini-title text-sm md:text-base">@<?= htmlspecialchars($tag_nev) ?></h4>
                                    <p class="mini-meta text-xs md:text-sm"><?= htmlspecialchars($szerep_kiir) ?></p>
                                </div>
                                <?php if ($aktualis_felhasznalo_tulaj && $tag_id != $tulaj_id): ?>
                                    <form method="post">
                                        <input type="hidden" name="remove_user_id" value="<?= (int)$tag_id ?>">
                                        <button type="submit" name="remove_member" class="btn-ghost text-sm md:text-base">
                                            Eltávolítás
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="entry-meta text-sm md:text-base">Még nincsenek tagok ebben a csoportban.</p>
                <?php endif; ?>
                <?php if ($aktualis_felhasznalo_tulaj): ?>
                    <hr class="my-4 md:my-6 border-white/10">
                    <h3 class="text-lg md:text-xl mb-3">Függőben lévő jelentkezések</h3>
                    <?php
                        $pending_tagok_lekerdezes = db_query($conn, "SELECT group_members.*, users.username FROM group_members, users WHERE group_members.user_id = users.id AND group_members.group_id = ? AND group_members.status = 'pending'", "i", [$csoport_id]);
                    ?>
                    <?php if ($pending_tagok_lekerdezes && $pending_tagok_lekerdezes->num_rows > 0): ?>
                        <div class="list-compact flex flex-col gap-2 md:gap-3">
                            <?php while ($egy_pending = $pending_tagok_lekerdezes->fetch_assoc()):
                                $pending_tag_id  = $egy_pending['user_id'];
                                $pending_tag_nev = $egy_pending['username'];
                            ?>
                                <article class="mini-card p-3 md:p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
                                    <div class="mini-main">
                                        <h4 class="mini-title text-sm md:text-base">@<?= htmlspecialchars($pending_tag_nev) ?></h4>
                                        <p class="mini-meta text-xs md:text-sm">függőben</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <form method="post">
                                            <input type="hidden" name="kezelt_user_id" value="<?= (int)$pending_tag_id ?>">
                                            <button type="submit" name="elfogadas" class="btn-cta text-sm md:text-base">
                                                Elfogadás
                                            </button>
                                        </form>
                                        <form method="post">
                                            <input type="hidden" name="kezelt_user_id" value="<?= (int)$pending_tag_id ?>">
                                            <button type="submit" name="elutasitas" class="btn-ghost text-sm md:text-base">
                                                Elutasítás
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="entry-meta text-sm md:text-base">Nincsenek függőben lévő jelentkezések.</p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <p class="entry-meta text-sm md:text-base">A tagok listája csak tagok és a tulajdonos számára elérhető.</p>
            <?php endif; ?>
            <?php if ($hiba_uzenet == "" && ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj)): ?>
            <section class="card p-4 md:p-6 mb-6">
                <h2 class="text-xl md:text-2xl mb-4">Csoport jegyzetek</h2>
                <?php if ($aktualis_felhasznalo_tag): ?>
                    <h3 class="text-lg md:text-xl mb-3">Új jegyzet feltöltése</h3>
                    <form method="post" enctype="multipart/form-data" class="auth-card p-4 md:p-6 mb-4">
                        <div class="form-grid grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-field">
                                <label class="text-sm md:text-base font-semibold">Jegyzet neve</label>
                                <input type="text" name="jegyzet_nev" class="input w-full text-sm md:text-base">
                            </div>
                            <div class="form-field">
                                <label class="text-sm md:text-base font-semibold">Fájl</label>
                                <input type="file" name="jegyzet_fajl" class="input w-full text-sm md:text-base">
                            </div>
                        </div>
                        <div class="form-field mt-3">
                            <label class="text-sm md:text-base font-semibold">Leírás (nem kötelező)</label>
                            <textarea name="jegyzet_leiras" rows="3" class="input w-full text-sm md:text-base"></textarea>
                        </div>
                        <div class="auth-actions mt-4">
                            <button type="submit" name="uj_jegyzet" class="btn-cta text-sm md:text-base">
                                Feltöltés a csoportba
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
                <h3 class="text-lg md:text-xl mb-3">Feltöltött jegyzetek</h3>
                <?php
                    $group_files_lekerdezes = db_query($conn, "SELECT group_files.*, users.username FROM group_files, users WHERE group_files.uploaded_by = users.id AND group_files.group_id = ? AND group_files.is_approved = 1 ORDER BY group_files.id DESC", "i", [$csoport_id]);
                ?>
                <?php if ($group_files_lekerdezes && $group_files_lekerdezes->num_rows > 0): ?>
                    <div class="list-compact flex flex-col gap-2 md:gap-3">
                        <?php while ($egy_jegyzet = $group_files_lekerdezes->fetch_assoc()):
                            $jegyzet_id = $egy_jegyzet['id'];
                            $jegyzet_nev = $egy_jegyzet['name'];
                            $jegyzet_leiras = $egy_jegyzet['description'];
                            $jegyzet_fajlnev = $egy_jegyzet['file_name'];
                            $jegyzet_feltolto = $egy_jegyzet['username'];
                            $fajl_elercim = "users/".$jegyzet_feltolto."/".$jegyzet_fajlnev;
                        ?>
                            <article class="mini-card p-3 md:p-4 flex items-start justify-between gap-3 break-words">
                                <div class="mini-main flex-1 min-w-0">
                                    <h4 class="mini-title text-sm md:text-base truncate"><?= htmlspecialchars($jegyzet_nev) ?></h4>
                                    <p class="mini-meta text-xs md:text-sm">
                                        @<?= htmlspecialchars($jegyzet_feltolto) ?>
                                    </p>
                                    <?php if ($jegyzet_leiras != ""): ?>
                                        <p class="entry-meta text-xs md:text-sm mt-1"><?= nl2br(htmlspecialchars($jegyzet_leiras)) ?></p>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= htmlspecialchars($fajl_elercim) ?>" target="_blank" class="mini-download flex-shrink-0" title="Fájl megnyitása / letöltése">
                                    <svg class="icon icon-download w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="entry-meta text-sm md:text-base">Még nincsenek elfogadott csoport jegyzetek.</p>
                <?php endif; ?>
                <?php if ($aktualis_felhasznalo_tulaj): ?>
                    <hr class="my-4 md:my-6 border-white/10">
                    <h3 class="text-lg md:text-xl mb-3">Elfogadásra váró jegyzetek</h3>
                    <?php
                        $varakozo_jegyzetek = db_query($conn, "SELECT group_files.*, users.username FROM group_files, users WHERE group_files.uploaded_by = users.id AND group_files.group_id = ? AND group_files.is_approved = 0 ORDER BY group_files.id DESC", "i", [$csoport_id]);
                    ?>
                    <?php if ($varakozo_jegyzetek && $varakozo_jegyzetek->num_rows > 0): ?>
                        <div class="list-compact flex flex-col gap-2 md:gap-3">
                            <?php while ($egy_varakozo = $varakozo_jegyzetek->fetch_assoc()):
                                    $v_jegyzet_id = $egy_varakozo['id'];
                                    $v_jegyzet_nev = $egy_varakozo['name'];
                                    $v_jegyzet_leiras = $egy_varakozo['description'];
                                    $v_jegyzet_fajlnev = $egy_varakozo['file_name'];
                                    $v_jegyzet_feltolto = $egy_varakozo['username'];
                                    $v_fajl_elercim = "users/".$v_jegyzet_feltolto."/".$v_jegyzet_fajlnev;
                                ?>
                                <article class="mini-card p-3 md:p-4 flex flex-col md:flex-row items-start gap-3 break-words">
                                    <div class="mini-main flex-1 min-w-0">
                                        <h4 class="mini-title text-sm md:text-base truncate"><?= htmlspecialchars($v_jegyzet_nev) ?></h4>
                                        <p class="mini-meta text-xs md:text-sm">
                                            @<?= htmlspecialchars($v_jegyzet_feltolto) ?> – jóváhagyásra vár
                                        </p>
                                        <?php if ($v_jegyzet_leiras != ""): ?>
                                            <p class="entry-meta text-xs md:text-sm mt-1"><?= nl2br(htmlspecialchars($v_jegyzet_leiras)) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <a href="<?= htmlspecialchars($v_fajl_elercim) ?>" target="_blank" class="mini-download flex-shrink-0" title="Fájl megnyitása / letöltése">
                                            <svg class="icon icon-download w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none">
                                                <path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                        <form method="post">
                                            <input type="hidden" name="jegyzet_id" value="<?= (int)$v_jegyzet_id ?>">
                                            <button type="submit" name="jegyzet_elfogadas" class="btn-cta text-sm md:text-base">
                                                Elfogadás
                                            </button>
                                        </form>
                                        <form method="post" onsubmit="return confirm('Biztos elutasítod / törlöd ezt a jegyzetet?');">
                                            <input type="hidden" name="jegyzet_id" value="<?= (int)$v_jegyzet_id ?>">
                                            <button type="submit" name="jegyzet_elutasitas" class="btn-ghost text-sm md:text-base">
                                                Elutasítás
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="entry-meta text-sm md:text-base">Nincs elfogadásra váró jegyzet.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
            <?php endif; ?>
        </section>
        <div class="mt-4 flex flex-col md:flex-row gap-3">
            <a href="groups.php" class="btn-ghost text-sm md:text-base">Vissza a csoportokhoz</a>
        <?php if ($aktualis_felhasznalo_tulaj): ?>
        <form method="post" onsubmit="return confirm('Biztosan törölni szeretnéd a csoportot? Ez a művelet nem visszavonható.');">
            <button type="submit" name="csoport_torles" class="btn-ghost text-sm md:text-base">
                Csoport törlése
            </button>
        </form>
        <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'assets/php/footer.php'; ?>
</body>
</html>
