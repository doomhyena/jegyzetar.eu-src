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
                &copy; 2025 - <?= date('Y') ?> Jegyzetár
            </p>
            <p class="footer-meta">
                <?= t('footer_built_with') ?>
            </p>
        </div>
        <div class="footer-right">
            <div class="footer-links-group">
                <span><?= t('footer_group_info') ?></span>
                <a href="about.php"><?= t('footer_link_about') ?></a>
                <a href="team.php"><?= t('footer_link_team') ?></a>
                <a href="partners.php"><?= t('footer_link_partners') ?></a>
                <a href="faq.php"><?= t('footer_link_faq') ?></a>
                <a href="rules.php"><?= t('footer_link_rules') ?></a>
                <a href="report.php"><?= t('footer_link_report') ?></a>
            </div>
        </div>
        <div class="footer-right">
            <div class="footer-links-group">
                <span><?= t('footer_group_legal') ?></span>
                <a href="privacy.php"><?= t('footer_link_privacy') ?></a>
                <a href="terms.php"><?= t('footer_link_terms') ?></a>
                <a href="contact.php"><?= t('footer_link_contact') ?></a>
            </div>
        </div>
        <div class="footer-right">
            <div class="footer-links-group">
                <span><?= t('footer_group_community') ?></span>
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
