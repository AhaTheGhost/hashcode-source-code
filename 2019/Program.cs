using System;
using System.IO;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Slidy
{
    class Program
    {
        //public static string[] fName = { "a_example", "b_lovely_landscapes", "c_memorable_moments", "d_pet_pictures", "e_shiny_selfies" };
        //public static string[] fName = { "a_example", "b_lovely_landscapes"};
        //public static string[] fName = { "a_example" };
        public static string[] fName = { "d_pet_pictures" };

        public static List<string> slide = new List<string>();
        public static List<string> ptag = new List<string>();
        public static List<char> po = new List<char>();
        public static Boolean fn = false;
        static void Main(string[] args)
        {
            for (int j = 0; fName.Length > j; j++)
            {
                slide.Clear(); ptag.Clear(); po.Clear();

                Console.WriteLine("Reading " + fName[j] + "...");
                readFile(j);

                Console.WriteLine("Processing...");
                processFile();

                Console.WriteLine("Sorting...");
                sortFile();

                Console.WriteLine("Saving...");
                saveFile(j);

                Console.WriteLine("Done!\n");
            }

            Console.ReadKey();
        }

        static void readFile(int j)
        {
            string line;
            StreamReader file = new StreamReader(@"" + fName[j] + ".txt");

            int pn = 0;
            fn = false;
            while ((line = file.ReadLine()) != null)
            {
                if (line.Substring(0, 1) == "H")
                {
                    po.Add('H');
                    ptag.Add(String.Join(" ", line.Split(' ').Skip(2)));
                }

                else if (line.Substring(0, 1) == "V")
                {
                    po.Add('V');

                    if (fn == false)
                    {
                        ptag.Add(String.Join(" ", line.Split(' ').Skip(2)));
                        pn = ptag.ToArray().Length - 1;
                        fn = true;
                    }
                    else
                    {
                        ptag[pn] = ptag[pn] + " " + String.Join(" ", line.Split(' ').Skip(2));
                        fn = false;
                    }
                }
            }
            file.Close();
        }
        static void processFile()
        {
            fn = false;
            string Vslide = "";

            for (int i = 0; i < po.ToArray().Length; i++)
            {
                if (po[i] == 'H') { slide.Add("" + i); }

                else if (po[i] == 'V')
                {

                    if (fn == false) { Vslide = "" + i; fn = true; }

                    else
                    {
                        Vslide = Vslide + " " + i;
                        slide.Add(Vslide);
                        fn = false;
                    }
                }
            }
        }
        static void sortFile()
        {
            int j;
            string temp, tem;
            for (int i = 1; i < ptag.ToArray().Length; i++)
            {
                temp = ptag[i];
                tem = slide[i];
                for (j = i - 1; (j >= 0) && (ptag[j].Split().Intersect(ptag[i].Split()).Any() == true); j--)
                { ptag[j + 1] = ptag[j]; slide[j + 1] = slide[j]; }
                ptag[j + 1] = temp;
                slide[j + 1] = tem;
            }
        }

        static void saveFile(int j)
        {
            StreamWriter ofile = new StreamWriter(@"" + fName[j] + "_o.txt");

            ofile.WriteLine(slide.ToArray().Length);
            for (int i = 0; i < slide.ToArray().Length; i++) { ofile.WriteLine(slide[i]); }
            ofile.Close();
        }
    }
}