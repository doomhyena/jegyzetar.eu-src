using MySql.Data.MySqlClient;
using PdfiumViewer;
using System;
using System.Diagnostics;
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

        public NoteDetails(int fileId, string constring)
        {
            this.fileId = fileId;
            this.constring = constring;

            InitializeComponent();
        }

        private void NoteDetails_Load(object sender, EventArgs e)
        {
            LoadFileInfo();
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
                                string subject = r["subject"]?.ToString() ?? "";
                                string tags = r["tags"]?.ToString() ?? "";
                                string uploadedBy = r["username"]?.ToString() ?? "ismeretlen";
                                filePath = r["file_path"]?.ToString();
                                fileName = r["file_name"]?.ToString() ?? name;
                                long fileSize = 0;
                                if (r["file_size"] != DBNull.Value)
                                    long.TryParse(r["file_size"].ToString(), out fileSize);

                                titleLbl.Text = name;
                                uploadedByLbl.Text = $"Feltöltötte: {uploadedBy}";
                                subjectLbl.Text = $"Tantárgy: {subject}";
                                tagsLbl.Text = $"Címkék: {tags}";
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

        private void DownloadBtn_Click(object sender, EventArgs e)
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

                using (SaveFileDialog sfd = new SaveFileDialog())
                {
                    sfd.FileName = fileName;
                    sfd.Filter = "All files|*.*";
                    if (sfd.ShowDialog(this) == DialogResult.OK)
                    {
                        File.Copy(sourceFull, sfd.FileName, overwrite: true);
                        MessageBox.Show("Sikeres letöltés.");
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show($"{ex.Message}");
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