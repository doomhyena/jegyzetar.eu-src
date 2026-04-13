using System;
using System.Diagnostics;
using System.Windows.Forms;
using MySql.Data.MySqlClient;

namespace jegyzetar
{
    public partial class Login : Form
    {
        string constring = "Server=localhost;Database=jegyzetar;Uid=root;Pwd=;";

        public Login()
        {
            InitializeComponent();
        }

        private void login_btn_Click(object sender, EventArgs e)
        {
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

                    string query = "SELECT id, username, password FROM users WHERE username = @username LIMIT 1";
                    using (MySqlCommand cmd = new MySqlCommand(query, con))
                    {
                        cmd.Parameters.AddWithValue("@username", usernameTb.Text.Trim());

                        using (MySqlDataReader reader = cmd.ExecuteReader())
                        {
                            if (reader.Read())
                            {
                                string hashedPassword = reader["password"].ToString();
                                if (BCrypt.Net.BCrypt.Verify(passwordTb.Text, hashedPassword))
                                {
                                    UserSession.UserId = Convert.ToInt32(reader["id"]);
                                    UserSession.Save();

                                    Main main = new Main();
                                    main.Show();
                                    this.Hide();
                                    return;
                                }
                            }

                            MessageBox.Show("Hibás felhasználónév vagy jelszó!");
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }

        private void login_btn_MouseEnter(object sender, EventArgs e)
        {
            login_btn.BackColor = System.Drawing.Color.FromArgb(140, 90, 200);
        }

        private void login_btn_MouseLeave(object sender, EventArgs e)
        {
            login_btn.BackColor = System.Drawing.Color.FromArgb(120, 70, 180);
        }

        private void registerBtn_Click(object sender, EventArgs e)
        {
            try
            {
                Process.Start(new ProcessStartInfo("https://jegyzetar.hu/reglog.php?form=reg") { UseShellExecute = true });
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }
    }
}
