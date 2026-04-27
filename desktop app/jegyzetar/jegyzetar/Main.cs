using MySql.Data.MySqlClient;
using System;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.Windows.Forms;

namespace jegyzetar
{
    public partial class Main : Form
    {
        //jegyzetar nevű adatbazishoz csatlakozik
        string constring = "Server=localhost;Database=jegyzetar;Uid=root;Pwd=;"; //<-- ez a constirng csatlakozik a szerverhez, jelenleg localhosthoz
        int userId = UserSession.UserId;
        string username = "";

        public Main()
        {
            InitializeComponent();

            this.FormBorderStyle = FormBorderStyle.FixedSingle;
            this.MaximizeBox = false;
        }

        private void Main_Load(object sender, EventArgs e)
        {
            if (userId == -1)
            {
                Logout();
                return;
            }

            try
            {
                using (MySqlConnection con = new MySqlConnection(constring))
                {
                    con.Open();
                    if (con.State != System.Data.ConnectionState.Open)
                    {
                        MessageBox.Show("Sikertelen kapcsolódás!");
                        return;
                    }

                    string query = "SELECT username FROM users WHERE id = @userId LIMIT 1";
                    using (MySqlCommand cmd = new MySqlCommand(query, con))
                    {
                        cmd.Parameters.AddWithValue("@userId", userId);

                        using (MySqlDataReader reader = cmd.ExecuteReader())
                        {
                            if (reader.Read())
                            {
                                username = reader["username"].ToString();
                            }
                        }
                    }

                    welcomeLbl.Text = $"Üdvözöllek, {username}!";
                    SetProfileInitial(username);

                    LoadNotes();
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }

        private void SetProfileInitial(string name)
        {
            if (!string.IsNullOrEmpty(name))
            {
                profileInitialsLabel.Text = name[0].ToString().ToUpper();
            }
        }

        public void Logout()
        {
            UserSession.Clear();
            Login login = new Login();

            login.FormBorderStyle = FormBorderStyle.FixedSingle;
            login.MaximizeBox = false;

            login.Show();
            this.Hide();
        }

        private void logout_btn_Click(object sender, EventArgs e)
        {
            Logout();
        }

        private void profileInitialsLabel_Click(object sender, EventArgs e)
        {
            if (!profilePanel.Visible)
            {
                var screenPoint = profileInitialsLabel.PointToScreen(new Point(0, profileInitialsLabel.Height));
                var clientPoint = this.PointToClient(screenPoint);

                int x = clientPoint.X + profileInitialsLabel.Width - profilePanel.Width;
                int y = topPanel.Bottom;
                if (x < topPanel.Left) x = clientPoint.X;

                profilePanel.Location = new Point(x, y);
                profilePanel.BringToFront();
                profilePanel.Visible = true;
            }
            else
            {
                profilePanel.Visible = false;
            }
        }

        private void profileInitialsLabel_MouseEnter(object sender, EventArgs e)
        {
            profileInitialsLabel.BackColor = Color.FromArgb(140, 90, 200);
        }

        private void profileInitialsLabel_MouseLeave(object sender, EventArgs e)
        {
            profileInitialsLabel.BackColor = Color.FromArgb(120, 70, 180);
        }

        private void profileBtn_Click(object sender, EventArgs e)
        {
            try
            {
                if (string.IsNullOrWhiteSpace(username))
                {
                    MessageBox.Show("A felhasználónév nem érhető el.");
                    return;
                }

                string encodedUsername = Uri.EscapeDataString(username);
                string profileUrl = $"https://jegyzetar.hu/profile.php?username={encodedUsername}";

                Process.Start(new ProcessStartInfo(profileUrl) { UseShellExecute = true });
            }
            catch (Exception ex)
            {
                MessageBox.Show($"{ex.Message}");
            }
        }

        private void menuBtn_Click(object sender, EventArgs e)
        {
            profilePanel.Visible = false;

            menuBtn1.BackColor = Color.Transparent;
            menuBtn2.BackColor = Color.Transparent;
            menuBtn3.BackColor = Color.Transparent;

            menuBtn1.ForeColor = Color.FromArgb(180, 180, 200);
            menuBtn2.ForeColor = Color.FromArgb(180, 180, 200);
            menuBtn3.ForeColor = Color.FromArgb(180, 180, 200);
            Button clickedBtn = sender as Button;
            clickedBtn.BackColor = Color.FromArgb(120, 70, 180);
            clickedBtn.ForeColor = Color.White;

            if (clickedBtn == menuBtn1)
            {
                settingsPanel.Visible = false;
                favoritesFlowPanel.Visible = false;
                notesFlowPanel.Visible = true;
                searchPanel.Visible = true;
                welcomeLbl.Visible = true;
                LoadNotes();
            }
            else if (clickedBtn == menuBtn2)
            {
                settingsPanel.Visible = false;
                notesFlowPanel.Visible = false;
                favoritesFlowPanel.Visible = true;
                searchPanel.Visible = false;
                welcomeLbl.Visible = false;
                LoadFavorites();
            }
            else if (clickedBtn == menuBtn3)
            {
                notesFlowPanel.Visible = false;
                favoritesFlowPanel.Visible = false;
                searchPanel.Visible = false;
                welcomeLbl.Visible = false;
                settingsPanel.Visible = true;
                LoadDownloadedNotes();
            }
        }

        private void menuBtn_MouseEnter(object sender, EventArgs e)
        {
            Button btn = sender as Button;
            if (btn.BackColor != Color.FromArgb(120, 70, 180))
            {
                btn.BackColor = Color.FromArgb(35, 35, 50);
            }
        }

        private void menuBtn_MouseLeave(object sender, EventArgs e)
        {
            Button btn = sender as Button;
            if (btn.BackColor != Color.FromArgb(120, 70, 180))
            {
                btn.BackColor = Color.Transparent;
            }
        }

        private void searchTextBox_Enter(object sender, EventArgs e)
        {
            if (searchTextBox.Text == "🔍 Keresés...")
            {
                searchTextBox.Text = "";
                searchTextBox.ForeColor = Color.White;
            }
        }

        private void searchTextBox_Leave(object sender, EventArgs e)
        {
            if (string.IsNullOrWhiteSpace(searchTextBox.Text))
            {
                searchTextBox.Text = "🔍 Keresés...";
                searchTextBox.ForeColor = Color.FromArgb(150, 150, 170);
            }
        }

        private void searchTextBox_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Enter)
            {
                e.SuppressKeyPress = true;
                PerformSearch(searchTextBox.Text);
            }
        }

        private void PerformSearch(string query)
        {
            notesFlowPanel.Controls.Clear();

            try
            {
                using (MySqlConnection con = new MySqlConnection(constring))
                {
                    con.Open();

                    string sql = "SELECT id, name, description, tags, uploaded_by FROM files " +
                                 "WHERE name LIKE @query OR description LIKE @query OR tags LIKE @query " +
                                 "ORDER BY id DESC";
                    using (MySqlCommand cmd = new MySqlCommand(sql, con))
                    {
                        cmd.Parameters.AddWithValue("@query", $"%{query}%");

                        using (MySqlDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                var id = reader.GetInt32("id");
                                var name = reader["name"]?.ToString() ?? "";
                                var desc = reader["description"]?.ToString() ?? "";
                                var tags = reader["tags"]?.ToString() ?? "";

                                var card = CreateNoteCard(id, name, desc, tags);
                                notesFlowPanel.Controls.Add(card);
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show($"{ex.Message}");
            }
        }

        private void LoadNotes()
        {
            notesFlowPanel.Controls.Clear();

            try
            {
                using (MySqlConnection con = new MySqlConnection(constring))
                {
                    con.Open();
                    string query = "SELECT id, name, description, tags FROM files ORDER BY id DESC";

                    using (MySqlCommand cmd = new MySqlCommand(query, con))
                    using (MySqlDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            int id = Convert.ToInt32(reader["id"]);
                            string name = reader["name"]?.ToString() ?? "";
                            string description = reader["description"]?.ToString() ?? "";
                            string tags = reader["tags"]?.ToString() ?? "";

                            var card = CreateNoteCard(id, name, description, tags);
                            notesFlowPanel.Controls.Add(card);
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Hiba: {ex.Message}");
            }
        }

        private void LoadFavorites()
        {
            favoritesFlowPanel.Controls.Clear();
            settingsPanel.Visible = false;
            notesFlowPanel.Visible = false;

            try
            {
                using (MySqlConnection con = new MySqlConnection(constring))
                {
                    con.Open();

                    string query = @"SELECT f.id AS fav_id, fl.id, fl.name, fl.description, fl.tags
                                     FROM favorites f
                                     JOIN files fl ON f.file_id = fl.id
                                     WHERE f.user_id = @userId
                                     ORDER BY f.created_at DESC";
                    using (MySqlCommand cmd = new MySqlCommand(query, con))
                    {
                        cmd.Parameters.AddWithValue("@userId", userId);

                        using (MySqlDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                var id = Convert.ToInt32(reader["id"]);
                                var name = reader["name"]?.ToString() ?? "";
                                var desc = reader["description"]?.ToString() ?? "";
                                var tags = reader["tags"]?.ToString() ?? "";

                                var card = CreateNoteCard(id, name, desc, tags);
                                favoritesFlowPanel.Controls.Add(card);
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show($"{ex.Message}");
            }
        }

        private Control CreateNoteCard(int id, string name, string description, string tags)
        {
            var panel = new Panel
            {
                Width = notesFlowPanel.ClientSize.Width - 25,
                Height = 90,
                BackColor = Color.FromArgb(30, 30, 45),
                Margin = new Padding(6)
            };

            var title = new Label
            {
                Text = name,
                Font = new Font("Segoe UI", 11F, FontStyle.Bold),
                ForeColor = Color.White,
                Location = new Point(10, 8),
                AutoSize = false,
                Width = panel.Width - 140,
                Height = 24,
                Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right
            };

            var subj = new Label
            {
                Text = tags,
                Font = new Font("Segoe UI", 9F),
                ForeColor = Color.FromArgb(180, 180, 200),
                Location = new Point(10, 34),
                AutoSize = false,
                Width = panel.Width - 140,
                Height = 18,
                Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right
            };

            var desc = new Label
            {
                Text = description,
                Font = new Font("Segoe UI", 9F),
                ForeColor = Color.FromArgb(160, 160, 180),
                Location = new Point(10, 52),
                AutoEllipsis = true,
                Width = panel.Width - 140,
                Height = 30,
                Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right
            };

            var detailsBtn = new Button
            {
                Text = "Részletek",
                BackColor = Color.FromArgb(120, 70, 180),
                ForeColor = Color.White,
                FlatStyle = FlatStyle.Flat,
                Location = new Point(panel.Width - 120, 18),
                Size = new Size(100, 30),
                Tag = id,
                Anchor = AnchorStyles.Top | AnchorStyles.Right
            };
            detailsBtn.FlatAppearance.BorderSize = 0;
            detailsBtn.Click += DetailsBtn_Click;

            bool alreadyDownloaded = IsNoteDownloaded(id);

            var downloadBtn = new Button
            {
                Text = alreadyDownloaded ? "Letöltve" : "Letöltés",
                BackColor = alreadyDownloaded ? Color.White  : Color.FromArgb(45, 45, 60),
                ForeColor = alreadyDownloaded ? Color.White : Color.White,
                FlatStyle = FlatStyle.Flat,
                Location = new Point(panel.Width - 120, 50),
                Size = new Size(100, 30),
                Tag = id,
                Anchor = AnchorStyles.Top | AnchorStyles.Right,
                Enabled = !alreadyDownloaded
            };
            downloadBtn.FlatAppearance.BorderSize = 0;
            if (!alreadyDownloaded)
                downloadBtn.Click += DownloadBtn_Click;

            panel.Controls.Add(title);
            panel.Controls.Add(subj);
            panel.Controls.Add(desc);
            panel.Controls.Add(detailsBtn);
            panel.Controls.Add(downloadBtn);

            return panel;
        }
        /// <summary>
        ///      --------
        /// </summary>

        private Control CreateDownloadedNoteCard(string filePath, int originalId)
        {
            var panel = new Panel
            {
                Width = settingsPanel.ClientSize.Width - 25,
                Height = 90,
                BackColor = Color.FromArgb(30, 30, 45),
                Margin = new Padding(6)
            };

            string fileName = Path.GetFileName(filePath);
            var title = new Label
            {
                Text = fileName,
                Font = new Font("Segoe UI", 11F, FontStyle.Bold),
                ForeColor = Color.White,
                Location = new Point(10, 8),
                AutoSize = false,
                Width = panel.Width - 140,
                Height = 24,
                Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right
            };

            var subj = new Label
            {
                Text = string.Empty,
                Font = new Font("Segoe UI", 9F),
                ForeColor = Color.FromArgb(180, 180, 200),
                Location = new Point(10, 34),
                AutoSize = false,
                Width = panel.Width - 140,
                Height = 18,
                Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right
            };

            var desc = new Label
            {
                Text = $"Útvonal: {filePath}",
                Font = new Font("Segoe UI", 8.5F),
                ForeColor = Color.FromArgb(160, 160, 180),
                Location = new Point(10, 52),
                AutoEllipsis = true,
                Width = panel.Width - 140,
                Height = 30,
                Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right
            };

            var detailsBtn = new Button
            {
                Text = "Részletek",
                BackColor = Color.FromArgb(120, 70, 180),
                ForeColor = Color.White,
                FlatStyle = FlatStyle.Flat,
                Location = new Point(panel.Width - 240, 30),
                Size = new Size(100, 30),
                Tag = originalId,
                Anchor = AnchorStyles.Top | AnchorStyles.Right
            };
            detailsBtn.FlatAppearance.BorderSize = 0;
            detailsBtn.Click += DetailsBtn_Click;
            detailsBtn.Enabled = originalId != -1;

            var externalBtn = new Button
            {
                Text = "Külső megnyitás",
                BackColor = Color.FromArgb(45, 45, 60),
                ForeColor = Color.White,
                FlatStyle = FlatStyle.Flat,
                Location = new Point(panel.Width - 120, 30),
                Size = new Size(100, 30),
                Tag = filePath,
                Anchor = AnchorStyles.Top | AnchorStyles.Right
            };
            externalBtn.FlatAppearance.BorderSize = 0;
            externalBtn.Click += ExternalOpenBtn_Click;

            panel.Controls.Add(title);
            panel.Controls.Add(subj);
            panel.Controls.Add(desc);
            panel.Controls.Add(detailsBtn);
            panel.Controls.Add(externalBtn);

            return panel;
        }

        private void DetailsBtn_Click(object sender, EventArgs e)
        {
            var btn = sender as Button;
            int id = Convert.ToInt32(btn.Tag);

            if (id == -1)
            {
                MessageBox.Show("Eredeti jegyzet azonosító nem áll rendelkezésre.");
                return;
            }

            try
            {
                using (var detailsForm = new NoteDetails(id, constring))
                {
                    detailsForm.ShowDialog(this);
                }
            }
            catch
            {
            }
        }

        private bool IsNoteDownloaded(int id)
        {
            string destFolder = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.MyDocuments), "jegyzetar");
            if (!Directory.Exists(destFolder))
                return false;

            foreach (string metaFile in Directory.GetFiles(destFolder, "*.meta"))
            {
                try
                {
                    string content = File.ReadAllText(metaFile).Trim();
                    if (content == id.ToString())
                        return true;
                }
                catch { }
            }
            return false;
        }

        private void DownloadBtn_Click(object sender, EventArgs e)
        {
            var btn = sender as Button;
            int id = Convert.ToInt32(btn.Tag);

            try
            {
                DownloadFileById(id);
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Letöltés sikertelen: {ex.Message}");
            }
        }

        private void ExternalOpenBtn_Click(object sender, EventArgs e)
        {
            var btn = sender as Button;
            string path = btn.Tag as string;
            if (string.IsNullOrEmpty(path))
            {
                MessageBox.Show("Fájlelérési út nem található.");
                return;
            }

            try
            {
                Process.Start(new ProcessStartInfo(path) { UseShellExecute = true });
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Nem sikerült megnyitni a fájlt: {ex.Message}");
            }
        }

        private void DownloadFileById(int id)
        {
            string srcPath = null;
            string displayName = null;

            using (MySqlConnection con = new MySqlConnection(constring))
            {
                con.Open();
                string q = "SELECT file_path, file_name, name FROM files WHERE id = @id LIMIT 1";
                using (MySqlCommand cmd = new MySqlCommand(q, con))
                {
                    cmd.Parameters.AddWithValue("@id", id);
                    using (MySqlDataReader r = cmd.ExecuteReader())
                    {
                        if (r.Read())
                        {
                            srcPath = r["file_path"]?.ToString();
                            displayName = r["file_name"]?.ToString() ?? r["name"]?.ToString() ?? $"note_{id}";
                        }
                        else
                        {
                            MessageBox.Show("A jegyzet nem található az adatbázisban.");
                            return;
                        }
                    }
                }
            }

            if (string.IsNullOrWhiteSpace(srcPath))
            {
                MessageBox.Show("A jegyzet forrásútvonala nincs megadva.");
                return;
            }

            try
            {
                string normalized = srcPath.Replace("C:xampp", "C:\\xampp").Replace("/", "\\");
                string sourceFull = normalized;
                if (!Path.IsPathRooted(sourceFull))
                {
                    sourceFull = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, sourceFull);
                }

                if (!File.Exists(sourceFull))
                {
                    MessageBox.Show($"Forrásfájl nem található: {sourceFull}");
                    return;
                }

                string destFolder = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.MyDocuments), "jegyzetar");
                Directory.CreateDirectory(destFolder);

                string destFileName = displayName;
                string destFull = Path.Combine(destFolder, destFileName);

                if (File.Exists(destFull))
                {
                    int i = 1;
                    string nameOnly = Path.GetFileNameWithoutExtension(destFileName);
                    string ext = Path.GetExtension(destFileName);
                    string candidate;
                    do
                    {
                        candidate = $"{nameOnly} ({i}){ext}";
                        destFull = Path.Combine(destFolder, candidate);
                        i++;
                    } while (File.Exists(destFull));
                }

                File.Copy(sourceFull, destFull, overwrite: false);

                string metaPath = destFull + ".meta";
                try
                {
                    File.WriteAllText(metaPath, id.ToString());
                }
                catch
                {
                }

                //MessageBox.Show("Sikeres letöltés.");

                if (notesFlowPanel.Visible)
                {
                    LoadNotes();
                }
                else if (favoritesFlowPanel.Visible)
                {
                    LoadFavorites();
                }

                if (settingsPanel.Visible)
                {
                    LoadDownloadedNotes();
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Hiba másolás közben: {ex.Message}");
            }
        }

        private void OpenDownloadsFolder()
        {
            string destFolder = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.MyDocuments), "jegyzetar");
            if (!Directory.Exists(destFolder))
            {
                MessageBox.Show($"A célmappa nem található: {destFolder}");
                return;
            }

            try
            {
                Process.Start(new ProcessStartInfo(destFolder) { UseShellExecute = true });
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Nem sikerült megnyitni a mappát: {ex.Message}");
            }
        }

        //letöltés meta fájlok seigtségével, .meta létezik akkor megjelenik az a letöltött jegyzet
        private void LoadDownloadedNotes()
        {
            settingsPanel.Controls.Clear();

            string destFolder = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.MyDocuments), "jegyzetar");
            if (!Directory.Exists(destFolder))
            {
                var lbl = new Label
                {
                    Text = "Nincsenek letöltött jegyzetek.",
                    ForeColor = Color.FromArgb(180, 180, 200),
                    Location = new Point(10, 10),
                    AutoSize = true
                };
                settingsPanel.Controls.Add(lbl);
                return;
            }

            var topBar = new Panel
            {
                Dock = DockStyle.Top,
                Height = 44,
                BackColor = Color.FromArgb(25, 25, 35)
            };

            var openFolderBtn = new Button
            {
                Text = "Célmappa megnyitása",
                BackColor = Color.FromArgb(120, 70, 180),
                ForeColor = Color.White,
                FlatStyle = FlatStyle.Flat,
                Dock = DockStyle.Right,
                Width = 160,
                Margin = new Padding(6)
            };
            openFolderBtn.FlatAppearance.BorderSize = 0;
            openFolderBtn.Click += (s, e) => OpenDownloadsFolder();

            topBar.Controls.Add(openFolderBtn);

            var downloadsFlow = new FlowLayoutPanel
            {
                Dock = DockStyle.Fill,
                AutoScroll = true,
                FlowDirection = FlowDirection.TopDown,
                WrapContents = false,
                Padding = new Padding(6)
            };

            downloadsFlow.ControlAdded += (s, e) =>
            {
                try
                {
                    e.Control.Width = Math.Max(100, downloadsFlow.ClientSize.Width - downloadsFlow.Padding.Horizontal - 10);
                }
                catch { }
            };
            downloadsFlow.Resize += (s, e) =>
            {
                try
                {
                    foreach (Control c in downloadsFlow.Controls)
                    {
                        c.Width = Math.Max(100, downloadsFlow.ClientSize.Width - downloadsFlow.Padding.Horizontal - 10);
                    }
                }
                catch { }
            };

            var files = Directory.GetFiles(destFolder);
            foreach (var file in files)
            {
                if (file.EndsWith(".meta", StringComparison.OrdinalIgnoreCase))
                    continue;

                int originalId = -1;
                string meta = file + ".meta";
                if (File.Exists(meta))
                {
                    var content = File.ReadAllText(meta).Trim();
                    int parsed;
                    if (int.TryParse(content, out parsed))
                    {
                        originalId = parsed;
                    }
                }

                var card = CreateDownloadedNoteCard(file, originalId);

                try
                {
                    int targetWidth = downloadsFlow.ClientSize.Width;
                    if (targetWidth <= 0) targetWidth = settingsPanel.ClientSize.Width;
                    card.Width = Math.Max(100, targetWidth - downloadsFlow.Padding.Horizontal - 10);
                }
                catch
                {
                }

                downloadsFlow.Controls.Add(card);
            }

            if (downloadsFlow.Controls.Count == 0)
            {
                var lbl = new Label
                {
                    Text = "Nincsenek letöltött jegyzetek.",
                    ForeColor = Color.FromArgb(180, 180, 200),
                    Location = new Point(10, 10),
                    AutoSize = true
                };
                downloadsFlow.Controls.Add(lbl);
            }

            settingsPanel.Controls.Add(downloadsFlow);
            settingsPanel.Controls.Add(topBar);

            downloadsFlow.PerformLayout();
            downloadsFlow.Refresh();
            try
            {
                foreach (Control c in downloadsFlow.Controls)
                {
                    c.Width = Math.Max(100, downloadsFlow.ClientSize.Width - downloadsFlow.Padding.Horizontal - 10);
                }
            }
            catch { }
        }
    }
}
