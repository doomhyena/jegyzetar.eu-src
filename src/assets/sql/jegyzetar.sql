CREATE TABLE `2fa_codes` (
                             `id` int(11) NOT NULL,
                             `userid` int(11) NOT NULL,
                             `code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `2fa_codes`
--

INSERT INTO `2fa_codes` (`id`, `userid`, `code`) VALUES
                                                     (6, 4, 82329),
                                                     (8, 4, 26092),
                                                     (9, 4, 87859),
                                                     (13, 5, 90064);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `badges`
--

CREATE TABLE `badges` (
                          `id` int(11) NOT NULL,
                          `name` varchar(64) NOT NULL,
                          `slug` varchar(64) NOT NULL,
                          `description` varchar(255) DEFAULT NULL,
                          `icon` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `badges`
--

INSERT INTO `badges` (`id`, `name`, `slug`, `description`, `icon`) VALUES
    (1, 'Tulajdonos', 'owner', 'Az oldal tulajdonosa', '🔰');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `comments`
--

CREATE TABLE `comments` (
                            `id` int(11) NOT NULL,
                            `userid` int(11) NOT NULL,
                            `postid` int(11) NOT NULL,
                            `text` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `favorites`
--

CREATE TABLE `favorites` (
                             `id` int(11) NOT NULL,
                             `user_id` int(11) NOT NULL,
                             `file_id` int(11) NOT NULL,
                             `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `file_id`, `created_at`) VALUES
    (1, 4, 1, '2025-12-02 10:54:38');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `files`
--

CREATE TABLE `files` (
                         `id` int(11) NOT NULL,
                         `uploaded_by` int(11) DEFAULT NULL,
                         `name` varchar(255) NOT NULL,
                         `file_name` varchar(255) NOT NULL,
                         `description` text DEFAULT NULL,
                         `file_path` varchar(255) NOT NULL,
                         `subject` varchar(100) NOT NULL,
                         `tags` varchar(255) NOT NULL,
                         `tn_name` varchar(255) DEFAULT NULL,
                         `file_size` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `files`
--

INSERT INTO `files` (`id`, `uploaded_by`, `name`, `file_name`, `description`, `file_path`, `subject`, `tags`, `tn_name`, `file_size`) VALUES
    (2, 1, 'Java zero to hero', 'JavaNotesForProfessionals.pdf', 'Ezzel a csodával megtanulsz javaul. Garantált siker!', 'C:xampphtdocsjegyzetar.eu-srcsrc/users/csontoskincso05/JavaNotesForProfessionals.pdf', 'Informatika', 'Tankönyv', NULL, NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `friends`
--

CREATE TABLE `friends` (
                           `id` int(11) NOT NULL,
                           `fromid` int(11) NOT NULL,
                           `toid` int(11) NOT NULL,
                           `status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `groups`
--

CREATE TABLE `groups` (
                          `id` int(11) NOT NULL,
                          `name` varchar(100) NOT NULL,
                          `description` text DEFAULT NULL,
                          `owner_id` int(11) NOT NULL,
                          `is_private` tinyint(1) NOT NULL DEFAULT 0,
                          `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `group_files`
--

CREATE TABLE `group_files` (
                               `id` int(11) NOT NULL,
                               `group_id` int(11) NOT NULL,
                               `uploaded_by` int(11) NOT NULL,
                               `name` varchar(255) NOT NULL,
                               `description` text DEFAULT NULL,
                               `file_name` varchar(255) NOT NULL,
                               `created_at` datetime NOT NULL,
                               `is_approved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `group_members`
--

CREATE TABLE `group_members` (
                                 `id` int(11) NOT NULL,
                                 `group_id` int(11) NOT NULL,
                                 `user_id` int(11) NOT NULL,
                                 `role` enum('owner','member') NOT NULL DEFAULT 'member',
                                 `status` enum('accepted','pending') NOT NULL DEFAULT 'accepted',
                                 `joined_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `languages`
--

CREATE TABLE `languages` (
                             `id` int(11) NOT NULL,
                             `code` varchar(5) NOT NULL,
                             `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`) VALUES
                                                   (1, 'hu', 'Magyar'),
                                                   (2, 'en', 'English'),
                                                   (3, 'de', 'Deutsch');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `messages`
--

CREATE TABLE `messages` (
                            `id` int(255) NOT NULL,
                            `fromid` int(255) NOT NULL,
                            `toid` int(255) NOT NULL,
                            `content` text NOT NULL,
                            `sent_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `namedays`
--

CREATE TABLE `namedays` (
                            `id` int(11) NOT NULL,
                            `datum` varchar(5) DEFAULT NULL,
                            `nevek` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `namedays`
--

INSERT INTO `namedays` (`id`, `datum`, `nevek`) VALUES
                                                    (1, '01-01', 'Fruzsina'),
                                                    (2, '01-02', 'Ábel'),
                                                    (3, '01-03', 'Benjámin, Genovéva'),
                                                    (4, '01-04', 'Leóna, Titusz'),
                                                    (5, '01-05', 'Simon'),
                                                    (6, '01-06', 'Boldizsár'),
                                                    (7, '01-07', 'Attila, Ramóna'),
                                                    (8, '01-08', 'Gyöngyvér'),
                                                    (9, '01-09', 'Marcell'),
                                                    (10, '01-10', 'Melánia'),
                                                    (11, '01-11', 'Ágota'),
                                                    (12, '01-12', 'Ernő'),
                                                    (13, '01-13', 'Veronika'),
                                                    (14, '01-14', 'Bódog'),
                                                    (15, '01-15', 'Lóránd, Lóránt'),
                                                    (16, '01-16', 'Gusztáv'),
                                                    (17, '01-17', 'Antal, Antónia'),
                                                    (18, '01-18', 'Piroska'),
                                                    (19, '01-19', 'Márió, Sára'),
                                                    (20, '01-20', 'Fábián, Sebestyén'),
                                                    (21, '01-21', 'Ágnes'),
                                                    (22, '01-22', 'Artúr, Vince'),
                                                    (23, '01-23', 'Rajmund, Zelma'),
                                                    (24, '01-24', 'Timót'),
                                                    (25, '01-25', 'Pál'),
                                                    (26, '01-26', 'Paula, Vanda'),
                                                    (27, '01-27', 'Angelika'),
                                                    (28, '01-28', 'Karola, Károly'),
                                                    (29, '01-29', 'Adél'),
                                                    (30, '01-30', 'Martina'),
                                                    (31, '01-31', 'Gerda, Marcella'),
                                                    (32, '02-01', 'Ignác'),
                                                    (33, '02-02', 'Aida, Karolina'),
                                                    (34, '02-03', 'Balázs'),
                                                    (35, '02-04', 'Csenge, Ráhel'),
                                                    (36, '02-05', 'Ágota, Ingrid'),
                                                    (37, '02-06', 'Dóra, Dorottya'),
                                                    (38, '02-07', 'Rómeó, Tódor'),
                                                    (39, '02-08', 'Aranka'),
                                                    (40, '02-09', 'Abigél, Alex'),
                                                    (41, '02-10', 'Elvira'),
                                                    (42, '02-11', 'Bertold, Marietta'),
                                                    (43, '02-12', 'Lídia, Lívia'),
                                                    (44, '02-13', 'Ella, Linda'),
                                                    (45, '02-14', 'Bálint, Valentin'),
                                                    (46, '02-15', 'Georgina, Kolos'),
                                                    (47, '02-16', 'Julianna, Lilla'),
                                                    (48, '02-17', 'Donát'),
                                                    (49, '02-18', 'Bernadett'),
                                                    (50, '02-19', 'Zsuzsanna'),
                                                    (51, '02-20', 'Aladár, Álmos'),
                                                    (52, '02-21', 'Eleonóra'),
                                                    (53, '02-22', 'Gerzson'),
                                                    (54, '02-23', 'Alfréd'),
                                                    (55, '02-24', 'Mátyás'),
                                                    (56, '02-25', 'Géza'),
                                                    (57, '02-26', 'Edina'),
                                                    (58, '02-27', 'Ákos, Bátor'),
                                                    (59, '02-28', 'Elemér'),
                                                    (60, '03-01', 'Albin'),
                                                    (61, '03-02', 'Lujza'),
                                                    (62, '03-03', 'Kornélia'),
                                                    (63, '03-04', 'Kázmér'),
                                                    (64, '03-05', 'Adorján, Adrián'),
                                                    (65, '03-06', 'Inez, Leonóra'),
                                                    (66, '03-07', 'Tamás'),
                                                    (67, '03-08', 'Zoltán'),
                                                    (68, '03-09', 'Fanni, Franciska'),
                                                    (69, '03-10', 'Ildikó'),
                                                    (70, '03-11', 'Szilárd'),
                                                    (71, '03-12', 'Gergely'),
                                                    (72, '03-13', 'Ajtony, Krisztián'),
                                                    (73, '03-14', 'Matild'),
                                                    (74, '03-15', 'Kristóf'),
                                                    (75, '03-16', 'Henrietta'),
                                                    (76, '03-17', 'Gertrúd, Patrik'),
                                                    (77, '03-18', 'Ede, Sándor'),
                                                    (78, '03-19', 'Bánk, József'),
                                                    (79, '03-20', 'Klaudia'),
                                                    (80, '03-21', 'Benedek'),
                                                    (81, '03-22', 'Beáta, Izolda'),
                                                    (82, '03-23', 'Emőke'),
                                                    (83, '03-24', 'Gábor, Karina'),
                                                    (84, '03-25', 'Irén, Írisz'),
                                                    (85, '03-26', 'Emánuel'),
                                                    (86, '03-27', 'Hajnalka'),
                                                    (87, '03-28', 'Gedeon, Johanna'),
                                                    (88, '03-29', 'Auguszta'),
                                                    (89, '03-30', 'Zalán'),
                                                    (90, '03-31', 'Árpád'),
                                                    (91, '04-01', 'Hugó'),
                                                    (92, '04-02', 'Áron'),
                                                    (93, '04-03', 'Buda, Richárd'),
                                                    (94, '04-04', 'Izidor'),
                                                    (95, '04-05', 'Vince'),
                                                    (96, '04-06', 'Bíborka, Vilmos'),
                                                    (97, '04-07', 'Herman'),
                                                    (98, '04-08', 'Dénes'),
                                                    (99, '04-09', 'Erhard'),
                                                    (100, '04-10', 'Zsolt'),
                                                    (101, '04-11', 'Leó, Szaniszló'),
                                                    (102, '04-12', 'Gyula'),
                                                    (103, '04-13', 'Ida'),
                                                    (104, '04-14', 'Tibor'),
                                                    (105, '04-15', 'Anasztázia, Tas'),
                                                    (106, '04-16', 'Csongor'),
                                                    (107, '04-17', 'Rudolf'),
                                                    (108, '04-18', 'Andrea, Ilma'),
                                                    (109, '04-19', 'Emma'),
                                                    (110, '04-20', 'Tivadar'),
                                                    (111, '04-21', 'Konrád'),
                                                    (112, '04-22', 'Csilla, Noémi'),
                                                    (113, '04-23', 'Béla'),
                                                    (114, '04-24', 'György'),
                                                    (115, '04-25', 'Márk'),
                                                    (116, '04-26', 'Ervin'),
                                                    (117, '04-27', 'Zita'),
                                                    (118, '04-28', 'Valéria'),
                                                    (119, '04-29', 'Péter'),
                                                    (120, '04-30', 'Katalin, Kitti'),
                                                    (121, '05-01', 'Fülöp, Jakab'),
                                                    (122, '05-02', 'Zsigmond'),
                                                    (123, '05-03', 'Irma, Tímea'),
                                                    (124, '05-04', 'Flórián, Mónika'),
                                                    (125, '05-05', 'Adrián, Györgyi'),
                                                    (126, '05-06', 'Frida, Ivett'),
                                                    (127, '05-07', 'Gizella'),
                                                    (128, '05-08', 'Mihály'),
                                                    (129, '05-09', 'Gergely'),
                                                    (130, '05-10', 'Ármin, Pálma, Mira'),
                                                    (131, '05-11', 'Ferenc'),
                                                    (132, '05-12', 'Pongrác'),
                                                    (133, '05-13', 'Imola, Szervác'),
                                                    (134, '05-14', 'Bonifác'),
                                                    (135, '05-15', 'Szonja, Zsófia'),
                                                    (136, '05-16', 'Botond, Mózes'),
                                                    (137, '05-17', 'Paszkál'),
                                                    (138, '05-18', 'Alexandra, Erik'),
                                                    (139, '05-19', 'Ivó, Milán'),
                                                    (140, '05-20', 'Bernát, Felícia'),
                                                    (141, '05-21', 'Konstantin'),
                                                    (142, '05-22', 'Júlia, Rita'),
                                                    (143, '05-23', 'Dezső'),
                                                    (144, '05-24', 'Eliza, Eszter'),
                                                    (145, '05-25', 'Orbán'),
                                                    (146, '05-26', 'Evelin, Fülöp'),
                                                    (147, '05-27', 'Hella'),
                                                    (148, '05-28', 'Csanád, Emil'),
                                                    (149, '05-29', 'Magdolna'),
                                                    (150, '05-30', 'Janka, Zsanett'),
                                                    (151, '05-31', 'Angéla'),
                                                    (152, '06-01', 'Tünde'),
                                                    (153, '06-02', 'Anita, Kármen'),
                                                    (154, '06-03', 'Klotild'),
                                                    (155, '06-04', 'Bulcsú'),
                                                    (156, '06-05', 'Fatime'),
                                                    (157, '06-06', 'Cintia, Norbert'),
                                                    (158, '06-07', 'Róbert'),
                                                    (159, '06-08', 'Medárd'),
                                                    (160, '06-09', 'Félix'),
                                                    (161, '06-10', 'Gréta, Margit'),
                                                    (162, '06-11', 'Barnabás'),
                                                    (163, '06-12', 'Villő'),
                                                    (164, '06-13', 'Anett, Antal'),
                                                    (165, '06-14', 'Vazul'),
                                                    (166, '06-15', 'Jolán, Vid'),
                                                    (167, '06-16', 'Jusztin'),
                                                    (168, '06-17', 'Alida, Laura'),
                                                    (169, '06-18', 'Arnold, Levente'),
                                                    (170, '06-19', 'Gyárfás'),
                                                    (171, '06-20', 'Rafael'),
                                                    (172, '06-21', 'Alajos, Leila'),
                                                    (173, '06-22', 'Paulina'),
                                                    (174, '06-23', 'Zoltán'),
                                                    (175, '06-24', 'Iván'),
                                                    (176, '06-25', 'Vilmos'),
                                                    (177, '06-26', 'János, Pál'),
                                                    (178, '06-27', 'László'),
                                                    (179, '06-28', 'Irén, Levente'),
                                                    (180, '06-29', 'Péter, Pál'),
                                                    (181, '06-30', 'Pál'),
                                                    (182, '07-01', 'Annamária, Tihamér'),
                                                    (183, '07-02', 'Ottó'),
                                                    (184, '07-03', 'Kornél, Soma'),
                                                    (185, '07-04', 'Ulrik'),
                                                    (186, '07-05', 'Emese, Sarolta'),
                                                    (187, '07-06', 'Csaba'),
                                                    (188, '07-07', 'Apollónia'),
                                                    (189, '07-08', 'Ellák'),
                                                    (190, '07-09', 'Lukrécia'),
                                                    (191, '07-10', 'Amália'),
                                                    (192, '07-11', 'Lili, Nóra'),
                                                    (193, '07-12', 'Dalma, Izabella'),
                                                    (194, '07-13', 'Jenő'),
                                                    (195, '07-14', 'Örs, Stella'),
                                                    (196, '07-15', 'Henrik, Roland'),
                                                    (197, '07-16', 'Valter'),
                                                    (198, '07-17', 'Elek, Endre'),
                                                    (199, '07-18', 'Frigyes'),
                                                    (200, '07-19', 'Emília'),
                                                    (201, '07-20', 'Illés'),
                                                    (202, '07-21', 'Dániel, Daniella'),
                                                    (203, '07-22', 'Magdolna'),
                                                    (204, '07-23', 'Lenke'),
                                                    (205, '07-24', 'Kincső, Kinga'),
                                                    (206, '07-25', 'Jakab, Kristóf'),
                                                    (207, '07-26', 'Anikó, Anna'),
                                                    (208, '07-27', 'Liliána, Olga'),
                                                    (209, '07-28', 'Szabolcs'),
                                                    (210, '07-29', 'Flóra, Márta'),
                                                    (211, '07-30', 'Judit, Xénia'),
                                                    (212, '07-31', 'Oszkár'),
                                                    (213, '08-01', 'Boglárka'),
                                                    (214, '08-02', 'Lehel'),
                                                    (215, '08-03', 'Hermina'),
                                                    (216, '08-04', 'Dominika, Domonkos'),
                                                    (217, '08-05', 'Krisztina'),
                                                    (218, '08-06', 'Berta, Bettina'),
                                                    (219, '08-07', 'Ibolya'),
                                                    (220, '08-08', 'László'),
                                                    (221, '08-09', 'Emőd'),
                                                    (222, '08-10', 'Lőrinc'),
                                                    (223, '08-11', 'Tiborc, Zsuzsanna'),
                                                    (224, '08-12', 'Klára'),
                                                    (225, '08-13', 'Ipoly'),
                                                    (226, '08-14', 'Marcell'),
                                                    (227, '08-15', 'Mária'),
                                                    (228, '08-16', 'Ábrahám'),
                                                    (229, '08-17', 'Jácint'),
                                                    (230, '08-18', 'Ilona'),
                                                    (231, '08-19', 'Huba'),
                                                    (232, '08-20', 'István'),
                                                    (233, '08-21', 'Hajna, Sámuel'),
                                                    (234, '08-22', 'Menyhért, Mirjam'),
                                                    (235, '08-23', 'Bence'),
                                                    (236, '08-24', 'Bertalan'),
                                                    (237, '08-25', 'Lajos, Patrícia'),
                                                    (238, '08-26', 'Izsó'),
                                                    (239, '08-27', 'Gáspár'),
                                                    (240, '08-28', 'Ágoston'),
                                                    (241, '08-29', 'Beatrix, Erna'),
                                                    (242, '08-30', 'Rózsa'),
                                                    (243, '08-31', 'Bella, Erika'),
                                                    (244, '09-01', 'Egon, Egyed'),
                                                    (245, '09-02', 'Dorina, Rebeka'),
                                                    (246, '09-03', 'Hilda'),
                                                    (247, '09-04', 'Rozália'),
                                                    (248, '09-05', 'Lőrinc, Viktor'),
                                                    (249, '09-06', 'Zakariás'),
                                                    (250, '09-07', 'Regina'),
                                                    (251, '09-08', 'Adrienn, Mária'),
                                                    (252, '09-09', 'Adám'),
                                                    (253, '09-10', 'Hunor, Nikolett'),
                                                    (254, '09-11', 'Teodóra'),
                                                    (255, '09-12', 'Mária'),
                                                    (256, '09-13', 'Kornél'),
                                                    (257, '09-14', 'Roxána, Szeréna'),
                                                    (258, '09-15', 'Enikő, Melitta'),
                                                    (259, '09-16', 'Edit'),
                                                    (260, '09-17', 'Zsófia'),
                                                    (261, '09-18', 'Diána'),
                                                    (262, '09-19', 'Vilhelmina'),
                                                    (263, '09-20', 'Friderika'),
                                                    (264, '09-21', 'Máté, Mirella'),
                                                    (265, '09-22', 'Móric'),
                                                    (266, '09-23', 'Tekla'),
                                                    (267, '09-24', 'Gellért, Mercédesz'),
                                                    (268, '09-25', 'Eufrozina, Kende'),
                                                    (269, '09-26', 'Jusztina, Pál'),
                                                    (270, '09-27', 'Adalbert'),
                                                    (271, '09-28', 'Vencel'),
                                                    (272, '09-29', 'Mihály'),
                                                    (273, '09-30', 'Jeromos'),
                                                    (274, '10-01', 'Malvin'),
                                                    (275, '10-02', 'Petra'),
                                                    (276, '10-03', 'Helga'),
                                                    (277, '10-04', 'Ferenc'),
                                                    (278, '10-05', 'Aurél'),
                                                    (279, '10-06', 'Brúnó, Renáta'),
                                                    (280, '10-07', 'Amália'),
                                                    (281, '10-08', 'Koppány'),
                                                    (282, '10-09', 'Dénes'),
                                                    (283, '10-10', 'Gedeon'),
                                                    (284, '10-11', 'Brigitta'),
                                                    (285, '10-12', 'Miksa'),
                                                    (286, '10-13', 'Ede, Kálmán'),
                                                    (287, '10-14', 'Helén'),
                                                    (288, '10-15', 'Teréz'),
                                                    (289, '10-16', 'Gál'),
                                                    (290, '10-17', 'Hedvig'),
                                                    (291, '10-18', 'Lukács'),
                                                    (292, '10-19', 'Nándor'),
                                                    (293, '10-20', 'Vendel'),
                                                    (294, '10-21', 'Orsolya'),
                                                    (295, '10-22', 'Előd'),
                                                    (296, '10-23', 'Gyöngyi'),
                                                    (297, '10-24', 'Salamon'),
                                                    (298, '10-25', 'Bianka, Blanka'),
                                                    (299, '10-26', 'Dömötör'),
                                                    (300, '10-27', 'Szabina'),
                                                    (301, '10-28', 'Simon, Szimonetta'),
                                                    (302, '10-29', 'Nárcisz'),
                                                    (303, '10-30', 'Alfonz'),
                                                    (304, '10-31', 'Farkas'),
                                                    (305, '11-01', 'Marianna'),
                                                    (306, '11-02', 'Achilles'),
                                                    (307, '11-03', 'Győző'),
                                                    (308, '11-04', 'Károly'),
                                                    (309, '11-05', 'Imre'),
                                                    (310, '11-06', 'Lénárd'),
                                                    (311, '11-07', 'Rezső'),
                                                    (312, '11-08', 'Zsombor'),
                                                    (313, '11-09', 'Tivadar'),
                                                    (314, '11-10', 'Réka'),
                                                    (315, '11-11', 'Márton'),
                                                    (316, '11-12', 'Jónás, Renátó'),
                                                    (317, '11-13', 'Szilvia'),
                                                    (318, '11-14', 'Aliz'),
                                                    (319, '11-15', 'Albert, Lipót'),
                                                    (320, '11-16', 'Ödön'),
                                                    (321, '11-17', 'Gergő, Hortenzia'),
                                                    (322, '11-18', 'Jenő'),
                                                    (323, '11-19', 'Erzsébet'),
                                                    (324, '11-20', 'Jolán'),
                                                    (325, '11-21', 'Olivér'),
                                                    (326, '11-22', 'Cecília'),
                                                    (327, '11-23', 'Kelemen, Klementina'),
                                                    (328, '11-24', 'Emma'),
                                                    (329, '11-25', 'Katalin'),
                                                    (330, '11-26', 'Virág'),
                                                    (331, '11-27', 'Virgil'),
                                                    (332, '11-28', 'Stefánia'),
                                                    (333, '11-29', 'Taksony'),
                                                    (334, '11-30', 'Andor, András'),
                                                    (335, '12-01', 'Elza'),
                                                    (336, '12-02', 'Melinda, Vivien'),
                                                    (337, '12-03', 'Ferenc, Olívia'),
                                                    (338, '12-04', 'Barbara, Borbála'),
                                                    (339, '12-05', 'Vilma'),
                                                    (340, '12-06', 'Miklós'),
                                                    (341, '12-07', 'Ambrus'),
                                                    (342, '12-08', 'Mária'),
                                                    (343, '12-09', 'Natália'),
                                                    (344, '12-10', 'Judit'),
                                                    (345, '12-11', 'Árpád'),
                                                    (346, '12-12', 'Gabriella'),
                                                    (347, '12-13', 'Luca, Otília'),
                                                    (348, '12-14', 'Szilárda'),
                                                    (349, '12-15', 'Valér'),
                                                    (350, '12-16', 'Aletta, Etelka'),
                                                    (351, '12-17', 'Lázár, Olimpia'),
                                                    (352, '12-18', 'Auguszta'),
                                                    (353, '12-19', 'Viola'),
                                                    (354, '12-20', 'Teofil'),
                                                    (355, '12-21', 'Tamás'),
                                                    (356, '12-22', 'Zénó'),
                                                    (357, '12-23', 'Viktória'),
                                                    (358, '12-24', 'Adám, Éva'),
                                                    (359, '12-25', 'Eugénia'),
                                                    (360, '12-26', 'István'),
                                                    (361, '12-27', 'János'),
                                                    (362, '12-28', 'Kamilla'),
                                                    (363, '12-29', 'Tamara, Tamás'),
                                                    (364, '12-30', 'Dávid'),
                                                    (365, '12-31', 'Szilveszter');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `notifys`
--

CREATE TABLE `notifys` (
                           `id` int(11) NOT NULL,
                           `fromid` int(255) NOT NULL,
                           `toid` int(255) NOT NULL,
                           `notifytype` varchar(100) NOT NULL,
                           `readed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ratings`
--

CREATE TABLE `ratings` (
                           `id` int(11) NOT NULL,
                           `file_id` int(11) NOT NULL,
                           `user_id` int(11) NOT NULL,
                           `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
                           `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                           `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `ratings`
--

INSERT INTO `ratings` (`id`, `file_id`, `user_id`, `rating`, `created_at`, `updated_at`) VALUES
                                                                                             (1, 1, 4, 5, '2025-12-02 10:55:24', '2025-12-02 10:55:24'),
                                                                                             (2, 2, 1, 5, '2025-12-16 01:00:56', '2025-12-16 01:00:56');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `reg_codes`
--

CREATE TABLE `reg_codes` (
                             `id` int(11) NOT NULL,
                             `code` varchar(64) NOT NULL,
                             `description` varchar(255) DEFAULT NULL,
                             `max_uses` int(11) DEFAULT NULL,
                             `used` int(11) NOT NULL DEFAULT 0,
                             `expires_at` datetime DEFAULT NULL,
                             `active` tinyint(1) NOT NULL DEFAULT 1,
                             `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `reg_codes`
--

INSERT INTO `reg_codes` (`id`, `code`, `description`, `max_uses`, `used`, `expires_at`, `active`, `created_at`) VALUES
    (1, 'EARLY-BETA-2025', 'Nagyon korai béta tesztelő kód', 10, 3, NULL, 1, '2025-12-07 14:31:16');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `reports`
--

CREATE TABLE `reports` (
                           `id` int(11) NOT NULL,
                           `reporter_id` int(11) NOT NULL,
                           `target_type` enum('user','group','note') NOT NULL,
                           `target_id` int(11) NOT NULL,
                           `reason` text DEFAULT NULL,
                           `status` enum('open','resolved','dismissed') NOT NULL DEFAULT 'open',
                           `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                           `handled_by` int(11) DEFAULT NULL,
                           `handled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `reports`
--

INSERT INTO `reports` (`id`, `reporter_id`, `target_type`, `target_id`, `reason`, `status`, `created_at`, `handled_by`, `handled_at`) VALUES
                                                                                                                                          (1, 4, 'note', 2, 'Nincs megadott indok.', 'dismissed', '2025-12-07 13:43:45', 4, '2025-12-07 13:44:03'),
                                                                                                                                          (2, 8, 'note', 2, 'Ez egy teszt', 'open', '2025-12-16 00:21:29', NULL, NULL),
                                                                                                                                          (3, 1, 'user', 8, 'Ez egy teszt jelentés egy user felé', 'open', '2025-12-16 00:31:04', NULL, NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tokens`
--

CREATE TABLE `tokens` (
                          `id` int(11) NOT NULL,
                          `user_id` int(11) NOT NULL,
                          `token` int(10) UNSIGNED NOT NULL,
                          `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `tokens`
--

INSERT INTO `tokens` (`id`, `user_id`, `token`, `created_at`) VALUES
    (1, 8, 120502, '2025-12-15 23:19:29');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `translations`
--

CREATE TABLE `translations` (
                                `id` int(11) NOT NULL,
                                `t_key` varchar(100) NOT NULL,
                                `lang_code` varchar(5) NOT NULL,
                                `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `translations`
--

INSERT INTO `translations` (`id`, `t_key`, `lang_code`, `text`) VALUES
                                                                    (1, 'nav_home', 'hu', 'Főoldal'),
                                                                    (2, 'nav_home', 'en', 'Home'),
                                                                    (3, 'nav_upload', 'hu', 'Új jegyzet'),
                                                                    (4, 'nav_upload', 'en', 'New note'),
                                                                    (5, 'nav_messages', 'hu', 'Üzenetek'),
                                                                    (6, 'nav_messages', 'en', 'Messages'),
                                                                    (7, 'nav_login', 'hu', 'Bejelentkezés'),
                                                                    (8, 'nav_login', 'en', 'Login'),
                                                                    (9, 'nav_logout', 'hu', 'Kijelentkezés'),
                                                                    (10, 'nav_logout', 'en', 'Logout'),
                                                                    (11, 'hero_greeting', 'hu', 'Szia,'),
                                                                    (12, 'hero_greeting', 'en', 'Hi,'),
                                                                    (13, 'hero_nameday', 'hu', 'Mai névnap'),
                                                                    (14, 'hero_nameday', 'en', 'Name day today'),
                                                                    (15, 'guest', 'hu', 'Vendég'),
                                                                    (16, 'guest', 'en', 'Guest'),
                                                                    (17, 'footer_copy', 'hu', '&copy; 2025 Jegyzetár'),
                                                                    (18, 'footer_copy', 'en', '&copy; 2025 NoteShare'),
                                                                    (19, 'site_tagline', 'hu', 'Iskolai jegyzeteket megosztó oldal'),
                                                                    (20, 'site_tagline', 'en', 'A platform for sharing school notes'),
                                                                    (21, 'hero_logged_out_subtitle', 'hu', 'Jelentkezz be vagy hozz létre új fiókot az induláshoz.'),
                                                                    (22, 'hero_logged_out_subtitle', 'en', 'Log in or create a new account to get started.'),
                                                                    (23, 'hero_welcome', 'hu', 'Üdvözlünk a Jegyzetár rendszerében!'),
                                                                    (24, 'hero_welcome', 'en', 'Welcome to the NoteShare system!'),
                                                                    (25, 'nameday_none', 'hu', 'Nincs névnap ma.'),
                                                                    (26, 'nameday_none', 'en', 'There is no name day today.'),
                                                                    (27, 'birthday_congrats', 'hu', 'Boldog születésnapot,'),
                                                                    (28, 'birthday_congrats', 'en', 'Happy birthday,'),
                                                                    (29, 'meta_keywords', 'hu', 'iskola, jegyzet, megosztás, tanulás'),
                                                                    (30, 'meta_keywords', 'en', 'school, notes, sharing, studying'),
                                                                    (31, 'nav_new_note_plus', 'hu', '+ Új jegyzet'),
                                                                    (32, 'nav_new_note_plus', 'en', '+ New note'),
                                                                    (33, 'btn_details', 'hu', 'Részletek'),
                                                                    (34, 'btn_details', 'en', 'Details'),
                                                                    (35, 'btn_download', 'hu', 'Letöltés'),
                                                                    (36, 'btn_download', 'en', 'Download'),
                                                                    (37, 'btn_delete', 'hu', 'Törlés'),
                                                                    (38, 'btn_delete', 'en', 'Delete'),
                                                                    (39, 'btn_back_home', 'hu', 'Vissza a főoldalra'),
                                                                    (40, 'btn_back_home', 'en', 'Back to home'),
                                                                    (41, 'btn_back_login', 'hu', 'Vissza a bejelentkezéshez'),
                                                                    (42, 'btn_back_login', 'en', 'Back to login'),
                                                                    (43, 'btn_accept', 'hu', 'Elfogadás'),
                                                                    (44, 'btn_accept', 'en', 'Accept'),
                                                                    (45, 'btn_send', 'hu', 'Küldés'),
                                                                    (46, 'btn_send', 'en', 'Send'),
                                                                    (47, 'btn_send_alt', 'hu', 'Elküldés'),
                                                                    (48, 'btn_send_alt', 'en', 'Send'),
                                                                    (49, 'btn_login_cta', 'hu', 'Lépj be!'),
                                                                    (50, 'btn_login_cta', 'en', 'Log in!'),
                                                                    (51, 'btn_register_cta', 'hu', 'Regisztrálj!'),
                                                                    (52, 'btn_register_cta', 'en', 'Register!'),
                                                                    (53, 'btn_continue_discord', 'hu', 'Folytatás Discorddal'),
                                                                    (54, 'btn_continue_discord', 'en', 'Continue with Discord'),
                                                                    (55, 'btn_go_to_note', 'hu', 'Ugrás a jegyzetre'),
                                                                    (56, 'btn_go_to_note', 'en', 'Go to note'),
                                                                    (57, 'auth_login_title', 'hu', 'Belépés'),
                                                                    (58, 'auth_login_title', 'en', 'Login'),
                                                                    (59, 'auth_register_title', 'hu', 'Regisztráció'),
                                                                    (60, 'auth_register_title', 'en', 'Registration'),
                                                                    (61, 'auth_already_have_account', 'hu', 'Már van fiókod?'),
                                                                    (62, 'auth_already_have_account', 'en', 'Already have an account?'),
                                                                    (63, 'auth_no_account_yet', 'hu', 'Még nincs fiókod?'),
                                                                    (64, 'auth_no_account_yet', 'en', 'Don’t have an account yet?'),
                                                                    (65, 'auth_field_lastname', 'hu', 'Vezetéknév'),
                                                                    (66, 'auth_field_lastname', 'en', 'Last name'),
                                                                    (67, 'auth_field_firstname', 'hu', 'Keresztnév'),
                                                                    (68, 'auth_field_firstname', 'en', 'First name'),
                                                                    (69, 'auth_field_username', 'hu', 'Felhasználónév'),
                                                                    (70, 'auth_field_username', 'en', 'Username'),
                                                                    (71, 'auth_field_username_colon', 'hu', 'Felhasználónév:'),
                                                                    (72, 'auth_field_username_colon', 'en', 'Username:'),
                                                                    (73, 'auth_field_email', 'hu', 'Email'),
                                                                    (74, 'auth_field_email', 'en', 'Email'),
                                                                    (75, 'auth_field_password', 'hu', 'Jelszó'),
                                                                    (76, 'auth_field_password', 'en', 'Password'),
                                                                    (77, 'auth_field_password_again', 'hu', 'Jelszó újra'),
                                                                    (78, 'auth_field_password_again', 'en', 'Repeat password'),
                                                                    (79, 'auth_field_password_again_colon', 'hu', 'Jelszó újra:'),
                                                                    (80, 'auth_field_password_again_colon', 'en', 'Repeat password:'),
                                                                    (81, 'auth_field_birthdate', 'hu', 'Születési dátum'),
                                                                    (82, 'auth_field_birthdate', 'en', 'Date of birth'),
                                                                    (83, 'auth_field_gender', 'hu', 'Nem'),
                                                                    (84, 'auth_field_gender', 'en', 'Gender'),
                                                                    (85, 'auth_gender_male', 'hu', 'Férfi'),
                                                                    (86, 'auth_gender_male', 'en', 'Male'),
                                                                    (87, 'auth_gender_female', 'hu', 'Nő'),
                                                                    (88, 'auth_gender_female', 'en', 'Female'),
                                                                    (89, 'auth_gender_other', 'hu', 'Egyéb'),
                                                                    (90, 'auth_gender_other', 'en', 'Other'),
                                                                    (91, 'auth_field_security_question', 'hu', 'Biztonsági kérdés:'),
                                                                    (92, 'auth_field_security_question', 'en', 'Security question:'),
                                                                    (93, 'auth_field_security_answer', 'hu', 'Biztonsági kérdés válasza:'),
                                                                    (94, 'auth_field_security_answer', 'en', 'Answer to security question:'),
                                                                    (95, 'auth_field_answer', 'hu', 'Válasz'),
                                                                    (96, 'auth_field_answer', 'en', 'Answer'),
                                                                    (97, 'auth_placeholder_start_typing', 'hu', 'Kezdj el gépelni...'),
                                                                    (98, 'auth_placeholder_start_typing', 'en', 'Start typing...'),
                                                                    (99, 'secq_fav_book', 'hu', 'Mi a kedvenc könyved?'),
                                                                    (100, 'secq_fav_book', 'en', 'What is your favourite book?'),
                                                                    (101, 'secq_fav_food', 'hu', 'Mi a kedvenc ételed?'),
                                                                    (102, 'secq_fav_food', 'en', 'What is your favourite food?'),
                                                                    (103, 'secq_birth_city', 'hu', 'Mi a születési városod?'),
                                                                    (104, 'secq_birth_city', 'en', 'What is your birth city?'),
                                                                    (105, 'secq_mothers_maiden', 'hu', 'Mi az édesanyád leánykori neve?'),
                                                                    (106, 'secq_mothers_maiden', 'en', 'What is your mother’s maiden name?'),
                                                                    (107, 'secq_first_pet', 'hu', 'Mi volt az első háziállatod neve?'),
                                                                    (108, 'secq_first_pet', 'en', 'What was the name of your first pet?'),
                                                                    (109, 'forgot_title', 'hu', 'Elfelejtett Jelszó'),
                                                                    (110, 'forgot_title', 'en', 'Forgotten password'),
                                                                    (111, 'forgot_heading', 'hu', 'Jelszó visszaállítása'),
                                                                    (112, 'forgot_heading', 'en', 'Password reset'),
                                                                    (113, 'forgot_new_password', 'hu', 'Új jelszó beállítása'),
                                                                    (114, 'forgot_new_password', 'en', 'Set new password'),
                                                                    (115, 'forgot_new_password_label', 'hu', 'Új jelszó:'),
                                                                    (116, 'forgot_new_password_label', 'en', 'New password:'),
                                                                    (117, 'msg_password_change_success', 'hu', 'A jelszavad sikeresen megváltozott!'),
                                                                    (118, 'msg_password_change_success', 'en', 'Your password has been successfully changed!'),
                                                                    (119, 'msg_passwords_not_match', 'hu', 'A jelszavak nem egyeznek!'),
                                                                    (120, 'msg_passwords_not_match', 'en', 'The passwords do not match!'),
                                                                    (121, 'msg_password_same_as_old', 'hu', 'Az új jelszavad nem egyezhet a régivel.'),
                                                                    (122, 'msg_password_same_as_old', 'en', 'Your new password cannot be the same as the old one.'),
                                                                    (123, 'error_wrong_security_answer', 'hu', 'Helytelen biztonsági válasz!'),
                                                                    (124, 'error_wrong_security_answer', 'en', 'Incorrect security answer!'),
                                                                    (125, 'error_wrong_password', 'hu', 'Hibás jelszó!'),
                                                                    (126, 'error_wrong_password', 'en', 'Incorrect password!'),
                                                                    (127, 'email_change_page_title', 'hu', 'Email cím módosítása'),
                                                                    (128, 'email_change_page_title', 'en', 'Change email address'),
                                                                    (129, 'email_change_title', 'hu', 'Email Módosítás'),
                                                                    (130, 'email_change_title', 'en', 'Email change'),
                                                                    (131, 'email_edit_title', 'hu', 'Email módosítás'),
                                                                    (132, 'email_edit_title', 'en', 'Change email'),
                                                                    (133, 'email_change_label_new', 'hu', 'Új email cím'),
                                                                    (134, 'email_change_label_new', 'en', 'New email address'),
                                                                    (135, 'email_change_label_new_colon', 'hu', 'Új email cím:'),
                                                                    (136, 'email_change_label_new_colon', 'en', 'New email address:'),
                                                                    (137, 'email_change_label_again', 'hu', 'Email cím újra:'),
                                                                    (138, 'email_change_label_again', 'en', 'Email again:'),
                                                                    (139, 'email_change_label_again_short', 'hu', 'Email újra'),
                                                                    (140, 'email_change_label_again_short', 'en', 'Email again'),
                                                                    (141, 'msg_email_change_success', 'hu', 'Az új email címed sikeresen megváltozott!'),
                                                                    (142, 'msg_email_change_success', 'en', 'Your new email address has been successfully changed!'),
                                                                    (143, 'msg_email_same_as_old', 'hu', 'Az új email címed nem egyezhet a régivel.'),
                                                                    (144, 'msg_email_same_as_old', 'en', 'Your new email address cannot be the same as the old one.'),
                                                                    (145, 'msg_emails_not_match', 'hu', 'A két email cím nem egyezik!'),
                                                                    (146, 'msg_emails_not_match', 'en', 'The two email addresses do not match!'),
                                                                    (147, 'msg_email_exists', 'hu', 'Már létezik ilyen email cím!'),
                                                                    (148, 'msg_email_exists', 'en', 'This email address is already in use!'),
                                                                    (149, 'msg_generic_success_change', 'hu', 'Sikeres módosítás!'),
                                                                    (150, 'msg_generic_success_change', 'en', 'Change successful!'),
                                                                    (151, 'msg_file_upload_failed', 'hu', 'A fájl feltöltése sikertelen!'),
                                                                    (152, 'msg_file_upload_failed', 'en', 'File upload failed!'),
                                                                    (153, 'msg_file_uploaded', 'hu', 'A fájl sikeresen feltöltve!'),
                                                                    (154, 'msg_file_uploaded', 'en', 'File uploaded successfully!'),
                                                                    (155, 'msg_only_pdf_mp4_docx', 'hu', 'Csak PDF, MP4 vagy DOCX fájlokat lehet feltölteni!'),
                                                                    (156, 'msg_only_pdf_mp4_docx', 'en', 'Only PDF, MP4 or DOCX files can be uploaded!'),
                                                                    (157, 'msg_storage_create_failed', 'hu', 'Nem sikerült létrehozni a tárhelyet!'),
                                                                    (158, 'msg_storage_create_failed', 'en', 'Failed to create storage!'),
                                                                    (159, 'msg_storage_created', 'hu', 'Tárhely sikeresen létrehozva!'),
                                                                    (160, 'msg_storage_created', 'en', 'Storage created successfully!'),
                                                                    (161, 'msg_user_not_found', 'hu', 'Nincs ilyen felhasználó!'),
                                                                    (162, 'msg_user_not_found', 'en', 'No such user found!'),
                                                                    (163, 'msg_no_permission_admin', 'hu', 'Nincs jogosultságod az admin felület megtekintéséhez.'),
                                                                    (164, 'msg_no_permission_admin', 'en', 'You do not have permission to view the admin panel.'),
                                                                    (165, 'msg_friendid_missing', 'hu', 'Hiba: hiányzó barát azonosító.'),
                                                                    (166, 'msg_friendid_missing', 'en', 'Error: missing friend ID.'),
                                                                    (167, 'msg_invalid_user_id', 'hu', 'Hiányzó vagy érvénytelen felhasználó azonosító!'),
                                                                    (168, 'msg_invalid_user_id', 'en', 'Missing or invalid user identifier!'),
                                                                    (169, 'msg_invalid_profile_id', 'hu', 'Érvénytelen profil azonosító.'),
                                                                    (170, 'msg_invalid_profile_id', 'en', 'Invalid profile ID.'),
                                                                    (171, 'msg_message_empty', 'hu', 'Az üzenet nem lehet üres!'),
                                                                    (172, 'msg_message_empty', 'en', 'The message cannot be empty!'),
                                                                    (173, 'msg_comment_write_error', 'hu', 'Hiba történt a komment írásakor!'),
                                                                    (174, 'msg_comment_write_error', 'en', 'An error occurred while posting the comment!'),
                                                                    (175, 'msg_file_upload_error', 'hu', 'Hiba történt a fájl feltöltésekor.'),
                                                                    (176, 'msg_file_upload_error', 'en', 'An error occurred while uploading the file.'),
                                                                    (177, 'msg_note_not_found', 'hu', 'Jegyzet nem található!'),
                                                                    (178, 'msg_note_not_found', 'en', 'Note not found!'),
                                                                    (179, 'msg_note_missing_or_deleted', 'hu', 'A keresett jegyzet nem létezik vagy törölve lett.'),
                                                                    (180, 'msg_note_missing_or_deleted', 'en', 'The requested note does not exist or has been deleted.'),
                                                                    (181, 'msg_profile_not_found', 'hu', 'A keresett profil nem található.'),
                                                                    (182, 'msg_profile_not_found', 'en', 'The requested profile was not found.'),
                                                                    (183, 'empty_no_comments', 'hu', 'Még nincs komment.'),
                                                                    (184, 'empty_no_comments', 'en', 'There are no comments yet.'),
                                                                    (185, 'empty_no_files', 'hu', 'Még nincsenek feltöltött fájlok.'),
                                                                    (186, 'empty_no_files', 'en', 'There are no uploaded files yet.'),
                                                                    (187, 'empty_no_friends', 'hu', 'Még nincsenek barátaid.'),
                                                                    (188, 'empty_no_friends', 'en', 'You don’t have any friends yet.'),
                                                                    (189, 'empty_no_notifications', 'hu', 'Nincs új értesítésed.'),
                                                                    (190, 'empty_no_notifications', 'en', 'You have no new notifications.'),
                                                                    (191, 'empty_no_messages', 'hu', 'Nincsenek üzenetek.'),
                                                                    (192, 'empty_no_messages', 'en', 'There are no messages.'),
                                                                    (193, 'empty_no_users_search', 'hu', 'Nincs felhasználó találat.'),
                                                                    (194, 'empty_no_users_search', 'en', 'No users found.'),
                                                                    (195, 'empty_no_files_search', 'hu', 'Nincs fájl találat a megadott szűrőkre.'),
                                                                    (196, 'empty_no_files_search', 'en', 'No files match the given filters.'),
                                                                    (197, 'upload_page_title', 'hu', 'Anyag feltöltése'),
                                                                    (198, 'upload_page_title', 'en', 'Upload material'),
                                                                    (199, 'upload_label_file', 'hu', 'Fájl kiválasztása:'),
                                                                    (200, 'upload_label_file', 'en', 'Select file:'),
                                                                    (201, 'upload_label_subject', 'hu', 'Tárgy:'),
                                                                    (202, 'upload_label_subject', 'en', 'Subject:'),
                                                                    (203, 'upload_label_tags', 'hu', 'Kulcsszavak, címkék:'),
                                                                    (204, 'upload_label_tags', 'en', 'Keywords, tags:'),
                                                                    (205, 'upload_label_description', 'hu', 'Leírás'),
                                                                    (206, 'upload_label_description', 'en', 'Description'),
                                                                    (207, 'upload_label_description_colon', 'hu', 'Leírás:'),
                                                                    (208, 'upload_label_description_colon', 'en', 'Description:'),
                                                                    (209, 'upload_btn_upload', 'hu', 'Feltöltés'),
                                                                    (210, 'upload_btn_upload', 'en', 'Upload'),
                                                                    (211, 'upload_heading_uploaded_files', 'hu', 'Feltöltött anyagok'),
                                                                    (212, 'upload_heading_uploaded_files', 'en', 'Uploaded materials'),
                                                                    (213, 'msg_fill_subject_and_tags', 'hu', 'Kérjük, adja meg a tárgyat és a címkéket!'),
                                                                    (214, 'msg_fill_subject_and_tags', 'en', 'Please provide the subject and tags.'),
                                                                    (215, 'label_uploaded_by', 'hu', 'Feltöltötte:'),
                                                                    (216, 'label_uploaded_by', 'en', 'Uploaded by:'),
                                                                    (217, 'label_rating_average', 'hu', 'Átlag értékelés:'),
                                                                    (218, 'label_rating_average', 'en', 'Average rating:'),
                                                                    (219, 'suffix_ratings_short', 'hu', 'ért.'),
                                                                    (220, 'suffix_ratings_short', 'en', 'ratings'),
                                                                    (221, 'suffix_ratings_paren', 'hu', 'ért.)'),
                                                                    (222, 'suffix_ratings_paren', 'en', 'ratings)'),
                                                                    (223, 'suffix_rating_singular', 'hu', 'értékelés)'),
                                                                    (224, 'suffix_rating_singular', 'en', 'rating)'),
                                                                    (225, 'sidebar_top_rated', 'hu', 'Top értékelt'),
                                                                    (226, 'sidebar_top_rated', 'en', 'Top rated'),
                                                                    (227, 'label_new_uploads', 'hu', 'Új feltöltések'),
                                                                    (228, 'label_new_uploads', 'en', 'New uploads'),
                                                                    (229, 'label_new_comment', 'hu', 'Új hozzászólás'),
                                                                    (230, 'label_new_comment', 'en', 'New comment'),
                                                                    (231, 'label_text', 'hu', 'Szöveg'),
                                                                    (232, 'label_text', 'en', 'Text'),
                                                                    (233, 'comments_heading', 'hu', 'Kommentek'),
                                                                    (234, 'comments_heading', 'en', 'Comments'),
                                                                    (235, 'comments_placeholder', 'hu', 'Írj kommentet.'),
                                                                    (236, 'comments_placeholder', 'en', 'Write a comment.'),
                                                                    (237, 'btn_send_rating', 'hu', 'Értékelés küldése'),
                                                                    (238, 'btn_send_rating', 'en', 'Send rating'),
                                                                    (239, 'label_rating', 'hu', 'Értékelés'),
                                                                    (240, 'label_rating', 'en', 'Rating'),
                                                                    (241, 'label_mark', 'hu', 'Jelölés'),
                                                                    (242, 'label_mark', 'en', 'Mark'),
                                                                    (243, 'btn_login_to_rate', 'hu', 'Belépés az értékeléshez'),
                                                                    (244, 'btn_login_to_rate', 'en', 'Log in to rate'),
                                                                    (245, 'label_favorite', 'hu', 'Kedvencezés'),
                                                                    (246, 'label_favorite', 'en', 'Add to favourites'),
                                                                    (247, 'search_label_keyword', 'hu', 'Kulcsszó:'),
                                                                    (248, 'search_label_keyword', 'en', 'Keyword:'),
                                                                    (249, 'search_label_users', 'hu', 'Felhasználók'),
                                                                    (250, 'search_label_users', 'en', 'Users'),
                                                                    (251, 'search_label_files', 'hu', 'Fájlok'),
                                                                    (252, 'search_label_files', 'en', 'Files'),
                                                                    (253, 'search_btn_search', 'hu', 'Keresés'),
                                                                    (254, 'search_btn_search', 'en', 'Search'),
                                                                    (255, 'search_filters_title', 'hu', 'Keresés'),
                                                                    (256, 'search_filters_title', 'en', 'Search'),
                                                                    (257, 'search_placeholder', 'hu', 'Keresés…'),
                                                                    (258, 'search_placeholder', 'en', 'Search…'),
                                                                    (259, 'profile_heading', 'hu', 'Saját fiók'),
                                                                    (260, 'profile_heading', 'en', 'My account'),
                                                                    (261, 'profile_label_name', 'hu', 'Teljes név'),
                                                                    (262, 'profile_label_name', 'en', 'Full name'),
                                                                    (263, 'profile_label_firstname', 'hu', 'Keresztnév'),
                                                                    (264, 'profile_label_firstname', 'en', 'First name'),
                                                                    (265, 'profile_label_lastname', 'hu', 'Vezetéknév'),
                                                                    (266, 'profile_label_lastname', 'en', 'Last name'),
                                                                    (267, 'profile_label_username', 'hu', 'Felhasználó'),
                                                                    (268, 'profile_label_username', 'en', 'User'),
                                                                    (269, 'profile_label_birthdate', 'hu', 'Születési dátum'),
                                                                    (270, 'profile_label_birthdate', 'en', 'Date of birth'),
                                                                    (271, 'profile_label_avatar', 'hu', 'Profilkép'),
                                                                    (272, 'profile_label_avatar', 'en', 'Profile picture'),
                                                                    (273, 'profile_label_avatar_upload', 'hu', 'Profilkép feltöltése'),
                                                                    (274, 'profile_label_avatar_upload', 'en', 'Upload profile picture'),
                                                                    (275, 'admin_title', 'hu', 'Admin Panel'),
                                                                    (276, 'admin_title', 'en', 'Admin Panel'),
                                                                    (277, 'admin_users_manage', 'hu', 'Felhasználók kezelése'),
                                                                    (278, 'admin_users_manage', 'en', 'Manage users'),
                                                                    (279, 'admin_files_manage', 'hu', 'Fájlok kezelése'),
                                                                    (280, 'admin_files_manage', 'en', 'Manage files'),
                                                                    (281, 'admin_comments_manage', 'hu', 'Kommentek kezelése'),
                                                                    (282, 'admin_comments_manage', 'en', 'Manage comments'),
                                                                    (283, 'admin_categories_manage', 'hu', 'Kategóriák kezelése'),
                                                                    (284, 'admin_categories_manage', 'en', 'Manage categories'),
                                                                    (285, 'admin_column_name', 'hu', 'Név'),
                                                                    (286, 'admin_column_name', 'en', 'Name'),
                                                                    (287, 'admin_column_fullname', 'hu', 'Teljes név'),
                                                                    (288, 'admin_column_fullname', 'en', 'Full name'),
                                                                    (289, 'admin_column_username', 'hu', 'Felhasználónév'),
                                                                    (290, 'admin_column_username', 'en', 'Username'),
                                                                    (291, 'admin_column_email', 'hu', 'Email'),
                                                                    (292, 'admin_column_email', 'en', 'Email'),
                                                                    (293, 'admin_column_subject', 'hu', 'Kategória'),
                                                                    (294, 'admin_column_subject', 'en', 'Category'),
                                                                    (295, 'admin_column_action', 'hu', 'Művelet'),
                                                                    (296, 'admin_column_action', 'en', 'Action'),
                                                                    (297, 'notify_title', 'hu', 'Értesítések'),
                                                                    (298, 'notify_title', 'en', 'Notifications'),
                                                                    (299, 'notify_clear_all', 'hu', 'Összes értesítés törlése'),
                                                                    (300, 'notify_clear_all', 'en', 'Clear all notifications'),
                                                                    (301, 'notify_more', 'hu', 'Összes →'),
                                                                    (302, 'notify_more', 'en', 'All →'),
                                                                    (303, 'notify_friend_request_sent_to_you', 'hu', 'A felhasználó küldött neked barátfelkérést.'),
                                                                    (304, 'notify_friend_request_sent_to_you', 'en', 'This user sent you a friend request.'),
                                                                    (305, 'notify_you_sent_request', 'hu', 'Te küldted a barátfelkérést.'),
                                                                    (306, 'notify_you_sent_request', 'en', 'You sent the friend request.'),
                                                                    (307, 'notify_is_your_friend', 'hu', 'Ti már barátok vagytok!'),
                                                                    (308, 'notify_is_your_friend', 'en', 'You are already friends!'),
                                                                    (309, 'notify_marked_as_friend', 'hu', 'barátnak jelölt!'),
                                                                    (310, 'notify_marked_as_friend', 'en', 'marked you as a friend!'),
                                                                    (311, 'notify_commented_post', 'hu', 'hozzászólt egy posztodhoz!'),
                                                                    (312, 'notify_commented_post', 'en', 'commented on your post!'),
                                                                    (313, 'friends_list_title', 'hu', 'Barátaid'),
                                                                    (314, 'friends_list_title', 'en', 'Your friends'),
                                                                    (315, 'friends_request_title', 'hu', 'Barátjelölés'),
                                                                    (316, 'friends_request_title', 'en', 'Friend request'),
                                                                    (317, 'friends_mark_as_friend', 'hu', 'Barátnak jelölés'),
                                                                    (318, 'friends_mark_as_friend', 'en', 'Mark as friend'),
                                                                    (319, 'friends_relationship', 'hu', 'Barátság'),
                                                                    (320, 'friends_relationship', 'en', 'Friendship'),
                                                                    (321, 'friends_already_handled', 'hu', 'Már feldolgozott barátjelölés.'),
                                                                    (322, 'friends_already_handled', 'en', 'This friend request has already been handled.'),
                                                                    (323, 'friends_no_friends_yet', 'hu', 'Még nincsenek barátaid.'),
                                                                    (324, 'friends_no_friends_yet', 'en', 'You don’t have any friends yet.'),
                                                                    (325, 'messages_title', 'hu', 'Üzenetek'),
                                                                    (326, 'messages_title', 'en', 'Messages'),
                                                                    (327, 'messages_no_messages', 'hu', 'Nincsenek üzenetek.'),
                                                                    (328, 'messages_no_messages', 'en', 'There are no messages.'),
                                                                    (329, 'messages_choose_friend', 'hu', 'Válassz egy barátot az üzenetküldéshez.'),
                                                                    (330, 'messages_choose_friend', 'en', 'Choose a friend to start messaging.'),
                                                                    (331, 'messages_placeholder', 'hu', 'Írj egy üzenetet...'),
                                                                    (332, 'messages_placeholder', 'en', 'Write a message...'),
                                                                    (333, 'label_name', 'hu', 'Név'),
                                                                    (334, 'label_name', 'en', 'Name'),
                                                                    (335, 'label_firstname', 'hu', 'Keresztnév'),
                                                                    (336, 'label_firstname', 'en', 'First name'),
                                                                    (337, 'label_lastname', 'hu', 'Vezetéknév'),
                                                                    (338, 'label_lastname', 'en', 'Last name'),
                                                                    (339, 'label_users', 'hu', 'Felhasználók'),
                                                                    (340, 'label_users', 'en', 'Users'),
                                                                    (341, 'label_files', 'hu', 'Fájlok'),
                                                                    (342, 'label_files', 'en', 'Files'),
                                                                    (343, 'label_video', 'hu', 'Videó'),
                                                                    (344, 'label_video', 'en', 'Video'),
                                                                    (345, 'label_new_password_same_error', 'hu', 'Az új jelszavad nem egyezhet a régivel.'),
                                                                    (346, 'label_new_password_same_error', 'en', 'Your new password cannot be the same as the old one.'),
                                                                    (347, 'nav_admin', 'hu', 'Admin Panel'),
                                                                    (348, 'nav_admin', 'en', 'Admin Panel'),
                                                                    (349, 'nav_profil', 'hu', 'Fiók'),
                                                                    (350, 'nav_profil', 'en', 'Account'),
                                                                    (351, 'nav_search', 'hu', 'Keresés'),
                                                                    (352, 'nav_search', 'en', 'Search'),
                                                                    (353, 'nav_notify', 'hu', 'Értesítések'),
                                                                    (354, 'nav_notify', 'en', 'Notifications'),
                                                                    (355, 'note_docx_download_hint', 'hu', '.docx fájl - töltsd le a megnyitáshoz.'),
                                                                    (356, 'note_docx_download_hint', 'en', 'This is a .docx file - download it to open.'),
                                                                    (357, 'nameday_none_today', 'hu', 'Nincs névnap ma.'),
                                                                    (358, 'nameday_none_today', 'en', 'There is no name day today.'),
                                                                    (359, 'meta_description_home', 'hu', 'Iskolai jegyzeteket megosztó oldal'),
                                                                    (360, 'meta_description_home', 'en', 'A site for sharing school notes'),
                                                                    (361, 'meta_keywords_home', 'hu', 'iskola, jegyzet, megosztás, tanulás'),
                                                                    (362, 'meta_keywords_home', 'en', 'school, notes, sharing, learning'),
                                                                    (363, 'home_new_uploads', 'hu', 'Új feltöltések'),
                                                                    (364, 'home_new_uploads', 'en', 'New uploads'),
                                                                    (365, 'home_all_arrow', 'hu', 'Összes →'),
                                                                    (366, 'home_all_arrow', 'en', 'All →'),
                                                                    (367, 'upload_title', 'hu', 'Feltöltés'),
                                                                    (368, 'upload_title', 'en', 'Upload'),
                                                                    (369, 'index_title', 'hu', 'Főoldal'),
                                                                    (370, 'index_title', 'en', 'Home'),
                                                                    (371, 'upload_heading', 'hu', 'Anyag feltöltése'),
                                                                    (372, 'upload_heading', 'en', 'Upload material'),
                                                                    (373, 'upload_label_name', 'hu', 'Anyag neve:'),
                                                                    (374, 'upload_label_name', 'en', 'Material name:'),
                                                                    (375, 'upload_placeholder_name', 'hu', 'pl. Fizika ZH anyag'),
                                                                    (376, 'upload_placeholder_name', 'en', 'e.g. Physics test material'),
                                                                    (377, 'upload_placeholder_description', 'hu', 'Rövid leírás az anyagról...'),
                                                                    (378, 'upload_placeholder_description', 'en', 'Short description of the material...'),
                                                                    (379, 'upload_placeholder_subject', 'hu', 'pl. fizika, történelem'),
                                                                    (380, 'upload_placeholder_subject', 'en', 'e.g. physics, history'),
                                                                    (381, 'upload_placeholder_tags', 'hu', 'pl. ZH, jegyzet, beadandó'),
                                                                    (382, 'upload_placeholder_tags', 'en', 'e.g. test, notes, assignment'),
                                                                    (383, 'profile_title', 'hu', 'Fiók'),
                                                                    (384, 'profile_title', 'en', 'Account'),
                                                                    (385, 'profile_of', 'hu', 'profilja'),
                                                                    (386, 'profile_of', 'en', 's profile'),
                                                                    (387, 'profile_data', 'hu', 'Profil adatok'),
                                                                    (388, 'profile_data', 'en', 'Profile information'),
                                                                    (389, 'profile_fullname', 'hu', 'Teljes név'),
                                                                    (390, 'profile_fullname', 'en', 'Full name'),
                                                                    (391, 'profile_username', 'hu', 'Felhasználónév'),
                                                                    (392, 'profile_username', 'en', 'Username'),
                                                                    (393, 'btn_edit_email', 'hu', 'Email szerkesztése'),
                                                                    (394, 'btn_edit_email', 'en', 'Edit email'),
                                                                    (395, 'btn_upload_profile_pic', 'hu', 'Profilkép feltöltése'),
                                                                    (396, 'btn_upload_profile_pic', 'en', 'Upload profile picture'),
                                                                    (397, 'btn_add_friend', 'hu', 'Barátnak jelölés'),
                                                                    (398, 'btn_add_friend', 'en', 'Add friend'),
                                                                    (399, 'profile_friendship', 'hu', 'Barátság'),
                                                                    (400, 'profile_friendship', 'en', 'Friendship'),
                                                                    (401, 'friend_status_friends', 'hu', 'Ti már barátok vagytok!'),
                                                                    (402, 'friend_status_friends', 'en', 'You are already friends!'),
                                                                    (403, 'friend_status_sent_by_you', 'hu', 'Te küldted a barátfelkérést.'),
                                                                    (404, 'friend_status_sent_by_you', 'en', 'You sent the friend request.'),
                                                                    (405, 'friend_status_sent_to_you', 'hu', 'A felhasználó küldött neked barátfelkérést.'),
                                                                    (406, 'friend_status_sent_to_you', 'en', 'This user sent you a friend request.'),
                                                                    (407, 'profile_uploaded_files', 'hu', 'Feltöltött anyagok'),
                                                                    (408, 'profile_uploaded_files', 'en', 'Uploaded materials'),
                                                                    (409, 'label_subject', 'hu', 'Tárgy:'),
                                                                    (410, 'label_subject', 'en', 'Subject:'),
                                                                    (411, 'label_tags', 'hu', 'Címkék:'),
                                                                    (412, 'label_tags', 'en', 'Tags:'),
                                                                    (413, 'docx_warning', 'hu', 'Ez egy .docx fájl. A megtekintéshez töltsd le és nyisd meg Microsoft Word-ben.'),
                                                                    (414, 'docx_warning', 'en', 'This is a .docx file. Download it and open it in Microsoft Word.'),
                                                                    (415, 'bday_title', 'hu', 'Boldog születésnapot,'),
                                                                    (416, 'bday_title', 'en', 'Happy Birthday,'),
                                                                    (417, 'bday_message', 'hu', 'Kívánunk sok sikert és rengeteg kreatív ötletet!'),
                                                                    (418, 'bday_message', 'en', 'We wish you lots of success and many creative ideas!'),
                                                                    (419, 'profile_picture_alt', 'hu', 'Profilkép'),
                                                                    (420, 'profile_picture_alt', 'en', 'Profile picture'),
                                                                    (421, 'profile_registration', 'hu', 'Regisztráció'),
                                                                    (422, 'profile_registration', 'en', 'Registration'),
                                                                    (423, 'btn_delete_file', 'hu', 'Törlés'),
                                                                    (424, 'btn_delete_file', 'en', 'Delete'),
                                                                    (425, 'search_title', 'hu', 'Keresés'),
                                                                    (426, 'search_title', 'en', 'Search'),
                                                                    (427, 'search_keyword', 'hu', 'Kulcsszó:'),
                                                                    (428, 'search_keyword', 'en', 'Keyword:'),
                                                                    (429, 'search_scope_label', 'hu', 'Keresés típusa'),
                                                                    (430, 'search_scope_label', 'en', 'Search scope'),
                                                                    (431, 'search_scope_all', 'hu', 'Mindkettő'),
                                                                    (432, 'search_scope_all', 'en', 'Both'),
                                                                    (433, 'search_scope_files', 'hu', 'Csak fájlok'),
                                                                    (434, 'search_scope_files', 'en', 'Files only'),
                                                                    (435, 'search_scope_users', 'hu', 'Csak felhasználók'),
                                                                    (436, 'search_scope_users', 'en', 'Users only'),
                                                                    (437, 'search_type_label', 'hu', 'Fájltípus'),
                                                                    (438, 'search_type_label', 'en', 'File type'),
                                                                    (439, 'search_type_all', 'hu', 'Összes fájl'),
                                                                    (440, 'search_type_all', 'en', 'All files'),
                                                                    (441, 'search_type_pdf', 'hu', 'PDF'),
                                                                    (442, 'search_type_pdf', 'en', 'PDF'),
                                                                    (443, 'search_type_mp4', 'hu', 'Videó (MP4)'),
                                                                    (444, 'search_type_mp4', 'en', 'Video (MP4)'),
                                                                    (445, 'search_type_docx', 'hu', 'Word (DOCX)'),
                                                                    (446, 'search_type_docx', 'en', 'Word (DOCX)'),
                                                                    (447, 'search_sort_label', 'hu', 'Rendezés'),
                                                                    (448, 'search_sort_label', 'en', 'Sort by'),
                                                                    (449, 'search_sort_new', 'hu', 'Legújabb elöl'),
                                                                    (450, 'search_sort_new', 'en', 'Newest first'),
                                                                    (451, 'search_sort_old', 'hu', 'Legrégebbi elöl'),
                                                                    (452, 'search_sort_old', 'en', 'Oldest first'),
                                                                    (453, 'search_sort_top', 'hu', 'Top értékelt'),
                                                                    (454, 'search_sort_top', 'en', 'Top rated'),
                                                                    (455, 'search_btn', 'hu', 'Keresés'),
                                                                    (456, 'search_btn', 'en', 'Search'),
                                                                    (457, 'pill_pdf', 'hu', 'PDF'),
                                                                    (458, 'pill_pdf', 'en', 'PDF'),
                                                                    (459, 'pill_video', 'hu', 'Videó'),
                                                                    (460, 'pill_video', 'en', 'Video'),
                                                                    (461, 'pill_word', 'hu', 'Word'),
                                                                    (462, 'pill_word', 'en', 'Word'),
                                                                    (463, 'pill_top_rated', 'hu', 'Top értékelt'),
                                                                    (464, 'pill_top_rated', 'en', 'Top rated'),
                                                                    (465, 'pill_users', 'hu', 'Felhasználók'),
                                                                    (466, 'pill_users', 'en', 'Users'),
                                                                    (467, 'result_users', 'hu', 'Felhasználók'),
                                                                    (468, 'result_users', 'en', 'Users'),
                                                                    (469, 'result_files', 'hu', 'Fájlok'),
                                                                    (470, 'result_files', 'en', 'Files'),
                                                                    (471, 'empty_no_users', 'hu', 'Nincs felhasználó találat.'),
                                                                    (472, 'empty_no_users', 'en', 'No matching users found.'),
                                                                    (473, 'empty_no_files_filter', 'hu', 'Nincs fájl találat a megadott szűrőkre.'),
                                                                    (474, 'empty_no_files_filter', 'en', 'No files match the selected filters.'),
                                                                    (475, 'video_fallback', 'hu', 'A böngésződ nem támogatja a video lejátszást.'),
                                                                    (476, 'video_fallback', 'en', 'Your browser does not support video playback.'),
                                                                    (477, 'rating_average', 'hu', 'Átlag értékelés:'),
                                                                    (478, 'rating_average', 'en', 'Average rating:'),
                                                                    (479, 'rating_count_suffix', 'hu', 'ért.'),
                                                                    (480, 'rating_count_suffix', 'en', 'ratings'),
                                                                    (481, 'notif_friend_request_title', 'hu', 'Barátjelölés'),
                                                                    (482, 'notif_friend_request_title', 'en', 'Friend request'),
                                                                    (483, 'notif_friend_marked_you', 'hu', 'barátnak jelölt!'),
                                                                    (484, 'notif_friend_marked_you', 'en', 'sent you a friend request!'),
                                                                    (485, 'btn_accept_friend', 'hu', 'Elfogadás'),
                                                                    (486, 'btn_accept_friend', 'en', 'Accept'),
                                                                    (487, 'notif_friend_already_processed', 'hu', 'Már feldolgozott barátjelölés.'),
                                                                    (488, 'notif_friend_already_processed', 'en', 'This friend request has already been handled.'),
                                                                    (489, 'notif_new_comment_title', 'hu', 'Új hozzászólás'),
                                                                    (490, 'notif_new_comment_title', 'en', 'New comment'),
                                                                    (491, 'notif_comment_your_post', 'hu', 'hozzászólt egy posztodhoz!'),
                                                                    (492, 'notif_comment_your_post', 'en', 'commented on one of your posts!'),
                                                                    (493, 'btn_delete_all_notifications', 'hu', 'Összes értesítés törlése'),
                                                                    (494, 'btn_delete_all_notifications', 'en', 'Delete all notifications'),
                                                                    (495, 'messages_friends_heading', 'hu', 'Barátaid'),
                                                                    (496, 'messages_friends_heading', 'en', 'Your friends'),
                                                                    (497, 'messages_no_friends', 'hu', 'Még nincsenek barátaid.'),
                                                                    (498, 'messages_no_friends', 'en', 'You have no friends yet.'),
                                                                    (499, 'messages_friend_not_found', 'hu', 'A kiválasztott felhasználó nem található.'),
                                                                    (500, 'messages_friend_not_found', 'en', 'The selected user could not be found.'),
                                                                    (501, 'msg_message_send_error', 'hu', 'Hiba történt az üzenet küldésekor.'),
                                                                    (502, 'msg_message_send_error', 'en', 'An error occurred while sending the message.'),
                                                                    (503, 'auth_page_title', 'hu', 'Bejelentkezés'),
                                                                    (504, 'auth_page_title', 'en', 'Log in'),
                                                                    (505, 'auth_welcome_title', 'hu', 'Üdvözlünk a Jegyzetár rendszerében!'),
                                                                    (506, 'auth_welcome_title', 'en', 'Welcome to the Jegyzetár system!'),
                                                                    (507, 'auth_welcome_subtitle', 'hu', 'Jelentkezz be vagy hozz létre új fiókot az induláshoz.'),
                                                                    (508, 'auth_welcome_subtitle', 'en', 'Log in or create a new account to get started.'),
                                                                    (509, 'auth_login_heading', 'hu', 'Bejelentkezés'),
                                                                    (510, 'auth_login_heading', 'en', 'Log in'),
                                                                    (511, 'label_username', 'hu', 'Felhasználónév'),
                                                                    (512, 'label_username', 'en', 'Username'),
                                                                    (513, 'label_password', 'hu', 'Jelszó'),
                                                                    (514, 'label_password', 'en', 'Password'),
                                                                    (515, 'auth_btn_login', 'hu', 'Belépés'),
                                                                    (516, 'auth_btn_login', 'en', 'Log in'),
                                                                    (517, 'auth_forgot_password', 'hu', 'Elfelejtetted a jelszavad?'),
                                                                    (518, 'auth_forgot_password', 'en', 'Forgot your password?'),
                                                                    (519, 'auth_no_account', 'hu', 'Még nincs fiókod?'),
                                                                    (520, 'auth_no_account', 'en', 'Don’t have an account yet?'),
                                                                    (521, 'auth_link_register', 'hu', 'Regisztrálj!'),
                                                                    (522, 'auth_link_register', 'en', 'Sign up!'),
                                                                    (523, 'auth_register_heading', 'hu', 'Regisztráció'),
                                                                    (524, 'auth_register_heading', 'en', 'Registration'),
                                                                    (525, 'label_birthdate', 'hu', 'Születési dátum'),
                                                                    (526, 'label_birthdate', 'en', 'Date of birth'),
                                                                    (527, 'label_gender', 'hu', 'Nem'),
                                                                    (528, 'label_gender', 'en', 'Gender'),
                                                                    (529, 'gender_male', 'hu', 'Férfi'),
                                                                    (530, 'gender_male', 'en', 'Male'),
                                                                    (531, 'gender_female', 'hu', 'Nő'),
                                                                    (532, 'gender_female', 'en', 'Female'),
                                                                    (533, 'gender_other', 'hu', 'Egyéb'),
                                                                    (534, 'gender_other', 'en', 'Other'),
                                                                    (535, 'label_email', 'hu', 'Email'),
                                                                    (536, 'label_email', 'en', 'Email'),
                                                                    (537, 'label_password_again', 'hu', 'Jelszó újra'),
                                                                    (538, 'label_password_again', 'en', 'Password again'),
                                                                    (539, 'auth_security_question_label', 'hu', 'Biztonsági kérdés:'),
                                                                    (540, 'auth_security_question_label', 'en', 'Security question:'),
                                                                    (541, 'auth_security_answer_label', 'hu', 'Válasz'),
                                                                    (542, 'auth_security_answer_label', 'en', 'Answer'),
                                                                    (543, 'auth_btn_register', 'hu', 'Regisztráció'),
                                                                    (544, 'auth_btn_register', 'en', 'Register'),
                                                                    (545, 'auth_have_account', 'hu', 'Már van fiókod?'),
                                                                    (546, 'auth_have_account', 'en', 'Already have an account?'),
                                                                    (547, 'auth_link_login', 'hu', 'Lépj be!'),
                                                                    (548, 'auth_link_login', 'en', 'Log in!'),
                                                                    (549, 'auth_continue_with_discord', 'hu', 'Folytatás Discorddal'),
                                                                    (550, 'auth_continue_with_discord', 'en', 'Continue with Discord'),
                                                                    (551, 'sec_q_favorite_book', 'hu', 'Mi a kedvenc könyved?'),
                                                                    (552, 'sec_q_favorite_book', 'en', 'What is your favorite book?'),
                                                                    (553, 'sec_q_first_pet_name', 'hu', 'Mi volt az első háziállatod neve?'),
                                                                    (554, 'sec_q_first_pet_name', 'en', 'What was the name of your first pet?'),
                                                                    (555, 'sec_q_mother_maiden_name', 'hu', 'Mi az édesanyád leánykori neve?'),
                                                                    (556, 'sec_q_mother_maiden_name', 'en', 'What is your mother’s maiden name?'),
                                                                    (557, 'sec_q_birth_city', 'hu', 'Mi a születési városod?'),
                                                                    (558, 'sec_q_birth_city', 'en', 'What is your birth city?'),
                                                                    (559, 'sec_q_favorite_food', 'hu', 'Mi a kedvenc ételed?'),
                                                                    (560, 'sec_q_favorite_food', 'en', 'What is your favorite food?'),
                                                                    (561, 'msg_storage_failed', 'hu', 'Nem sikerült létrehozni a tárhelyet!'),
                                                                    (562, 'msg_storage_failed', 'en', 'Failed to create storage!'),
                                                                    (563, 'msg_username_exists', 'hu', 'Már létezik ilyen felhasználó!'),
                                                                    (564, 'msg_username_exists', 'en', 'This username is already taken!'),
                                                                    (565, 'msg_wrong_password', 'hu', 'Hibás jelszó!'),
                                                                    (566, 'msg_wrong_password', 'en', 'Incorrect password!'),
                                                                    (567, 'msg_registration_failed', 'hu', 'Hiba történt a regisztráció során.'),
                                                                    (568, 'msg_registration_failed', 'en', 'An error occurred during registration.'),
                                                                    (569, 'email_edit_heading_main', 'hu', 'Email cím módosítása'),
                                                                    (570, 'email_edit_heading_main', 'en', 'Change email address'),
                                                                    (571, 'email_edit_heading_new', 'hu', 'Új email cím'),
                                                                    (572, 'email_edit_heading_new', 'en', 'New email address'),
                                                                    (573, 'label_new_email', 'hu', 'Új email cím:'),
                                                                    (574, 'label_new_email', 'en', 'New email address:'),
                                                                    (575, 'label_new_email_again', 'hu', 'Email cím újra:'),
                                                                    (576, 'label_new_email_again', 'en', 'Email address again:'),
                                                                    (577, 'placeholder_email', 'hu', 'Email'),
                                                                    (578, 'placeholder_email', 'en', 'Email'),
                                                                    (579, 'placeholder_email_again', 'hu', 'Email újra'),
                                                                    (580, 'placeholder_email_again', 'en', 'Email again'),
                                                                    (581, 'label_security_answer_full', 'hu', 'Biztonsági kérdés válasza:'),
                                                                    (582, 'label_security_answer_full', 'en', 'Answer to the security question:'),
                                                                    (583, 'placeholder_security_answer', 'hu', 'Válasz'),
                                                                    (584, 'placeholder_security_answer', 'en', 'Answer'),
                                                                    (585, 'btn_submit', 'hu', 'Elküldés'),
                                                                    (586, 'btn_submit', 'en', 'Submit'),
                                                                    (587, 'btn_back_profile', 'hu', 'Vissza a profilhoz'),
                                                                    (588, 'btn_back_profile', 'en', 'Back to profile'),
                                                                    (589, 'change_success_title', 'hu', 'Sikeres módosítás!'),
                                                                    (590, 'change_success_title', 'en', 'Change successful!'),
                                                                    (591, 'email_edit_success_text', 'hu', 'Az új email címed sikeresen megváltozott!'),
                                                                    (592, 'email_edit_success_text', 'en', 'Your email address has been successfully updated!'),
                                                                    (593, 'msg_wrong_security_answer', 'hu', 'Helytelen biztonsági válasz!'),
                                                                    (594, 'msg_wrong_security_answer', 'en', 'Incorrect security answer!'),
                                                                    (595, 'placeholder_username', 'hu', 'Felhasználónév'),
                                                                    (596, 'placeholder_username', 'en', 'Username'),
                                                                    (597, 'password_forgot_title', 'hu', 'Elfelejtett jelszó'),
                                                                    (598, 'password_forgot_title', 'en', 'Forgot password'),
                                                                    (599, 'password_reset_heading_main', 'hu', 'Jelszó visszaállítása'),
                                                                    (600, 'password_reset_heading_main', 'en', 'Password reset'),
                                                                    (601, 'password_reset_heading_new', 'hu', 'Új jelszó beállítása'),
                                                                    (602, 'password_reset_heading_new', 'en', 'Set a new password'),
                                                                    (603, 'label_new_password', 'hu', 'Új jelszó:'),
                                                                    (604, 'label_new_password', 'en', 'New password:'),
                                                                    (605, 'placeholder_password', 'hu', 'Jelszó'),
                                                                    (606, 'placeholder_password', 'en', 'Password'),
                                                                    (607, 'placeholder_password_again', 'hu', 'Jelszó újra'),
                                                                    (608, 'placeholder_password_again', 'en', 'Password again'),
                                                                    (609, 'btn_change_password', 'hu', 'Jelszó módosítása'),
                                                                    (610, 'btn_change_password', 'en', 'Change password'),
                                                                    (611, 'password_change_success_text', 'hu', 'A jelszavad sikeresen megváltozott!'),
                                                                    (612, 'password_change_success_text', 'en', 'Your password has been successfully changed!'),
                                                                    (613, 'btn_go_to_login', 'hu', 'Bejelentkezés'),
                                                                    (614, 'btn_go_to_login', 'en', 'Back to login'),
                                                                    (615, 'link_back_to_login', 'hu', 'Vissza a bejelentkezéshez'),
                                                                    (616, 'link_back_to_login', 'en', 'Back to login screen'),
                                                                    (617, 'footer_developers_label', 'hu', 'Fejlesztők'),
                                                                    (618, 'footer_developers_label', 'en', 'Developers'),
                                                                    (619, 'footer_github_link', 'hu', 'GitHub'),
                                                                    (620, 'footer_github_link', 'en', 'GitHub'),
                                                                    (621, 'footer_rights', 'hu', '© 2025 Jegyzetár'),
                                                                    (622, 'footer_rights', 'en', '© 2025 Jegyzetár'),
                                                                    (623, 'nav_home', 'de', 'Startseite'),
                                                                    (624, 'nav_upload', 'de', 'Hochladen'),
                                                                    (625, 'nav_messages', 'de', 'Nachrichten'),
                                                                    (626, 'nav_login', 'de', 'Anmelden'),
                                                                    (627, 'nav_logout', 'de', 'Abmelden'),
                                                                    (628, 'hero_greeting', 'de', 'Hallo'),
                                                                    (629, 'hero_nameday', 'de', 'Namenstag heute'),
                                                                    (630, 'guest', 'de', 'Gast'),
                                                                    (631, 'footer_copy', 'de', '© 2025 Jegyzetár'),
                                                                    (632, 'site_tagline', 'de', 'Plattform zum Teilen von Schulnotizen'),
                                                                    (633, 'hero_logged_out_subtitle', 'de', 'Melde dich an oder erstelle ein Konto, um zu starten.'),
                                                                    (634, 'hero_welcome', 'de', 'Willkommen im Jegyzetár-System!'),
                                                                    (635, 'nameday_none', 'de', 'Heute gibt es keinen Namenstag.'),
                                                                    (636, 'birthday_congrats', 'de', 'Alles Gute zum Geburtstag,'),
                                                                    (637, 'meta_keywords', 'de', 'Schule, Notizen, Teilen, Lernen'),
                                                                    (638, 'btn_details', 'de', 'Details'),
                                                                    (639, 'btn_download', 'de', 'Herunterladen'),
                                                                    (640, 'btn_delete', 'de', 'Löschen'),
                                                                    (641, 'btn_back_home', 'de', 'Zur Startseite'),
                                                                    (642, 'btn_back_login', 'de', 'Zur Anmeldung'),
                                                                    (643, 'btn_accept', 'de', 'Akzeptieren'),
                                                                    (644, 'btn_send', 'de', 'Senden'),
                                                                    (645, 'btn_send_alt', 'de', 'Senden'),
                                                                    (646, 'btn_login_cta', 'de', 'Jetzt anmelden'),
                                                                    (647, 'btn_register_cta', 'de', 'Registrieren'),
                                                                    (648, 'btn_continue_discord', 'de', 'Mit Discord fortfahren'),
                                                                    (649, 'btn_go_to_note', 'de', 'Zur Notiz'),
                                                                    (650, 'footer_developers_label', 'de', 'Entwickler'),
                                                                    (651, 'footer_github_link', 'de', 'GitHub'),
                                                                    (652, 'footer_rights', 'de', '© 2025 Jegyzetár'),
                                                                    (653, 'site_title', 'de', 'Jegyzetár'),
                                                                    (654, 'meta_description_home', 'de', 'Plattform zum Teilen von Schulnotizen'),
                                                                    (655, 'meta_keywords_home', 'de', 'Schule, Notizen, Teilen, Lernen'),
                                                                    (656, 'nav_notifications', 'de', 'Benachrichtigungen'),
                                                                    (657, 'nav_profile', 'de', 'Profil'),
                                                                    (658, 'nav_register', 'de', 'Registrieren'),
                                                                    (659, 'btn_edit', 'de', 'Bearbeiten'),
                                                                    (660, 'btn_save', 'de', 'Speichern'),
                                                                    (661, 'btn_cancel', 'de', 'Abbrechen'),
                                                                    (662, 'btn_back_profile', 'de', 'Zurück zum Profil'),
                                                                    (663, 'btn_accept_friend', 'de', 'Freundschaft akzeptieren'),
                                                                    (664, 'btn_send_message', 'de', 'Nachricht senden'),
                                                                    (665, 'btn_delete_all_notifications', 'de', 'Alle Benachrichtigungen löschen'),
                                                                    (666, 'nameday_today', 'de', 'Namenstag heute'),
                                                                    (667, 'nameday_none_today', 'de', 'Heute gibt es keinen Namenstag.'),
                                                                    (668, 'empty_no_notifications', 'de', 'Du hast keine neuen Benachrichtigungen.'),
                                                                    (669, 'empty_no_files', 'de', 'Keine Dateien gefunden.'),
                                                                    (670, 'empty_no_users', 'de', 'Keine Benutzer gefunden.'),
                                                                    (671, 'empty_no_messages', 'de', 'Noch keine Nachrichten.'),
                                                                    (672, 'empty_no_friends', 'de', 'Du hast noch keine Freunde.'),
                                                                    (673, 'notify_title', 'de', 'Benachrichtigungen'),
                                                                    (674, 'notif_friend_request_title', 'de', 'Freundschaftsanfrage'),
                                                                    (675, 'notif_friend_marked_you', 'de', 'hat dich als Freund markiert!'),
                                                                    (676, 'nofif_friend_already_processed', 'de', 'Freundschaftsanfrage bereits bearbeitet.'),
                                                                    (677, 'notif_new_comment_title', 'de', 'Neuer Kommentar'),
                                                                    (678, 'notif_comment_your_post', 'de', 'hat deinen Beitrag kommentiert.'),
                                                                    (679, 'notify_mark_all_read', 'de', 'Alle als gelesen markieren'),
                                                                    (680, 'search_title', 'de', 'Suche'),
                                                                    (681, 'search_keyword', 'de', 'Suchbegriff'),
                                                                    (682, 'search_placeholder', 'de', 'Suche...'),
                                                                    (683, 'search_scope_all', 'de', 'Alles'),
                                                                    (684, 'search_scope_files', 'de', 'Nur Dateien'),
                                                                    (685, 'search_scope_users', 'de', 'Nur Benutzer'),
                                                                    (686, 'search_type_all', 'de', 'Alle Dateitypen'),
                                                                    (687, 'search_type_pdf', 'de', 'PDF'),
                                                                    (688, 'search_type_mp4', 'de', 'Video (MP4)'),
                                                                    (689, 'search_type_docx', 'de', 'Word (DOCX)'),
                                                                    (690, 'search_sort_new', 'de', 'Neueste zuerst'),
                                                                    (691, 'search_sort_old', 'de', 'Älteste zuerst'),
                                                                    (692, 'search_sort_top', 'de', 'Top bewertet'),
                                                                    (693, 'profile_title', 'de', 'Profil'),
                                                                    (694, 'profile_edit', 'de', 'Profil bearbeiten'),
                                                                    (695, 'profile_email', 'de', 'E-Mail'),
                                                                    (696, 'profile_username', 'de', 'Benutzername'),
                                                                    (697, 'profile_birthdate', 'de', 'Geburtsdatum'),
                                                                    (698, 'profile_gender', 'de', 'Geschlecht'),
                                                                    (699, 'profile_registration_date', 'de', 'Registrierungsdatum'),
                                                                    (700, 'gender_male', 'de', 'Männlich'),
                                                                    (701, 'gender_female', 'de', 'Weiblich'),
                                                                    (702, 'gender_other', 'de', 'Divers'),
                                                                    (703, 'email_edit_title', 'de', 'E-Mail ändern'),
                                                                    (704, 'email_new', 'de', 'Neue E-Mail-Adresse'),
                                                                    (705, 'email_new_repeat', 'de', 'Neue E-Mail-Adresse wiederholen'),
                                                                    (706, 'email_change_success', 'de', 'E-Mail erfolgreich geändert!'),
                                                                    (707, 'password_reset_title', 'de', 'Passwort zurücksetzen'),
                                                                    (708, 'password_new', 'de', 'Neues Passwort'),
                                                                    (709, 'password_new_repeat', 'de', 'Passwort wiederholen'),
                                                                    (710, 'password_change_success', 'de', 'Das Passwort wurde erfolgreich geändert!'),
                                                                    (711, 'auth_page_title', 'de', 'Anmeldung'),
                                                                    (712, 'auth_welcome_title', 'de', 'Willkommen im Jegyzetár-System!'),
                                                                    (713, 'auth_welcome_subtitle', 'de', 'Melde dich an oder erstelle ein neues Konto, um zu starten.'),
                                                                    (714, 'auth_login_heading', 'de', 'Anmeldung'),
                                                                    (715, 'auth_register_heading', 'de', 'Registrierung'),
                                                                    (716, 'label_username', 'de', 'Benutzername'),
                                                                    (717, 'label_password', 'de', 'Passwort'),
                                                                    (718, 'label_lastname', 'de', 'Nachname'),
                                                                    (719, 'label_firstname', 'de', 'Vorname'),
                                                                    (720, 'label_birthdate', 'de', 'Geburtsdatum'),
                                                                    (721, 'label_gender', 'de', 'Geschlecht'),
                                                                    (722, 'label_email', 'de', 'E-Mail'),
                                                                    (723, 'label_password_again', 'de', 'Passwort erneut'),
                                                                    (724, 'auth_security_question_label', 'de', 'Sicherheitsfrage:'),
                                                                    (725, 'auth_security_answer_label', 'de', 'Antwort'),
                                                                    (726, 'auth_forgot_password', 'de', 'Passwort vergessen?'),
                                                                    (727, 'auth_btn_login', 'de', 'Anmelden'),
                                                                    (728, 'auth_btn_register', 'de', 'Registrieren'),
                                                                    (729, 'auth_continue_with_discord', 'de', 'Mit Discord fortfahren'),
                                                                    (730, 'auth_no_account', 'de', 'Du hast noch kein Konto?'),
                                                                    (731, 'auth_link_register', 'de', 'Registriere dich!'),
                                                                    (732, 'auth_have_account', 'de', 'Du hast schon ein Konto?'),
                                                                    (733, 'auth_link_login', 'de', 'Melde dich an!'),
                                                                    (734, 'sec_q_favorite_book', 'de', 'Was ist dein Lieblingsbuch?'),
                                                                    (735, 'sec_q_first_pet_name', 'de', 'Wie hieß dein erstes Haustier?'),
                                                                    (736, 'sec_q_mother_maiden_name', 'de', 'Wie lautet der Mädchenname deiner Mutter?'),
                                                                    (737, 'sec_q_birth_city', 'de', 'In welcher Stadt bist du geboren?'),
                                                                    (738, 'sec_q_favorite_food', 'de', 'Was ist dein Lieblingsessen?'),
                                                                    (739, 'msg_storage_created', 'de', 'Speicher wurde erfolgreich erstellt!'),
                                                                    (740, 'msg_storage_failed', 'de', 'Speicher konnte nicht erstellt werden!'),
                                                                    (741, 'msg_storage_create_failed', 'de', 'Speicher konnte nicht erstellt werden!'),
                                                                    (742, 'msg_passwords_not_match', 'de', 'Die Passwörter stimmen nicht überein!'),
                                                                    (743, 'msg_email_exists', 'de', 'Diese E-Mail-Adresse wird bereits verwendet!'),
                                                                    (744, 'msg_username_exists', 'de', 'Dieser Benutzername ist bereits vergeben!'),
                                                                    (745, 'msg_wrong_password', 'de', 'Falsches Passwort!'),
                                                                    (746, 'msg_user_not_found', 'de', 'Es wurde kein solcher Benutzer gefunden!'),
                                                                    (747, 'msg_registration_failed', 'de', 'Bei der Registrierung ist ein Fehler aufgetreten.'),
                                                                    (748, 'msg_wrong_security_answer', 'de', 'Falsche Antwort auf die Sicherheitsfrage!'),
                                                                    (749, 'msg_invalid_user_id', 'de', 'Fehlende oder ungültige Benutzer-ID!'),
                                                                    (750, 'msg_email_same_as_old', 'de', 'Deine neue E-Mail-Adresse darf nicht mit der alten übereinstimmen.'),
                                                                    (751, 'msg_emails_not_match', 'de', 'Die zwei E-Mail-Adressen stimmen nicht überein!'),
                                                                    (752, 'msg_password_same_as_old', 'de', 'Dein neues Passwort darf nicht mit dem alten übereinstimmen.'),
                                                                    (753, 'email_edit_heading_main', 'de', 'E-Mail-Adresse ändern'),
                                                                    (754, 'email_edit_heading_new', 'de', 'Neue E-Mail-Adresse'),
                                                                    (755, 'label_new_email', 'de', 'Neue E-Mail-Adresse:'),
                                                                    (756, 'label_new_email_again', 'de', 'E-Mail-Adresse erneut:'),
                                                                    (757, 'placeholder_email', 'de', 'E-Mail'),
                                                                    (758, 'placeholder_email_again', 'de', 'E-Mail erneut'),
                                                                    (759, 'placeholder_username', 'de', 'Benutzername'),
                                                                    (760, 'label_security_answer_full', 'de', 'Antwort auf die Sicherheitsfrage:'),
                                                                    (761, 'placeholder_security_answer', 'de', 'Antwort'),
                                                                    (762, 'btn_submit', 'de', 'Absenden'),
                                                                    (763, 'change_success_title', 'de', 'Änderung erfolgreich!'),
                                                                    (764, 'email_edit_success_text', 'de', 'Deine neue E-Mail-Adresse wurde erfolgreich gespeichert!'),
                                                                    (765, 'password_forgot_title', 'de', 'Passwort vergessen'),
                                                                    (766, 'password_reset_heading_main', 'de', 'Passwort zurücksetzen'),
                                                                    (767, 'password_reset_heading_new', 'de', 'Neues Passwort festlegen'),
                                                                    (768, 'label_new_password', 'de', 'Neues Passwort:'),
                                                                    (769, 'placeholder_password', 'de', 'Passwort'),
                                                                    (770, 'placeholder_password_again', 'de', 'Passwort erneut'),
                                                                    (771, 'btn_change_password', 'de', 'Passwort ändern'),
                                                                    (772, 'password_change_success_text', 'de', 'Dein Passwort wurde erfolgreich geändert!'),
                                                                    (773, 'btn_go_to_login', 'de', 'Zur Anmeldung'),
                                                                    (774, 'link_back_to_login', 'de', 'Zurück zum Anmeldebildschirm'),
                                                                    (775, 'home_new_uploads', 'de', 'Neue Uploads'),
                                                                    (776, 'home_all_arrow', 'de', 'Alle →'),
                                                                    (777, 'messages_title', 'de', 'Nachrichten'),
                                                                    (778, 'messages_friends_heading', 'de', 'Deine Freunde'),
                                                                    (779, 'messages_no_friends', 'de', 'Du hast noch keine Freunde.'),
                                                                    (780, 'messages_no_messages', 'de', 'Noch keine Nachrichten.'),
                                                                    (781, 'messages_placeholder', 'de', 'Schreibe eine Nachricht...'),
                                                                    (782, 'messages_choose_friend', 'de', 'Wähle einen Freund, um eine Nachricht zu schreiben.'),
                                                                    (783, 'messages_friend_not_found', 'de', 'Der ausgewählte Benutzer wurde nicht gefunden.'),
                                                                    (784, 'btn_upload_profile_pic', 'de', 'Profilbild hochladen'),
                                                                    (785, 'label_subject', 'de', 'Fach'),
                                                                    (786, 'label_tags', 'de', 'Schlagwörter'),
                                                                    (787, 'label_rating_average', 'de', 'Durchschnittliche Bewertung:'),
                                                                    (788, 'rating_average_label', 'de', 'Durchschnittliche Bewertung'),
                                                                    (789, 'rating_count_suffix', 'de', 'Bewertungen'),
                                                                    (790, 'suffix_rating_singular', 'de', 'Bewertung'),
                                                                    (791, 'suffix_rating_plural', 'de', 'Bewertungen'),
                                                                    (792, 'upload_page_title', 'de', 'Hochladen'),
                                                                    (793, 'upload_title', 'de', 'Hochladen'),
                                                                    (794, 'upload_heading', 'de', 'Neue Notiz hochladen'),
                                                                    (795, 'upload_heading_uploaded_files', 'de', 'Deine hochgeladenen Dateien'),
                                                                    (796, 'upload_label_name', 'de', 'Titel:'),
                                                                    (797, 'upload_label_subject', 'de', 'Fach:'),
                                                                    (798, 'upload_label_description', 'de', 'Beschreibung:'),
                                                                    (799, 'upload_label_description_colon', 'de', 'Beschreibung:'),
                                                                    (800, 'upload_label_tags', 'de', 'Schlagwörter:'),
                                                                    (801, 'upload_label_file', 'de', 'Datei:'),
                                                                    (802, 'upload_placeholder_name', 'de', 'z. B. Physik-Testmaterial'),
                                                                    (803, 'upload_placeholder_subject', 'de', 'z. B. Physik, Geschichte'),
                                                                    (804, 'upload_placeholder_description', 'de', 'Kurze Beschreibung des Materials...'),
                                                                    (805, 'upload_placeholder_tags', 'de', 'z. B. Test, Notizen, Abgabe'),
                                                                    (806, 'upload_btn_upload', 'de', 'Hochladen'),
                                                                    (807, 'msg_file_upload_success', 'de', 'Die Datei wurde erfolgreich hochgeladen!'),
                                                                    (808, 'msg_file_upload_failed', 'de', 'Beim Hochladen der Datei ist ein Fehler aufgetreten.'),
                                                                    (809, 'msg_file_upload_error', 'de', 'Beim Hochladen der Datei ist ein Fehler aufgetreten.'),
                                                                    (810, 'msg_invalid_filetype', 'de', 'Dieser Dateityp ist nicht erlaubt.'),
                                                                    (811, 'msg_no_file_selected', 'de', 'Es wurde keine Datei ausgewählt.'),
                                                                    (812, 'docx_warning', 'de', 'Dies ist eine .docx-Datei. Lade sie herunter und öffne sie in Microsoft Word.'),
                                                                    (813, 'video_fallback', 'de', 'Dein Browser unterstützt die Videowiedergabe nicht.'),
                                                                    (814, 'note_details_title', 'de', 'Notizdetails'),
                                                                    (815, 'note_uploaded_by', 'de', 'Hochgeladen von'),
                                                                    (816, 'note_uploaded_at', 'de', 'Hochgeladen am'),
                                                                    (817, 'note_subject', 'de', 'Fach'),
                                                                    (818, 'note_description', 'de', 'Beschreibung'),
                                                                    (819, 'note_tags', 'de', 'Tags'),
                                                                    (820, 'note_file_type', 'de', 'Dateityp'),
                                                                    (821, 'note_back_to_search', 'de', 'Zurück zur Suche'),
                                                                    (822, 'file_preview_pdf', 'de', 'PDF-Vorschau'),
                                                                    (823, 'file_preview_mp4', 'de', 'Videovorschau'),
                                                                    (824, 'file_preview_docx', 'de', 'Dies ist eine DOCX-Datei. Lade sie herunter, um sie in Microsoft Word zu öffnen.'),
                                                                    (825, 'file_preview_not_supported', 'de', 'Dieser Dateityp kann nicht direkt angezeigt werden.'),
                                                                    (826, 'note_rating_title', 'de', 'Bewertung'),
                                                                    (827, 'note_rating_your_rating', 'de', 'Deine Bewertung'),
                                                                    (828, 'note_rating_average', 'de', 'Durchschnittliche Bewertung'),
                                                                    (829, 'note_rating_total', 'de', 'Anzahl Bewertungen'),
                                                                    (830, 'note_rating_login_needed', 'de', 'Du musst angemeldet sein, um zu bewerten.'),
                                                                    (831, 'note_rating_thanks', 'de', 'Danke für deine Bewertung!'),
                                                                    (832, 'comments_title', 'de', 'Kommentare'),
                                                                    (833, 'comments_none', 'de', 'Keine Kommentare vorhanden.'),
                                                                    (834, 'comments_add_comment', 'de', 'Kommentar hinzufügen'),
                                                                    (835, 'comments_placeholder', 'de', 'Schreibe einen Kommentar...'),
                                                                    (836, 'comments_btn_send', 'de', 'Absenden'),
                                                                    (837, 'msg_comment_added', 'de', 'Kommentar erfolgreich hinzugefügt!'),
                                                                    (838, 'msg_comment_failed', 'de', 'Beim Hinzufügen des Kommentars ist ein Fehler aufgetreten.'),
                                                                    (839, 'file_info_title', 'de', 'Dateiinformationen'),
                                                                    (840, 'file_info_size', 'de', 'Dateigröße'),
                                                                    (841, 'file_info_name', 'de', 'Dateiname'),
                                                                    (842, 'file_info_extension', 'de', 'Erweiterung'),
                                                                    (843, 'file_info_last_modified', 'de', 'Zuletzt geändert'),
                                                                    (844, 'file_info_downloads', 'de', 'Downloads'),
                                                                    (845, 'uploader_profile', 'de', 'Zum Profil'),
                                                                    (846, 'uploader_other_files', 'de', 'Andere Dateien dieses Benutzers'),
                                                                    (847, 'note_btn_download', 'de', 'Datei herunterladen'),
                                                                    (848, 'note_btn_back', 'de', 'Zurück'),
                                                                    (849, 'note_btn_delete', 'de', 'Notiz löschen'),
                                                                    (850, 'note_btn_edit', 'de', 'Notiz bearbeiten'),
                                                                    (851, 'note_delete_confirm_title', 'de', 'Löschen bestätigen'),
                                                                    (852, 'note_delete_confirm_text', 'de', 'Bist du sicher, dass du diese Notiz löschen möchtest?'),
                                                                    (853, 'note_delete_success', 'de', 'Die Notiz wurde gelöscht!'),
                                                                    (854, 'note_delete_failed', 'de', 'Beim Löschen der Notiz ist ein Fehler aufgetreten.'),
                                                                    (855, 'note_related_files', 'de', 'Ähnliche Dateien'),
                                                                    (856, 'note_related_no_files', 'de', 'Keine ähnlichen Dateien gefunden.'),
                                                                    (857, 'msg_email_invalid', 'de', 'Ungültige E-Mail-Adresse'),
                                                                    (858, 'msg_invalid_credentials', 'de', 'Ungültige Anmeldedaten'),
                                                                    (859, 'error_generic', 'de', 'Es ist ein Fehler aufgetreten'),
                                                                    (860, 'msg_profile_update_success', 'de', 'Profil wurde aktualisiert'),
                                                                    (861, 'msg_profile_update_failed', 'de', 'Profilaktualisierung fehlgeschlagen'),
                                                                    (862, 'btn_update', 'de', 'Aktualisieren'),
                                                                    (863, 'btn_change', 'de', 'Ändern'),
                                                                    (864, 'btn_retry', 'de', 'Erneut versuchen'),
                                                                    (865, 'sidebar_top_rated', 'de', 'Top bewertet'),
                                                                    (866, 'pill_top_rated', 'de', 'Top bewertet'),
                                                                    (867, 'admin_categories_manage', 'de', 'Kategorien verwalten'),
                                                                    (868, 'admin_column_action', 'de', 'Aktion'),
                                                                    (869, 'admin_column_email', 'de', 'E-Mail'),
                                                                    (870, 'admin_column_fullname', 'de', 'Vollständiger Name'),
                                                                    (871, 'admin_column_name', 'de', 'Name'),
                                                                    (872, 'admin_column_subject', 'de', 'Kategorie'),
                                                                    (873, 'admin_column_username', 'de', 'Benutzername'),
                                                                    (874, 'admin_comments_manage', 'de', 'Kommentare verwalten'),
                                                                    (875, 'admin_files_manage', 'de', 'Dateien verwalten'),
                                                                    (876, 'admin_title', 'de', 'Adminbereich'),
                                                                    (877, 'admin_users_manage', 'de', 'Benutzer verwalten'),
                                                                    (878, 'auth_already_have_account', 'de', 'Du hast schon ein Konto?'),
                                                                    (879, 'auth_field_answer', 'de', 'Antwort'),
                                                                    (880, 'auth_field_birthdate', 'de', 'Geburtsdatum'),
                                                                    (881, 'auth_field_email', 'de', 'E-Mail'),
                                                                    (882, 'auth_field_firstname', 'de', 'Vorname'),
                                                                    (883, 'auth_field_gender', 'de', 'Geschlecht'),
                                                                    (884, 'auth_field_lastname', 'de', 'Nachname'),
                                                                    (885, 'auth_field_password', 'de', 'Passwort'),
                                                                    (886, 'auth_field_password_again', 'de', 'Passwort erneut'),
                                                                    (887, 'auth_field_password_again_colon', 'de', 'Passwort erneut:'),
                                                                    (888, 'auth_field_security_answer', 'de', 'Antwort auf die Sicherheitsfrage:'),
                                                                    (889, 'auth_field_security_question', 'de', 'Sicherheitsfrage:'),
                                                                    (890, 'auth_field_username', 'de', 'Benutzername'),
                                                                    (891, 'auth_field_username_colon', 'de', 'Benutzername:'),
                                                                    (892, 'auth_gender_female', 'de', 'Weiblich'),
                                                                    (893, 'auth_gender_male', 'de', 'Männlich'),
                                                                    (894, 'auth_gender_other', 'de', 'Divers'),
                                                                    (895, 'auth_login_title', 'de', 'Anmeldung'),
                                                                    (896, 'auth_no_account_yet', 'de', 'Du hast noch kein Konto?'),
                                                                    (897, 'auth_placeholder_start_typing', 'de', 'Fang an zu tippen...'),
                                                                    (898, 'auth_register_title', 'de', 'Registrierung'),
                                                                    (899, 'bday_message', 'de', 'Wir wünschen dir viel Erfolg und viele kreative Ideen!'),
                                                                    (900, 'bday_title', 'de', 'Alles Gute zum Geburtstag,'),
                                                                    (901, 'btn_add_friend', 'de', 'Als Freund markieren'),
                                                                    (902, 'btn_delete_file', 'de', 'Datei löschen'),
                                                                    (903, 'btn_login_to_rate', 'de', 'Zum Bewerten anmelden'),
                                                                    (904, 'btn_send_rating', 'de', 'Bewertung senden'),
                                                                    (905, 'comments_heading', 'de', 'Kommentare'),
                                                                    (906, 'email_change_label_again', 'de', 'E-Mail-Adresse erneut:'),
                                                                    (907, 'email_change_label_again_short', 'de', 'E-Mail erneut');
INSERT INTO `translations` (`id`, `t_key`, `lang_code`, `text`) VALUES
                                                                    (908, 'email_change_label_new', 'de', 'Neue E-Mail-Adresse'),
                                                                    (909, 'email_change_label_new_colon', 'de', 'Neue E-Mail-Adresse:'),
                                                                    (910, 'email_change_page_title', 'de', 'E-Mail-Adresse ändern'),
                                                                    (911, 'email_change_title', 'de', 'E-Mail-Änderung'),
                                                                    (912, 'empty_no_comments', 'de', 'Es gibt noch keine Kommentare.'),
                                                                    (913, 'empty_no_files_filter', 'de', 'Keine Dateien entsprechen den ausgewählten Filtern.'),
                                                                    (914, 'empty_no_files_search', 'de', 'Keine Dateien entsprechen den angegebenen Filtern.'),
                                                                    (915, 'empty_no_users_search', 'de', 'Es wurden keine Benutzer gefunden.'),
                                                                    (916, 'error_wrong_password', 'de', 'Falsches Passwort!'),
                                                                    (917, 'error_wrong_security_answer', 'de', 'Falsche Antwort auf die Sicherheitsfrage!'),
                                                                    (918, 'forgot_heading', 'de', 'Passwort zurücksetzen'),
                                                                    (919, 'forgot_new_password', 'de', 'Neues Passwort festlegen'),
                                                                    (920, 'forgot_new_password_label', 'de', 'Neues Passwort:'),
                                                                    (921, 'forgot_title', 'de', 'Passwort vergessen'),
                                                                    (922, 'friend_status_friends', 'de', 'Ihr seid bereits befreundet!'),
                                                                    (923, 'friend_status_sent_by_you', 'de', 'Du hast die Freundschaftsanfrage gesendet.'),
                                                                    (924, 'friend_status_sent_to_you', 'de', 'Dieser Benutzer hat dir eine Freundschaftsanfrage gesendet.'),
                                                                    (925, 'friends_already_handled', 'de', 'Diese Freundschaftsanfrage wurde bereits bearbeitet.'),
                                                                    (926, 'friends_list_title', 'de', 'Deine Freunde'),
                                                                    (927, 'friends_mark_as_friend', 'de', 'Als Freund markieren'),
                                                                    (928, 'friends_no_friends_yet', 'de', 'Du hast noch keine Freunde.'),
                                                                    (929, 'friends_relationship', 'de', 'Freundschaft'),
                                                                    (930, 'friends_request_title', 'de', 'Freundschaftsanfrage'),
                                                                    (931, 'index_title', 'de', 'Startseite'),
                                                                    (932, 'label_favorite', 'de', 'Zu Favoriten hinzufügen'),
                                                                    (933, 'label_files', 'de', 'Dateien'),
                                                                    (934, 'label_mark', 'de', 'Markierung'),
                                                                    (935, 'label_name', 'de', 'Name'),
                                                                    (936, 'label_new_comment', 'de', 'Neuer Kommentar'),
                                                                    (937, 'label_new_password_same_error', 'de', 'Dein neues Passwort darf nicht mit dem alten übereinstimmen.'),
                                                                    (938, 'label_new_uploads', 'de', 'Neue Uploads'),
                                                                    (939, 'label_rating', 'de', 'Bewertung'),
                                                                    (940, 'label_text', 'de', 'Text'),
                                                                    (941, 'label_uploaded_by', 'de', 'Hochgeladen von:'),
                                                                    (942, 'label_users', 'de', 'Benutzer'),
                                                                    (943, 'label_video', 'de', 'Video'),
                                                                    (944, 'msg_comment_write_error', 'de', 'Beim Schreiben des Kommentars ist ein Fehler aufgetreten!'),
                                                                    (945, 'msg_email_change_success', 'de', 'Deine neue E-Mail-Adresse wurde erfolgreich geändert!'),
                                                                    (946, 'msg_file_uploaded', 'de', 'Die Datei wurde erfolgreich hochgeladen!'),
                                                                    (947, 'msg_fill_subject_and_tags', 'de', 'Bitte gib das Fach und die Schlagwörter an!'),
                                                                    (948, 'msg_friendid_missing', 'de', 'Fehler: fehlende Freund-ID.'),
                                                                    (949, 'msg_generic_success_change', 'de', 'Änderung erfolgreich!'),
                                                                    (950, 'msg_invalid_profile_id', 'de', 'Ungültige Profil-ID.'),
                                                                    (951, 'msg_message_empty', 'de', 'Die Nachricht darf nicht leer sein!'),
                                                                    (952, 'msg_message_send_error', 'de', 'Beim Senden der Nachricht ist ein Fehler aufgetreten.'),
                                                                    (953, 'msg_no_permission_admin', 'de', 'Du hast keine Berechtigung, das Admin-Panel zu sehen.'),
                                                                    (954, 'msg_note_missing_or_deleted', 'de', 'Die angeforderte Notiz existiert nicht oder wurde gelöscht.'),
                                                                    (955, 'msg_note_not_found', 'de', 'Notiz nicht gefunden!'),
                                                                    (956, 'msg_only_pdf_mp4_docx', 'de', 'Es können nur PDF-, MP4- oder DOCX-Dateien hochgeladen werden!'),
                                                                    (957, 'msg_password_change_success', 'de', 'Dein Passwort wurde erfolgreich geändert!'),
                                                                    (958, 'msg_profile_not_found', 'de', 'Das angeforderte Profil wurde nicht gefunden.'),
                                                                    (959, 'nav_admin', 'de', 'Admin-Panel'),
                                                                    (960, 'nav_new_note_plus', 'de', '+ Neue Notiz'),
                                                                    (961, 'nav_notify', 'de', 'Benachrichtigungen'),
                                                                    (962, 'nav_profil', 'de', 'Konto'),
                                                                    (963, 'nav_search', 'de', 'Suche'),
                                                                    (964, 'note_docx_download_hint', 'de', 'Dies ist eine .docx-Datei – lade sie herunter, um sie zu öffnen.'),
                                                                    (965, 'notify_clear_all', 'de', 'Alle Benachrichtigungen löschen'),
                                                                    (966, 'notify_commented_post', 'de', 'hat deinen Beitrag kommentiert!'),
                                                                    (967, 'notify_friend_request_sent_to_you', 'de', 'Dieser Benutzer hat dir eine Freundschaftsanfrage gesendet.'),
                                                                    (968, 'notify_is_your_friend', 'de', 'Ihr seid bereits befreundet!'),
                                                                    (969, 'notify_marked_as_friend', 'de', 'hat dich als Freund markiert!'),
                                                                    (970, 'notify_more', 'de', 'Alle →'),
                                                                    (971, 'notify_you_sent_request', 'de', 'Du hast die Freundschaftsanfrage gesendet.'),
                                                                    (972, 'pill_pdf', 'de', 'PDF'),
                                                                    (973, 'pill_users', 'de', 'Benutzer'),
                                                                    (974, 'pill_video', 'de', 'Video'),
                                                                    (975, 'pill_word', 'de', 'Word'),
                                                                    (976, 'profile_data', 'de', 'Profildaten'),
                                                                    (977, 'profile_friendship', 'de', 'Freundschaft'),
                                                                    (978, 'profile_fullname', 'de', 'Vollständiger Name'),
                                                                    (979, 'profile_heading', 'de', 'Mein Konto'),
                                                                    (980, 'profile_label_avatar', 'de', 'Profilbild'),
                                                                    (981, 'profile_label_avatar_upload', 'de', 'Profilbild hochladen'),
                                                                    (982, 'profile_label_birthdate', 'de', 'Geburtsdatum'),
                                                                    (983, 'profile_label_firstname', 'de', 'Vorname'),
                                                                    (984, 'profile_label_lastname', 'de', 'Nachname'),
                                                                    (985, 'profile_label_name', 'de', 'Vollständiger Name'),
                                                                    (986, 'profile_label_username', 'de', 'Benutzer'),
                                                                    (987, 'profile_of', 'de', 'Profil'),
                                                                    (988, 'profile_picture_alt', 'de', 'Profilbild'),
                                                                    (989, 'profile_registration', 'de', 'Registrierung'),
                                                                    (990, 'profile_uploaded_files', 'de', 'Hochgeladene Materialien'),
                                                                    (991, 'rating_average', 'de', 'Durchschnittliche Bewertung:'),
                                                                    (992, 'result_files', 'de', 'Dateien'),
                                                                    (993, 'result_users', 'de', 'Benutzer'),
                                                                    (994, 'search_btn', 'de', 'Suche'),
                                                                    (995, 'search_btn_search', 'de', 'Suche'),
                                                                    (996, 'search_filters_title', 'de', 'Suche'),
                                                                    (997, 'search_label_files', 'de', 'Dateien'),
                                                                    (998, 'search_label_keyword', 'de', 'Suchbegriff:'),
                                                                    (999, 'search_label_users', 'de', 'Benutzer'),
                                                                    (1000, 'search_scope_label', 'de', 'Suchbereich'),
                                                                    (1001, 'search_sort_label', 'de', 'Sortieren nach'),
                                                                    (1002, 'search_type_label', 'de', 'Dateityp'),
                                                                    (1003, 'secq_birth_city', 'de', 'Wie heißt deine Geburtsstadt?'),
                                                                    (1004, 'secq_fav_book', 'de', 'Was ist dein Lieblingsbuch?'),
                                                                    (1005, 'secq_fav_food', 'de', 'Was ist dein Lieblingsessen?'),
                                                                    (1006, 'secq_first_pet', 'de', 'Wie hieß dein erstes Haustier?'),
                                                                    (1007, 'secq_mothers_maiden', 'de', 'Wie lautet der Mädchenname deiner Mutter?'),
                                                                    (1008, 'suffix_ratings_paren', 'de', 'Bewertungen)'),
                                                                    (1009, 'suffix_ratings_short', 'de', 'Bew.'),
                                                                    (1010, 'sec_q_first_pet_name', 'hu', 'Mi volt az első háziállatod neve?'),
                                                                    (1011, 'sec_q_first_pet_name', 'en', 'What was the name of your first pet?'),
                                                                    (1012, 'sec_q_first_pet_name', 'de', 'Wie hieß dein erstes Haustier?'),
                                                                    (1013, 'sec_q_favorite_food', 'hu', 'Mi a kedvenc ételed?'),
                                                                    (1014, 'sec_q_favorite_food', 'en', 'What is your favourite food?'),
                                                                    (1015, 'sec_q_favorite_food', 'de', 'Was ist dein Lieblingsessen?'),
                                                                    (1016, 'error_all_fields_required', 'hu', 'Minden mező kötelező.'),
                                                                    (1017, 'error_all_fields_required', 'en', 'All fields are required.'),
                                                                    (1018, 'error_all_fields_required', 'de', 'Alle Felder sind erforderlich.'),
                                                                    (1019, 'error_bad_email_format', 'hu', 'Hibás email formátum.'),
                                                                    (1020, 'error_bad_email_format', 'en', 'Invalid email format.'),
                                                                    (1021, 'error_bad_email_format', 'de', 'Ungültiges E-Mail-Format.'),
                                                                    (1022, 'error_security_answer_required', 'hu', 'Biztonsági kérdésre választ kell adnod.'),
                                                                    (1023, 'error_security_answer_required', 'en', 'Security question answer is required.'),
                                                                    (1024, 'error_security_answer_required', 'de', 'Antwort auf die Sicherheitsfrage erforderlich.'),
                                                                    (1025, 'msg_file_upload_error', 'hu', 'Hiba történt a fájl feltöltésekor.'),
                                                                    (1026, 'msg_file_upload_error', 'en', 'An error occurred while uploading the file.'),
                                                                    (1027, 'msg_file_upload_error', 'de', 'Beim Hochladen der Datei ist ein Fehler aufgetreten.'),
                                                                    (1028, 'profile_title', 'hu', 'Profil'),
                                                                    (1029, 'profile_title', 'en', 'Profile'),
                                                                    (1030, 'profile_title', 'de', 'Profil'),
                                                                    (1031, 'meta_description_home', 'hu', 'Iskolai jegyzetek megosztása, letöltése, feltöltése.'),
                                                                    (1032, 'meta_description_home', 'en', 'Share, download and upload school notes.'),
                                                                    (1033, 'meta_description_home', 'de', 'Schulnotizen teilen, herunterladen und hochladen.'),
                                                                    (1034, 'meta_keywords_home', 'hu', 'iskola, jegyzet, megosztás, tanulás'),
                                                                    (1035, 'meta_keywords_home', 'en', 'school, notes, sharing, studying'),
                                                                    (1036, 'meta_keywords_home', 'de', 'Schule, Notizen, Teilen, Lernen'),
                                                                    (1037, 'profile_of', 'hu', 'profilja'),
                                                                    (1038, 'profile_of', 'en', 'profile'),
                                                                    (1039, 'bday_title', 'hu', 'Boldog születésnapot,'),
                                                                    (1040, 'bday_title', 'en', 'Happy birthday,'),
                                                                    (1041, 'bday_title', 'de', 'Alles Gute zum Geburtstag,'),
                                                                    (1042, 'bday_message', 'hu', 'Kívánunk sok boldogságot!'),
                                                                    (1043, 'bday_message', 'en', 'We wish you many happy returns!'),
                                                                    (1044, 'bday_message', 'de', 'Wir wünschen dir alles Gute!'),
                                                                    (1045, 'btn_upload_profile_pic', 'hu', 'Feltöltés'),
                                                                    (1046, 'btn_upload_profile_pic', 'en', 'Upload'),
                                                                    (1047, 'btn_upload_profile_pic', 'de', 'Hochladen'),
                                                                    (1048, 'nav_favorites', 'hu', 'Kedvenceim'),
                                                                    (1049, 'nav_favorites', 'en', 'Favorites'),
                                                                    (1050, 'nav_favorites', 'de', 'Favoriten'),
                                                                    (1051, 'profile_friendship', 'hu', 'Barátság státusz'),
                                                                    (1052, 'profile_friendship', 'en', 'Friendship status'),
                                                                    (1053, 'profile_friendship', 'de', 'Freundschaftsstatus'),
                                                                    (1054, 'friend_status_friends', 'hu', 'Barátok'),
                                                                    (1055, 'friend_status_friends', 'en', 'Friends'),
                                                                    (1056, 'friend_status_friends', 'de', 'Freunde'),
                                                                    (1057, 'friend_status_sent_by_you', 'hu', 'Kérés elküldve (te)'),
                                                                    (1058, 'friend_status_sent_by_you', 'en', 'Request sent (by you)'),
                                                                    (1059, 'friend_status_sent_by_you', 'de', 'Anfrage gesendet (von dir)'),
                                                                    (1060, 'friend_status_sent_to_you', 'hu', 'Neked küldtek kérelmet'),
                                                                    (1061, 'friend_status_sent_to_you', 'en', 'Request sent (to you)'),
                                                                    (1062, 'friend_status_sent_to_you', 'de', 'Anfrage gesendet (an dich)'),
                                                                    (1063, 'btn_add_friend', 'hu', 'Barát hozzáadása'),
                                                                    (1064, 'btn_add_friend', 'en', 'Add friend'),
                                                                    (1065, 'btn_add_friend', 'de', 'Freund hinzufügen'),
                                                                    (1066, 'profile_data', 'hu', 'Adatok'),
                                                                    (1067, 'profile_data', 'en', 'Profile information'),
                                                                    (1068, 'profile_data', 'de', 'Profilinformationen'),
                                                                    (1069, 'profile_fullname', 'hu', 'Teljes név'),
                                                                    (1070, 'profile_fullname', 'en', 'Full name'),
                                                                    (1071, 'profile_fullname', 'de', 'Vollständiger Name'),
                                                                    (1072, 'profile_username', 'hu', 'Felhasználónév'),
                                                                    (1073, 'profile_username', 'en', 'Username'),
                                                                    (1074, 'profile_username', 'de', 'Benutzername'),
                                                                    (1075, 'profile_email', 'hu', 'Email'),
                                                                    (1076, 'profile_email', 'en', 'Email'),
                                                                    (1077, 'profile_email', 'de', 'E-Mail'),
                                                                    (1078, 'profile_birthdate', 'hu', 'Születésnap'),
                                                                    (1079, 'profile_birthdate', 'en', 'Birthday'),
                                                                    (1080, 'profile_birthdate', 'de', 'Geburtstag'),
                                                                    (1081, 'profile_registration', 'hu', 'Regisztráció dátuma'),
                                                                    (1082, 'profile_registration', 'en', 'Registered'),
                                                                    (1083, 'profile_registration', 'de', 'Registriert'),
                                                                    (1084, 'btn_edit_profile_data', 'hu', 'Adatok szerkesztése'),
                                                                    (1085, 'btn_edit_profile_data', 'en', 'Edit profile data'),
                                                                    (1086, 'btn_edit_profile_data', 'de', 'Daten bearbeiten'),
                                                                    (1087, 'profile_security_intro', 'hu', 'Mielőtt mentenénk az adataidat, állíts be egy biztonsági kérdést is.'),
                                                                    (1088, 'profile_security_intro', 'en', 'Before saving your data, set a security question.'),
                                                                    (1089, 'profile_security_intro', 'de', 'Bevor wir deine Daten speichern, richte bitte eine Sicherheitsfrage ein.'),
                                                                    (1090, 'auth_field_security_question', 'hu', 'Biztonsági kérdés'),
                                                                    (1091, 'auth_field_security_question', 'en', 'Security question'),
                                                                    (1092, 'auth_field_security_question', 'de', 'Sicherheitsfrage'),
                                                                    (1093, 'auth_field_answer', 'hu', 'Válasz'),
                                                                    (1094, 'auth_field_answer', 'en', 'Answer'),
                                                                    (1095, 'auth_field_answer', 'de', 'Antwort'),
                                                                    (1096, 'placeholder_security_answer', 'hu', 'Írd ide a választ'),
                                                                    (1097, 'placeholder_security_answer', 'en', 'Enter the answer'),
                                                                    (1098, 'placeholder_security_answer', 'de', 'Antwort eingeben'),
                                                                    (1099, 'profile_customization', 'hu', 'Profil testreszabása'),
                                                                    (1100, 'profile_customization', 'en', 'Profile customization'),
                                                                    (1101, 'profile_customization', 'de', 'Profilanpassung'),
                                                                    (1102, 'profile_bio', 'hu', 'Bemutatkozás'),
                                                                    (1103, 'profile_bio', 'en', 'About'),
                                                                    (1104, 'profile_bio', 'de', 'Über mich'),
                                                                    (1105, 'css_placeholder', 'hu', '/* Írd ide a CSS-t a profilodhoz */'),
                                                                    (1106, 'css_placeholder', 'en', '/* Write the CSS for your profile here */'),
                                                                    (1107, 'css_placeholder', 'de', '/* Schreibe hier das CSS für dein Profil */'),
                                                                    (1108, 'msg_css_empty_reset', 'hu', 'A CSS mező üres — visszaállítva.'),
                                                                    (1109, 'msg_css_empty_reset', 'en', 'CSS field is empty — reset.'),
                                                                    (1110, 'msg_css_empty_reset', 'de', 'CSS-Feld ist leer — zurückgesetzt.'),
                                                                    (1111, 'msg_css_approved_by_admin', 'hu', 'A CSS csak akkor lép életbe, ha egy admin jóváhagyja.'),
                                                                    (1112, 'msg_css_approved_by_admin', 'en', 'The CSS takes effect only after an admin approves it.'),
                                                                    (1113, 'msg_css_approved_by_admin', 'de', 'Das CSS tritt erst in Kraft, nachdem ein Admin es genehmigt hat.'),
                                                                    (1114, 'css_approval_note', 'hu', 'A CSS csak akkor lép életbe, ha egy admin jóváhagyja.'),
                                                                    (1115, 'css_approval_note', 'en', 'The CSS only takes effect after admin approval.'),
                                                                    (1116, 'css_approval_note', 'de', 'Das CSS wirkt nur nach Admin-Freigabe.'),
                                                                    (1117, 'profile_theme', 'hu', 'Téma'),
                                                                    (1118, 'profile_theme', 'en', 'Theme'),
                                                                    (1119, 'profile_theme', 'de', 'Thema'),
                                                                    (1120, 'profile_theme_default', 'hu', 'Alap (Sötét)'),
                                                                    (1121, 'profile_theme_default', 'en', 'Default (Dark)'),
                                                                    (1122, 'profile_theme_default', 'de', 'Standard (Dunkel)'),
                                                                    (1123, 'profile_theme_pastel', 'hu', 'Pastel'),
                                                                    (1124, 'profile_theme_pastel', 'en', 'Pastel'),
                                                                    (1125, 'profile_theme_pastel', 'de', 'Pastellfarbe'),
                                                                    (1126, 'profile_theme_forest', 'hu', 'Forest'),
                                                                    (1127, 'profile_theme_forest', 'en', 'Forest'),
                                                                    (1128, 'profile_theme_forest', 'de', 'Wald'),
                                                                    (1129, 'profile_theme_light', 'hu', 'Világos'),
                                                                    (1130, 'profile_theme_light', 'en', 'Light'),
                                                                    (1131, 'profile_theme_light', 'de', 'Hell'),
                                                                    (1132, 'btn_save', 'hu', 'Mentés'),
                                                                    (1133, 'btn_save', 'en', 'Save'),
                                                                    (1134, 'btn_save', 'de', 'Speichern'),
                                                                    (1135, 'profile_custom_css_request', 'hu', 'Egyedi CSS kérés'),
                                                                    (1136, 'profile_custom_css_request', 'en', 'Custom CSS request'),
                                                                    (1137, 'profile_custom_css_request', 'de', 'Benutzerdefinierte CSS-Anfrage'),
                                                                    (1138, 'profile_last_request_status', 'hu', 'Utolsó kérésed státusza:'),
                                                                    (1139, 'profile_last_request_status', 'en', 'Status of your last request:'),
                                                                    (1140, 'profile_last_request_status', 'de', 'Status deiner letzten Anfrage:'),
                                                                    (1141, 'profile_custom_css_not_requested', 'hu', 'Még nem adtál le egyedi CSS kérést.'),
                                                                    (1142, 'profile_custom_css_not_requested', 'en', 'You have not submitted a custom CSS request yet.'),
                                                                    (1143, 'profile_custom_css_not_requested', 'de', 'Du hast noch keine benutzerdefinierte CSS-Anfrage gestellt.'),
                                                                    (1144, 'profile_css_tutorial_summary', 'hu', 'Segítség: hogyan írj saját CSS-t a profilodhoz?'),
                                                                    (1145, 'profile_css_tutorial_summary', 'en', 'Help: how to write custom CSS for your profile?'),
                                                                    (1146, 'profile_css_tutorial_summary', 'de', 'Hilfe: Wie schreibt man eigenes CSS für das Profil?'),
                                                                    (1147, 'profile_css_tutorial_intro', 'hu', 'Az itt megadott CSS csak a profilodra fog hatni, miután egy admin jóváhagyta. Nyugodtan használj olyan szelektorokat, mint body, .main, .card, .profile-name, .profile-username, stb.'),
                                                                    (1148, 'profile_css_tutorial_intro', 'en', 'The CSS entered here will only affect your profile after an admin approves it. You may use selectors like body, .main, .card, .profile-name, .profile-username, etc.'),
                                                                    (1149, 'profile_css_tutorial_intro', 'de', 'Das hier eingegebene CSS wirkt erst auf dein Profil, nachdem ein Admin es freigegeben hat. Du kannst Selektoren wie body, .main, .card, .profile-name, .profile-username usw. verwenden.'),
                                                                    (1150, 'profile_css_tutorial_example', 'hu', 'Példa: sötét, \"neonos\" profil téma — kiindulási alap:'),
                                                                    (1151, 'profile_css_tutorial_example', 'en', 'Example: dark, \"neon\" profile theme — you can use this as a starting point:'),
                                                                    (1152, 'profile_css_tutorial_example', 'de', 'Beispiel: dunkles, \"neon\"-Profilthema — du kannst es als Ausgangspunkt verwenden:'),
                                                                    (1153, 'tip_profile_custom_css', 'hu', 'Tipp: ha csak a profilod egy részét akarod módosítani (pl. a gombokat), elég azokat a classokat piszkálni, amik ide tartoznak, pl. .btn-cta, .btn-ghost, .profile-info-item, .profile-badges, .badge-pill.'),
                                                                    (1154, 'tip_profile_custom_css', 'en', 'Tip: if you only want to change part of your profile (e.g., the buttons), modify the relevant classes such as .btn-cta, .btn-ghost, .profile-info-item, .profile-badges, .badge-pill.'),
                                                                    (1155, 'tip_profile_custom_css', 'de', 'Tipp: Wenn du nur einen Teil deines Profils ändern möchtest (z. B. die Buttons), bearbeite die entsprechenden Klassen wie .btn-cta, .btn-ghost, .profile-info-item, .profile-badges, .badge-pill.'),
                                                                    (1156, 'profile_css_label', 'hu', 'CSS kód'),
                                                                    (1157, 'profile_css_label', 'en', 'CSS code'),
                                                                    (1158, 'profile_css_label', 'de', 'CSS-Code'),
                                                                    (1159, 'profile_custom_css_submit', 'hu', 'Egyedi CSS elküldése'),
                                                                    (1160, 'profile_custom_css_submit', 'en', 'Submit custom CSS'),
                                                                    (1161, 'profile_custom_css_submit', 'de', 'Benutzerdefiniertes CSS senden'),
                                                                    (1162, 'profile_custom_css_reset_btn', 'hu', 'Egyedi CSS visszaállítása'),
                                                                    (1163, 'profile_custom_css_reset_btn', 'en', 'Reset custom CSS'),
                                                                    (1164, 'profile_custom_css_reset_btn', 'de', 'Benutzerdefiniertes CSS zurücksetzen'),
                                                                    (1165, 'profile_uploaded_files', 'hu', 'Feltöltött anyagok'),
                                                                    (1166, 'profile_uploaded_files', 'en', 'Uploaded files'),
                                                                    (1167, 'profile_uploaded_files', 'de', 'Hochgeladene Dateien'),
                                                                    (1168, 'label_unknown_user', 'hu', 'ismeretlen'),
                                                                    (1169, 'label_unknown_user', 'en', 'unknown'),
                                                                    (1170, 'label_unknown_user', 'de', 'unbekannt'),
                                                                    (1171, 'btn_details', 'hu', 'Részletek'),
                                                                    (1172, 'btn_details', 'en', 'Details'),
                                                                    (1173, 'btn_details', 'de', 'Details'),
                                                                    (1174, 'btn_download', 'hu', 'Letöltés'),
                                                                    (1175, 'btn_download', 'en', 'Download'),
                                                                    (1176, 'btn_download', 'de', 'Herunterladen'),
                                                                    (1177, 'label_subject', 'hu', 'Tantárgy:'),
                                                                    (1178, 'label_subject', 'en', 'Subject:'),
                                                                    (1179, 'label_subject', 'de', 'Fach:'),
                                                                    (1180, 'docx_warning', 'hu', 'A DOCX fájl megtekintése nem támogatott, töltsd le a fájlt.'),
                                                                    (1181, 'docx_warning', 'en', 'Viewing DOCX files is not supported; please download the file.'),
                                                                    (1182, 'docx_warning', 'de', 'Die Anzeige von DOCX-Dateien wird nicht unterstützt; bitte lade die Datei herunter.'),
                                                                    (1183, 'label_tags', 'hu', 'Címkék:'),
                                                                    (1184, 'label_tags', 'en', 'Tags:'),
                                                                    (1185, 'label_tags', 'de', 'Tags:'),
                                                                    (1186, 'btn_delete_file', 'hu', 'Törlés'),
                                                                    (1187, 'btn_delete_file', 'en', 'Delete'),
                                                                    (1188, 'btn_delete_file', 'de', 'Löschen'),
                                                                    (1189, 'empty_no_files', 'hu', 'Nincs feltöltött fájl.'),
                                                                    (1190, 'empty_no_files', 'en', 'No files uploaded.'),
                                                                    (1191, 'empty_no_files', 'de', 'Keine Dateien hochgeladen.'),
                                                                    (1192, 'msg_profile_update_success', 'hu', 'Profil frissítve ✅'),
                                                                    (1193, 'msg_profile_update_success', 'en', 'Profile updated ✅'),
                                                                    (1194, 'msg_profile_update_success', 'de', 'Profil aktualisiert ✅'),
                                                                    (1195, 'btn_cancel', 'hu', 'Mégse'),
                                                                    (1196, 'btn_cancel', 'en', 'Cancel');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
                         `id` int(11) NOT NULL,
                         `lastname` varchar(100) DEFAULT NULL,
                         `firstname` varchar(100) DEFAULT NULL,
                         `username` varchar(50) NOT NULL,
                         `birthdate` date DEFAULT NULL,
                         `gender` varchar(10) DEFAULT NULL,
                         `email` varchar(50) NOT NULL,
                         `profile_picture` varchar(255) DEFAULT NULL,
                         `password` varchar(255) NOT NULL,
                         `security_question` varchar(255) NOT NULL,
                         `security_answer` varchar(255) NOT NULL,
                         `admin` tinyint(1) NOT NULL,
                         `registration_date` datetime DEFAULT NULL,
                         `language` varchar(5) NOT NULL DEFAULT 'hu',
                         `oauth_provider` varchar(20) DEFAULT NULL,
                         `oauth_sub` varchar(191) DEFAULT NULL,
                         `email_verified` tinyint(1) NOT NULL DEFAULT 0,
                         `bio` text DEFAULT NULL,
                         `profile_theme` varchar(32) NOT NULL DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `lastname`, `firstname`, `username`, `birthdate`, `gender`, `email`, `profile_picture`, `password`, `security_question`, `security_answer`, `admin`, `registration_date`, `language`, `oauth_provider`, `oauth_sub`, `email_verified`, `bio`, `profile_theme`) VALUES
                                                                                                                                                                                                                                                                                              (1, 'Csontos', 'Kincső', 'csontoskincso05', '2005-04-04', 'female', 'csontoskincso@doomhyena.hu', 'christmaspfp.png', '$2y$10$ZLWnsc4oApKzTPcMkkeC8OcVEmKA3PVyV2Fu7Mn4cCKTrQR5wmLgK', 'Mi a kedvenc könyved?', 'Harry Potter', 1, '2025-12-02 08:52:05', 'hu', NULL, NULL, 1, 'I currently live and study in Budapest. I have been studying as a software developer and tester at Schola Europa Academy since September 2024, but since November 2025, I have also been a student at the Bláthy Otto Titus IT Secondary School, where I am studying to become an IT systems and application operations technician. In September 2019, I started working more actively with JavaScript, writing a Discord bot using the Discord API and creating smaller static websites. JavaScript was my main focus until December 2021, when I met my friend aki26, who introduced me to C#. Later, in 2022, I learned Python in a high school elective course. Since September 2024, I have been studying to become a software developer and tester, where I also learned Java and PHP. My favorite and main language is Java. Outside of school projects, I enjoy building things just to see how they work, from small backend systems to experimental game mechanics in Godot. I’m especially interested in clean architecture, backend development, and turning half-baked ideas into working software. When I’m not coding, I’m usually behind a camera or deep-diving into some random tech rabbit hole. I like learning by doing, breaking things, and then fixing them properly. Currently focused on Java, backend development, and building things that actually ship.', 'forest'),
                                                                                                                                                                                                                                                                                              (8, 'Teszt', 'User', 'tesztuser', '2005-12-16', 'female', 'csontoskincso05@gmail.com', NULL, '$2y$10$rsRPmF5j81OCfV3xbpkIHOCGXeKXLTOkUIb7tH4j73o74H8QQiHRK', 'Mi az édesanyád leánykori neve?', 'Harry Potter', 0, '2025-12-16 00:19:26', 'hu', NULL, NULL, 1, NULL, 'default');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `user_badges`
--

CREATE TABLE `user_badges` (
                               `id` int(11) NOT NULL,
                               `user_id` int(11) NOT NULL,
                               `badge_id` int(11) NOT NULL,
                               `granted_by` int(11) DEFAULT NULL,
                               `granted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `user_custom_css_archive`
--

CREATE TABLE `user_custom_css_archive` (
                                           `id` int(11) NOT NULL,
                                           `original_request_id` int(11) NOT NULL,
                                           `user_id` int(11) NOT NULL,
                                           `css` mediumtext NOT NULL,
                                           `status` enum('pending','approved','rejected') NOT NULL,
                                           `created_at` datetime NOT NULL,
                                           `reviewed_at` datetime DEFAULT NULL,
                                           `reviewed_by` int(11) DEFAULT NULL,
                                           `archived_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `user_custom_css_archive`
--

INSERT INTO `user_custom_css_archive` (`id`, `original_request_id`, `user_id`, `css`, `status`, `created_at`, `reviewed_at`, `reviewed_by`, `archived_at`) VALUES
    (1, 1, 4, 'body {\r\n    background:\r\n        radial-gradient(circle at 0% 0%, rgba(244,114,182,.35), transparent 60%),\r\n        radial-gradient(circle at 100% 0%, rgba(56,189,248,.28), transparent 55%),\r\n        radial-gradient(circle at 50% 100%, rgba(167,139,250,.3), transparent 55%),\r\n        linear-gradient(180deg, #050816 0%, #020617 100%);\r\n    color: #e5e7eb;\r\n}\r\n\r\n.main {\r\n    border-radius: 28px;\r\n    border: 1px solid rgba(148,163,184,.35);\r\n    background:\r\n        radial-gradient(circle at 0% 0%, rgba(244,114,182,.12), transparent 55%),\r\n        radial-gradient(circle at 100% 0%, rgba(56,189,248,.10), transparent 55%),\r\n        linear-gradient(180deg, rgba(15,23,42,.96), rgba(15,23,42,.94));\r\n    box-shadow: 0 24px 60px rgba(0,0,0,.7);\r\n    padding: 40px 34px;\r\n}', 'approved', '2025-12-02 10:57:19', '2025-12-02 10:58:14', 4, '2025-12-07 13:24:08');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `user_custom_css_requests`
--

CREATE TABLE `user_custom_css_requests` (
                                            `id` int(11) NOT NULL,
                                            `user_id` int(11) NOT NULL,
                                            `css` mediumtext NOT NULL,
                                            `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
                                            `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                                            `reviewed_at` datetime DEFAULT NULL,
                                            `reviewed_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `2fa_codes`
--
ALTER TABLE `2fa_codes`
    ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `badges`
--
ALTER TABLE `badges`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- A tábla indexei `comments`
--
ALTER TABLE `comments`
    ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `favorites`
--
ALTER TABLE `favorites`
    ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `files`
--
ALTER TABLE `files`
    ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `friends`
--
ALTER TABLE `friends`
    ADD PRIMARY KEY (`id`),
  ADD KEY `fromid` (`fromid`),
  ADD KEY `toid` (`toid`);

--
-- A tábla indexei `languages`
--
ALTER TABLE `languages`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- A tábla indexei `messages`
--
ALTER TABLE `messages`
    ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `namedays`
--
ALTER TABLE `namedays`
    ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `notifys`
--
ALTER TABLE `notifys`
    ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `ratings`
--
ALTER TABLE `ratings`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_file` (`file_id`,`user_id`),
  ADD KEY `idx_file` (`file_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- A tábla indexei `reg_codes`
--
ALTER TABLE `reg_codes`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- A tábla indexei `reports`
--
ALTER TABLE `reports`
    ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `tokens`
--
ALTER TABLE `tokens`
    ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tokens_user_id` (`user_id`);

--
-- A tábla indexei `translations`
--
ALTER TABLE `translations`
    ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `uniq_oauth` (`oauth_provider`,`oauth_sub`),
  ADD KEY `fk_user_lang` (`language`);

--
-- A tábla indexei `user_badges`
--
ALTER TABLE `user_badges`
    ADD PRIMARY KEY (`id`),
  ADD KEY `user_badges_ibfk_1` (`user_id`),
  ADD KEY `user_badges_ibfk_2` (`badge_id`),
  ADD KEY `user_badges_ibfk_3` (`granted_by`);

--
-- A tábla indexei `user_custom_css_archive`
--
ALTER TABLE `user_custom_css_archive`
    ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `user_custom_css_requests`
--
ALTER TABLE `user_custom_css_requests`
    ADD PRIMARY KEY (`id`),
  ADD KEY `fk_css_reviewer` (`reviewed_by`),
  ADD KEY `fk_css_user` (`user_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `2fa_codes`
--
ALTER TABLE `2fa_codes`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT a táblához `badges`
--
ALTER TABLE `badges`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `comments`
--
ALTER TABLE `comments`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `favorites`
--
ALTER TABLE `favorites`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `files`
--
ALTER TABLE `files`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `friends`
--
ALTER TABLE `friends`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `languages`
--
ALTER TABLE `languages`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `notifys`
--
ALTER TABLE `notifys`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `ratings`
--
ALTER TABLE `ratings`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `reg_codes`
--
ALTER TABLE `reg_codes`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `reports`
--
ALTER TABLE `reports`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `tokens`
--
ALTER TABLE `tokens`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `translations`
--
ALTER TABLE `translations`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1197;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT a táblához `user_badges`
--
ALTER TABLE `user_badges`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `user_custom_css_archive`
--
ALTER TABLE `user_custom_css_archive`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `user_custom_css_requests`
--
ALTER TABLE `user_custom_css_requests`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `tokens`
--
ALTER TABLE `tokens`
    ADD CONSTRAINT `fk_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `user_badges`
--
ALTER TABLE `user_badges`
    ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_ibfk_3` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Megkötések a táblához `user_custom_css_requests`
--
ALTER TABLE `user_custom_css_requests`
    ADD CONSTRAINT `fk_css_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_css_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
