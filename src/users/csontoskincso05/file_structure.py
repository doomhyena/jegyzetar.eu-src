import matplotlib.pyplot as plt

tree = r"""jegyzetar.eu-src/
├── src/
│   ├── 2fa.php                              # Kétlépcsős hitelesítés oldal
│   ├── admin_panel.php                      # Adminisztrációs panel
│   ├── create_group.php                     # Csoport létrehozása oldal
│   ├── favorites.php                        # Kedvenc jegyzetek oldal
│   ├── forgotpass.php                       # Jelszó visszaállítás oldal
│   ├── group.php                            # Csoport részletek oldal
│   ├── groups.php                           # Csoportok listája oldal
│   ├── index.php                            # Főoldal
│   ├── messages.php                         # Üzenetek oldal
│   ├── note.php                             # Jegyzet részletek oldal
│   ├── notify.php                           # Értesítések oldal
│   ├── profile.php                          # Profil oldal
│   ├── reg-ver.php                          # E-mail aktivációs oldal
│   ├── reglog.php                           # Regisztráció és bejelentkezés oldal
│   ├── search.php                           # Keresés oldal
│   ├── upload.php                           # Feltöltés oldal
│   ├── .idea/                               # IDE konfigurációs fájlok
│   ├── assets/
│   │   ├── composer.json                    # Composer függőségek
│   │   ├── ads/                             # Hirdetések mappája
│   │   ├── css/
│   │   │   └── styles.css                   # Fő stíluslap
│   │   ├── img/                             # Képek mappája
│   │   ├── js/
│   │   │   └── script.js                    # JavaScript fájl
│   │   ├── logs/                            # Log fájlok
│   │   ├── oauth/
│   │   │   ├── discord-callback.php         # Discord OAuth callback
│   │   │   └── discord-login.php            # Discord OAuth login
│   │   ├── php/
│   │   │   ├── accept_friend.php            # Barát elfogadása
│   │   │   ├── add_friend.php               # Barát hozzáadása
│   │   │   ├── ads.php                      # Hirdetések kezelése
│   │   │   ├── db.php                       # Adatbázis kapcsolat
│   │   │   ├── delete.php                   # Fájlok törlése
│   │   │   ├── download.php                 # Fájlok letöltése
│   │   │   ├── findanything.php             # Keresési funkció
│   │   │   ├── footer.php                   # Footer megjelenítés
│   │   │   ├── functions.php                # Közös függvények
│   │   │   ├── group_actions.php            # Csoport műveletek
│   │   │   ├── group_init.php               # Csoport inicializálás
│   │   │   ├── lang.php                     # Nyelvi kezelés
│   │   │   ├── loadmessages.php             # Üzenetek betöltése
│   │   │   ├── logout.php                   # Kijelentkezés
│   │   │   ├── mail-2fa.php                 # 2FA e-mail küldés
│   │   │   ├── mail-regver.php              # Regisztrációs e-mail
│   │   │   ├── navbar.php                   # Navigációs sáv
│   │   │   └── report.php                   # Jelentések kezelése
│   │   ├── sql/
│   │   │   └── jegyzetar.sql                # Adatbázis dump
│   │   └── vendor/                          # Composer vendor könyvtár
│   ├── docs/
│   │   ├── CHANGELOG.md                     # Változásnapló
│   │   ├── dokumentáció.md                  # Ez a dokumentáció
│   │   └── img/                             # Dokumentáció képei
│   └── users/                               # Felhasználói fájlok
└── LICENSE                                  # Licensz fájl
"""

# Render as image
lines = tree.splitlines()
max_len = max(len(l) for l in lines)
# Scale figure height by number of lines
fig_w = 16
fig_h = max(10, len(lines) * 0.32)

fig, ax = plt.subplots(figsize=(fig_w, fig_h))
ax.axis('off')

ax.text(
    0.01, 0.99, tree,
    va='top', ha='left',
    family='monospace',
    fontsize=11
)

out_path = "/mnt/data/file_structure.png"
plt.savefig(out_path, dpi=200, bbox_inches='tight')
plt.close()

out_path
