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
        private System.Windows.Forms.Button downloadBtn;
        private System.Windows.Forms.Button openExternalBtn;

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
            this.downloadBtn = new System.Windows.Forms.Button();
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
            this.detailsPanel.Name = "detailsPanel";
            this.detailsPanel.Padding = new System.Windows.Forms.Padding(12);
            this.detailsPanel.Size = new System.Drawing.Size(302, 600);
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
            this.detailsLayout.Location = new System.Drawing.Point(12, 12);
            this.detailsLayout.Name = "detailsLayout";
            this.detailsLayout.RowCount = 8;
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 34F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 20F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 20F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 20F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 8F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Percent, 50F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 70F));
            this.detailsLayout.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Absolute, 48F));
            this.detailsLayout.Size = new System.Drawing.Size(278, 576);
            this.detailsLayout.TabIndex = 0;
            // 
            // titleLbl
            // 
            this.titleLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.titleLbl.Font = new System.Drawing.Font("Segoe UI", 14F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.titleLbl.ForeColor = System.Drawing.Color.White;
            this.titleLbl.Location = new System.Drawing.Point(3, 0);
            this.titleLbl.Name = "titleLbl";
            this.titleLbl.Size = new System.Drawing.Size(272, 34);
            this.titleLbl.TabIndex = 0;
            this.titleLbl.Text = "cim";
            this.titleLbl.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            // 
            // uploadedByLbl
            // 
            this.uploadedByLbl.AutoSize = true;
            this.uploadedByLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.uploadedByLbl.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(200)))), ((int)(((byte)(200)))), ((int)(((byte)(210)))));
            this.uploadedByLbl.Location = new System.Drawing.Point(3, 34);
            this.uploadedByLbl.Name = "uploadedByLbl";
            this.uploadedByLbl.Size = new System.Drawing.Size(272, 20);
            this.uploadedByLbl.TabIndex = 1;
            this.uploadedByLbl.Text = "feltöltö";
            // 
            // subjectLbl
            // 
            this.subjectLbl.AutoSize = true;
            this.subjectLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.subjectLbl.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(200)))), ((int)(((byte)(200)))), ((int)(((byte)(210)))));
            this.subjectLbl.Location = new System.Drawing.Point(3, 54);
            this.subjectLbl.Name = "subjectLbl";
            this.subjectLbl.Size = new System.Drawing.Size(272, 20);
            this.subjectLbl.TabIndex = 2;
            this.subjectLbl.Text = "tantargy";
            // 
            // tagsLbl
            // 
            this.tagsLbl.AutoSize = true;
            this.tagsLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.tagsLbl.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(200)))), ((int)(((byte)(200)))), ((int)(((byte)(210)))));
            this.tagsLbl.Location = new System.Drawing.Point(3, 74);
            this.tagsLbl.Name = "tagsLbl";
            this.tagsLbl.Size = new System.Drawing.Size(272, 20);
            this.tagsLbl.TabIndex = 3;
            this.tagsLbl.Text = "tags";
            // 
            // descLbl
            // 
            this.descLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.descLbl.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(200)))), ((int)(((byte)(200)))), ((int)(((byte)(210)))));
            this.descLbl.Location = new System.Drawing.Point(3, 102);
            this.descLbl.Name = "descLbl";
            this.descLbl.Padding = new System.Windows.Forms.Padding(0, 6, 0, 0);
            this.descLbl.Size = new System.Drawing.Size(272, 356);
            this.descLbl.TabIndex = 4;
            this.descLbl.Text = "leirás";
            // 
            // infoLbl
            // 
            this.infoLbl.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(45)))), ((int)(((byte)(45)))), ((int)(((byte)(60)))));
            this.infoLbl.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.infoLbl.Dock = System.Windows.Forms.DockStyle.Fill;
            this.infoLbl.ForeColor = System.Drawing.Color.White;
            this.infoLbl.Location = new System.Drawing.Point(3, 458);
            this.infoLbl.Name = "infoLbl";
            this.infoLbl.Padding = new System.Windows.Forms.Padding(6);
            this.infoLbl.Size = new System.Drawing.Size(272, 70);
            this.infoLbl.TabIndex = 5;
            this.infoLbl.Text = "egyéb";
            // 
            // buttonsPanel
            // 
            this.buttonsPanel.AutoSize = true;
            this.buttonsPanel.Controls.Add(this.downloadBtn);
            this.buttonsPanel.Controls.Add(this.openExternalBtn);
            this.buttonsPanel.Dock = System.Windows.Forms.DockStyle.Fill;
            this.buttonsPanel.Location = new System.Drawing.Point(3, 531);
            this.buttonsPanel.Name = "buttonsPanel";
            this.buttonsPanel.Padding = new System.Windows.Forms.Padding(0, 8, 0, 0);
            this.buttonsPanel.Size = new System.Drawing.Size(272, 42);
            this.buttonsPanel.TabIndex = 6;
            // 
            // downloadBtn
            // 
            this.downloadBtn.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(45)))), ((int)(((byte)(45)))), ((int)(((byte)(60)))));
            this.downloadBtn.FlatAppearance.BorderSize = 0;
            this.downloadBtn.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.downloadBtn.ForeColor = System.Drawing.Color.White;
            this.downloadBtn.Location = new System.Drawing.Point(3, 11);
            this.downloadBtn.Name = "downloadBtn";
            this.downloadBtn.Size = new System.Drawing.Size(120, 30);
            this.downloadBtn.TabIndex = 0;
            this.downloadBtn.Text = "Letöltés";
            this.downloadBtn.UseVisualStyleBackColor = false;
            this.downloadBtn.Click += new System.EventHandler(this.DownloadBtn_Click);
            // 
            // openExternalBtn
            // 
            this.openExternalBtn.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(45)))), ((int)(((byte)(45)))), ((int)(((byte)(60)))));
            this.openExternalBtn.FlatAppearance.BorderSize = 0;
            this.openExternalBtn.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.openExternalBtn.ForeColor = System.Drawing.Color.White;
            this.openExternalBtn.Location = new System.Drawing.Point(129, 11);
            this.openExternalBtn.Name = "openExternalBtn";
            this.openExternalBtn.Size = new System.Drawing.Size(140, 30);
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
            this.viewerPanel.Location = new System.Drawing.Point(302, 0);
            this.viewerPanel.Name = "viewerPanel";
            this.viewerPanel.Padding = new System.Windows.Forms.Padding(8);
            this.viewerPanel.Size = new System.Drawing.Size(668, 600);
            this.viewerPanel.TabIndex = 0;
            // 
            // pdfViewer1
            // 
            this.pdfViewer1.Location = new System.Drawing.Point(12, 8);
            this.pdfViewer1.Margin = new System.Windows.Forms.Padding(4, 4, 4, 4);
            this.pdfViewer1.Name = "pdfViewer1";
            this.pdfViewer1.ShowBookmarks = false;
            this.pdfViewer1.ShowToolbar = false;
            this.pdfViewer1.Size = new System.Drawing.Size(642, 575);
            this.pdfViewer1.TabIndex = 1;
            this.pdfViewer1.Load += new System.EventHandler(this.pdfViewer1_Load);
            // 
            // viewerPlaceholder
            // 
            this.viewerPlaceholder.Dock = System.Windows.Forms.DockStyle.Fill;
            this.viewerPlaceholder.Font = new System.Drawing.Font("Segoe UI", 10F, System.Drawing.FontStyle.Italic, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.viewerPlaceholder.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(150)))), ((int)(((byte)(150)))), ((int)(((byte)(170)))));
            this.viewerPlaceholder.Location = new System.Drawing.Point(8, 8);
            this.viewerPlaceholder.Name = "viewerPlaceholder";
            this.viewerPlaceholder.Size = new System.Drawing.Size(650, 582);
            this.viewerPlaceholder.TabIndex = 0;
            this.viewerPlaceholder.Text = "pdf";
            this.viewerPlaceholder.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
            // 
            // NoteDetails
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(970, 600);
            this.Controls.Add(this.viewerPanel);
            this.Controls.Add(this.detailsPanel);
            this.Name = "NoteDetails";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterParent;
            this.Text = "Jegyzet";
            this.Load += new System.EventHandler(this.NoteDetails_Load);
            this.detailsPanel.ResumeLayout(false);
            this.detailsLayout.ResumeLayout(false);
            this.detailsLayout.PerformLayout();
            this.buttonsPanel.ResumeLayout(false);
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
