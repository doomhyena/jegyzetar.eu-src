let friendId = new URLSearchParams(window.location.search).get('friendid');
let lastMessageCount = 0;

document.addEventListener('DOMContentLoaded', function () {
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
});

function checkNewMessages() {
    if (!friendId) return;
    $.get("assets/php/loadmessages.php?friendid=" + encodeURIComponent(friendId) + "&countonly=1", function (newCount) {
        if (parseInt(newCount) > lastMessageCount) {
            $(".messages").load("assets/php/loadmessages.php?friendid=" + encodeURIComponent(friendId));
            lastMessageCount = parseInt(newCount);
        }
    });
}
setInterval(checkNewMessages, 1000);

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
        statusDiv.text('❌ Nem küldhetsz üres üzenetet.').css('color', 'red');
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

    document.querySelectorAll('[data-switch]').forEach(switcher => {
        switcher.addEventListener('click', (e) => {
            e.preventDefault();
            const targetForm = switcher.getAttribute('data-switch');
            showForm(targetForm);
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const regForm = document.getElementById('reg');
    const loginForm = document.getElementById('login');

    function setView(which) {
        if (which === 'reg') {
            regForm.style.display = 'block';
            loginForm.style.display = 'none';
            document.title = 'Regisztráció';
            history.replaceState(null, '', '?form=reg');
        } else {
            regForm.style.display = 'none';
            loginForm.style.display = 'block';
            document.title = 'Bejelentkezés';
            history.replaceState(null, '', '?form=login');
        }
    }

    const urlForm = new URLSearchParams(location.search).get('form');
    setView(urlForm === 'reg' ? 'reg' : 'login');

    document.querySelectorAll('.switcher').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            setView(a.getAttribute('data-switch') === 'reg' ? 'reg' : 'login');
        });
    });
});
