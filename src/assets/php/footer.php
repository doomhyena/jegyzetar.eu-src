<?php
    require __DIR__ . "/lang.php";
?>

<footer>
    <div class="footer-inner">
        <p>
            <?= t('footer_developers_label') ?>:
            <a href="https://github.com/baranyi0" target="_blank" rel="noopener noreferrer">Baranyi Norbert</a>, <a href="https://doomhyena.hu/" target="_blank" rel="noopener noreferrer">Csontos Kincső Anasztázia</a>, <a href="https://github.com/PaladiTech" target="_blank" rel="noopener noreferrer">Szekeres Levente</a>
        </p>
        <a href="https://github.com/doomhyena/jegyzetar.eu"
           target="_blank"
           rel="noopener noreferrer">
            <?= t('footer_github_link') ?>
        </a>
        <p>&copy; 2025 - <?= date('Y') ?> Jegyzetár</p>
    </div>
</footer>
