using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace jegyzetar
{
    internal static class Program
    {
        /// <summary>
        /// The main entry point for the application.
        /// </summary>
        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);

            //userrek betöltése, ha van egyáltalán
            UserSession.Load();

            if (UserSession.UserId != -1)
            {
                Application.Run(new Main());
            }
            else
            {
                Application.Run(new Login());
            }
        }
    }
}
