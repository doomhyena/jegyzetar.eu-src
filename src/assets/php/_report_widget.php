<?php

    $report_extra_class = $report_extra_class ?? '';
    $report_label = $report_label ?? t('report_content');

    $_report_status = $_GET['report_status'] ?? '';
    $_report_msg = '';
    $_report_kind = 'toast-info';
    if ($_report_status === 'sent') {
        $_report_msg  = t('report_sent_thanks');
        $_report_kind = 'toast-success';
    } elseif ($_report_status === 'already_reported') {
        $_report_msg  = t('report_already_sent');
        $_report_kind = 'toast-info';
    } elseif ($_report_status === 'error') {
        $_report_msg  = t('report_send_error');
        $_report_kind = 'toast-error';
    }
?>
<?php if ($_report_msg): ?>
    <div class="toast <?= $_report_kind ?>" role="alert">
        <?= htmlspecialchars($_report_msg) ?>
    </div>
<?php endif; ?>

<div class="report-widget <?= htmlspecialchars($report_extra_class) ?>">
    <button type="button" class="btn-ghost danger report-trigger" aria-expanded="false" aria-controls="report-box-<?= (int)$report_target_id ?>-<?= htmlspecialchars($report_type) ?>">
        <?= htmlspecialchars($report_label) ?>
    </button>
    <div id="report-box-<?= (int)$report_target_id ?>-<?= htmlspecialchars($report_type) ?>" class="report-box" hidden>
        <form method="post" action="assets/php/report_action.php" onsubmit="return confirmReportSubmit(this)" class="report-form">
            <input type="hidden" name="type"      value="<?= htmlspecialchars($report_type) ?>">
            <input type="hidden" name="target_id" value="<?= (int)$report_target_id ?>">
            <input type="hidden" name="redirect"  value="<?= htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') ?>">
            <textarea name="reason" rows="3" placeholder="<?= t('report_reason_placeholder') ?>" class="input report-textarea" maxlength="1000">
            </textarea>
            <div class="report-actions">
                <button type="submit" class="btn-cta danger">
                    <?= t('report_send_button') ?>
                </button>
                <button type="button" class="btn-ghost report-cancel">
                    <?= t('report_cancel_button') ?>
                </button>
            </div>
        </form>
    </div>
</div>
