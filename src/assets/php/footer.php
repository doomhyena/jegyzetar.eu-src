<?php
    require "lang.php";
?>

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-left">
            <div class="footer-brand">
                <span class="footer-logo">Jegyzetár</span>
                <span class="footer-beta">BETA</span>
            </div>
            <p class="footer-devs">
                <?= t('footer_developers_label') ?>:
                <a href="https://github.com/baranyi0" target="_blank" rel="noopener noreferrer">Baranyi Norbert</a>,
                <a href="https://doomhyena.hu/" target="_blank" rel="noopener noreferrer">Csontos Kincső Anasztázia</a>,
                <a href="https://github.com/PaladiTech" target="_blank" rel="noopener noreferrer">Szekeres Levente</a>
            </p>
        </div>
        <div class="footer-right">
            <nav class="footer-links">
                <a href="privacy.php">Adatvédelmi Tájékoztató</a>
                <a href="terms.php">Felhasználási feltételek</a>
                <a href="https://github.com/NoteForge-Development/jegyzetar.eu"
                   target="_blank"
                   rel="noopener noreferrer">
                    <?= t('footer_github_link') ?>
                </a>
            </nav>
            <p class="footer-copy">
                &copy; 2025 – <?= date('Y') ?> Jegyzetár
            </p>
        </div>
    </div>
</footer>
