let friendId = new URLSearchParams(window.location.search).get('friendid');
let lastMessageCount = 0;

document.addEventListener('DOMContentLoaded', function () {
    // jQuery-függő keresés
    if (typeof $ !== 'undefined') {
        const searchBox = document.getElementById("search-box");
        if (searchBox) {
            searchBox.addEventListener('keyup', (e) => {
                var ertek = e.target.value;
                $("#search").html("Keresés folyamatban...");
                $("#search").load("assets/php/findanything.php?keresett=" + encodeURIComponent(ertek));
            });
        }

        if (friendId) {
            $(".messages").load("assets/php/loadmessages.php?friendid=" + encodeURIComponent(friendId), function () {
                $.get("assets/php/loadmessages.php?friendid=" + encodeURIComponent(friendId) + "&countonly=1", function (newCount) {
                    lastMessageCount = parseInt(newCount);
                });
            });
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const themeSelect = document.getElementById('profile-theme-select');
    if (!themeSelect) return;

    const THEMES = ['default', 'pastel', 'forest', 'light', 'dark'];

    function applyTheme(theme) {
        const body = document.body;
        THEMES.forEach(t => body.classList.remove('theme-' + t));
        if (theme && THEMES.includes(theme)) {
            body.classList.add('theme-' + theme);
        } else {
            body.classList.add('theme-default');
        }
        themeSelect.setAttribute('data-theme', theme || 'default');
        try { themeSelect.value = theme || 'default'; } catch (err) { /* ignoráljuk */ }
    }

    const initial = themeSelect.value || themeSelect.dataset.theme || 'default';
    applyTheme(initial);
    window.applyTheme = applyTheme;

    themeSelect.addEventListener('change', function (e) {
        const newTheme = e.target.value;
        applyTheme(newTheme);
    });
});

function checkNewMessages() {
    if (!friendId || typeof $ === 'undefined') return;
    $.get("assets/php/loadmessages.php?friendid=" + encodeURIComponent(friendId) + "&countonly=1", function (newCount) {
        if (parseInt(newCount) > lastMessageCount) {
            $(".messages").load("assets/php/loadmessages.php?friendid=" + encodeURIComponent(friendId));
            lastMessageCount = parseInt(newCount);
        }
    });
}
setInterval(checkNewMessages, 1000);

// jQuery-függő kód - csak akkor fut, ha jQuery be van töltve
if (typeof $ !== 'undefined') {
    $('form.message-form').submit(function (e) {
        e.preventDefault();

        const form = $(this);
        const messageInput = form.find('input[name="message"]');
        const toid = form.find('input[name="toid"]').val();
        const message = messageInput.val().trim();

        if ($('#message-status').length === 0) {
            form.after('<div id="message-status" style="margin-top: 10px;"></div>');
        }

        const statusDiv = $('#message-status');

        if (message.length === 0) {
            statusDiv.text('Nem küldhetsz üres üzenetet.').css('color', 'red');
            return;
        }

        $.post('messages.php', {
            toid: toid,
            message: message
        })
        .done(function () {
            $(".messages").load("assets/php/loadmessages.php?friendid=" + encodeURIComponent(toid));
            messageInput.val('');
            statusDiv.text('Üzenet elküldve.').css('color', 'green');
            $.get("assets/php/loadmessages.php?friendid=" + encodeURIComponent(toid) + "&countonly=1", function (newCount) {
                lastMessageCount = parseInt(newCount);
            });
        })
        .fail(function () {
            statusDiv.text('Hiba történt az üzenet küldése közben.').css('color', 'red');
        })
        .always(function () {
            setTimeout(() => {
                statusDiv.fadeOut(400, function () {
                    $(this).text('').css('display', 'block');
                });
            }, 3000);
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navLinks = document.querySelector('.nav-links');

    if (navbarToggler && navLinks) {
        navbarToggler.addEventListener('click', function () {
            this.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.navbar-content')) {
                navbarToggler.classList.remove('active');
                navLinks.classList.remove('active');
            }
        });

        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navbarToggler.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    }

    const accountItem = document.querySelector('.nav-item-has-dropdown');
    const accountLink = document.querySelector('.nav-account-link');

    if (accountItem && accountLink) {
        accountLink.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            accountItem.classList.toggle('is-open');
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.nav-item-has-dropdown')) {
                accountItem.classList.remove('is-open');
            }
        });
    }
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('friend-btn')) {
        e.preventDefault();
        const btn = e.target;
        const form = btn.closest('form');

        btn.classList.add('added');
        btn.disabled = true;

        setTimeout(() => {
            form.submit();
        }, 1000);
    }
});

document.addEventListener('click', (e) => {
  if (e.target.matches('.star-rating label')) {
    const targetId = e.target.getAttribute('for');
    if (targetId) {
      const input = document.getElementById(targetId);
      if (input) input.checked = true;
    }
  }
});

document.addEventListener('DOMContentLoaded', function(){
    const tabs = document.querySelectorAll('.tablist .tab');
    const panels = document.querySelectorAll('.tabpanel');
    tabs.forEach((t,i)=>{
        t.addEventListener('click', ()=>{
            tabs.forEach(x=>x.setAttribute('aria-selected','false'));
            panels.forEach(x=>x.classList.remove('is-active'));
            t.setAttribute('aria-selected','true');
            panels[i].classList.add('is-active');
        });
    });
    document.querySelectorAll('[data-toggle-pass]').forEach(btn=>{
        btn.addEventListener('click', (e)=>{
            const id = btn.getAttribute('data-toggle-pass');
            const input = document.getElementById(id);
            if(!input) return;
            const isPw = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPw ? 'text':'password');
            btn.setAttribute('aria-pressed', isPw ? 'true':'false');
        });
    });

    function showForm(which) {
        const regForm = document.getElementById('reg');
        const loginForm = document.getElementById('login');
        if (!regForm || !loginForm) return;
        if (which === 'reg') {
            regForm.style.display = '';
            loginForm.style.display = 'none';
            loginForm.classList.add('is-hidden');
            regForm.classList.remove('is-hidden');
            loginForm.setAttribute('aria-hidden', 'true');
            regForm.setAttribute('aria-hidden', 'false');
            document.body.classList.add('auth-view-reg');
            document.body.classList.remove('auth-view-login');
            document.title = 'Regisztráció';
            history.replaceState(null, '', '?form=reg');
        } else {
            regForm.style.display = 'none';
            regForm.classList.add('is-hidden');
            loginForm.classList.remove('is-hidden');
            regForm.setAttribute('aria-hidden', 'true');
            loginForm.setAttribute('aria-hidden', 'false');
            document.body.classList.add('auth-view-login');
            document.body.classList.remove('auth-view-reg');
            loginForm.style.display = '';
            document.title = 'Bejelentkezés';
            history.replaceState(null, '', '?form=login');
        }
    }

    document.querySelectorAll('[data-switch]').forEach(switcher => {
        switcher.addEventListener('click', (e) => {
            e.preventDefault();
            const targetForm = switcher.getAttribute('data-switch');
            console.info('Auth switch clicked, target:', targetForm);
            showForm(targetForm);
        });
    });

    document.querySelectorAll('.switcher').forEach(a => {
        a.addEventListener('click', (e) => {
            e.preventDefault();
            const target = a.getAttribute('data-switch') || (a.dataset ? a.dataset.switch : null);
            if (target) {
                console.info('Switcher (legacy) clicked, target:', target);
                showForm(target);
            }
        });
    });
    try { window.showForm = showForm; } catch (err) { /* ignore if not allowed */ }

    try {
        const regForm = document.getElementById('reg');
        const loginForm = document.getElementById('login');
        if (regForm && loginForm) {
            const regVisible = window.getComputedStyle(regForm).display !== 'none';
            if (regVisible) {
                document.body.classList.add('auth-view-reg');
                document.body.classList.remove('auth-view-login');
                regForm.setAttribute('aria-hidden', 'false');
                loginForm.setAttribute('aria-hidden', 'true');
            } else {
                document.body.classList.add('auth-view-login');
                document.body.classList.remove('auth-view-reg');
                regForm.setAttribute('aria-hidden', 'true');
                loginForm.setAttribute('aria-hidden', 'false');
            }
        }
    } catch (err) { /* ignore */ }
});

    document.addEventListener('DOMContentLoaded', function () {
        const staticBlock = document.getElementById('basic-profile-static');
        const formBlock = document.getElementById('basic-profile-form');
        const editBtn = document.getElementById('edit-basic-profile-btn');
        const cancelBtn = document.getElementById('cancel-basic-profile-edit');

        if (editBtn && staticBlock && formBlock) {
            editBtn.addEventListener('click', function () {
                staticBlock.classList.add('hidden');
                formBlock.classList.remove('hidden');
            });
        }

        if (cancelBtn && staticBlock && formBlock) {
            cancelBtn.addEventListener('click', function () {
                formBlock.classList.add('hidden');
                staticBlock.classList.remove('hidden');
            });
        }

        const pfpOpenBtn = document.getElementById('pfp-open-btn');
        const pfpForm = document.getElementById('pfp-form');
        const pfpInput = pfpForm ? pfpForm.querySelector('input[name="profile_picture"]') : null;

        if (pfpOpenBtn && pfpInput) {
            pfpOpenBtn.addEventListener('click', function () {
                pfpInput.click();
            });

            pfpInput.addEventListener('change', function () {
                if (pfpInput.files.length > 0 && pfpForm) {
                    pfpForm.submit();
                }
            });
        }
    });
document.addEventListener("DOMContentLoaded", () => {
    const cssHelp = document.querySelector(".css-tutorial");

    if (!cssHelp) return;

    cssHelp.addEventListener("toggle", () => {
        if (cssHelp.open) {
            document.body.classList.add("css-help-open");
        } else {
            document.body.classList.remove("css-help-open");
        }
    });
});

function openReportBox(button) {
    const form = button.closest('form');
    if (!form) return;
    const box = form.querySelector('.report-box');
    if (!box) return;
    box.style.display = 'block';
    button.style.display = 'none';
}
function cancelReport(button) {
    const box = button.closest('.report-box');
    if (!box) return;
    const form = box.closest('form');
    if (!form) return;
    const trigger = form.querySelector('.report-trigger');
    const textarea = form.querySelector('textarea[name="reason"]');
    if (textarea) textarea.value = '';
    if (trigger) trigger.style.display = 'inline-block';
    box.style.display = 'none';
}
function confirmReportSubmit(form) {
    return confirm('Biztosan elküldöd a jelentést?');
}

document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.getElementById('report-toggle-btn');
  const box = document.getElementById('report-box');

  if (!toggleBtn || !box) return;

  toggleBtn.addEventListener('click', () => {
    const isHidden = (box.style.display === 'none' || box.style.display === '');
    box.style.display = isHidden ? 'block' : 'none';

    if (isHidden) {
      const ta = box.querySelector('textarea');
      if (ta) ta.focus();
    }
  });
});
const bio = document.getElementById('profile-bio-input');
const counter = document.getElementById('bio-counter');

if (bio && counter) {
    const updateCounter = () => {
        counter.textContent = `${bio.value.length} / 1500`;
    };
    bio.addEventListener('input', updateCounter);
    updateCounter();
}

(() => {
    const form = document.querySelector('.search-panel form');
    if (!form) return;

    const selects = form.querySelectorAll('select');

    selects.forEach(s => s.addEventListener('change', () => {
        const page = form.querySelector('input[name="page"]');
        if (page) page.value = '1';

        const url = new URL(window.location.href);
        url.searchParams.delete('cursor');
        window.history.replaceState({}, '', url.toString());

        form.submit();
    }));
})();

document.addEventListener("DOMContentLoaded", () => {
    const accordion = document.getElementById("faq-accordion");
    if (!accordion) return;

    const items = [...accordion.querySelectorAll(".border")];

    const closeItem = (item) => {
      const btn = item.querySelector(".faq-btn");
      const panel = item.querySelector(".faq-panel");
      const icon = item.querySelector(".faq-icon");

      btn.setAttribute("aria-expanded", "false");
      panel.style.maxHeight = "0px";
      icon.textContent = "+";
    };

    const openItem = (item) => {
      const btn = item.querySelector(".faq-btn");
      const panel = item.querySelector(".faq-panel");
      const icon = item.querySelector(".faq-icon");

      btn.setAttribute("aria-expanded", "true");
      panel.style.maxHeight = panel.scrollHeight + "px";
      icon.textContent = "–";
    };

    items.forEach((item) => {
      const btn = item.querySelector(".faq-btn");
      btn.addEventListener("click", () => {
        const isOpen = btn.getAttribute("aria-expanded") === "true";

        items.forEach(closeItem);

        if (!isOpen) openItem(item);
      });
    });

    window.addEventListener("resize", () => {
      items.forEach((item) => {
        const btn = item.querySelector(".faq-btn");
        const panel = item.querySelector(".faq-panel");
        const isOpen = btn.getAttribute("aria-expanded") === "true";
        if (isOpen) panel.style.maxHeight = panel.scrollHeight + "px";
      });
    });
});

document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.toggle-pass, [data-toggle-pass]');
    if (!btn) return;

    const id = btn.getAttribute('data-target') || btn.getAttribute('data-toggle-pass');
    if (!id) return;

    const input = document.getElementById(id);
    if (!input) return;

    const isPw = input.type === 'password';
    input.type = isPw ? 'text' : 'password';

    // opcionális UI
    if (btn.classList.contains('toggle-pass')) {
      btn.textContent = isPw ? '🙈' : '👁';
    } else {
      btn.setAttribute('aria-pressed', isPw ? 'true' : 'false');
    }
  });

  const p1 = document.getElementById('password1');
  const p2 = document.getElementById('password2');
  const bar = document.getElementById('pw_bar');
  const label = document.getElementById('pw_label');
  const match = document.getElementById('pw_match');

  function scorePassword(pw) {
    let score = 0;
    if (!pw) return 0;
    if (pw.length >= 8) score += 1;
    if (/[a-z]/.test(pw)) score += 1;
    if (/[A-Z]/.test(pw)) score += 1;
    if (/[0-9]/.test(pw)) score += 1;
    if (/[^A-Za-z0-9]/.test(pw)) score += 1;
    return score;
  }

  function updateStrength() {
    if (!p1 || !bar || !label) return;

    const s = scorePassword(p1.value);
    const pct = Math.min(100, Math.round((s / 5) * 100));
    bar.style.width = pct + '%';

    let txt = '—';
    if (s <= 1) txt = 'gyenge';
    else if (s === 2) txt = 'közepes';
    else if (s === 3) txt = 'jó';
    else txt = 'erős';

    label.textContent = 'Jelszó erőssége: ' + txt;

    if (p2 && match) {
      if (!p2.value) match.textContent = '';
      else if (p1.value === p2.value) match.textContent = 'A jelszavak egyeznek.';
      else match.textContent = 'A jelszavak nem egyeznek.';
    }
  }

  if (p1) p1.addEventListener('input', updateStrength);
  if (p2) p2.addEventListener('input', updateStrength);
  updateStrength();
});

document.addEventListener('DOMContentLoaded', () => {
  const fileWrap = document.getElementById('file_wrap');
  const mdWrap = document.getElementById('markdown_wrap');
  const linkWrap = document.getElementById('link_wrap');
  const fileInput = document.querySelector('input[name="upload-file"]');
  const mdInput = document.getElementById('markdown_note');
  const linkInput = document.getElementById('external_url');

	function syncMode() {
	const mode = document.querySelector('input[name="content_mode"]:checked')?.value || 'file';

	const isMd = mode === 'markdown';
	const isLink = mode === 'link';

	if (fileWrap) fileWrap.style.display = (isMd || isLink) ? 'none' : '';
	if (mdWrap) mdWrap.style.display = isMd ? '' : 'none';
	if (linkWrap) linkWrap.style.display = isLink ? '' : 'none';

	if (fileInput) fileInput.required = !(isMd || isLink);
	if (mdInput) mdInput.required = isMd;
	if (linkInput) linkInput.required = isLink;
}

  document.querySelectorAll('input[name="content_mode"]').forEach(r => {
    r.addEventListener('change', syncMode);
  });
  syncMode();
});

document.addEventListener('DOMContentLoaded', function(){

    if (typeof $ === 'undefined') return;

    const adminSearch = document.getElementById('admin-user-search');
    const resultsBox = document.getElementById('admin-user-results');

    if (!adminSearch || !resultsBox) return;

    adminSearch.addEventListener('keyup', function(e){
        const ertek = e.target.value;

        if (ertek.trim() === '') {
            resultsBox.innerHTML = "<p class='search-text'>Kezdj el gépelni...</p>";
            return;
        }

        $("#admin-user-results").html("Keresés folyamatban...");
        $("#admin-user-results").load(
            "assets/php/findanything.php?mode=admin_users&keresett=" 
            + encodeURIComponent(ertek)
        );
    });

});

function showAnswer() {
    document.getElementById('flash-answer').style.display = 'block';
}

function nextCard() {
    location.reload();
}

(function () {
    const MAX_TAGS = 10;

    const widget    = document.getElementById('tag-widget');
    const pillsWrap = document.getElementById('tag-pills');
    const textInput = document.getElementById('tag-input');
    const hidden    = document.getElementById('applied_tags');

    if (!widget || !pillsWrap || !textInput || !hidden) return;

    let tags = [];

    function syncHidden() {
        hidden.value = tags.join(',');
    }

    function renderPills() {
        pillsWrap.innerHTML = '';
        tags.forEach((tag, i) => {
            const pill = document.createElement('span');
            pill.className = 'tag-pill-item';
            pill.textContent = tag;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'tag-pill-remove';
            btn.setAttribute('aria-label', 'Törlés: ' + tag);
            btn.textContent = '×';
            btn.addEventListener('click', () => {
                tags.splice(i, 1);
                renderPills();
                syncHidden();
            });

            pill.appendChild(btn);
            pillsWrap.appendChild(pill);
        });
    }

    function addTag(raw) {
        const parts = raw.split(/[,;]+/).map(t => t.trim()).filter(Boolean);
        parts.forEach(part => {
            if (
                part.length > 0 &&
                tags.length < MAX_TAGS &&
                !tags.includes(part)
            ) {
                tags.push(part);
            }
        });
        renderPills();
        syncHidden();
    }

    textInput.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ',' || e.key === ';') {
            e.preventDefault();
            const val = textInput.value.trim();
            if (val) addTag(val);
            textInput.value = '';
        }
        if (e.key === 'Backspace' && textInput.value === '' && tags.length > 0) {
            tags.pop();
            renderPills();
            syncHidden();
        }
    });

    textInput.addEventListener('input', () => {
        const datalist = document.getElementById('tag-datalist');
        if (!datalist) return;
        const options = Array.from(datalist.options).map(o => o.value);
        if (options.includes(textInput.value.trim())) {
            addTag(textInput.value.trim());
            textInput.value = '';
        }
    });

    textInput.addEventListener('blur', () => {
        const val = textInput.value.trim();
        if (val) {
            addTag(val);
            textInput.value = '';
        }
    });

    widget.addEventListener('click', () => textInput.focus());
})();
