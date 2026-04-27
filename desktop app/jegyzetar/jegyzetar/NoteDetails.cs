using MySql.Data.MySqlClient;
using PdfiumViewer;
using System;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.Windows.Forms;

namespace jegyzetar
{
    public partial class NoteDetails : Form
    {
        private readonly int fileId;
        private readonly string constring;

        private string filePath;
        private string fileName;
        private bool isLiked = false;
        private int likesCount = 0;

        public NoteDetails(int fileId, string constring)
        {
            this.fileId = fileId;
            this.constring = constring;

            InitializeComponent();
        }

        private void NoteDetails_Load(object sender, EventArgs e)
        {
            LoadFileInfo();
            LoadLikeStatus();
            pdfViewer1_Load(this, EventArgs.Empty);
        }

        private void LoadFileInfo()
        {
            try
            {
                using (MySqlConnection con = new MySqlConnection(constring))
                {
                    con.Open();
                    string q = @"SELECT f.*, u.username
                                 FROM files f
                                 LEFT JOIN users u ON f.uploaded_by = u.id
                                 WHERE f.id = @id LIMIT 1";
                    using (MySqlCommand cmd = new MySqlCommand(q, con))
                    {
                        cmd.Parameters.AddWithValue("@id", fileId);
                        using (MySqlDataReader r = cmd.ExecuteReader())
                        {
                            if (r.Read())
                            {
                                string name = r["name"]?.ToString() ?? "";
                                string description = r["description"]?.ToString() ?? "";
                                string tags = r["tags"]?.ToString() ?? "";
                                string uploadedBy = r["username"]?.ToString() ?? "ismeretlen";
                                filePath = r["file_path"]?.ToString();
                                fileName = r["file_name"]?.ToString() ?? name;
                                long fileSize = 0;
                                if (r["file_size"] != DBNull.Value)
                                    long.TryParse(r["file_size"].ToString(), out fileSize);

                                titleLbl.Text = name;
                                uploadedByLbl.Text = $"Feltöltötte: {uploadedBy}";
                                subjectLbl.Text = $"Címkék: {tags}";
                                tagsLbl.Text = $"Megjegyzés: {description}";
                                descLbl.Text = description;

                                infoLbl.Text = $"Fájlnév: {fileName}\r\nMéret: {(fileSize > 0 ? (fileSize / 1024.0).ToString("0.##") + " KB" : "ismeretlen")}";
                            }
                            else
                            {
                                MessageBox.Show("A jegyzet nem található");
                                this.Close();
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show($"{ex.Message}");
                this.Close();
            }
        }

        private void LoadLikeStatus()
        {
            try
            {
                using (MySqlConnection con = new MySqlConnection(constring))
                {
                    con.Open();

                    string countQuery = "SELECT COUNT(*) FROM favorites WHERE file_id = @fileId";
                    using (MySqlCommand cmd = new MySqlCommand(countQuery, con))
                    {
                        cmd.Parameters.AddWithValue("@fileId", fileId);
                        likesCount = Convert.ToInt32(cmd.ExecuteScalar());
                    }

                    string checkQuery = "SELECT COUNT(*) FROM favorites WHERE file_id = @fileId AND user_id = @userId";
                    using (MySqlCommand cmd = new MySqlCommand(checkQuery, con))
                    {
                        cmd.Parameters.AddWithValue("@fileId", fileId);
                        cmd.Parameters.AddWithValue("@userId", UserSession.UserId);
                        isLiked = Convert.ToInt32(cmd.ExecuteScalar()) > 0;
                    }

                    UpdateLikeButton();
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Hiba a kedvelés állapotának betöltésekor: {ex.Message}");
            }
        }

        private void UpdateLikeButton()
        {
            if (isLiked)
            {
                likeBtn.Text = "💔 Eltávolítás";
                likeBtn.BackColor = Color.FromArgb(180, 70, 70);
            }
            else
            {
                likeBtn.Text = "❤ Kedvencekhez";
                likeBtn.BackColor = Color.FromArgb(120, 70, 180);
            }

            likesCountLbl.Text = likesCount == 1 ? "1 kedvelés" : $"{likesCount} kedvelés";
        }

        private void LikeBtn_Click(object sender, EventArgs e)
        {
            try
            {
                using (MySqlConnection con = new MySqlConnection(constring))
                {
                    con.Open();

                    if (isLiked)
                    {
                        // Remove from favorites
                        string deleteQuery = "DELETE FROM favorites WHERE file_id = @fileId AND user_id = @userId";
                        using (MySqlCommand cmd = new MySqlCommand(deleteQuery, con))
                        {
                            cmd.Parameters.AddWithValue("@fileId", fileId);
                            cmd.Parameters.AddWithValue("@userId", UserSession.UserId);
                            cmd.ExecuteNonQuery();
                        }

                        isLiked = false;
                        likesCount--;
                    }
                    else
                    {
                        // Add to favorites
                        string insertQuery = "INSERT INTO favorites (user_id, file_id, created_at) VALUES (@userId, @fileId, NOW())";
                        using (MySqlCommand cmd = new MySqlCommand(insertQuery, con))
                        {
                            cmd.Parameters.AddWithValue("@userId", UserSession.UserId);
                            cmd.Parameters.AddWithValue("@fileId", fileId);
                            cmd.ExecuteNonQuery();
                        }

                        isLiked = true;
                        likesCount++;
                    }

                    UpdateLikeButton();
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Hiba a kedvelés mentésekor: {ex.Message}");
            }
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

                MessageBox.Show("Sikeres letöltés.");
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Hiba másolás közben: {ex.Message}");
            }
        }

        private void OpenExternalBtn_Click(object sender, EventArgs e)
        {
            if (string.IsNullOrWhiteSpace(filePath))
            {
                MessageBox.Show("Nincs elérhető fájl az útvonal alapján.");
                return;
            }

            try
            {
                string normalized = filePath.Replace("C:xampp", "C:\\xampp").Replace("/", "\\");
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

                Process.Start(new ProcessStartInfo(sourceFull) { UseShellExecute = true });
            }
            catch (Exception ex)
            {
                MessageBox.Show($"{ex.Message}");
            }
        }

        //pdf megjlnites pdfium segitségével.
        private void pdfViewer1_Load(object sender, EventArgs e)
        {
            try
            {
                viewerPlaceholder.Visible = true;

                if (string.IsNullOrWhiteSpace(filePath))
                {
                    viewerPlaceholder.Text = "Nincs megjeleníthető PDF fájl.";
                    return;
                }

                string normalized = filePath.Replace("C:xampp", "C:\\xampp").Replace("/", "\\");
                string sourceFull = normalized;
                if (!Path.IsPathRooted(sourceFull))
                    sourceFull = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, sourceFull);

                if (!File.Exists(sourceFull))
                {
                    viewerPlaceholder.Text = $"Forrásfájl nem található: {sourceFull}";
                    return;
                }
                try
                {
                    if (pdfViewer1.Document != null)
                    {
                        pdfViewer1.Document.Dispose();
                        pdfViewer1.Document = null;
                    }
                }
                catch { }

                var doc = PdfDocument.Load(sourceFull);
                pdfViewer1.Document = doc;
                pdfViewer1.ZoomMode = PdfViewerZoomMode.FitWidth;

                viewerPlaceholder.Visible = false;
            }
            catch
            {
            }
        }
    }
}