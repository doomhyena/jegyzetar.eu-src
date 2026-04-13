using System;
using System.IO;

namespace jegyzetar
{
    public static class UserSession
    {
        private static readonly string AppFolder = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData), "jegyzetar");
        private static readonly string SessionFile = Path.Combine(AppFolder, "session.txt");

        // -1 == nincs user signed in
        public static int UserId { get; set; } = -1;

        public static void Load()
        {
            try
            {
                if (!Directory.Exists(AppFolder))
                    Directory.CreateDirectory(AppFolder);

                if (File.Exists(SessionFile))
                {
                    var txt = File.ReadAllText(SessionFile).Trim();
                    if (int.TryParse(txt, out int id))
                        UserId = id;
                    else
                        UserId = -1;
                }
                else
                {
                    UserId = -1;
                }
            }
            catch
            {
                UserId = -1;
            }
        }

        public static void Save()
        {
            try
            {
                if (!Directory.Exists(AppFolder))
                    Directory.CreateDirectory(AppFolder);

                File.WriteAllText(SessionFile, UserId.ToString());
            }
            catch
            {
            }
        }

        public static void Clear()
        {
            UserId = -1;
            try
            {
                if (File.Exists(SessionFile))
                    File.Delete(SessionFile);
            }
            catch
            {
            }
        }
    }
}