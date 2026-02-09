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
            <p class="footer-copy">
                &copy; 2025 – <?= date('Y') ?> Jegyzetár
            </p>
            <p class="footer-meta">
                Built with <span>❤️</span> by the NoteForge Development
            </p>
        </div>
        <div class="footer-right">
            <div class="footer-links-group">
                <span>Információ</span>
                <a href="about.php">Rólunk</a>
                <a href="team.php">Csapattagjaink</a>
                <a href="partners.php">Partnereink</a>
                <a href="faq.php">GYIK</a>
                <a href="rules.php">Szabályzat</a>
                <a href="report.php">Hibajelentés</a>
            </div>
        </div>
        <div class="footer-right">
            <div class="footer-links-group">
                <span>Jogi</span>
                <a href="privacy.php">Adatvédelem</a>
                <a href="terms.php">ÁSZF</a>
                <a href="contact.php">Kapcsolat</a>
            </div>
        </div>
        <div class="footer-right">
            <div class="footer-links-group">
                <span>Közösség</span>
                <a href="https://github.com/NoteForge-Development/jegyzetar.eu" target="_blank">
                    <?= t('footer_github_link') ?>
                </a>
                <a href="https://www.instagram.com/jegyzetar.eu/" target="_blank">Instagram</a>
                <a href="https://discord.gg/XFte257XBc" target="_blank">Discord</a>
                <a href="https://www.tiktok.com/@jegyzetar.eu" target="_blank">TikTok</a>
            </div>
        </div>
    </div>
</footer>
