namespace jegyzetar
{
    partial class Main
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.welcomeLbl = new System.Windows.Forms.Label();
            this.logout_btn = new System.Windows.Forms.Button();
            this.sideMenuPanel = new System.Windows.Forms.Panel();
            this.menuBtn3 = new System.Windows.Forms.Button();
            this.menuBtn2 = new System.Windows.Forms.Button();
            this.menuBtn1 = new System.Windows.Forms.Button();
            this.menuLogo = new System.Windows.Forms.Label();
            this.topPanel = new System.Windows.Forms.Panel();
            this.profileInitialsLabel = new System.Windows.Forms.Label();
            this.profilePanel = new System.Windows.Forms.Panel();
            this.profileBtn = new System.Windows.Forms.Button();
            this.profileLogoutBtn = new System.Windows.Forms.Button();
            this.contentPanel = new System.Windows.Forms.Panel();
            this.settingsPanel = new System.Windows.Forms.Panel();
            this.settingsLabel = new System.Windows.Forms.Label();
            this.searchPanel = new System.Windows.Forms.Panel();
            this.searchTextBox = new System.Windows.Forms.TextBox();
            this.notesFlowPanel = new System.Windows.Forms.FlowLayoutPanel();
            this.favoritesFlowPanel = new System.Windows.Forms.FlowLayoutPanel();
            this.sideMenuPanel.SuspendLayout();
            this.topPanel.SuspendLayout();
            this.profilePanel.SuspendLayout();
            this.contentPanel.SuspendLayout();
            this.settingsPanel.SuspendLayout();
            this.searchPanel.SuspendLayout();
            this.SuspendLayout();
            // 
            // welcomeLbl
            // 
            this.welcomeLbl.AutoSize = true;
            this.welcomeLbl.Font = new System.Drawing.Font("Segoe UI", 16F, System.Drawing.FontStyle.Bold);
            this.welcomeLbl.ForeColor = System.Drawing.Color.White;
            this.welcomeLbl.Location = new System.Drawing.Point(30, 100);
            this.welcomeLbl.Name = "welcomeLbl";
            this.welcomeLbl.Size = new System.Drawing.Size(276, 37);
            this.welcomeLbl.TabIndex = 0;
            this.welcomeLbl.Text = "Üdvözöllek, valaki";
            // 
            // logout_btn (kept for event handler)
            // 
            this.logout_btn.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(45)))), ((int)(((byte)(45)))), ((int)(((byte)(60)))));
            this.logout_btn.FlatAppearance.BorderSize = 0;
            this.logout_btn.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.logout_btn.Font = new System.Drawing.Font("Segoe UI", 9F);
            this.logout_btn.ForeColor = System.Drawing.Color.White;
            this.logout_btn.Location = new System.Drawing.Point(10, 75);
            this.logout_btn.Name = "logout_btn";
            this.logout_btn.Size = new System.Drawing.Size(130, 35);
            this.logout_btn.TabIndex = 1;
            this.logout_btn.Text = "Kijelentkezés";
            this.logout_btn.UseVisualStyleBackColor = false;
            this.logout_btn.Click += new System.EventHandler(this.logout_btn_Click);
            // 
            // sideMenuPanel
            // 
            this.sideMenuPanel.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(25)))), ((int)(((byte)(25)))), ((int)(((byte)(40)))));
            this.sideMenuPanel.Controls.Add(this.menuBtn3);
            this.sideMenuPanel.Controls.Add(this.menuBtn2);
            this.sideMenuPanel.Controls.Add(this.menuBtn1);
            this.sideMenuPanel.Controls.Add(this.menuLogo);
            this.sideMenuPanel.Dock = System.Windows.Forms.DockStyle.Left;
            this.sideMenuPanel.Location = new System.Drawing.Point(0, 0);
            this.sideMenuPanel.Name = "sideMenuPanel";
            this.sideMenuPanel.Size = new System.Drawing.Size(220, 650);
            this.sideMenuPanel.TabIndex = 2;
            // 
            // menuBtn3 - Beállítások
            // 
            this.menuBtn3.BackColor = System.Drawing.Color.Transparent;
            this.menuBtn3.FlatAppearance.BorderSize = 0;
            this.menuBtn3.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.menuBtn3.Font = new System.Drawing.Font("Segoe UI", 11F);
            this.menuBtn3.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(180)))), ((int)(((byte)(180)))), ((int)(((byte)(200)))));
            this.menuBtn3.Location = new System.Drawing.Point(0, 260);
            this.menuBtn3.Name = "menuBtn3";
            this.menuBtn3.Padding = new System.Windows.Forms.Padding(20, 0, 0, 0);
            this.menuBtn3.Size = new System.Drawing.Size(220, 50);
            this.menuBtn3.TabIndex = 3;
            this.menuBtn3.Text = "⇓ Letöltések";
            this.menuBtn3.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            this.menuBtn3.UseVisualStyleBackColor = false;
            this.menuBtn3.Click += new System.EventHandler(this.menuBtn_Click);
            this.menuBtn3.MouseEnter += new System.EventHandler(this.menuBtn_MouseEnter);
            this.menuBtn3.MouseLeave += new System.EventHandler(this.menuBtn_MouseLeave);
            // 
            // menuBtn2 - Kedvencek
            // 
            this.menuBtn2.BackColor = System.Drawing.Color.Transparent;
            this.menuBtn2.FlatAppearance.BorderSize = 0;
            this.menuBtn2.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.menuBtn2.Font = new System.Drawing.Font("Segoe UI", 11F);
            this.menuBtn2.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(180)))), ((int)(((byte)(180)))), ((int)(((byte)(200)))));
            this.menuBtn2.Location = new System.Drawing.Point(0, 210);
            this.menuBtn2.Name = "menuBtn2";
            this.menuBtn2.Padding = new System.Windows.Forms.Padding(20, 0, 0, 0);
            this.menuBtn2.Size = new System.Drawing.Size(220, 50);
            this.menuBtn2.TabIndex = 2;
            this.menuBtn2.Text = "⭐ Kedvencek";
            this.menuBtn2.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            this.menuBtn2.UseVisualStyleBackColor = false;
            this.menuBtn2.Click += new System.EventHandler(this.menuBtn_Click);
            this.menuBtn2.MouseEnter += new System.EventHandler(this.menuBtn_MouseEnter);
            this.menuBtn2.MouseLeave += new System.EventHandler(this.menuBtn_MouseLeave);
            // 
            // menuBtn1 - Főoldal
            // 
            this.menuBtn1.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(120)))), ((int)(((byte)(70)))), ((int)(((byte)(180)))));
            this.menuBtn1.FlatAppearance.BorderSize = 0;
            this.menuBtn1.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.menuBtn1.Font = new System.Drawing.Font("Segoe UI", 11F);
            this.menuBtn1.ForeColor = System.Drawing.Color.White;
            this.menuBtn1.Location = new System.Drawing.Point(0, 160);
            this.menuBtn1.Name = "menuBtn1";
            this.menuBtn1.Padding = new System.Windows.Forms.Padding(20, 0, 0, 0);
            this.menuBtn1.Size = new System.Drawing.Size(220, 50);
            this.menuBtn1.TabIndex = 1;
            this.menuBtn1.Text = "🏠 Főoldal";
            this.menuBtn1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            this.menuBtn1.UseVisualStyleBackColor = false;
            this.menuBtn1.Click += new System.EventHandler(this.menuBtn_Click);
            this.menuBtn1.MouseEnter += new System.EventHandler(this.menuBtn_MouseEnter);
            this.menuBtn1.MouseLeave += new System.EventHandler(this.menuBtn_MouseLeave);
            // 
            // menuLogo
            // 
            this.menuLogo.AutoSize = true;
            this.menuLogo.Font = new System.Drawing.Font("Segoe UI", 18F, System.Drawing.FontStyle.Bold);
            this.menuLogo.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(120)))), ((int)(((byte)(70)))), ((int)(((byte)(180)))));
            this.menuLogo.Location = new System.Drawing.Point(40, 40);
            this.menuLogo.Name = "menuLogo";
            this.menuLogo.Size = new System.Drawing.Size(141, 41);
            this.menuLogo.TabIndex = 0;
            this.menuLogo.Text = "Jegyzetár";
            // 
            // topPanel
            // 
            this.topPanel.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(30)))), ((int)(((byte)(30)))), ((int)(((byte)(45)))));
            this.topPanel.Controls.Add(this.profileInitialsLabel);
            this.topPanel.Dock = System.Windows.Forms.DockStyle.Top;
            this.topPanel.Location = new System.Drawing.Point(220, 0);
            this.topPanel.Name = "topPanel";
            this.topPanel.Size = new System.Drawing.Size(880, 70);
            this.topPanel.TabIndex = 3;
            // 
            // profileInitialsLabel
            // 
            this.profileInitialsLabel.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(120)))), ((int)(((byte)(70)))), ((int)(((byte)(180)))));
            this.profileInitialsLabel.Cursor = System.Windows.Forms.Cursors.Hand;
            this.profileInitialsLabel.Font = new System.Drawing.Font("Segoe UI", 16F, System.Drawing.FontStyle.Bold);
            this.profileInitialsLabel.ForeColor = System.Drawing.Color.White;
            this.profileInitialsLabel.Location = new System.Drawing.Point(800, 10);
            this.profileInitialsLabel.Name = "profileInitialsLabel";
            this.profileInitialsLabel.Size = new System.Drawing.Size(50, 50);
            this.profileInitialsLabel.TabIndex = 0;
            this.profileInitialsLabel.Text = "U";
            this.profileInitialsLabel.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
            this.profileInitialsLabel.Click += new System.EventHandler(this.profileInitialsLabel_Click);
            this.profileInitialsLabel.MouseEnter += new System.EventHandler(this.profileInitialsLabel_MouseEnter);
            this.profileInitialsLabel.MouseLeave += new System.EventHandler(this.profileInitialsLabel_MouseLeave);
            // 
            // profilePanel (dropdown) - NOTE: added to form Controls later (not to topPanel) so it won't be clipped
            // 
            this.profilePanel.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(45)))), ((int)(((byte)(45)))), ((int)(((byte)(60)))));
            this.profilePanel.Controls.Add(this.profileBtn);
            this.profilePanel.Controls.Add(this.profileLogoutBtn);
            this.profilePanel.Name = "profilePanel";
            this.profilePanel.Size = new System.Drawing.Size(160, 90);
            this.profilePanel.TabIndex = 2;
            this.profilePanel.Visible = false;
            // 
            // profileBtn (opens website)
            // 
            this.profileBtn.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.profileBtn.ForeColor = System.Drawing.Color.White;
            this.profileBtn.BackColor = System.Drawing.Color.Transparent;
            this.profileBtn.FlatAppearance.BorderSize = 0;
            this.profileBtn.Location = new System.Drawing.Point(10, 10);
            this.profileBtn.Name = "profileBtn";
            this.profileBtn.Size = new System.Drawing.Size(140, 30);
            this.profileBtn.TabIndex = 0;
            this.profileBtn.Text = "Profil";
            this.profileBtn.UseVisualStyleBackColor = true;
            this.profileBtn.Click += new System.EventHandler(this.profileBtn_Click);
            // 
            // profileLogoutBtn
            // 
            this.profileLogoutBtn.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.profileLogoutBtn.ForeColor = System.Drawing.Color.White;
            this.profileLogoutBtn.BackColor = System.Drawing.Color.Transparent;
            this.profileLogoutBtn.FlatAppearance.BorderSize = 0;
            this.profileLogoutBtn.Location = new System.Drawing.Point(10, 45);
            this.profileLogoutBtn.Name = "profileLogoutBtn";
            this.profileLogoutBtn.Size = new System.Drawing.Size(140, 30);
            this.profileLogoutBtn.TabIndex = 1;
            this.profileLogoutBtn.Text = "Kijelentkezés";
            this.profileLogoutBtn.UseVisualStyleBackColor = true;
            this.profileLogoutBtn.Click += new System.EventHandler(this.logout_btn_Click);
            // 
            // contentPanel
            // 
            this.contentPanel.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(20)))), ((int)(((byte)(20)))), ((int)(((byte)(30)))));
            this.contentPanel.Controls.Add(this.settingsPanel);
            this.contentPanel.Controls.Add(this.favoritesFlowPanel);
            this.contentPanel.Controls.Add(this.notesFlowPanel);
            this.contentPanel.Controls.Add(this.searchPanel);
            this.contentPanel.Controls.Add(this.welcomeLbl);
            this.contentPanel.Dock = System.Windows.Forms.DockStyle.Fill;
            this.contentPanel.Location = new System.Drawing.Point(220, 70);
            this.contentPanel.Name = "contentPanel";
            this.contentPanel.Size = new System.Drawing.Size(880, 580);
            this.contentPanel.TabIndex = 4;
            // 
            // settingsPanel
            // 
            this.settingsPanel.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(28)))), ((int)(((byte)(28)))), ((int)(((byte)(38)))));
            this.settingsPanel.Controls.Add(this.settingsLabel);
            this.settingsPanel.Location = new System.Drawing.Point(30, 150);
            this.settingsPanel.Name = "settingsPanel";
            this.settingsPanel.Size = new System.Drawing.Size(820, 400);
            this.settingsPanel.TabIndex = 5;
            this.settingsPanel.Visible = false;
            // 
            // settingsLabel
            // 
            this.settingsLabel.AutoSize = true;
            this.settingsLabel.Font = new System.Drawing.Font("Segoe UI", 12F, System.Drawing.FontStyle.Regular);
            this.settingsLabel.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(200)))), ((int)(((byte)(200)))), ((int)(((byte)(210)))));
            this.settingsLabel.Location = new System.Drawing.Point(12, 12);
            this.settingsLabel.Name = "settingsLabel";
            this.settingsLabel.Size = new System.Drawing.Size(311, 28);
            this.settingsLabel.TabIndex = 0;
            this.settingsLabel.Text = "Beállítások";
            // 
            // searchPanel (contains searchTextBox)
            // 
            this.searchPanel.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(45)))), ((int)(((byte)(45)))), ((int)(((byte)(60)))));
            this.searchPanel.Controls.Add(this.searchTextBox);
            this.searchPanel.Location = new System.Drawing.Point(30, 20);
            this.searchPanel.Name = "searchPanel";
            this.searchPanel.Size = new System.Drawing.Size(600, 50);
            this.searchPanel.TabIndex = 2;
            // 
            // searchTextBox
            // 
            this.searchTextBox.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(45)))), ((int)(((byte)(45)))), ((int)(((byte)(60)))));
            this.searchTextBox.BorderStyle = System.Windows.Forms.BorderStyle.None;
            this.searchTextBox.Font = new System.Drawing.Font("Segoe UI", 12F);
            this.searchTextBox.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(150)))), ((int)(((byte)(150)))), ((int)(((byte)(170)))));
            this.searchTextBox.Location = new System.Drawing.Point(15, 12);
            this.searchTextBox.Name = "searchTextBox";
            this.searchTextBox.Size = new System.Drawing.Size(570, 27);
            this.searchTextBox.TabIndex = 1;
            this.searchTextBox.Text = "🔍 Keresés...";
            this.searchTextBox.Enter += new System.EventHandler(this.searchTextBox_Enter);
            this.searchTextBox.Leave += new System.EventHandler(this.searchTextBox_Leave);
            this.searchTextBox.KeyDown += new System.Windows.Forms.KeyEventHandler(this.searchTextBox_KeyDown);
            // 
            // notesFlowPanel
            // 
            this.notesFlowPanel.AutoScroll = true;
            this.notesFlowPanel.FlowDirection = System.Windows.Forms.FlowDirection.TopDown;
            this.notesFlowPanel.Location = new System.Drawing.Point(30, 150);
            this.notesFlowPanel.Name = "notesFlowPanel";
            this.notesFlowPanel.Size = new System.Drawing.Size(820, 400);
            this.notesFlowPanel.TabIndex = 3;
            this.notesFlowPanel.WrapContents = false;
            // 
            // favoritesFlowPanel
            // 
            this.favoritesFlowPanel.AutoScroll = true;
            this.favoritesFlowPanel.FlowDirection = System.Windows.Forms.FlowDirection.TopDown;
            this.favoritesFlowPanel.Location = new System.Drawing.Point(30, 150);
            this.favoritesFlowPanel.Name = "favoritesFlowPanel";
            this.favoritesFlowPanel.Size = new System.Drawing.Size(820, 400);
            this.favoritesFlowPanel.TabIndex = 4;
            this.favoritesFlowPanel.WrapContents = false;
            this.favoritesFlowPanel.Visible = false;
            // 
            // Main
            // 
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(1100, 650);
            this.Controls.Add(this.profilePanel); 
            this.Controls.Add(this.contentPanel);
            this.Controls.Add(this.topPanel);
            this.Controls.Add(this.sideMenuPanel);
            this.Name = "Main";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "Jegyzetár";
            this.Load += new System.EventHandler(this.Main_Load);
            this.sideMenuPanel.ResumeLayout(false);
            this.sideMenuPanel.PerformLayout();
            this.topPanel.ResumeLayout(false);
            this.profilePanel.ResumeLayout(false);
            this.contentPanel.ResumeLayout(false);
            this.contentPanel.PerformLayout();
            this.settingsPanel.ResumeLayout(false);
            this.settingsPanel.PerformLayout();
            this.searchPanel.ResumeLayout(false);
            this.searchPanel.PerformLayout();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.Label welcomeLbl;
        private System.Windows.Forms.Button logout_btn;
        private System.Windows.Forms.Panel sideMenuPanel;
        private System.Windows.Forms.Button menuBtn3;
        private System.Windows.Forms.Button menuBtn2;
        private System.Windows.Forms.Button menuBtn1;
        private System.Windows.Forms.Label menuLogo;
        private System.Windows.Forms.Panel topPanel;
        private System.Windows.Forms.Panel profilePanel;
        private System.Windows.Forms.Label profileInitialsLabel;
        private System.Windows.Forms.Panel contentPanel;
        private System.Windows.Forms.Panel searchPanel;
        private System.Windows.Forms.TextBox searchTextBox;
        private System.Windows.Forms.FlowLayoutPanel notesFlowPanel;
        private System.Windows.Forms.FlowLayoutPanel favoritesFlowPanel;
        private System.Windows.Forms.Button profileBtn;
        private System.Windows.Forms.Button profileLogoutBtn;
        private System.Windows.Forms.Panel settingsPanel;
        private System.Windows.Forms.Label settingsLabel;
    }
}