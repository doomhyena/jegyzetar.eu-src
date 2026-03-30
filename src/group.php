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
    <meta name="author" content="Baranyi Norbert, Csontos Kincső, Szekeres Levente">
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

        <!-- FEJLÉC -->
        <section class="card" style="width:100%; max-width:100%; overflow:hidden;">
            <div class="section-titlebar">
                <div>
                    <h1><?= htmlspecialchars($csoport_nev) ?></h1>
                    <p class="entry-meta">
                        <?php if ($privat == 1): ?>
                            🔒 Privát csoport
                        <?php else: ?>
                            🌍 Nyilvános csoport
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <?php if ($csoport_leiras != ""): ?>
                <p><?= nl2br(htmlspecialchars($csoport_leiras)) ?></p>
            <?php else: ?>
                <p class="entry-meta">Ehhez a csoporthoz még nincs leírás megadva.</p>
            <?php endif; ?>

            <?php if ($hiba_uzenet != ""): ?>
                <div class="toast toast-error">
                    <?= htmlspecialchars($hiba_uzenet) ?>
                </div>
            <?php endif; ?>

            <div class="profile-actions">
                <?php if (!$aktualis_felhasznalo_tag && !$aktualis_felhasznalo_pending): ?>
                    <form method="post">
                        <button type="submit" name="join_group" class="btn-cta">Csatlakozás a csoporthoz</button>
                    </form>
                <?php endif; ?>

                <?php if ($aktualis_felhasznalo_pending): ?>
                    <div class="entry-meta">Csatlakozási kérelmed függőben van.</div>
                <?php endif; ?>

                <?php if ($aktualis_felhasznalo_tag && !$aktualis_felhasznalo_tulaj): ?>
                    <form method="post">
                        <button type="submit" name="kilepes" class="btn-ghost">Kilépés a csoportból</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if ($aktualis_felhasznalo_tag && !$aktualis_felhasznalo_tulaj): ?>
                <details>
                    <summary class="link">⚠ Csoport jelentése</summary>
                    <div style="margin-top:12px;">
                        <?php
                            $report_type      = 'group';
                            $report_target_id = $csoport_id;
                            $report_label     = 'Csoport jelentése';
                            $report_extra_class = '';
                            include '_report_widget.php';
                        ?>
                    </div>
                </details>
            <?php endif; ?>
        </section>

        <div class="layout">

            <!-- BAL OLDAL -->
            <div>

                <!-- TAGOK -->
                <section class="card" style="width:100%; max-width:100%; overflow:hidden;">
                    <div class="section-titlebar">
                        <h3>👥 Tagok</h3>
                    </div>

                    <?php if ($hiba_uzenet == "" && ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj)): ?>
                        <?php
                            $tagok_lekerdezes = db_query(
                                $conn,
                                "SELECT group_members.*, users.username
                                 FROM group_members, users
                                 WHERE group_members.user_id = users.id
                                 AND group_members.group_id = ?
                                 AND group_members.status = 'accepted'",
                                "i",
                                [$csoport_id]
                            );
                        ?>

                        <?php if ($tagok_lekerdezes && $tagok_lekerdezes->num_rows > 0): ?>
                            <div style="display:grid; gap:14px;">
                                <?php while ($egy_tag = $tagok_lekerdezes->fetch_assoc()):
									$tag_id = $egy_tag['user_id'];
									$tag_nev = $egy_tag['username'];
									if ($egy_tag['role'] == "owner") {
										$tag_szerep = "tulajdonos";
									} elseif ($egy_tag['role'] == "moderator") {
										$tag_szerep = "moderátor";
									} else {
										$tag_szerep = "tag";
}
								?>
									<article class="mini-card" style="display:block; width:100%; max-width:100%; min-width:0; overflow:hidden;">
										<div class="mini-main" style="min-width:0;">
											<h4 class="mini-title" style="overflow-wrap:anywhere;">@<?= htmlspecialchars($tag_nev) ?></h4>
											<p class="mini-meta"><?= htmlspecialchars($tag_szerep) ?></p>
										</div>

										<?php if ($aktualis_felhasznalo_tulaj && $tag_id != $tulaj_id): ?>
										
										    <?php if ($egy_tag['role'] == 'member'): ?>
												<form method="post" style="margin-top:12px;">
													<input type="hidden" name="kezelt_user_id" value="<?= (int)$tag_id ?>">
													<button type="submit" name="moderator_add" class="btn-cta" style="width:100%; min-width:0;">
														Moderátor
													</button>
												</form>
											<?php endif; ?>

											<?php if ($egy_tag['role'] == 'moderator'): ?>
												<form method="post" style="margin-top:12px;">
													<input type="hidden" name="kezelt_user_id" value="<?= (int)$tag_id ?>">
													<button type="submit" name="moderator_remove" class="btn-ghost" style="width:100%; min-width:0;">
														Moderátor jog elvétele
													</button>
												</form>
											<?php endif; ?>
										
											<form method="post" style="margin-top:12px;">
												<input type="hidden" name="remove_user_id" value="<?= (int)$tag_id ?>">
												<button type="submit" name="remove_member" class="btn-ghost" style="width:100%; min-width:0;">
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

                        <?php if ($aktualis_felhasznalo_tulaj || $aktualis_felhasznalo_moderator): ?>
                            <h3>Függőben lévő jelentkezések</h3>

                            <?php
                                $pending_tagok_lekerdezes = db_query(
                                    $conn,
                                    "SELECT group_members.*, users.username
                                     FROM group_members, users
                                     WHERE group_members.user_id = users.id
                                     AND group_members.group_id = ?
                                     AND group_members.status = 'pending'",
                                    "i",
                                    [$csoport_id]
                                );
                            ?>

                            <?php if ($pending_tagok_lekerdezes && $pending_tagok_lekerdezes->num_rows > 0): ?>
                                <?php while ($egy_pending = $pending_tagok_lekerdezes->fetch_assoc()):
									$pending_tag_id  = $egy_pending['user_id'];
									$pending_tag_nev = $egy_pending['username'];
								?>
									<article class="mini-card" style="display:block; width:100%; max-width:100%; min-width:0; overflow:hidden;">
										<div class="mini-main" style="min-width:0;">
											<h4 class="mini-title" style="overflow-wrap:anywhere;">@<?= htmlspecialchars($pending_tag_nev) ?></h4>
											<p class="mini-meta">függőben</p>
										</div>

										<div class="profile-actions" style="margin-top:12px;">
											<form method="post" style="width:100%;">
												<input type="hidden" name="kezelt_user_id" value="<?= (int)$pending_tag_id ?>">
												<button type="submit" name="elfogadas" class="btn-cta" style="width:100%; min-width:0;">
													Elfogadás
												</button>
											</form>

											<form method="post" style="width:100%;">
												<input type="hidden" name="kezelt_user_id" value="<?= (int)$pending_tag_id ?>">
												<button type="submit" name="elutasitas" class="btn-ghost" style="width:100%; min-width:0;">
													Elutasítás
												</button>
											</form>
										</div>
									</article>
								<?php endwhile; ?>
                            <?php else: ?>
                                <p class="entry-meta">Nincsenek függőben lévő jelentkezések.</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="entry-meta">A tagok listája csak tagok és a tulajdonos számára elérhető.</p>
                    <?php endif; ?>
                </section>
				<br>

                <?php if ($hiba_uzenet == "" && ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj)): ?>

                    <!-- KOMMENT FAL -->
                    <?php
                        $kommentek = db_query(
                            $conn,
                            "SELECT group_comments.*, users.username
                             FROM group_comments, users
                             WHERE group_comments.user_id = users.id
                             AND group_comments.group_id = ?
                             ORDER BY group_comments.id DESC",
                            "i",
                            [$csoport_id]
                        );
                    ?>
                    <section class="card" style="width:100%; max-width:100%; overflow:hidden;">
                        <div class="section-titlebar">
                            <h3>💬 Csoport üzenőfal</h3>
                        </div>

                        <form method="post" class="form-field">
                            <textarea name="komment_szoveg" rows="4" class="input" placeholder="Írj egy üzenetet a csoportnak..."></textarea>
                            <button type="submit" name="uj_komment" class="btn-cta">Küldés</button>
                        </form>

                        <?php if ($kommentek && $kommentek->num_rows > 0): ?>
                            <?php while ($komment = $kommentek->fetch_assoc()): ?>
                                <?php
                                    $komment_iro_id = (int)$komment['user_id'];
                                    $sajat_komment = ($komment_iro_id === (int)$aktualis_felhasznalo_id);
                                ?>
								<article class="mini-card" style="display:block; width:100%; max-width:100%; min-width:0; overflow:hidden;">
									<div class="mini-main" style="min-width:0;">
										<h4 class="mini-title" style="overflow-wrap:anywhere;">@<?= htmlspecialchars($komment['username']) ?></h4>
										<p class="mini-meta"><?= htmlspecialchars($komment['created_at']) ?></p>
										<p style="margin-top:10px; overflow-wrap:anywhere;"><?= nl2br(htmlspecialchars($komment['comment_text'])) ?></p>
									</div>

									<?php if ($sajat_komment || $aktualis_felhasznalo_tulaj || $admin): ?>
										<form method="post" style="margin-top:12px;">
											<input type="hidden" name="komment_id" value="<?= (int)$komment['id'] ?>">
											<button type="submit" name="komment_torles" class="btn-ghost" style="width:100%; min-width:0;">
												Törlés
											</button>
										</form>
									<?php endif; ?>
								</article>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="entry-meta">Még nincs üzenet a csoport falán.</p>
                        <?php endif; ?>
                    </section>
					<br>

                    <!-- JEGYZETEK -->
                    <section class="card" style="width:100%; max-width:100%; overflow:hidden;">
                        <div class="section-titlebar">
                            <h3>📚 Csoport jegyzetek</h3>
                        </div>

                        <?php if ($aktualis_felhasznalo_tag): ?>
                            <details>
                                <summary class="link">➕ Új jegyzet feltöltése</summary>
                                <div style="margin-top:12px;">
                                    <form method="post" enctype="multipart/form-data" class="auth-card">
                                        <div class="form-grid">
                                            <div class="form-field">
                                                <label>Jegyzet neve</label>
                                                <input type="text" name="jegyzet_nev" class="input">
                                            </div>

                                            <div class="form-field">
                                                <label>Fájl</label>
                                                <input type="file" name="jegyzet_fajl" class="input">
                                            </div>

                                            <div class="form-field">
                                                <label>Leírás (nem kötelező)</label>
                                                <textarea name="jegyzet_leiras" rows="3" class="input"></textarea>
                                            </div>
                                        </div>

                                        <div class="auth-actions">
                                            <button type="submit" name="uj_jegyzet" class="btn-cta">Feltöltés a csoportba</button>
                                        </div>
                                    </form>
                                </div>
                            </details>
                        <?php endif; ?>

                        <h3>Feltöltött jegyzetek</h3>

                        <?php
                            $group_files_lekerdezes = db_query(
                                $conn,
                                "SELECT group_files.*, users.username
                                 FROM group_files, users
                                 WHERE group_files.uploaded_by = users.id
                                 AND group_files.group_id = ?
                                 AND group_files.is_approved = 1
                                 ORDER BY group_files.id DESC",
                                "i",
                                [$csoport_id]
                            );
                        ?>

                        <?php if ($group_files_lekerdezes && $group_files_lekerdezes->num_rows > 0): ?>
                            <?php while ($egy_jegyzet = $group_files_lekerdezes->fetch_assoc()):
                                $jegyzet_nev = $egy_jegyzet['name'];
                                $jegyzet_leiras = $egy_jegyzet['description'];
                                $jegyzet_fajlnev = $egy_jegyzet['file_name'];
                                $jegyzet_feltolto = $egy_jegyzet['username'];
                                $fajl_elercim = "users/".$jegyzet_feltolto."/".$jegyzet_fajlnev;
                            ?>
                                <article class="mini-card" style="display:block; width:100%; max-width:100%; min-width:0; overflow:hidden;">
									<div class="mini-main" style="min-width:0;">
										<h4 class="mini-title" style="overflow-wrap:anywhere;"><?= htmlspecialchars($jegyzet_nev) ?></h4>
										<p class="mini-meta">@<?= htmlspecialchars($jegyzet_feltolto) ?></p>

										<?php if ($jegyzet_leiras != ""): ?>
											<p style="margin-top:10px; overflow-wrap:anywhere;"><?= nl2br(htmlspecialchars($jegyzet_leiras)) ?></p>
										<?php endif; ?>
									</div>

									<div style="margin-top:12px;">
										<a href="<?= htmlspecialchars($fajl_elercim) ?>" target="_blank" class="entry-download-btn" style="width:100%; min-width:0;">
											Letöltés
										</a>
									</div>				
									<?php
									$jegyzet_kommentek = db_query(
										$conn,
										"SELECT group_file_comments.*, users.username
										 FROM group_file_comments, users
										 WHERE group_file_comments.user_id = users.id
										   AND group_file_comments.group_file_id = ?
										 ORDER BY group_file_comments.id DESC",
										"i",
										[$egy_jegyzet['id']]
									);
									?>
									<div style="margin-top:14px;">
										<h5 style="margin-bottom:8px;">Kommentek</h5>

										<?php if ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj): ?>
											<form method="post" style="margin-bottom:12px;">
												<input type="hidden" name="group_file_id" value="<?= (int)$egy_jegyzet['id'] ?>">
												<textarea name="jegyzet_komment_szoveg" rows="2" class="input" placeholder="Írj kommentet ehhez a jegyzethez..."></textarea>
												<button type="submit" name="uj_jegyzet_komment" class="btn-cta" style="margin-top:8px;">
													Küldés
												</button>
											</form>
										<?php endif; ?>

										<?php if ($jegyzet_kommentek && $jegyzet_kommentek->num_rows > 0): ?>
											<div style="display:grid; gap:10px;">
												<?php while ($jegyzet_komment = $jegyzet_kommentek->fetch_assoc()): ?>
													<?php
														$komment_sajat = ((int)$jegyzet_komment['user_id'] === (int)$aktualis_felhasznalo_id);
														$komment_admin = (isset($user['admin']) && (int)$user['admin'] === 1);
													?>
													<article class="mini-card" style="display:block; width:100%; max-width:100%; min-width:0; overflow:hidden;">
														<div class="mini-main" style="min-width:0;">
															<h4 class="mini-title" style="overflow-wrap:anywhere;">@<?= htmlspecialchars($jegyzet_komment['username']) ?></h4>
															<p class="mini-meta"><?= htmlspecialchars($jegyzet_komment['created_at']) ?></p>
															<p style="margin-top:8px; overflow-wrap:anywhere;"><?= nl2br(htmlspecialchars($jegyzet_komment['comment_text'])) ?></p>
														</div>

														<?php if ($komment_sajat || $aktualis_felhasznalo_tulaj || $komment_admin): ?>
															<form method="post" style="margin-top:10px;" onsubmit="return confirm('Biztosan törölni szeretnéd ezt a kommentet?');">
																<input type="hidden" name="jegyzet_komment_id" value="<?= (int)$jegyzet_komment['id'] ?>">
																<button type="submit" name="jegyzet_komment_torles" class="btn-ghost" style="width:100%; min-width:0;">
																	Törlés
																</button>
															</form>
														<?php endif; ?>
													</article>
												<?php endwhile; ?>
											</div>
										<?php else: ?>
											<p class="entry-meta">Még nincs komment ennél a jegyzetnél.</p>
										<?php endif; ?>
									</div>
								</article>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="entry-meta">Még nincsenek elfogadott csoport jegyzetek.</p>
                        <?php endif; ?>

                        <?php if ($aktualis_felhasznalo_tulaj || $aktualis_felhasznalo_moderator): ?>
                            <h3>Elfogadásra váró jegyzetek</h3>

                            <?php
                                $varakozo_jegyzetek = db_query(
                                    $conn,
                                    "SELECT group_files.*, users.username
                                     FROM group_files, users
                                     WHERE group_files.uploaded_by = users.id
                                     AND group_files.group_id = ?
                                     AND group_files.is_approved = 0
                                     ORDER BY group_files.id DESC",
                                    "i",
                                    [$csoport_id]
                                );
                            ?>

                            <?php if ($varakozo_jegyzetek && $varakozo_jegyzetek->num_rows > 0): ?>
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
                                            <p class="mini-meta">@<?= htmlspecialchars($v_jegyzet_feltolto) ?> – jóváhagyásra vár</p>

                                            <?php if ($v_jegyzet_leiras != ""): ?>
                                                <p><?= nl2br(htmlspecialchars($v_jegyzet_leiras)) ?></p>
                                            <?php endif; ?>
                                        </div>

                                        <div class="profile-actions">
                                            <a href="<?= htmlspecialchars($v_fajl_elercim) ?>" target="_blank" class="entry-download-btn">Megnyitás</a>

                                            <form method="post">
                                                <input type="hidden" name="jegyzet_id" value="<?= (int)$v_jegyzet_id ?>">
                                                <button type="submit" name="jegyzet_elfogadas" class="btn-cta">Elfogadás</button>
                                            </form>

                                            <form method="post" onsubmit="return confirm('Biztos elutasítod / törlöd ezt a jegyzetet?');">
                                                <input type="hidden" name="jegyzet_id" value="<?= (int)$v_jegyzet_id ?>">
                                                <button type="submit" name="jegyzet_elutasitas" class="btn-ghost">Elutasítás</button>
                                            </form>
                                        </div>
                                    </article>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="entry-meta">Nincs elfogadásra váró jegyzet.</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </section>

                <?php endif; ?>
            </div>

            <!-- JOBB OLDAL -->
            <aside class="sidebar">

                <!-- ESEMÉNYEK -->
                <section class="sidebar-card">
                    <h2 class="sidebar-title">📅 Csoport események</h2>

                    <?php if ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj): ?>
                        <?php
                            $events = db_query(
                                $conn,
                                "SELECT group_events.*, users.username
                                 FROM group_events, users
                                 WHERE group_events.created_by = users.id
                                 AND group_events.group_id = ?
                                 ORDER BY event_date ASC",
                                "i",
                                [$csoport_id]
                            );
                        ?>

                        <?php if ($events && $events->num_rows > 0): ?>
                            <?php while ($event = $events->fetch_assoc()): ?>
                                <article class="mini-card">
                                    <div class="mini-main">
                                        <h4 class="mini-title"><?= htmlspecialchars($event['title']) ?></h4>
                                        <p class="mini-meta">📅 <?= htmlspecialchars($event['event_date']) ?></p>
                                        <p class="mini-meta">@<?= htmlspecialchars($event['username']) ?></p>
                                        <?php if ($event['description'] != ""): ?>
                                            <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="sidebar-meta">Nincs esemény ebben a csoportban.</p>
                        <?php endif; ?>

                        <?php if ($aktualis_felhasznalo_tulaj || $aktualis_felhasznalo_moderator): ?>
                            <details>
                                <summary class="link">➕ Esemény hozzáadása</summary>
                                <div style="margin-top:12px;">
                                    <form method="post" class="form-field">
                                        <input type="text" name="event_title" placeholder="Esemény neve" class="input">
                                        <input type="datetime-local" name="event_date" class="input">
                                        <textarea name="event_desc" rows="3" placeholder="Leírás (nem kötelező)" class="input"></textarea>
                                        <button type="submit" name="uj_esemeny" class="btn-cta">Esemény mentése</button>
                                    </form>
                                </div>
                            </details>
                        <?php endif; ?>
                    <?php endif; ?>
                </section>

                <!-- FLASHCARD -->
                <?php if ($hiba_uzenet == "" && ($aktualis_felhasznalo_tag || $aktualis_felhasznalo_tulaj)): ?>
                    <section class="sidebar-card" id="flashcards">
                        <h2 class="sidebar-title">🃏 Flashcard tanulás</h2>
                        <details>
                            <summary class="link">➕ Új flashcard hozzáadása</summary>
							
                            <div style="margin-top:16px;">
                                <form method="post" class="auth-card">
                                    <div class="form-field">
                                        <label>Kérdés</label>
                                        <textarea name="flash_q" rows="2" class="input" placeholder="Ide írd a kérdésed" required></textarea>
                                    </div>

                                    <div class="form-field">
                                        <label>Válasz</label>
                                        <textarea name="flash_a" rows="3" class="input" placeholder="Válasz a kérdésre" required></textarea>
                                    </div>
									<br>
                                    <div class="auth-actions">
                                        <button type="submit" name="flashcard_add" class="btn-cta">Flashcard mentése</button>
                                    </div>
                                </form>
                            </div>
                        </details>

                        <?php
                            $flashcards = $conn->query("
                                SELECT * FROM group_flashcards
                                WHERE group_id = '$csoport_id'
                                ORDER BY RAND()
                                LIMIT 1
                            ");
                        ?>

                        <?php if ($flashcards && $flashcards->num_rows > 0): ?>
                            <?php $fc = $flashcards->fetch_assoc(); ?>

                            <article class="mini-card">
                                <div class="mini-main">
                                    <h4 class="mini-title"><?= htmlspecialchars($fc['question']) ?></h4>

                                    <div id="flash-answer" style="display:none; margin-top:10px;">
                                        <p><?= nl2br(htmlspecialchars($fc['answer'])) ?></p>
                                    </div>

                                    <div class="auth-actions" style="margin-top:12px;">
                                        <button type="button" class="btn-ghost" onclick="showAnswer()">Mutasd a választ</button>
                                        <button type="button" class="btn-ghost" onclick="nextCard()">Következő</button>
                                    </div>

                                    <div class="mini-meta" style="margin-top:12px;">
                                        ✔ <?= (int)$fc['correct_count'] ?> |
                                        ✖ <?= (int)$fc['wrong_count'] ?>
                                    </div>

                                    <div class="auth-actions" style="margin-top:12px;">
                                        <form method="post">
                                            <input type="hidden" name="flashcard_id" value="<?= (int)$fc['id'] ?>">
                                            <button type="submit" name="flashcard_mark" value="correct" class="btn-cta">✔ Tudtam</button>
                                        </form>

                                        <form method="post">
                                            <input type="hidden" name="flashcard_id" value="<?= (int)$fc['id'] ?>">
                                            <button type="submit" name="flashcard_mark" value="wrong" class="btn-ghost">✖ Nem tudtam</button>
                                        </form>

                                        <?php if ($aktualis_felhasznalo_tulaj || $aktualis_felhasznalo_moderator): ?>
                                            <form method="post">
                                                <input type="hidden" name="flashcard_id" value="<?= (int)$fc['id'] ?>">
                                                <button type="submit" name="flashcard_delete" class="btn-ghost">🗑 Törlés</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        <?php else: ?>
                            <p class="sidebar-meta">Nincs flashcard ebben a csoportban.</p>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>

                <!-- SZAVAZÁS -->
                <section class="sidebar-card">
                    <h2 class="sidebar-title">📊 Csoport szavazás</h2>

					<?php if ($aktualis_felhasznalo_tulaj || $aktualis_felhasznalo_moderator): ?>
                        <details>
                            <summary class="link">➕ Szavazás létrehozása</summary>
                            <div style="margin-top:12px;">
                                <form method="post" class="form-field">
                                    <input type="text" name="poll_question" placeholder="Szavazás kérdése" class="input">
                                    <input type="text" name="opt1" placeholder="Opció 1" class="input">
                                    <input type="text" name="opt2" placeholder="Opció 2" class="input">
                                    <input type="text" name="opt3" placeholder="Opció 3 (nem kötelező)" class="input">
                                    <button type="submit" name="uj_poll" class="btn-cta">Szavazás létrehozása</button>
                                </form>
                            </div>
                        </details>
                    <?php endif; ?>

                    <?php
                        $poll = $conn->query("SELECT * FROM group_polls WHERE group_id = '$csoport_id' ORDER BY id DESC LIMIT 1");
                    ?>

                    <?php if ($poll && $poll->num_rows > 0): ?>
                        <?php $p = $poll->fetch_assoc(); ?>

                        <h3><?= htmlspecialchars($p['question']) ?></h3>

                        <?php
                            $options = $conn->query("SELECT * FROM group_poll_options WHERE poll_id = '{$p['id']}'");
                            $total_votes_row = $conn->query("SELECT COUNT(*) AS total FROM group_poll_votes WHERE poll_id = '{$p['id']}'")->fetch_assoc();
                            $total_votes = (int)($total_votes_row['total'] ?? 0);
                            $user_voted_row = $conn->query("SELECT id FROM group_poll_votes WHERE poll_id = '{$p['id']}' AND user_id = '$aktualis_felhasznalo_id' LIMIT 1");
                            $user_already_voted = ($user_voted_row && $user_voted_row->num_rows > 0);
                        ?>

                        <?php while ($o = $options->fetch_assoc()): ?>
                            <?php
                                $votes_row = $conn->query("
                                    SELECT COUNT(*) AS c
                                    FROM group_poll_votes
                                    WHERE option_id = '{$o['id']}'
                                ")->fetch_assoc();
                                $votes = (int)($votes_row['c'] ?? 0);
                                $percent = $total_votes > 0 ? round(($votes / $total_votes) * 100) : 0;
                            ?>

                            <?php if ((int)$p['closed'] === 0): ?>
                                <?php if (!$user_already_voted): ?>
                                    <form method="post" class="form-field">
                                        <input type="hidden" name="poll_id" value="<?= (int)$p['id'] ?>">
                                        <input type="hidden" name="option_id" value="<?= (int)$o['id'] ?>">
                                        <button type="submit" name="poll_vote" class="btn-ghost"><?= htmlspecialchars($o['option_text']) ?></button>
                                    </form>
                                <?php else: ?>
                                    <article class="mini-card">
                                        <div class="mini-main">
                                            <h4 class="mini-title"><?= htmlspecialchars($o['option_text']) ?></h4>
                                            <p class="mini-meta">Már szavaztál ennél a szavazásnál.</p>
                                        </div>
                                    </article>
                                <?php endif; ?>
                            <?php else: ?>
                                <article class="mini-card">
                                    <div class="mini-main">
                                        <h4 class="mini-title"><?= htmlspecialchars($o['option_text']) ?></h4>
                                        <p class="mini-meta"><?= $percent ?>% (<?= $votes ?> szavazat)</p>
                                    </div>
                                </article>
                            <?php endif; ?>
                        <?php endwhile; ?>

                        <?php if ((int)$p['closed'] === 0): ?>
                            <p class="sidebar-meta">Összes szavazat: <?= $total_votes ?></p>
                        <?php else: ?>
                            <p class="sidebar-meta">A szavazás lezárva. Összes szavazat: <?= $total_votes ?></p>
                        <?php endif; ?>

                        <?php if ($aktualis_felhasznalo_tulaj && (int)$p['closed'] === 0): ?>
                            <form method="post">
                                <input type="hidden" name="poll_id" value="<?= (int)$p['id'] ?>">
                                <button type="submit" name="poll_close" class="btn-cta">Szavazás lezárása</button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="sidebar-meta">Nincs aktív szavazás.</p>
                    <?php endif; ?>
                </section>

            </aside>
        </div>

        <!-- ALSÓ GOMBOK -->
        <div class="profile-actions" style="margin-top:36px;">
            <a href="groups.php" class="btn-ghost">Vissza a csoportokhoz</a>

            <?php if ($aktualis_felhasznalo_tulaj): ?>
                <form method="post" onsubmit="return confirm('Biztosan törölni szeretnéd a csoportot? Ez a művelet nem visszavonható.');">
                    <button type="submit" name="csoport_torles" class="btn-ghost">Csoport törlése</button>
                </form>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include 'assets/php/footer.php'; ?>
</body>
</html>