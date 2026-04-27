using System;
using System.ComponentModel;
using System.Drawing;
using System.Windows.Forms;

namespace jegyzetar
{
    partial class NoteDetails
    {
        private System.ComponentModel.IContainer components = null;

        private System.Windows.Forms.Panel detailsPanel;
        private System.Windows.Forms.TableLayoutPanel detailsLayout;
        private System.Windows.Forms.Panel viewerPanel;
        private System.Windows.Forms.Label viewerPlaceholder;
        private System.Windows.Forms.FlowLayoutPanel buttonsPanel;

        private System.Windows.Forms.Label titleLbl;
        private System.Windows.Forms.Label uploadedByLbl;
        private System.Windows.Forms.Label subjectLbl;
        private System.Windows.Forms.Label tagsLbl;
        private System.Windows.Forms.Label descLbl;
        private System.Windows.Forms.Label infoLbl;
        private System.Windows.Forms.Button openExternalBtn;
        private System.Windows.Forms.Button likeBtn;
        private System.Windows.Forms.Label likesCountLbl;

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.detailsPanel = new System.Windows.Forms.Panel();
            this.detailsLayout = new System.Windows.Forms.TableLayoutPanel();
            this.titleLbl = new System.Windows.Forms.Label();
            this.uploadedByLbl = new System.Windows.Forms.Label();
            this.subjectLbl = new System.Windows.Forms.Label();
            this.tagsLbl = new System.Windows.Forms.Label();
            this.descLbl = new System.Windows.Forms.Label();
            this.infoLbl = new System.Windows.Forms.Label();
            this.buttonsPanel = new System.Windows.Forms.FlowLayoutPanel();
            this.likeBtn = new System.Windows.Forms.Button();
            this.likesCountLbl = new System.Windows.Forms.Label();
            this.openExternalBtn = new System.Windows.Forms.Button();
            this.viewerPanel = new System.Windows.Forms.Panel();
            this.pdfViewer1 = new PdfiumViewer.PdfViewer();
            this.viewerPlaceholder = new System.Windows.Forms.Label();
            this.detailsPanel.SuspendLayout();
            this.detailsLayout.SuspendLayout();
            this.buttonsPanel.SuspendLayout();
            this.viewerPanel.SuspendLayout();
            this.SuspendLayout();
            // 
            // detailsPanel
            // 
            this.detailsPanel.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(28)))), ((int)(((byte)(28)))), ((int)(((byte)(38)))));
            this.detailsPanel.Controls.Add(this.detailsLayout);
            this.detailsPanel.Dock = System.Windows.Forms.DockStyle.Left;
            this.detailsPanel.Location = new System.Drawing.Point(0, 0);
            this.detailsPanel.Margin = new System.Windows.Forms.Padding(2);
            this.detailsPanel.Name = "detailsPanel";
            this.detailsPanel.Padding = new System.Windows.Forms.Padding(9, 10, 9, 10);
            this.detailsPanel.Size = new System.Drawing.Size(226, 488);
            this.detailsPanel.TabIndex = 1;
            // 
            // detailsLayout
            // 
            this.detailsLayout.ColumnCount = 1;
            this.detailsLayout.ColumnStyles.Add(new System.Windows.Forms.ColumnStyle(System.Windows.Forms.SizeType.Percent, 100F));
            this.detailsLayout.Controls.Add(this.titleLbl, 0, 0);
            this.detailsLayout.Controls.Add(this.uploadedByLbl, 0, 1);
            this.detailsLayout.Controls.Add(this.subjectLbl, 0, 2);
            this.detailsLayout.Controls.Add(this.tagsLbl, 0, 3);
            this.detailsLayout.Controls.Add(this.descLbl, 0, 5);
            this.detailsLayout.Controls.Add(this.infoLbl, 0, 6);
            this.detailsLayout.Controls.Add(this.buttonsPanel, 0, 7);
            this.detailsLayout.Dock = System.Windows.Forms.DockStyle.Fill;
            this.detailsLayout.Location = new System.Drawing.Point(9, 10);
            this.detailsLayout.Margin = new System.Windows.Forms.Padding(2);
            this.detailsLayout.Name = "detailsLayout";
            this.detailsLayout.RowCount = 8;
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 28F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 16F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 16F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 16F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 6F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Percent, 50F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 57F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 65F));
            this.detailsLayout.Size = new System.Drawing.Size(208, 468);
            this.detailsLayout.TabIndex = 0;
            // 
            // titleLbl
            // 
            this.titleLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.titleLbl.Font = new System.Drawing.Font("Segoe UI", 14F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.titleLbl.ForeColor = System.Drawing.Color.White;
            this.titleLbl.Location = new System.Drawing.Point(2, 0);
            this.titleLbl.Margin = new System.Windows.Forms.Padding(2, 0, 2, 0);
            this.titleLbl.Name = "titleLbl";
            this.titleLbl.Size = new System.Drawing.Size(204, 28);
            this.titleLbl.TabIndex = 0;
            this.titleLbl.Text = "cim";
            this.titleLbl.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            // 
            // uploadedByLbl
            // 
            this.uploadedByLbl.AutoSize = true;
            this.uploadedByLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.uploadedByLbl.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(200)))), ((int)(((byte)(200)))), ((int)(((byte)(210)))));
            this.uploadedByLbl.Location = new System.Drawing.Point(2, 28);
            this.uploadedByLbl.Margin = new System.Windows.Forms.Padding(2, 0, 2, 0);
            this.uploadedByLbl.Name = "uploadedByLbl";
            this.uploadedByLbl.Size = new System.Drawing.Size(204, 16);
            this.uploadedByLbl.TabIndex = 1;
            this.uploadedByLbl.Text = "feltöltö";
            // 
            // subjectLbl
            // 
            this.subjectLbl.AutoSize = true;
            this.subjectLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.subjectLbl.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(200)))), ((int)(((byte)(200)))), ((int)(((byte)(210)))));
            this.subjectLbl.Location = new System.Drawing.Point(2, 44);
            this.subjectLbl.Margin = new System.Windows.Forms.Padding(2, 0, 2, 0);
            this.subjectLbl.Name = "subjectLbl";
            this.subjectLbl.Size = new System.Drawing.Size(204, 16);
            this.subjectLbl.TabIndex = 2;
            this.subjectLbl.Text = "tantargy";
            // 
            // tagsLbl
            // 
            this.tagsLbl.AutoSize = true;
            this.tagsLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.tagsLbl.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(200)))), ((int)(((byte)(200)))), ((int)(((byte)(210)))));
            this.tagsLbl.Location = new System.Drawing.Point(2, 60);
            this.tagsLbl.Margin = new System.Windows.Forms.Padding(2, 0, 2, 0);
            this.tagsLbl.Name = "tagsLbl";
            this.tagsLbl.Size = new System.Drawing.Size(204, 16);
            this.tagsLbl.TabIndex = 3;
            this.tagsLbl.Text = "tags";
            // 
            // descLbl
            // 
            this.descLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.descLbl.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(200)))), ((int)(((byte)(200)))), ((int)(((byte)(210)))));
            this.descLbl.Location = new System.Drawing.Point(2, 82);
            this.descLbl.Margin = new System.Windows.Forms.Padding(2, 0, 2, 0);
            this.descLbl.Name = "descLbl";
            this.descLbl.Padding = new System.Windows.Forms.Padding(0, 5, 0, 0);
            this.descLbl.Size = new System.Drawing.Size(204, 264);
            this.descLbl.TabIndex = 4;
            this.descLbl.Text = "leirás";
            // 
            // infoLbl
            // 
            this.infoLbl.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(45)))), ((int)(((byte)(45)))), ((int)(((byte)(60)))));
            this.infoLbl.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.infoLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.infoLbl.ForeColor = System.Drawing.Color.White;
            this.infoLbl.Location = new System.Drawing.Point(2, 346);
            this.infoLbl.Margin = new System.Windows.Forms.Padding(2, 0, 2, 0);
            this.infoLbl.Name = "infoLbl";
            this.infoLbl.Padding = new System.Windows.Forms.Padding(4, 5, 4, 5);
            this.infoLbl.Size = new System.Drawing.Size(204, 57);
            this.infoLbl.TabIndex = 5;
            this.infoLbl.Text = "egyéb";
            // 
            // buttonsPanel
            // 
            this.buttonsPanel.AutoSize = true;
            this.buttonsPanel.Controls.Add(this.likeBtn);
            this.buttonsPanel.Controls.Add(this.likesCountLbl);
            this.buttonsPanel.Controls.Add(this.openExternalBtn);
            this.buttonsPanel.Dock = System.Windows.Forms.DockStyle.Fill;
            this.buttonsPanel.FlowDirection = System.Windows.Forms.FlowDirection.TopDown;
            this.buttonsPanel.Location = new System.Drawing.Point(2, 405);
            this.buttonsPanel.Margin = new System.Windows.Forms.Padding(2);
            this.buttonsPanel.Name = "buttonsPanel";
            this.buttonsPanel.Padding = new System.Windows.Forms.Padding(0, 6, 0, 0);
            this.buttonsPanel.Size = new System.Drawing.Size(204, 61);
            this.buttonsPanel.TabIndex = 6;
            // 
            // likeBtn
            // 
            this.likeBtn.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(120)))), ((int)(((byte)(70)))), ((int)(((byte)(180)))));
            this.likeBtn.FlatAppearance.BorderSize = 0;
            this.likeBtn.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.likeBtn.Font = new System.Drawing.Font("Segoe UI", 9F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.likeBtn.ForeColor = System.Drawing.Color.White;
            this.likeBtn.Location = new System.Drawing.Point(2, 8);
            this.likeBtn.Margin = new System.Windows.Forms.Padding(2);
            this.likeBtn.Name = "likeBtn";
            this.likeBtn.Size = new System.Drawing.Size(118, 24);
            this.likeBtn.TabIndex = 2;
            this.likeBtn.Text = "❤ Kedvencekhez";
            this.likeBtn.UseVisualStyleBackColor = false;
            this.likeBtn.Click += new System.EventHandler(this.LikeBtn_Click);
            // 
            // likesCountLbl
            // 
            this.likesCountLbl.AutoSize = true;
            this.likesCountLbl.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(180)))), ((int)(((byte)(180)))), ((int)(((byte)(200)))));
            this.likesCountLbl.Location = new System.Drawing.Point(2, 34);
            this.likesCountLbl.Margin = new System.Windows.Forms.Padding(2, 0, 2, 6);
            this.likesCountLbl.Name = "likesCountLbl";
            this.likesCountLbl.Padding = new System.Windows.Forms.Padding(0, 6, 0, 0);
            this.likesCountLbl.Size = new System.Drawing.Size(59, 19);
            this.likesCountLbl.TabIndex = 3;
            this.likesCountLbl.Text = "0 kedvelés";
            // 
            // openExternalBtn
            // 
            this.openExternalBtn.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(45)))), ((int)(((byte)(45)))), ((int)(((byte)(60)))));
            this.openExternalBtn.FlatAppearance.BorderSize = 0;
            this.openExternalBtn.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.openExternalBtn.ForeColor = System.Drawing.Color.White;
            this.openExternalBtn.Location = new System.Drawing.Point(124, 8);
            this.openExternalBtn.Margin = new System.Windows.Forms.Padding(2);
            this.openExternalBtn.Name = "openExternalBtn";
            this.openExternalBtn.Size = new System.Drawing.Size(80, 24);
            this.openExternalBtn.TabIndex = 1;
            this.openExternalBtn.Text = "Megnyitás";
            this.openExternalBtn.UseVisualStyleBackColor = false;
            this.openExternalBtn.Click += new System.EventHandler(this.OpenExternalBtn_Click);
            // 
            // viewerPanel
            // 
            this.viewerPanel.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(20)))), ((int)(((byte)(20)))), ((int)(((byte)(30)))));
            this.viewerPanel.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.viewerPanel.Controls.Add(this.pdfViewer1);
            this.viewerPanel.Controls.Add(this.viewerPlaceholder);
            this.viewerPanel.Dock = System.Windows.Forms.DockStyle.Fill;
            this.viewerPanel.Location = new System.Drawing.Point(226, 0);
            this.viewerPanel.Margin = new System.Windows.Forms.Padding(2);
            this.viewerPanel.Name = "viewerPanel";
            this.viewerPanel.Padding = new System.Windows.Forms.Padding(6);
            this.viewerPanel.Size = new System.Drawing.Size(502, 488);
            this.viewerPanel.TabIndex = 0;
            // 
            // pdfViewer1
            // 
            this.pdfViewer1.Location = new System.Drawing.Point(9, 6);
            this.pdfViewer1.Name = "pdfViewer1";
            this.pdfViewer1.ShowBookmarks = false;
            this.pdfViewer1.ShowToolbar = false;
            this.pdfViewer1.Size = new System.Drawing.Size(482, 467);
            this.pdfViewer1.TabIndex = 1;
            this.pdfViewer1.Load += new System.EventHandler(this.pdfViewer1_Load);
            // 
            // viewerPlaceholder
            // 
            this.viewerPlaceholder.Dock = System.Windows.Forms.DockStyle.Fill;
            this.viewerPlaceholder.Font = new System.Drawing.Font("Segoe UI", 10F, System.Drawing.FontStyle.Italic, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.viewerPlaceholder.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(150)))), ((int)(((byte)(150)))), ((int)(((byte)(170)))));
            this.viewerPlaceholder.Location = new System.Drawing.Point(6, 6);
            this.viewerPlaceholder.Margin = new System.Windows.Forms.Padding(2, 0, 2, 0);
            this.viewerPlaceholder.Name = "viewerPlaceholder";
            this.viewerPlaceholder.Size = new System.Drawing.Size(488, 474);
            this.viewerPlaceholder.TabIndex = 0;
            this.viewerPlaceholder.Text = "pdf";
            this.viewerPlaceholder.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
            // 
            // NoteDetails
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(728, 488);
            this.Controls.Add(this.viewerPanel);
            this.Controls.Add(this.detailsPanel);
            this.Margin = new System.Windows.Forms.Padding(2);
            this.Name = "NoteDetails";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterParent;
            this.Text = "Jegyzet";
            this.Load += new System.EventHandler(this.NoteDetails_Load);
            this.detailsPanel.ResumeLayout(false);
            this.detailsLayout.ResumeLayout(false);
            this.detailsLayout.PerformLayout();
            this.buttonsPanel.ResumeLayout(false);
            this.buttonsPanel.PerformLayout();
            this.viewerPanel.ResumeLayout(false);
            this.ResumeLayout(false);

        }

        /// <summary>
        /// Dispose resources
        /// </summary>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        private PdfiumViewer.PdfViewer pdfViewer1;
    }
}
