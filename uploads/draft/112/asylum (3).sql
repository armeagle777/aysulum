-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Авг 17 2021 г., 08:06
-- Версия сервера: 10.4.18-MariaDB
-- Версия PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `asylum`
--

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uploaded_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `file_type` int(11) DEFAULT NULL,
  `uploader` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `order_process_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_appeals`
--

CREATE TABLE `tb_appeals` (
  `appeal_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `appeal_notification_date` date DEFAULT NULL,
  `appeal_notification_file` varchar(250) DEFAULT NULL,
  `court_admit_date` date DEFAULT NULL,
  `court_admit_file` varchar(250) DEFAULT NULL,
  `court_admit_status` int(11) NOT NULL DEFAULT 0,
  `filled_in` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `filled_by` int(11) NOT NULL,
  `ms_lawyer` int(11) NOT NULL,
  `processor` int(11) DEFAULT NULL,
  `court_decision` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_appeal_process`
--

CREATE TABLE `tb_appeal_process` (
  `appeal_prcess_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `court_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_appeal_types`
--

CREATE TABLE `tb_appeal_types` (
  `appeal_type_id` int(11) NOT NULL,
  `appeal_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_appeal_types`
--

INSERT INTO `tb_appeal_types` (`appeal_type_id`, `appeal_type`) VALUES
(1, 'պարտավորեցման հայցապահանջ'),
(2, 'անվավերության հայցապահանջ'),
(3, 'անվավերության + պարտավորեցման հայցապահանջ');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_arm_com`
--

CREATE TABLE `tb_arm_com` (
  `community_id` int(11) NOT NULL,
  `marz_id` int(11) NOT NULL,
  `ADM3_PCODE` varchar(7) NOT NULL,
  `ADM3_ARM` varchar(30) NOT NULL,
  `ADM3_EN` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_arm_com`
--

INSERT INTO `tb_arm_com` (`community_id`, `marz_id`, `ADM3_PCODE`, `ADM3_ARM`, `ADM3_EN`) VALUES
(1001, 1, 'AM01001', 'Երևան', 'Yerevan'),
(2001, 2, 'AM02001', 'Աշտարակ', 'Ashtarak'),
(2002, 2, 'AM02002', 'Ապարան', 'Aparan'),
(2003, 2, 'AM02003', 'Թալին', 'Talin'),
(2004, 2, 'AM02004', 'Ագարակ', 'Agarak'),
(2005, 2, 'AM02005', 'Ագարակավան', 'Agarakavan'),
(2006, 2, 'AM02006', 'Ալագյազ', 'Alagyaz'),
(2007, 2, 'AM02007', 'Ակունք', 'Akunk'),
(2008, 2, 'AM02008', 'Աղձք', 'Aghdzk'),
(2010, 2, 'AM02010', 'Անտառուտ', 'Antarut'),
(2011, 2, 'AM02011', 'Աշնակ', 'Ashnak'),
(2012, 2, 'AM02012', 'Ավան', 'Avan'),
(2013, 2, 'AM02013', 'Մեծաձոր', 'Metsadzor'),
(2016, 2, 'AM02016', 'Արագածավան', 'Aragatsavan'),
(2017, 2, 'AM02017', 'Արագածոտն', 'Aragatsotn'),
(2019, 2, 'AM02019', 'Թաթուլ', 'Tatul'),
(2020, 2, 'AM02020', 'Արտաշատավան', 'Artashatavan'),
(2022, 2, 'AM02022', 'Արուճ', 'Aruch'),
(2024, 2, 'AM02024', 'Բազմաղբյուր', 'Bazmaghbyur'),
(2025, 2, 'AM02025', 'Օթևան', 'Otevan'),
(2026, 2, 'AM02026', 'Արևուտ', 'Arevut'),
(2028, 2, 'AM02028', 'Բյուրական', 'Byurakan'),
(2029, 2, 'AM02029', 'Գառնահովիտ', 'Garnahovit'),
(2034, 2, 'AM02034', 'Կանչ', 'Kanch'),
(2035, 2, 'AM02035', 'Դաշտադեմ', 'Dashtadem'),
(2036, 2, 'AM02036', 'Դավթաշեն', 'Davtashen'),
(2038, 2, 'AM02038', 'Դիան', 'Dian'),
(2039, 2, 'AM02039', 'Դպրևանք', 'Dprevank'),
(2041, 2, 'AM02041', 'Եղնիկ', 'Yeghnik'),
(2044, 2, 'AM02044', 'Զարինջա', 'Zarinja'),
(2045, 2, 'AM02045', 'Զովասար', 'Zovasar'),
(2047, 2, 'AM02047', 'Թլիկ', 'Tlik'),
(2048, 2, 'AM02048', 'Իրինդ', 'Irind'),
(2050, 2, 'AM02050', 'Լեռնարոտ', 'Lernarot'),
(2053, 2, 'AM02053', 'Ծաղկահովիտ', 'Tsaghkahovit'),
(2055, 2, 'AM02055', 'Ծաղկասար', 'Tsaghkasar'),
(2057, 2, 'AM02057', 'Կաթնաղբյուր', 'Katnaghbyur'),
(2058, 2, 'AM02058', 'Կարբի', 'Karbi'),
(2059, 2, 'AM02059', 'Կարմրաշեն', 'Karmrashen'),
(2060, 2, 'AM02060', 'Կաքավաձոր', 'Kakavadzor'),
(2061, 2, 'AM02061', 'Կոշ', 'Kosh'),
(2062, 2, 'AM02062', 'Հակո', 'Hako'),
(2064, 2, 'AM02064', 'Հացաշեն', 'Hatsashen'),
(2067, 2, 'AM02067', 'Դդմասար', 'Ddmasar'),
(2068, 2, 'AM02068', 'Ղազարավան', 'Ghazaravan'),
(2069, 2, 'AM02069', 'Մաստարա', 'Mastara'),
(2070, 2, 'AM02070', 'Մելիքգյուղ', 'Melikgyugh'),
(2073, 2, 'AM02073', 'Ներքին Բազմաբերդ', 'Nerkin Bazmaberd'),
(2074, 2, 'AM02074', 'Ներքին Սասնաշեն', 'Nerkin Sasnashen'),
(2076, 2, 'AM02076', 'Նոր Ամանոս', 'Nor Amanos'),
(2079, 2, 'AM02079', 'Նոր Արթիկ', 'Nor Artik'),
(2080, 2, 'AM02080', 'Նոր Եդեսիա', 'Nor Edesia'),
(2081, 2, 'AM02081', 'Շաﬕրամ', 'Shamiram'),
(2084, 2, 'AM02084', 'Շղարշիկ', 'Shgharshik'),
(2085, 2, 'AM02085', 'Ոսկեթաս', 'Vosketas'),
(2086, 2, 'AM02086', 'Ոսկեհատ', 'Voskehat'),
(2087, 2, 'AM02087', 'Ոսկեվազ', 'Voskevaz'),
(2089, 2, 'AM02089', 'Պարտիզակ', 'Partizak'),
(2093, 2, 'AM02093', 'Սաղմոսավան', 'Saghmosavan'),
(2095, 2, 'AM02095', 'Սասունիկ', 'Sasunik'),
(2098, 2, 'AM02098', 'Սորիկ', 'Sorik'),
(2099, 2, 'AM02099', 'Սուսեր', 'Suser'),
(2103, 2, 'AM02103', 'Վերին Բազմաբերդ', 'Verin Bazmaberd'),
(2104, 2, 'AM02104', 'Վերին Սասնաշեն', 'Verin Sasnashen'),
(2105, 2, 'AM02105', 'Վերին Սասունիկ', 'Verin Sasunik'),
(2106, 2, 'AM02106', 'Տեղեր', 'Tegher'),
(2107, 2, 'AM02107', 'Ցամաքասար', 'Tsamakasar'),
(2108, 2, 'AM02108', 'Ուշի', 'Ushi'),
(2109, 2, 'AM02109', 'Ուջան', 'Ujan'),
(2110, 2, 'AM02110', 'Փարպի', 'Parpi'),
(2112, 2, 'AM02112', 'Օհանավան', 'Ohanavan'),
(2113, 2, 'AM02113', 'Օշական', 'Oshakan'),
(2114, 2, 'AM02114', 'Օրգով', 'Orgov'),
(3001, 3, 'AM03001', 'Արտաշատ', 'Artashat'),
(3002, 3, 'AM03002', 'Արարատ', 'Ararat'),
(3003, 3, 'AM03003', 'Մասիս', 'Masis'),
(3004, 3, 'AM03004', 'Վեդի', 'Vedi'),
(3005, 3, 'AM03005', 'Աբովյան', 'Abovyan'),
(3006, 3, 'AM03006', 'Ազատաշեն', 'Azatashen'),
(3007, 3, 'AM03007', 'Ազատավան', 'Azatavan'),
(3008, 3, 'AM03008', 'Այգավան', 'Aygavan'),
(3009, 3, 'AM03009', 'Այգեզարդ', 'Aygezard'),
(3010, 3, 'AM03010', 'Այգեպատ', 'Aygepat'),
(3011, 3, 'AM03011', 'Այգեստան', 'Aygestan'),
(3012, 3, 'AM03012', 'Այնթապ', 'Ayntap'),
(3013, 3, 'AM03013', 'Ավշար', 'Avshar'),
(3014, 3, 'AM03014', 'Արալեզ', 'Aralez'),
(3015, 3, 'AM03015', 'Արարատ', 'Ararat'),
(3016, 3, 'AM03016', 'Արաքսավան', 'Araksavan'),
(3017, 3, 'AM03017', 'Արբաթ', 'Arbat'),
(3018, 3, 'AM03018', 'Արգավանդ', 'Argavand'),
(3019, 3, 'AM03019', 'Արմաշ', 'Armash'),
(3020, 3, 'AM03020', 'Արևաբույր', 'Arevabuyr'),
(3021, 3, 'AM03021', 'Արևշատ', 'Arevshat'),
(3022, 3, 'AM03022', 'Բաղրամյան', 'Baghramyan'),
(3023, 3, 'AM03023', 'Բարձրաշեն', 'Bardzrashen'),
(3024, 3, 'AM03024', 'Բերդիկ', 'Berdik'),
(3025, 3, 'AM03025', 'Բերքանուշ', 'Berkanush'),
(3026, 3, 'AM03026', 'Բյուրավան', 'Byuravan'),
(3027, 3, 'AM03027', 'Բուրաստան', 'Burastan'),
(3028, 3, 'AM03028', 'Գեղանիստ', 'Geghanist'),
(3029, 3, 'AM03029', 'Գետազատ', 'Getazat'),
(3030, 3, 'AM03030', 'Գետափնյա', 'Getapnya'),
(3031, 3, 'AM03031', 'Գոռավան', 'Goravan'),
(3032, 3, 'AM03032', 'Դալար', 'Dalar'),
(3033, 3, 'AM03033', 'Դաշտավան', 'Dashtavan'),
(3034, 3, 'AM03034', 'Դաշտաքար', 'Dashtakar'),
(3035, 3, 'AM03035', 'Դարակերտ', 'Darakert'),
(3036, 3, 'AM03036', 'Դարբնիկ', 'Darbnik'),
(3037, 3, 'AM03037', 'Դեղձուտ', 'Deghdzut'),
(3038, 3, 'AM03038', 'Դիﬕտրով', 'Dimitrov'),
(3039, 3, 'AM03039', 'Դիտակ', 'Ditak'),
(3040, 3, 'AM03040', 'Դվին', 'Dvin'),
(3041, 3, 'AM03041', 'Եղեգնավան', 'Yeghegnavan'),
(3042, 3, 'AM03042', 'Երասխ', 'Yeraskh'),
(3043, 3, 'AM03043', 'Զանգակատուն', 'Zangakatun'),
(3044, 3, 'AM03044', 'Զորակ', 'Zorak'),
(3045, 3, 'AM03045', 'Լանջազատ', 'Lanjazat'),
(3047, 3, 'AM03047', 'Լանջառ', 'Lanjar'),
(3048, 3, 'AM03048', 'Լուսաշող', 'Lusashogh'),
(3049, 3, 'AM03049', 'Լուսառատ', 'Lusarat'),
(3050, 3, 'AM03050', 'Խաչփառ', 'Khachpar'),
(3051, 3, 'AM03051', 'Կանաչուտ', 'Kanachut'),
(3052, 3, 'AM03052', 'Հայանիստ', 'Hayanist'),
(3053, 3, 'AM03053', 'Հնաբերդ', 'Hnaberd'),
(3054, 3, 'AM03054', 'Հովտաշատ', 'Hovtashat'),
(3055, 3, 'AM03055', 'Հովտաշեն', 'Hovtashen'),
(3056, 3, 'AM03056', 'Ղուկասավան', 'Ghukasavan'),
(3057, 3, 'AM03057', 'Մասիս', 'Masis'),
(3058, 3, 'AM03058', 'Մարմարաշեն', 'Marmarashen'),
(3059, 3, 'AM03059', 'Մխչյան', 'Mkhchyan'),
(3060, 3, 'AM03060', 'Մրգանուշ', 'Mrganush'),
(3061, 3, 'AM03061', 'Մրգավան', 'Mrgavan'),
(3062, 3, 'AM03062', 'Մրգավետ', 'Mrgavet'),
(3063, 3, 'AM03063', 'Նարեկ', 'Narek'),
(3064, 3, 'AM03064', 'Նիզաﬕ', 'Nizami'),
(3065, 3, 'AM03065', 'Նշավան', 'Nshavan'),
(3066, 3, 'AM03066', 'Նոյակերտ', 'Noyakert'),
(3067, 3, 'AM03067', 'Նորաբաց', 'Norabats'),
(3068, 3, 'AM03068', 'Նորամարգ', 'Noramarg'),
(3069, 3, 'AM03069', 'Նորաշեն', 'Norashen'),
(3070, 3, 'AM03070', 'Նոր Խարբերդ', 'Nor Kharberd'),
(3071, 3, 'AM03071', 'Նոր կյանք', 'Nor Kyank'),
(3072, 3, 'AM03072', 'Նոր Կյուրին', 'Nor Kyurin'),
(3073, 3, 'AM03073', 'Նոր ուղի', 'Nor Ughi'),
(3074, 3, 'AM03074', 'Շահումյան', 'Shahumyan'),
(3076, 3, 'AM03076', 'Ոսկետափ', 'Vosketap'),
(3077, 3, 'AM03077', 'Ոստան', 'Vostan'),
(3078, 3, 'AM03078', 'Պարույր Սևակ', 'Paruyr Sevak'),
(3079, 3, 'AM03079', 'Ջրահովիտ', 'Jrahovit'),
(3080, 3, 'AM03080', 'Ջրաշեն', 'Jrashen'),
(3081, 3, 'AM03081', 'Ռանչպար', 'Ranchpar'),
(3082, 3, 'AM03082', 'Սայաթ-Նովա', 'Sayat-Nova'),
(3083, 3, 'AM03083', 'Սիս', 'Sis'),
(3084, 3, 'AM03084', 'Սիսավան', 'Sisavan'),
(3085, 3, 'AM03085', 'Սիփանիկ', 'Sipanik'),
(3086, 3, 'AM03086', 'Սուրենավան', 'Surenavan'),
(3087, 3, 'AM03087', 'Վանաշեն', 'Vanashen'),
(3088, 3, 'AM03088', 'Վարդաշատ', 'Vardashat'),
(3089, 3, 'AM03089', 'Վարդաշեն', 'Vardashen'),
(3090, 3, 'AM03090', 'Գինեվետ', 'Ginevet'),
(3091, 3, 'AM03091', 'Վերին Արտաշատ', 'Verin Artashat'),
(3092, 3, 'AM03092', 'Վերին Դվին', 'Verin Dvin'),
(3093, 3, 'AM03093', 'Տափերական', 'Taperakan'),
(3094, 3, 'AM03094', 'Ուրցալանջ', 'Urtsalanj'),
(3095, 3, 'AM03095', 'Ուրցաձոր', 'Urtsadzor'),
(3096, 3, 'AM03096', 'Փոքր Վեդի', 'Pokr Vedi'),
(3097, 3, 'AM03097', 'Քաղցրաշեն', 'Kaghtsrashen'),
(4001, 4, 'AM04001', 'Արմավիր', 'Armavir'),
(4002, 4, 'AM04002', 'Վաղարշապատ', 'Vagharshapat'),
(4003, 4, 'AM04003', 'Մեծամոր', 'Metsamor'),
(4004, 4, 'AM04004', 'Ակնալիճ', 'Aknalich'),
(4005, 4, 'AM04005', 'Ակնաշեն', 'Aknashen'),
(4006, 4, 'AM04006', 'Աղաﬖատուն', 'Aghavnatun'),
(4007, 4, 'AM04007', 'Ամասիա', 'Amasia'),
(4008, 4, 'AM04008', 'Ամբերդ', 'Amberd'),
(4009, 4, 'AM04009', 'Այգեկ', 'Aygek'),
(4010, 4, 'AM04010', 'Այգեշատ (Արմավիր)', 'Aygeshat (Armavir)'),
(4011, 4, 'AM04011', 'Այգեշատ (Էջմիածին)', 'Aygeshat (Echmiadzin)'),
(4012, 4, 'AM04012', 'Ապագա', 'Apaga'),
(4013, 4, 'AM04013', 'Առատաշեն', 'Aratashen'),
(4014, 4, 'AM04014', 'Արագած', 'Aragats'),
(4015, 4, 'AM04015', 'Արազափ', 'Arazap'),
(4016, 4, 'AM04016', 'Արաքս (Արմավիր)', 'Araks (Armavir)'),
(4017, 4, 'AM04017', 'Արաքս (Էջմիածին)', 'Araks (Echmiadzin)'),
(4018, 4, 'AM04018', 'Արգավանդ', 'Argavand'),
(4019, 4, 'AM04019', 'Արգինա', 'Argina'),
(4020, 4, 'AM04020', 'Արմավիր', 'Armavir'),
(4021, 4, 'AM04021', 'Արշալույս', 'Arshaluys'),
(4022, 4, 'AM04022', 'Արտաﬔտ', 'Artamet'),
(4023, 4, 'AM04023', 'Արտիﬔտ', 'Artimet'),
(4024, 4, 'AM04024', 'Արտաշար', 'Artashar'),
(4025, 4, 'AM04025', 'Արևադաշտ', 'Arevadasht'),
(4026, 4, 'AM04026', 'Արևաշատ', 'Arevashat'),
(4027, 4, 'AM04027', 'Արևիկ', 'Arevik'),
(4028, 4, 'AM04028', 'Բագարան', 'Bagaran'),
(4029, 4, 'AM04029', 'Բաղրամյան (Բաղրամյան)', 'Baghramyan (Baghramyan)'),
(4030, 4, 'AM04030', 'Բաղրամյան (Էջմիածին)', 'Baghramyan (Echmiadzin)'),
(4031, 4, 'AM04031', 'Բամբակաշատ', 'Bambakashat'),
(4032, 4, 'AM04032', 'Բերքաշատ', 'Berkashat'),
(4033, 4, 'AM04033', 'Գայ', 'Gai'),
(4034, 4, 'AM04034', 'Գետաշեն', 'Getashen'),
(4035, 4, 'AM04035', 'Գրիբոյեդով', 'Griboyedov'),
(4036, 4, 'AM04036', 'Դալարիկ', 'Dalarik'),
(4037, 4, 'AM04037', 'Դաշտ', 'Dasht'),
(4038, 4, 'AM04038', 'Դողս', 'Doghs'),
(4039, 4, 'AM04039', 'Եղեգնուտ', 'Yeghegnut'),
(4040, 4, 'AM04040', 'Երասխահուն', 'Yeraskhahun'),
(4041, 4, 'AM04041', 'Երվանդաշատ', 'Yervandashat'),
(4042, 4, 'AM04042', 'Զարթոնք', 'Zartonk'),
(4043, 4, 'AM04043', 'Մայիսյան', 'Mayisyan'),
(4044, 4, 'AM04044', 'Լենուղի', 'Lenughi'),
(4045, 4, 'AM04045', 'Լեռնագոգ', 'Lernagog'),
(4046, 4, 'AM04046', 'Լեռնաﬔրձ', 'Lernamerdz'),
(4047, 4, 'AM04047', 'Լուկաշին', 'Lukashin'),
(4048, 4, 'AM04048', 'Լուսագյուղ', 'Lusagyugh'),
(4049, 4, 'AM04049', 'Խանջյան', 'Khanjyan'),
(4050, 4, 'AM04050', 'Խորոնք', 'Khoronk'),
(4051, 4, 'AM04051', 'Ծաղկալանջ', 'Tsaghkalanj'),
(4052, 4, 'AM04052', 'Ծաղկունք', 'Tsaghkunk'),
(4053, 4, 'AM04053', 'Ծիածան', 'Tsiatsan'),
(4054, 4, 'AM04054', 'Կողբավան', 'Koghbavan'),
(4055, 4, 'AM04055', 'Հայթաղ', 'Haytagh'),
(4056, 4, 'AM04056', 'Հայկաշեն', 'Haykashen'),
(4057, 4, 'AM04057', 'Հայկավան', 'Haykavan'),
(4058, 4, 'AM04058', 'Հացիկ', 'Hatsik'),
(4059, 4, 'AM04059', 'Սարդարապատ', 'Sardarapat'),
(4060, 4, 'AM04060', 'Հովտաﬔջ', 'Hovtamej'),
(4061, 4, 'AM04061', 'Հուշակերտ', 'Hushakert'),
(4062, 4, 'AM04062', 'Այգեվան', 'Aygevan'),
(4063, 4, 'AM04063', 'Մարգարա', 'Margara'),
(4064, 4, 'AM04064', 'Մեծամոր', 'Metsamor'),
(4065, 4, 'AM04065', 'Մերձավան', 'Merdzavan'),
(4066, 4, 'AM04066', 'Մյասնիկյան', 'Myasnikyan'),
(4067, 4, 'AM04067', 'Մրգաշատ', 'Mrgashat'),
(4068, 4, 'AM04068', 'Մրգաստան', 'Mrgastan'),
(4069, 4, 'AM04069', 'Մուսալեռ', 'Musaler'),
(4070, 4, 'AM04070', 'Նալբանդյան', 'Nalbandyan'),
(4071, 4, 'AM04071', 'Նոր Արմավիր', 'Nor Armavir'),
(4072, 4, 'AM04072', 'Նոր Արտագերս', 'Nor Artagers'),
(4073, 4, 'AM04073', 'Նոր Կեսարիա', 'Nor Kesaria'),
(4074, 4, 'AM04074', 'Նորակերտ', 'Norakert'),
(4075, 4, 'AM04075', 'Նորապատ', 'Norapat'),
(4076, 4, 'AM04076', 'Նորավան', 'Noravan'),
(4077, 4, 'AM04077', 'Շահումյան', 'Shahumyan'),
(4078, 4, 'AM04078', 'Շահումյանի թռչնաֆաբրիկա', 'Shahumyan poultry farm'),
(4079, 4, 'AM04079', 'Շենավան', 'Shenavan'),
(4080, 4, 'AM04080', 'Շենիկ', 'Shenik'),
(4081, 4, 'AM04081', 'Ոսկեհատ', 'Voskehat'),
(4082, 4, 'AM04082', 'Պտղունք', 'Ptghunk'),
(4083, 4, 'AM04083', 'Ջանֆիդա', 'Janfida'),
(4084, 4, 'AM04084', 'Ջրաշեն', 'Jrashen'),
(4085, 4, 'AM04085', 'Ջրառատ', 'Jrarat'),
(4086, 4, 'AM04086', 'Ջրարբի', 'Jrarbi'),
(4087, 4, 'AM04087', 'Գեղակերտ', 'Geghakert'),
(4088, 4, 'AM04088', 'Ալաշկերտ', 'Alashkert'),
(4089, 4, 'AM04089', 'Վանանդ', 'Vanand'),
(4090, 4, 'AM04090', 'Վարդանաշեն', 'Vardanashen'),
(4091, 4, 'AM04091', 'Տալվորիկ', 'Talvorik'),
(4092, 4, 'AM04092', 'Տանձուտ', 'Tandzut'),
(4093, 4, 'AM04093', 'Տարոնիկ', 'Taronik'),
(4094, 4, 'AM04094', 'Փարաքար', 'Parakar'),
(4095, 4, 'AM04095', 'Փշատավան', 'Pshatavan'),
(4096, 4, 'AM04096', 'Քարակերտ', 'Karakert'),
(4097, 4, 'AM04097', 'Ֆերիկ', 'Ferik'),
(5001, 5, 'AM05001', 'Գավառ', 'Gavar'),
(5002, 5, 'AM05002', 'Ճամբարակ', 'Chambarak'),
(5003, 5, 'AM05003', 'Մարտունի', 'Martuni'),
(5004, 5, 'AM05004', 'Սևան', 'Sevan'),
(5005, 5, 'AM05005', 'Վարդենիս', 'Vardenis'),
(5007, 5, 'AM05007', 'Ախպրաձոր', 'Akhpradzor'),
(5008, 5, 'AM05008', 'Ակունք', 'Akunk'),
(5013, 5, 'AM05013', 'Աստղաձոր', 'Astghadzor'),
(5016, 5, 'AM05016', 'Արծվանիստ', 'Artsvanist'),
(5020, 5, 'AM05020', 'Բերդկունք', 'Berdkunk'),
(5021, 5, 'AM05021', 'Գանձակ', 'Gandzak'),
(5023, 5, 'AM05023', 'Գեղամասար', 'Geghamasar'),
(5024, 5, 'AM05024', 'Գեղամավան', 'Geghamavan'),
(5025, 5, 'AM05025', 'Գեղարքունիք', 'Gegharkunik'),
(5026, 5, 'AM05026', 'Գեղաքար', 'Geghakar'),
(5027, 5, 'AM05027', 'Գեղհովիտ', 'Geghovit'),
(5030, 5, 'AM05030', 'Դդմաշեն', 'Ddmashen'),
(5033, 5, 'AM05033', 'Երանոս', 'Yeranos'),
(5034, 5, 'AM05034', 'Զոլաքար', 'Zolakar'),
(5035, 5, 'AM05035', 'Զովաբեր', 'Zovaber'),
(5036, 5, 'AM05036', 'Ծովասար', 'Tsovasar'),
(5038, 5, 'AM05038', 'Լանջաղբյուր', 'Lanjaghbyur'),
(5039, 5, 'AM05039', 'Լիճք', 'Lichk'),
(5040, 5, 'AM05040', 'Լճաշեն', 'Lchashen'),
(5041, 5, 'AM05041', 'Լճավան', 'Lchavan'),
(5042, 5, 'AM05042', 'Լճափ', 'Lchap'),
(5043, 5, 'AM05043', 'Լուսակունք', 'Lusakunk'),
(5044, 5, 'AM05044', 'Խաչաղբյուր', 'Khachaghbyur'),
(5045, 5, 'AM05045', 'Ծակքար', 'Tsakkar'),
(5046, 5, 'AM05046', 'Ծաղկաշեն', 'Tsaghkashen'),
(5047, 5, 'AM05047', 'Ծաղկունք', 'Tsaghkunk'),
(5049, 5, 'AM05049', 'Ծովագյուղ', 'Tsovagyugh'),
(5050, 5, 'AM05050', 'Ծովազարդ', 'Tsovazard'),
(5051, 5, 'AM05051', 'Ծովակ', 'Tsovak'),
(5052, 5, 'AM05052', 'Ծովինար', 'Tsovinar'),
(5055, 5, 'AM05055', 'Կարճաղբյուր', 'Karchaghbyur'),
(5056, 5, 'AM05056', 'Կարﬕրգյուղ', 'Karmirgyugh'),
(5059, 5, 'AM05059', 'Հայրավանք', 'Hayravank'),
(5060, 5, 'AM05060', 'Ձորագյուղ', 'Dzoragyugh'),
(5062, 5, 'AM05062', 'Մադինա', 'Madina'),
(5064, 5, 'AM05064', 'Մաքենիս', 'Makenis'),
(5065, 5, 'AM05065', 'Մեծ Մասրիկ', 'Mets Masrik'),
(5066, 5, 'AM05066', 'Ներքին Գետաշեն', 'Nerkin Getashen'),
(5069, 5, 'AM05069', 'Նորակերտ', 'Norakert'),
(5070, 5, 'AM05070', 'Նորաշեն', 'Norashen'),
(5071, 5, 'AM05071', 'Նորատուս', 'Noratus'),
(5074, 5, 'AM05074', 'Շողակաթ', 'Shoghakat'),
(5075, 5, 'AM05075', 'Չկալովկա', 'Chkalovka'),
(5078, 5, 'AM05078', 'Սարուխան', 'Sarukhan'),
(5079, 5, 'AM05079', 'Սեմյոնովկա', 'Semyonovka'),
(5082, 5, 'AM05082', 'Վաղաշեն', 'Vaghashen'),
(5083, 5, 'AM05083', 'Վանևան', 'Vanevan'),
(5084, 5, 'AM05084', 'Վարդաձոր', 'Vardadzor'),
(5085, 5, 'AM05085', 'Վարդենիկ', 'Vardenik'),
(5086, 5, 'AM05086', 'Վարսեր', 'Varser'),
(5087, 5, 'AM05087', 'Վերին Գետաշեն', 'Verin Getashen'),
(5089, 5, 'AM05089', 'Տորֆավան', 'Torfavan'),
(6001, 6, 'AM06001', 'Վանաձոր', 'Vanadzor'),
(6002, 6, 'AM06002', 'Ալավերդի', 'Alaverdi'),
(6003, 6, 'AM06003', 'Ախթալա', 'Akhtala'),
(6004, 6, 'AM06004', 'Թումանյան', 'Tumanyan'),
(6006, 6, 'AM06006', 'Սպիտակ', 'Spitak'),
(6007, 6, 'AM06007', 'Ստեփանավան', 'Stepanavan'),
(6008, 6, 'AM06008', 'Տաշիր', 'Tashir'),
(6010, 6, 'AM06010', 'Ազնվաձոր', 'Aznvadzor'),
(6015, 6, 'AM06015', 'Անտառամուտ', 'Antaramut'),
(6019, 6, 'AM06019', 'Արջուտ', 'Arjut'),
(6021, 6, 'AM06021', 'Արևաշող', 'Arevashogh'),
(6023, 6, 'AM06023', 'Բազում', 'Bazum'),
(6026, 6, 'AM06026', 'Անտառաշեն', 'Antarashen'),
(6028, 6, 'AM06028', 'Գեղասար', 'Geghasar'),
(6029, 6, 'AM06029', 'Գյուլագարակ', 'Gyulagarak'),
(6030, 6, 'AM06030', 'Գոգարան', 'Gogaran'),
(6031, 6, 'AM06031', 'Գուգարք', 'Gugark'),
(6033, 6, 'AM06033', 'Դարպաս', 'Darpas'),
(6034, 6, 'AM06034', 'Դեբետ', 'Debet'),
(6035, 6, 'AM06035', 'Դսեղ', 'Dsegh'),
(6036, 6, 'AM06036', 'Եղեգնուտ', 'Yeghegnut'),
(6040, 6, 'AM06040', 'Լեռնանցք', 'Lernantsk'),
(6041, 6, 'AM06041', 'Լեռնապատ', 'Lernapat'),
(6042, 6, 'AM06042', 'Լեռնավան', 'Lernavan'),
(6043, 6, 'AM06043', 'Լերմոնտովո', 'Lermontovo'),
(6044, 6, 'AM06044', 'Լոռի Բերդ', 'Lori Berd'),
(6046, 6, 'AM06046', 'Լուսաղբյուր', 'Lusaghbyur'),
(6047, 6, 'AM06047', 'Խնկոյան', 'Khnkoyan'),
(6049, 6, 'AM06049', 'Ծաղկաբեր', 'Tsaghkaber'),
(6052, 6, 'AM06052', 'Կաթնաջուր', 'Katnajur'),
(6059, 6, 'AM06059', 'Հալավար', 'Halavar'),
(6061, 6, 'AM06061', 'Հարթագյուղ', 'Hartagyugh'),
(6065, 6, 'AM06065', 'Ձորագետ', 'Dzoraget'),
(6066, 6, 'AM06066', 'Ձորագյուղ', 'Dzoragyugh'),
(6068, 6, 'AM06068', 'Ղուրսալ', 'Ghursal'),
(6070, 6, 'AM06070', 'Մարգահովիտ', 'Margahovit'),
(6074, 6, 'AM06074', 'Մեծավան', 'Metsavan'),
(6075, 6, 'AM06075', 'Մեծ Պարնի', 'Mets Parni'),
(6083, 6, 'AM06083', 'Նոր Խաչակապ', 'Nor Khachakap'),
(6084, 6, 'AM06084', 'Շահումյան', 'Shahumyan'),
(6086, 6, 'AM06086', 'Շենավան', 'Shenavan'),
(6087, 6, 'AM06087', 'Շիրակամուտ', 'Shirakamut'),
(6088, 6, 'AM06088', 'Շնող', 'Shnogh'),
(6089, 6, 'AM06089', 'Չկալով', 'Chkalov'),
(6095, 6, 'AM06095', 'Ջրաշեն', 'Jrashen'),
(6096, 6, 'AM06096', 'Սարալանջ', 'Saralanj'),
(6097, 6, 'AM06097', 'Սարահարթ', 'Sarahart'),
(6098, 6, 'AM06098', 'Սարաﬔջ', 'Saramej'),
(6100, 6, 'AM06100', 'Սարչապետ', 'Sarchapet'),
(6102, 6, 'AM06102', 'Վահագնաձոր', 'Vahagnadzor'),
(6103, 6, 'AM06103', 'Վահագնի', 'Vahagni'),
(6107, 6, 'AM06107', 'Փամբակ', 'Pambak'),
(6108, 6, 'AM06108', 'Քարաբերդ', 'Karaberd'),
(6109, 6, 'AM06109', 'Քարաձոր', 'Karadzor'),
(6112, 6, 'AM06112', 'Օձուն', 'Odzun'),
(6113, 6, 'AM06113', 'Ֆիոլետովո', 'Fioletovo'),
(7001, 7, 'AM07001', 'Հրազդան', 'Hrazdan'),
(7002, 7, 'AM07002', 'Աբովյան', 'Abovyan'),
(7003, 7, 'AM07003', 'Բյուրեղավան', 'Byureghavan'),
(7004, 7, 'AM07004', 'Եղվարդ', 'Yeghvard'),
(7005, 7, 'AM07005', 'Ծաղկաձոր', 'Tsakhkadzor'),
(7006, 7, 'AM07006', 'Նոր Հաճն', 'Nor Hachn'),
(7007, 7, 'AM07007', 'Չարենցավան', 'Charentsavan'),
(7009, 7, 'AM07009', 'Ակունք', 'Akunk'),
(7011, 7, 'AM07011', 'Առինջ', 'Arinj'),
(7013, 7, 'AM07013', 'Արամուս', 'Aramus'),
(7014, 7, 'AM07014', 'Արգել', 'Argel'),
(7016, 7, 'AM07016', 'Արզնի', 'Arzni'),
(7018, 7, 'AM07018', 'Բալահովիտ', 'Balahovit'),
(7021, 7, 'AM07021', 'Գառնի', 'Garni'),
(7022, 7, 'AM07022', 'Գեղադիր', 'Geghadir'),
(7023, 7, 'AM07023', 'Գեղաշեն', 'Geghashen'),
(7024, 7, 'AM07024', 'Գեղարդ', 'Geghard'),
(7025, 7, 'AM07025', 'Գետաﬔջ', 'Getamej'),
(7026, 7, 'AM07026', 'Գողթ', 'Goght'),
(7032, 7, 'AM07032', 'Թեղենիք', 'Teghenik'),
(7033, 7, 'AM07033', 'Լեռնանիստ', 'Lernanist'),
(7034, 7, 'AM07034', 'Կաթնաղբյուր', 'Katnaghbyur'),
(7035, 7, 'AM07035', 'Կամարիս', 'Kamaris'),
(7041, 7, 'AM07041', 'Հացավան', 'Hatsavan'),
(7043, 7, 'AM07043', 'Մայակովսկի', 'Mayakovsky'),
(7045, 7, 'AM07045', 'Մեղրաձոր', 'Meghradzor'),
(7046, 7, 'AM07046', 'Մրգաշեն', 'Mrgashen'),
(7047, 7, 'AM07047', 'Նոր Արտաﬔտ', 'Nor Artamet'),
(7048, 7, 'AM07048', 'Նոր Գեղի', 'Nor Geghi'),
(7050, 7, 'AM07050', 'Նոր Երզնկա', 'Nor Yerznka'),
(7052, 7, 'AM07052', 'Ողջաբերդ', 'Voghjaberd'),
(7053, 7, 'AM07053', 'Պռոշյան', 'Proshyan'),
(7054, 7, 'AM07054', 'Պտղնի', 'Ptghni'),
(7056, 7, 'AM07056', 'Ջրառատ', 'Jrarat'),
(7057, 7, 'AM07057', 'Ջրվեժ', 'Jrvezh'),
(7058, 7, 'AM07058', 'Գետարգել', 'Getargel'),
(7060, 7, 'AM07060', 'Սոլակ', 'Solak'),
(7062, 7, 'AM07062', 'Վերին Պտղնի', 'Verin Ptghni'),
(7063, 7, 'AM07063', 'Քաղսի', 'Kaghsi'),
(7064, 7, 'AM07064', 'Քանաքեռավան', 'Kanakeravan'),
(7065, 7, 'AM07065', 'Քասախ', 'Kasakh'),
(7066, 7, 'AM07066', 'Քարաշամբ', 'Karashamb'),
(8001, 8, 'AM08001', 'Գյումրի', 'Gyumri'),
(8002, 8, 'AM08002', 'Արթիկ', 'Artik'),
(8003, 8, 'AM08003', 'Անի', 'Ani'),
(8004, 8, 'AM08004', 'Ազատան', 'Azatan'),
(8006, 8, 'AM08006', 'Ախուրիկ', 'Akhurik'),
(8007, 8, 'AM08007', 'Ախուրյան', 'Akhuryan'),
(8010, 8, 'AM08010', 'Ամասիա', 'Amasia'),
(8014, 8, 'AM08014', 'Անուշավան', 'Anushavan'),
(8015, 8, 'AM08015', 'Աշոցք', 'Ashotsk'),
(8016, 8, 'AM08016', 'Առափի', 'Arapi'),
(8021, 8, 'AM08021', 'Արևշատ', 'Arevshat'),
(8023, 8, 'AM08023', 'Բայանդուր', 'Bayandur'),
(8027, 8, 'AM08027', 'Բենիաﬕն', 'Beniamin'),
(8028, 8, 'AM08028', 'Արփի', 'Arpi'),
(8030, 8, 'AM08030', 'Գեղանիստ', 'Geghanist'),
(8031, 8, 'AM08031', 'Գետափ', 'Getap'),
(8032, 8, 'AM08032', 'Գետք', 'Getk'),
(8037, 8, 'AM08037', 'Երազգավորս', 'Yerazgavors'),
(8046, 8, 'AM08046', 'Լեռնակերտ', 'Lernakert'),
(8048, 8, 'AM08048', 'Լուսակերտ', 'Lusakert'),
(8060, 8, 'AM08060', 'Հայկասար', 'Haykasar'),
(8061, 8, 'AM08061', 'Հայկավան', 'Haykavan'),
(8062, 8, 'AM08062', 'Հայրենյաց', 'Hayrenyats'),
(8063, 8, 'AM08063', 'Հառիճ', 'Harich'),
(8067, 8, 'AM08067', 'Հոռոմ', 'Horom'),
(8069, 8, 'AM08069', 'Հովտաշեն', 'Hovtashen'),
(8076, 8, 'AM08076', 'Ղարիբջանյան', 'Gharibjanyan'),
(8078, 8, 'AM08078', 'Մարմաշեն', 'Marmashen'),
(8079, 8, 'AM08079', 'Մեծ Մանթաշ', 'Mets Mantash'),
(8083, 8, 'AM08083', 'Մեղրաշեն', 'Meghrashen'),
(8086, 8, 'AM08086', 'Նահապետավան', 'Nahapetavan'),
(8087, 8, 'AM08087', 'Նոր կյանք', 'Nor Kyank'),
(8092, 8, 'AM08092', 'Ոսկեհասկ', 'Voskehask'),
(8093, 8, 'AM08093', 'Պեմզաշեն', 'Pemzashen'),
(8102, 8, 'AM08102', 'Սարալանջ', 'Saralanj'),
(8104, 8, 'AM08104', 'Սարապատ', 'Sarapat'),
(8105, 8, 'AM08105', 'Սարատակ', 'Saratak'),
(8107, 8, 'AM08107', 'Սպանդարյան', 'Spandaryan'),
(8110, 8, 'AM08110', 'Վարդաքար', 'Vardakar'),
(8111, 8, 'AM08111', 'Տուֆաշեն', 'Tufashen'),
(8113, 8, 'AM08113', 'Փանիկ', 'Panik'),
(8115, 8, 'AM08115', 'Փոքր Մանթաշ', 'Pokr Mantash'),
(9001, 9, 'AM09001', 'Կապան', 'Kapan'),
(9003, 9, 'AM09003', 'Գորիս', 'Goris'),
(9005, 9, 'AM09005', 'Մեղրի', 'Meghri'),
(9006, 9, 'AM09006', 'Սիսիան', 'Sisian'),
(9007, 9, 'AM09007', 'Քաջարան', 'Kajaran'),
(9028, 9, 'AM09028', 'Գորայք', 'Gorayk'),
(9097, 9, 'AM09097', 'Տաթև', 'Tatev'),
(9101, 9, 'AM09101', 'Տեղ', 'Tegh'),
(10001, 10, 'AM10001', 'Եղեգնաձոր', 'Yeghegnadzor'),
(10002, 10, 'AM10002', 'Ջերմուկ', 'Jermuk'),
(10003, 10, 'AM10003', 'Վայք', 'Vayk'),
(10008, 10, 'AM10008', 'Արենի', 'Areni'),
(10015, 10, 'AM10015', 'Գլաձոր', 'Gladzor'),
(10022, 10, 'AM10022', 'Զառիթափ', 'Zaritap'),
(10032, 10, 'AM10032', 'Մալիշկա', 'Malishka'),
(10035, 10, 'AM10035', 'Եղեգիս', 'Yeghegis'),
(11001, 11, 'AM11001', 'Իջևան', 'Ijevan'),
(11002, 11, 'AM11002', 'Բերդ', 'Berd'),
(11003, 11, 'AM11003', 'Դիլիջան', 'Dilijan'),
(11004, 11, 'AM11004', 'Նոյեմբերյան', 'Noyemberyan'),
(11005, 11, 'AM11005', 'Ազատամուտ', 'Azatamut'),
(11006, 11, 'AM11006', 'Ակնաղբյուր', 'Aknaghbyur'),
(11008, 11, 'AM11008', 'Աճարկուտ', 'Acharkut'),
(11009, 11, 'AM11009', 'Այգեհովիտ', 'Aygehovit'),
(11013, 11, 'AM11013', 'Աչաջուր', 'Achajur'),
(11020, 11, 'AM11020', 'Բերքաբեր', 'Berkaber'),
(11021, 11, 'AM11021', 'Գանձաքար', 'Gandzakar'),
(11022, 11, 'AM11022', 'Գետահովիտ', 'Getahovit'),
(11026, 11, 'AM11026', 'Դիտավան', 'Ditavan'),
(11028, 11, 'AM11028', 'Ենոքավան', 'Yenokavan'),
(11033, 11, 'AM11033', 'Լուսահովիտ', 'Lusahovit'),
(11034, 11, 'AM11034', 'Լուսաձոր', 'Lusadzor'),
(11035, 11, 'AM11035', 'Խաշթառակ', 'Khashtarak'),
(11037, 11, 'AM11037', 'Ծաղկավան', 'Tsaghkavan'),
(11039, 11, 'AM11039', 'Կիրանց', 'Kirants'),
(11041, 11, 'AM11041', 'Կողբ', 'Koghb'),
(11057, 11, 'AM11057', 'Սարիգյուղ', 'Sarigyugh'),
(11058, 11, 'AM11058', 'Սևքար', 'Sevkar'),
(11059, 11, 'AM11059', 'Վազաշեն', 'Vazashen'),
(11060, 11, 'AM11060', 'Այրում', 'Ayrum');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_calendar`
--

CREATE TABLE `tb_calendar` (
  `interview_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `inter_comment` text NOT NULL,
  `inter_date_from` datetime DEFAULT NULL,
  `inter_date_to` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_cards`
--

CREATE TABLE `tb_cards` (
  `card_id` int(11) NOT NULL,
  `serial` varchar(2) NOT NULL,
  `card_number` int(11) NOT NULL,
  `personal_id` int(11) NOT NULL,
  `issued` date NOT NULL,
  `valid` date NOT NULL,
  `bar` text NOT NULL,
  `printed` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `actual_card` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_case`
--

CREATE TABLE `tb_case` (
  `case_id` int(10) NOT NULL,
  `application_date` date NOT NULL,
  `input_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reg_by` int(11) NOT NULL,
  `officer` int(11) DEFAULT NULL,
  `preferred_lawyer` int(1) NOT NULL DEFAULT 0,
  `reason_1` text DEFAULT NULL,
  `reason_2` text DEFAULT NULL,
  `sequence_1` text DEFAULT NULL,
  `sequence_2` text DEFAULT NULL,
  `unaccompanied_child` int(11) NOT NULL DEFAULT 0,
  `separated_child` int(11) NOT NULL DEFAULT 0,
  `single_parent` int(11) NOT NULL DEFAULT 0,
  `prefered_language` varchar(25) DEFAULT NULL,
  `RA_marz` int(11) DEFAULT NULL,
  `RA_community` int(11) DEFAULT NULL,
  `RA_settlement` int(11) DEFAULT NULL,
  `RA_street` varchar(20) DEFAULT NULL,
  `RA_building` varchar(20) DEFAULT NULL,
  `RA_apartment` varchar(20) DEFAULT NULL,
  `contact_tel` varchar(50) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `case_status` int(10) DEFAULT NULL,
  `mul_num` varchar(25) DEFAULT NULL,
  `mul_date` date DEFAULT NULL,
  `MS_lawyer` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_case_status`
--

CREATE TABLE `tb_case_status` (
  `case_status_id` int(11) NOT NULL,
  `case_status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_case_status`
--

INSERT INTO `tb_case_status` (`case_status_id`, `case_status`) VALUES
(1, 'Ընթացիկ'),
(2, 'Ընթացիկ դատական'),
(3, 'Ավարտված'),
(4, 'Սպասում է բողոքարկման');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_checkin`
--

CREATE TABLE `tb_checkin` (
  `checkin_id` int(11) NOT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `personal_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `doss_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_coi`
--

CREATE TABLE `tb_coi` (
  `coi_id` int(11) NOT NULL,
  `from_officer` int(11) NOT NULL,
  `to_coispec` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `request_date` date NOT NULL,
  `request_deadline` date NOT NULL,
  `description` text DEFAULT NULL,
  `request_text` text NOT NULL,
  `coi_state` int(11) NOT NULL,
  `request_type` int(11) DEFAULT NULL,
  `file_name` varchar(250) DEFAULT NULL,
  `response_date` date DEFAULT NULL,
  `coi_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_country`
--

CREATE TABLE `tb_country` (
  `country_id` int(10) NOT NULL,
  `country_eng` varchar(150) NOT NULL,
  `country_arm` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tb_country`
--

INSERT INTO `tb_country` (`country_id`, `country_eng`, `country_arm`) VALUES
(1, 'Afghanistan', 'Աֆղանստան'),
(2, 'Albania', 'Ալբանիա'),
(3, 'Algeria', 'Ալժիր'),
(4, 'Andorra', 'Անդորրա'),
(5, 'Angola', 'Անգոլա'),
(6, 'Antigua and Barbuda', 'Անտիգուա և Բարբուդա'),
(7, 'Argentina', 'Արգենտինա'),
(8, 'Armenia', 'Հայաստան'),
(9, 'Australia', 'Ավստրալիա'),
(10, 'Austria', 'Ավստրիա'),
(11, 'Azerbaijan', 'Ադրբեջան'),
(12, 'The Bahamas', 'Բահամներ'),
(13, 'Bahrain', 'Բահրեյն'),
(14, 'Bangladesh', 'Բանգլադեշ'),
(15, 'Barbados', 'Բարբադոս'),
(16, 'Belarus', 'Բելառուս'),
(17, 'Belgium', 'Բելգիա'),
(18, 'Belize', 'Բելիզ'),
(19, 'Benin', 'Բենին'),
(20, 'Bhutan', 'Բութան'),
(21, 'Bolivia', 'Բոլիվիա'),
(22, 'Bosnia and Herzegovina', 'Բոսնիա եւ Հերցեգովինա'),
(23, 'Botswana', 'Բոտսվանա'),
(24, 'Brazil', 'Բրազիլիա'),
(25, 'Brunei', 'Բրունեյ'),
(26, 'Bulgaria', 'Բուլղարիա'),
(27, 'Burkina Faso', 'Բուրկինա Ֆասո'),
(28, 'Burundi', 'Բուրունդի'),
(29, 'Cabo Verde', 'Կաբո Վերդե'),
(30, 'Cambodia', 'Կամբոջա'),
(31, 'Cameroon', 'Կամերուն'),
(32, 'Canada', 'Կանադա'),
(33, 'Central African Republic', 'Կենտրոնաֆրիկյան Հանրապետություն'),
(34, 'Chad', 'Չադ'),
(35, 'Chile', 'Չիլի'),
(36, 'China', 'Չինաստան'),
(37, 'Colombia', 'Կոլումբիա'),
(38, 'Comoros', 'Կոմորներ'),
(39, 'Congo, Democratic Republic of the', 'Կոնգո, Դեմոկրատական ​​Հանրապետություն'),
(40, 'Congo, Republic of the', 'Կոնգո, Հանրապետություն'),
(41, 'Costa Rica', 'Կոստա Ռիկա'),
(42, 'Côte d’Ivoire', 'Կոտ դ՛Իվուար'),
(43, 'Croatia', 'Խորվաթիա'),
(44, 'Cuba', 'Կուբա'),
(45, 'Cyprus', 'Կիպրոս'),
(46, 'Czech Republic', 'Չեխիայի Հանրապետություն'),
(47, 'Denmark', 'Դանիա'),
(48, 'Djibouti', 'Ջիբուտի'),
(49, 'Dominica', 'Դոմինիկա'),
(50, 'Dominican Republic', 'Դոմինիկյան Հանրապետություն'),
(51, 'East Timor (Timor-Leste)', 'Արևելյան Թիմոր (Թիմոր-Լեստե)'),
(52, 'Ecuador', 'Էկվադոր'),
(53, 'Egypt', 'Եգիպտոս'),
(54, 'El Salvador', 'Սալվադոր'),
(55, 'Equatorial Guinea', 'Հասարակածային Գվինեա'),
(56, 'Eritrea', 'Էրիթրեա'),
(57, 'Estonia', 'Էստոնիա'),
(58, 'Eswatini', 'Էսվատինի'),
(59, 'Ethiopia', 'Եթովպիա'),
(60, 'Fiji', 'Ֆիջի'),
(61, 'Finland', 'Ֆինլանդիա'),
(62, 'France', 'Ֆրանսիա'),
(63, 'Gabon', 'Գաբոն'),
(64, 'The Gambia', 'Գամբիան'),
(65, 'Georgia', 'Վրաստան'),
(66, 'Germany', 'Գերմանիա'),
(67, 'Ghana', 'Գանա'),
(68, 'Greece', 'Հունաստան'),
(69, 'Grenada', 'Գրենադա'),
(70, 'Guatemala', 'Գվատեմալա'),
(71, 'Guinea', 'Գվինեա'),
(72, 'Guinea-Bissau', 'Գվինեա-Բիսաու'),
(73, 'Guyana', 'Գայանա'),
(74, 'Haiti', 'Հաիթի'),
(75, 'Honduras', 'Հոնդուրաս'),
(76, 'Hungary', 'Հունգարիա'),
(77, 'Iceland', 'Իսլանդիա'),
(78, 'India', 'Հնդկաստան'),
(79, 'Indonesia', 'Ինդոնեզիա'),
(80, 'Iran', 'Իրան'),
(81, 'Iraq', 'Իրաք'),
(82, 'Ireland', 'Իռլանդիա'),
(83, 'Israel', 'Իսրայել'),
(84, 'Italy', 'Իտալիա'),
(85, 'Jamaica', 'Ճամայկա'),
(86, 'Japan', 'Ապոնիա'),
(87, 'Jordan', 'Հորդանան'),
(88, 'Kazakhstan', 'Ղազախստան'),
(89, 'Kenya', 'Քենիա'),
(90, 'Kiribati', 'Կիրիբատի'),
(91, 'Korea, North', 'Կորեա, Հյուսիսային'),
(92, 'Korea, South', 'Կորեա, Հարավ'),
(93, 'Kosovo', 'Կոսովո'),
(94, 'Kuwait', 'Քուվեյթ'),
(95, 'Kyrgyzstan', 'Ղրղզստան'),
(96, 'Laos', 'Լաոս'),
(97, 'Latvia', 'Լատվիա'),
(98, 'Lebanon', 'Լիբանան'),
(99, 'Lesotho', 'Լեսոտո'),
(100, 'Liberia', 'Լիբերիա'),
(101, 'Libya', 'Լիբիա'),
(102, 'Liechtenstein', 'Լիխտենշտեյն'),
(103, 'Lithuania', 'Լիտվա'),
(104, 'Luxembourg', 'Լյուքսեմբուրգ'),
(105, 'Madagascar', 'Մադագասկար'),
(106, 'Malawi', 'Մալավի'),
(107, 'Malaysia', 'Մալայզիա'),
(108, 'Maldives', 'Մալդիվներ'),
(109, 'Mali', 'Մալի'),
(110, 'Malta', 'Մալթա'),
(111, 'Marshall Islands', 'Մարշալի կղզիներ'),
(112, 'Mauritania', 'Մավրիտանիա'),
(113, 'Mauritius', 'Մավրիկիոս'),
(114, 'Mexico', 'Մեքսիկա'),
(115, 'Micronesia, Federated States of', 'Միկրոնեզիա, Դաշնային Նահանգներ'),
(116, 'Moldova', 'Մոլդովա'),
(117, 'Monaco', 'Մոնակո'),
(118, 'Mongolia', 'Մոնղոլիա'),
(119, 'Montenegro', 'Չեռնոգորիա'),
(120, 'Morocco', 'Մարոկկո'),
(121, 'Mozambique', 'Մոզամբիկ'),
(122, 'Myanmar (Burma)', 'Մյանմար (Բիրմա)'),
(123, 'Namibia', 'Նամիբիա'),
(124, 'Nauru', 'Նաուրու'),
(125, 'Nepal', 'Նեպալ'),
(126, 'Netherlands', 'Նիդեռլանդներ'),
(127, 'New Zealand', 'Նոր Զելանդիա'),
(128, 'Nicaragua', 'Նիկարագուա'),
(129, 'Niger', 'Նիգեր'),
(130, 'Nigeria', 'Նիգերիա'),
(131, 'North Macedonia', 'Հյուսիսային Մակեդոնիա'),
(132, 'Norway', 'Նորվեգիա'),
(133, 'Oman', 'Օման'),
(134, 'Pakistan', 'Պակիստան'),
(135, 'Palau', 'Պալաու'),
(136, 'Panama', 'Պանամա'),
(137, 'Papua New Guinea', 'Պապուա Նոր Գվինեա'),
(138, 'Paraguay', 'Պարագվայ'),
(139, 'Peru', 'Պերու'),
(140, 'Philippines', 'Ֆիլիպիններ'),
(141, 'Poland', 'Լեհաստան'),
(142, 'Portugal', 'Պորտուգալիա'),
(143, 'Qatar', 'Կատար'),
(144, 'Romania', 'Ռումինիա'),
(145, 'Russia', 'Ռուսաստան'),
(146, 'Rwanda', 'Ռուանդա'),
(147, 'Saint Kitts and Nevis', 'Սենթ Քիթս և Նևիս'),
(148, 'Saint Lucia', 'Սենթ Լուչիա'),
(149, 'Saint Vincent and the Grenadines', 'Սենթ Վինսենթ և Գրենադներ'),
(150, 'Samoa', 'Սամոա'),
(151, 'San Marino', 'Սան Մարինո'),
(152, 'Sao Tome and Principe', 'Սան Տոմե և Պրինսիպի'),
(153, 'Saudi Arabia', 'Սաուդյան Արաբիա'),
(154, 'Senegal', 'Սենեգալ'),
(155, 'Serbia', 'Սերբիա'),
(156, 'Seychelles', 'Սեյշելյան կղզիներ'),
(157, 'Sierra Leone', 'Սիերա Լեոնե'),
(158, 'Singapore', 'Սինգապուր'),
(159, 'Slovakia', 'Սլովակիա'),
(160, 'Slovenia', 'Սլովենիա'),
(161, 'Solomon Islands', 'Սողոմոնյան կղզիներ'),
(162, 'Somalia', 'Սոմալի'),
(163, 'South Africa', 'Հարավային Աֆրիկա'),
(164, 'Spain', 'Իսպանիա'),
(165, 'Sri Lanka', 'Շրի Լանկա'),
(166, 'Sudan', 'Սուդանը'),
(167, 'Sudan, South', 'Սուդան, հարավ'),
(168, 'Suriname', 'Սուրինամ'),
(169, 'Sweden', 'Շվեդիա'),
(170, 'Switzerland', 'Շվեյցարիա'),
(171, 'Syria', 'Սիրիա'),
(172, 'Taiwan', 'Թայվան'),
(173, 'Tajikistan', 'Տաջիկստան'),
(174, 'Tanzania', 'Տանզանիա'),
(175, 'Thailand', 'Թաիլանդ'),
(176, 'Togo', 'Գնալ'),
(177, 'Tonga', 'Թոնգա'),
(178, 'Trinidad and Tobago', 'Տրինիդադ և Տոբագո'),
(179, 'Tunisia', 'Թունիս'),
(180, 'Turkey', 'Թուրքիա'),
(181, 'Turkmenistan', 'Թուրքմենստան'),
(182, 'Tuvalu', 'Տուվալու'),
(183, 'Uganda', 'Ուգանդա'),
(184, 'Ukraine', 'Ուկրաինա'),
(185, 'United Arab Emirates', 'Արաբական Միացյալ Էմիրություններ'),
(186, 'United Kingdom', 'Միացյալ թագավորություն'),
(187, 'United States', 'Միացյալ Նահանգներ'),
(188, 'Uruguay', 'Ուրուգվայ'),
(189, 'Uzbekistan', 'Ուզբեկստան'),
(190, 'Vanuatu', 'Վանուատու'),
(191, 'Vatican City', 'Քաղաք Վատիկան'),
(192, 'Venezuela', 'Վենեսուելա'),
(193, 'Vietnam', 'Վիետնամ'),
(194, 'Yemen', 'Եմեն'),
(195, 'Zambia', 'Զամբիա'),
(196, 'Zimbabwe', 'Զիմբաբվե');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_courts`
--

CREATE TABLE `tb_courts` (
  `court_id` int(11) NOT NULL,
  `court_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_courts`
--

INSERT INTO `tb_courts` (`court_id`, `court_title`) VALUES
(1, 'Առաջին ատյանի ընդհանուր իրավասության'),
(2, 'Վարչական վերաքննիչ դատարան'),
(3, 'Վճռաբեկ դատարան');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_court_decision_types`
--

CREATE TABLE `tb_court_decision_types` (
  `court_decision_type_id` int(11) NOT NULL,
  `court_type` int(11) NOT NULL,
  `court_decision` varchar(100) NOT NULL,
  `decision_status` int(11) NOT NULL,
  `decision_status_comment` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_court_decision_types`
--

INSERT INTO `tb_court_decision_types` (`court_decision_type_id`, `court_type`, `court_decision`, `decision_status`, `decision_status_comment`) VALUES
(1, 1, 'բավարարել', 0, 'pending'),
(2, 1, 'մերժել', 0, 'pending'),
(3, 2, 'բեկանել 1-ին ատյանի որոշումը (վերադարձնել 1-ին ատյան)', 0, 'pending'),
(4, 2, 'մերժել վերաքննիչ բողոքը', 0, 'pending'),
(5, 3, 'բեկանել վերաքննիչ դատարանի որոշումը ', 0, 'pending');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_deadline`
--

CREATE TABLE `tb_deadline` (
  `deadline_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `deadline_type` int(11) NOT NULL,
  `deadline_comment` varchar(250) DEFAULT NULL,
  `change_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `deadline` date DEFAULT NULL,
  `actual_dead` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_deadline_types`
--

CREATE TABLE `tb_deadline_types` (
  `deadline_type_id` int(11) NOT NULL,
  `deadline_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tb_deadline_types`
--

INSERT INTO `tb_deadline_types` (`deadline_type_id`, `deadline_type`) VALUES
(1, 'առաջնային վերջնաժամկետ (3 ամիս)'),
(2, 'երկարաձգված վերջնաժամկետ (+ 3 ամիս)'),
(3, 'երկարաձգված վերջնաժամկետ (+ 1 ամիս)'),
(4, 'երկարաձգված վերջնաժամկետ (+ 10 օր)'),
(5, 'կասեցված գործ (3 ամիս)'),
(6, 'բողոքարկման շրջան (2 ամիս)'),
(7, 'վերաբացված վերջնաժամկետ'),
(15, 'ավարտված');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_decisions`
--

CREATE TABLE `tb_decisions` (
  `decision_id` int(10) NOT NULL,
  `case_id` int(10) NOT NULL,
  `decision_file` varchar(250) NOT NULL,
  `decision_type` int(11) NOT NULL,
  `decison_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `decision_status` int(11) NOT NULL,
  `actual` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_decision_status`
--

CREATE TABLE `tb_decision_status` (
  `decision_status_id` int(10) NOT NULL,
  `decision_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tb_decision_status`
--

INSERT INTO `tb_decision_status` (`decision_status_id`, `decision_status`) VALUES
(1, 'Սպասում է բաժնի պետի հավանությանը'),
(2, 'Վերադարձված (Բացասական վիզա)'),
(3, 'Սպասում է ՄԾ պետի հավանությանը'),
(4, 'ՄԾ պետի բացասական վիզա'),
(5, 'Հաստատված'),
(6, 'ՀԵՏ ԿԱՆՉՎԱԾ');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_decision_types`
--

CREATE TABLE `tb_decision_types` (
  `decision_type_id` int(10) NOT NULL,
  `decision_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tb_decision_types`
--

INSERT INTO `tb_decision_types` (`decision_type_id`, `decision_type`) VALUES
(1, 'ժամկետի երկարաձգում'),
(2, 'Կասեցում (Չհամագործակցության հիմքով)'),
(3, 'Բավարարում'),
(4, 'Մերժում'),
(5, 'Կարճում'),
(9, 'Վերաբացում');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_doss`
--

CREATE TABLE `tb_doss` (
  `doss_id` int(11) NOT NULL,
  `room_num` int(11) NOT NULL,
  `doss` varchar(10) NOT NULL,
  `doss_status` int(11) NOT NULL,
  `doss_type` varchar(1) NOT NULL,
  `doss_sex` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_doss`
--

INSERT INTO `tb_doss` (`doss_id`, `room_num`, `doss`, `doss_status`, `doss_type`, `doss_sex`) VALUES
(1, 103, '103_A', 0, 'A', 0),
(2, 104, '104_A', 0, 'A', 0),
(3, 105, '105_A', 0, 'A', 0),
(4, 202, '202_A', 0, 'A', 0),
(5, 202, '202_B', 0, 'B', 0),
(6, 202, '202_C', 0, 'C', 0),
(7, 202, '202_D', 0, 'D', 0),
(8, 203, '203_A', 0, 'A', 0),
(9, 203, '203_B', 0, 'B', 0),
(10, 204, '204_A', 0, 'A', 0),
(11, 204, '204_B', 0, 'B', 0),
(12, 205, '205_A', 0, 'A', 0),
(13, 205, '205_B', 0, 'B', 0),
(14, 206, '206_A', 0, 'A', 0),
(15, 206, '206_B', 0, 'B', 0),
(16, 207, '207_A', 0, 'A', 0),
(17, 207, '207_B', 0, 'B', 0),
(18, 207, '207_C', 0, 'C', 0),
(19, 207, '207_D', 0, 'D', 0),
(20, 208, '208_A', 0, 'A', 0),
(21, 208, '208_B', 0, 'B', 0),
(22, 209, '209_A', 0, 'A', 0),
(23, 209, '209_B', 0, 'B', 0),
(24, 210, '210_A', 0, 'A', 0),
(25, 210, '210_B', 0, 'B', 0),
(26, 211, '211_A', 0, 'A', 0),
(27, 211, '211_B', 0, 'B', 0),
(28, 212, '212_A', 0, 'A', 0),
(29, 212, '212_B', 0, 'B', 0),
(30, 213, '213_A', 0, 'A', 0),
(31, 213, '213_B', 0, 'B', 0),
(32, 214, '214_A', 0, 'A', 0),
(33, 214, '214_B', 0, 'B', 0),
(34, 215, '215_A', 0, 'A', 0),
(35, 215, '215_B', 0, 'B', 0),
(36, 216, '216_A', 0, 'A', 0),
(37, 216, '216_B', 0, 'B', 0),
(38, 217, '217_A', 0, 'A', 0),
(39, 217, '217_B', 0, 'B', 0),
(40, 218, '218_A', 0, 'A', 0),
(41, 218, '218_B', 0, 'B', 0),
(42, 219, '219_A', 0, 'A', 0),
(43, 219, '219_B', 0, 'B', 0),
(44, 220, '220_A', 0, 'A', 0),
(45, 220, '220_B', 0, 'B', 0),
(46, 221, '221_A', 0, 'A', 0),
(47, 221, '221_B', 0, 'B', 0),
(48, 222, '222_A', 0, 'A', 0),
(49, 222, '222_B', 0, 'B', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tb_draft`
--

CREATE TABLE `tb_draft` (
  `draft_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `draft_file` varchar(250) NOT NULL,
  `autor` int(11) NOT NULL,
  `uploaded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deadline` date DEFAULT NULL,
  `receiver` int(11) NOT NULL,
  `draft_comment` text NOT NULL,
  `actual` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_drooms`
--

CREATE TABLE `tb_drooms` (
  `room_id` int(11) NOT NULL,
  `room_num` int(11) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `room_sex` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_drooms`
--

INSERT INTO `tb_drooms` (`room_id`, `room_num`, `floor`, `type`, `capacity`, `room_sex`) VALUES
(1, 103, 1, 'Single', 1, 0),
(2, 104, 1, 'Single', 1, 2),
(3, 105, 1, 'Single', 1, 0),
(4, 202, 2, 'Family', 4, 0),
(5, 203, 2, 'Double', 2, 0),
(6, 204, 2, 'Double', 2, 0),
(7, 205, 2, 'Double', 2, 0),
(8, 206, 2, 'Double', 2, 0),
(9, 207, 2, 'Family', 4, 0),
(10, 208, 2, 'Double', 2, 0),
(11, 209, 2, 'Double', 2, 0),
(12, 210, 2, 'Double', 2, 0),
(13, 211, 2, 'Double', 2, 0),
(14, 212, 2, 'Double', 2, 0),
(15, 213, 2, 'Double', 2, 0),
(16, 214, 2, 'Double', 2, 0),
(17, 215, 2, 'Double', 2, 0),
(18, 216, 2, 'Double', 2, 0),
(19, 217, 2, 'Double', 2, 0),
(20, 218, 2, 'Double', 2, 0),
(21, 219, 2, 'Double', 2, 0),
(22, 220, 2, 'Double', 2, 0),
(23, 221, 2, 'Double', 2, 0),
(24, 222, 2, 'Double', 2, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tb_education`
--

CREATE TABLE `tb_education` (
  `edu_id` int(11) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `institution` varchar(250) DEFAULT NULL,
  `edu_lvl` int(11) NOT NULL,
  `start_year` varchar(10) DEFAULT NULL,
  `end_year` varchar(10) DEFAULT NULL,
  `personal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_edu_lvl`
--

CREATE TABLE `tb_edu_lvl` (
  `lvl_id` int(11) NOT NULL,
  `edu_lvl` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tb_edu_lvl`
--

INSERT INTO `tb_edu_lvl` (`lvl_id`, `edu_lvl`) VALUES
(1, 'Նախադպրոցական'),
(2, 'Տարրական դպրոց'),
(3, 'Միջին դպրոց'),
(4, 'Ավագ դպրոց'),
(5, 'ԲՈՒՀ (բակալավր)'),
(6, 'ԲՈՒՀ (մագիստրատուրա)'),
(7, 'Հետբուհական'),
(8, 'Գիտությունների թեկնածու'),
(9, 'Գիտությունների դոկտոր'),
(10, 'Մասնագիտական արհեստագործական');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_employment`
--

CREATE TABLE `tb_employment` (
  `employment_id` int(11) NOT NULL,
  `start_date` varchar(10) DEFAULT NULL,
  `end_date` varchar(10) DEFAULT NULL,
  `occupation` varchar(50) DEFAULT NULL,
  `organization` varchar(50) DEFAULT NULL,
  `personal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_etnics`
--

CREATE TABLE `tb_etnics` (
  `etnic_id` int(11) NOT NULL,
  `etnic_arm` varchar(100) NOT NULL,
  `etnic_eng` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tb_etnics`
--

INSERT INTO `tb_etnics` (`etnic_id`, `etnic_arm`, `etnic_eng`) VALUES
(1, 'Abazins', 'Աբազիններ'),
(2, 'Abkhazians', 'Աբխազները'),
(3, 'Acholi', 'Ախոլի'),
(4, 'Afemai', 'Աֆեմայ'),
(5, 'Afar', 'Աֆար'),
(6, 'Afrikaners', 'Աֆրիկաներ'),
(7, 'Agaw', 'Ագավ'),
(8, 'Ahom', 'Ահոմ'),
(9, 'Aimaq', 'Այմաք'),
(10, 'Aja', 'Աջա'),
(11, 'Adjoukrou', 'Աջուկրու'),
(12, 'Akan', 'Ական'),
(13, 'Akha', 'Ախա'),
(14, 'Albanians', 'Ալբանացիներ'),
(15, 'Alur', 'Ալուր'),
(16, 'Ambonese', 'Ամբոնեզ'),
(17, 'Ambundu', 'Ամբունդու'),
(18, 'Amhara', 'Ամհարա'),
(19, 'Amis', 'Ամիս'),
(20, 'Anaang', 'Անաանգ'),
(21, 'Anuak', 'Անուակ'),
(22, 'Apache', 'Ապաչի'),
(23, 'Arabs', 'Արաբներ'),
(24, 'Argobba', 'Արգոբբա'),
(25, 'Armenians', 'Հայ'),
(26, 'Aromanians', 'Արոմացիներ'),
(27, 'Assyrians', 'Ասորիներ'),
(28, 'Atayal', 'Աթայալ'),
(29, 'Atoni', 'Ատոնի'),
(30, 'Atyap', 'Ատյապ'),
(31, 'Austrians', 'Ավստրիացիներ'),
(32, 'Avars', 'Ավարներ'),
(33, 'Awadhis', 'Ավադիս'),
(34, 'Aymara', 'Այմարա'),
(35, 'Azerbaijanis', 'Ադրբեջանցիներ'),
(36, 'Bahnar', 'Բահնար'),
(37, 'Bai', 'Բայ'),
(38, 'Bakossi', 'Բակոսի'),
(39, 'Balanta', 'Բալանտա'),
(40, 'Balinese', 'Բալիներեն'),
(41, 'Balkars', 'Բալկարներ'),
(42, 'Balochs', 'Բալոչներ'),
(43, 'Balti', 'Բալտի'),
(44, 'Bamars', 'Բամարներ'),
(45, 'Bambara', 'Բամբարա'),
(46, 'Bamileke', 'Բամիլեկե'),
(47, 'Bamum', 'Բամում'),
(48, 'Banda', 'Բանդա'),
(49, 'Banjarese', 'Բանջարեզե'),
(50, 'Bari', 'Բարի'),
(51, 'Bariba', 'Բարիբա'),
(52, 'Bassa', 'Բասսա'),
(53, 'Bashkirs', 'Բաշկիրներ'),
(54, 'Basques', 'Բասկեր'),
(55, 'Batak', 'Բատակ'),
(56, 'Beja', 'Բեժա'),
(57, 'Belarusians', 'Բելառուսներ'),
(58, 'Bemba', 'Բեմբա'),
(59, 'Bembe', 'Բեմբե'),
(60, 'Bengalis', 'Բենգալացիներ'),
(61, 'Berbers', 'Բերբերականներ'),
(62, 'Berom', 'Բերոմ'),
(63, 'Berta', 'Բերտա'),
(64, 'Betawis', 'Բետավիս'),
(65, 'Beti', 'Բեթի'),
(66, 'Bhils', 'Բիլս'),
(67, 'Bhojpuris', 'Բհոջպուրիս'),
(68, 'Bhumij', 'Բհումիջ'),
(69, 'Bicolanos', 'Բիկոլանոս'),
(70, 'Bidayuh', 'Բիդայուհ'),
(71, 'Bilala', 'Բիլալա'),
(72, 'Bishnupriya Manipuris', 'Բիշնուպրիա Մանիպուրիս'),
(73, 'Bissa', 'Բիսսա'),
(74, 'Blaan', 'Բլան'),
(75, 'Boa', 'Բոա'),
(76, 'Bodo', 'Բոդո'),
(77, 'Bosniaks', 'Բոսնիացիներ'),
(78, 'Bouyei', 'Բույե'),
(79, 'Bozo', 'Բոզո'),
(80, 'Brahuis', 'Բրահուիս'),
(81, 'Bretons', 'Բրետոններ'),
(82, 'Bru', 'Բրու'),
(83, 'Budu', 'Բուդու'),
(84, 'Buduma', 'Բուդումա'),
(85, 'Buginese', 'Բուգիներեն'),
(86, 'Bulgarians', 'Բուլղարացիներ'),
(87, 'Burusho', 'Բուրուշո'),
(88, 'Butonese', 'Բուտոնեզե'),
(89, 'Bwa', 'Բվա'),
(90, 'Catalans', 'Կատալոնացիներ'),
(91, 'Chamorro', 'Չամորրո'),
(92, 'Chams', 'Խամեր'),
(93, 'Chechens', 'Չեչեններ'),
(94, 'Cherokee', 'Չերոկի'),
(95, 'Cheyenne', 'Չեյեն'),
(96, 'Chokwe', 'Չոքվե'),
(97, 'Chukchi', 'Չուկչի'),
(98, 'Chutiya', 'Չուտիա'),
(99, 'Chuukese', 'Չուուկեզե'),
(100, 'Chuvash', 'Չուվաշերեն'),
(101, 'Circassians', 'Չերքեզներ'),
(102, 'Chakmas', 'Չակմասներ'),
(103, 'Chewa', 'Չեվա'),
(104, 'Copts', 'Ղպտիներ'),
(105, 'Cornish', 'Կորնու'),
(106, 'Corsicans', 'Կորսիկաներ'),
(107, 'Cree', 'Կրե'),
(108, 'Croats', 'Խորվաթներ'),
(109, 'Cuyunon', 'Կույունոն'),
(110, 'Czechs', 'Չեխեր'),
(111, 'Dagaaba', 'Դագաաբա'),
(112, 'Dagombas', 'Դագոմբաս'),
(113, 'Damara', 'Դամարա'),
(114, 'Danes', 'Դանիացիներ'),
(115, 'Dargins', 'Դարգինս'),
(116, 'Dinka', 'Դինկա'),
(117, 'Dogon', 'Դոգոն'),
(118, 'Dogra', 'Դոգրա'),
(119, 'Druze', 'Դրուզ'),
(120, 'Dubla', 'Դուբլա'),
(121, 'Dutch', 'Հոլանդերեն'),
(122, 'Dyula', 'Դյուլա'),
(123, 'Ebira', 'Էբիրա'),
(124, 'Edo', 'Էդո'),
(125, 'Efik', 'Էֆիկ'),
(126, 'Ekoi', 'Էկոյ'),
(127, 'Emberá', 'Էմբերա'),
(128, 'English', 'Անգլերեն'),
(129, 'Esan', 'Էսան'),
(130, 'Estonians', 'Էստոնացիներ'),
(131, 'Evenks', 'Երեկոներ'),
(132, 'Fang', 'Ժանիք'),
(133, 'Fijians', 'Ֆիջիացիներ'),
(134, 'Finns', 'Ֆիններ'),
(135, 'Flemings', 'Ֆլամանդացիներ'),
(136, 'Fon', 'Ֆոն'),
(137, 'French', 'Ֆրանսերեն'),
(138, 'Frisians', 'Ֆրիսիացիներ'),
(139, 'Friulians', 'Ֆրիուլներ'),
(140, 'Fula', 'Ֆուլա'),
(141, 'Fur', 'Մորթուց'),
(142, 'Ga-Adangbe', 'Գա-Ադանգբե'),
(143, 'Gagauz', 'Գագաուզ'),
(144, 'Galicians', 'Գալիցիաներ'),
(145, 'Ganda', 'Գանդա'),
(146, 'Garifuna', 'Գարիֆունա'),
(147, 'Garos', 'Գարոս'),
(148, 'Gayonese', 'Գայոնեզյան'),
(149, 'Gbagyi', 'Գբագի'),
(150, 'Gbaya', 'Գբայա'),
(151, 'Gedeo', 'Գեդեո'),
(152, 'Gelao', 'Գելաո'),
(153, 'Georgians', 'Վրացիներ'),
(154, 'Germans', 'Գերմանացիներ'),
(155, 'Gilaks', 'Գիլաքս'),
(156, 'Gola', 'Գոլա'),
(157, 'Gonds', 'Գոնդեր'),
(158, 'Gorontaloans', 'Գորոնտոլաներ'),
(159, 'Greeks', 'Հույներ'),
(160, 'Guan', 'Գուան'),
(161, 'Guaraní', 'Գուարանի'),
(162, 'Gujarati', 'Գուջարաթի'),
(163, 'Gujjar / Gurjar', 'Գուջար / Գուրջար'),
(164, 'Gumuz', 'Գումուզ'),
(165, 'Gurage', 'Գուրաժե'),
(166, 'Gurma', 'Գուրմա'),
(167, 'Gurunsi', 'Գուրունսի'),
(168, 'Hadiya', 'Հադիյա'),
(169, 'Han Chinese', 'Չինացի'),
(170, 'Hani', 'Հանի'),
(171, 'Harari', 'Հարարի'),
(172, 'Hausa', 'Հաուսա'),
(173, 'Hawaiians', 'Հավայականներ'),
(174, 'Hazaras', 'Հազարներ'),
(175, 'Herero', 'Հերերո'),
(176, 'Hmong', 'Հմոնգ'),
(177, 'Huli', 'Հուլի'),
(178, 'Hungarians', 'Հունգարացիներ'),
(179, 'Hutu', 'Հուտու'),
(180, 'Iban', 'Իբան'),
(181, 'Ibanag', 'Իբանագ'),
(182, 'Ibibio', 'Իբիբիո'),
(183, 'Icelanders', 'Իսլանդացիներ'),
(184, 'Idoma', 'Իդոմա'),
(185, 'Igbo', 'Իգբո'),
(186, 'Igede', 'Իգեդե'),
(187, 'Igorot', 'Իգորոտ'),
(188, 'Ijaw', 'Իջաու'),
(189, 'Ilocano', 'Իլոկանո'),
(190, 'Ingush', 'Ինգուշ'),
(191, 'Inuit', 'Ինուիտ'),
(192, 'Iranun', 'Իրանուն'),
(193, 'Irish', 'Իռլանդական'),
(194, 'Iroquois', 'Իրոկուիզա'),
(195, 'Isan', 'Իսան'),
(196, 'Isoko', 'Իսոկո'),
(197, 'Istro-Romanians', 'Իստրո-ռումինացիներ'),
(198, 'Italians', 'Իտալացիներ'),
(199, 'Itawes', 'Կտրուկներ'),
(200, 'Japanese', 'Ճապոներեն'),
(201, 'Javanese', 'Ճավայերեն'),
(202, 'Jews', 'Հրեաներ'),
(203, 'Kadazan-Dusun', 'Կադազան-Դուսուն'),
(204, 'Kalanga', 'Կալանգա'),
(205, 'Kalenjin', 'Կալենջին'),
(206, 'Kalinago', 'Կալինագո'),
(207, 'Kamba', 'Կամբա'),
(208, 'Kanaks', 'Կանակներ'),
(209, 'Kannadigas', 'Կաննադիգաս'),
(210, 'Kanuri', 'Կանուրի'),
(211, 'Kapampangans', 'Կապամպանգաններ'),
(212, 'Kapsiki', 'Կապսիկի'),
(213, 'Karachays', 'Կարաչայներ'),
(214, 'Karakalpaks', 'Կարակալպաքս'),
(215, 'Karbi', 'Կարբի'),
(216, 'Karen', 'Կարեն'),
(217, 'Kashmiris', 'Կաշմիրցիներ'),
(218, 'Kashubians', 'Քաշուբցիներ'),
(219, 'Kazakhs', 'Ղազախներ'),
(220, 'Khas', 'Խաս'),
(221, 'Khmer', 'Քմերական'),
(222, 'Khonds', 'Խոնդս'),
(223, 'Khorasani Turks', 'Խորասանի թուրքեր'),
(224, 'Kikuyu', 'Կիկույու'),
(225, 'Kilba', 'Կիլբա'),
(226, 'Kirati', 'Կիրատին'),
(227, 'Kissi', 'Քիսսի'),
(228, 'Kofyar', 'Կոֆյար'),
(229, 'Komi', 'Կոմի'),
(230, 'Konkani', 'Կոնկանի'),
(231, 'Kongo', 'Կոնգո'),
(232, 'Konjo', 'Կոնջո'),
(233, 'Konso', 'Կոնսո'),
(234, 'Koreans', 'Կորեացիներ'),
(235, 'Kpelle', 'Կպելե'),
(236, 'Kposo', 'Կպոսո'),
(237, 'Kru', 'Կրու'),
(238, 'Kumyks', 'Կումիքս'),
(239, 'Kunama', 'Կունամա'),
(240, 'Kurds', 'Քրդեր'),
(241, 'Kurukh', 'Կուրուխ'),
(242, 'Kuteb', 'Կուտեբ'),
(243, 'Kyrgyz', 'Ղրղզերեն'),
(244, 'Laks', 'Լաքեր'),
(245, 'Lamaholot', 'Լամահոլոտ'),
(246, 'Lampungs', 'Լամպունգներ'),
(247, 'Lao', 'Լաո'),
(248, 'Latvians', 'Լատվիացիներ'),
(249, 'Laz', 'Լազ'),
(250, 'Lega', 'Լեգա'),
(251, 'Lezgins', 'Լեզգիներ'),
(252, 'Li', 'Լի'),
(253, 'Lhoba', 'Լհոբա'),
(254, 'Limba', 'Լիմբա'),
(255, 'Lisu', 'Լիսու'),
(256, 'Lithuanians', 'Լիտվացիներ'),
(257, 'Luba', 'Լուբա'),
(258, 'Luhya', 'Լուհյա'),
(259, 'Luo', 'Լուո'),
(260, 'Lurs', 'Խայծեր'),
(261, 'Luxembourgers', 'Լյուքսեմբուրգցիներ'),
(262, 'Maasai', 'Մասաի'),
(263, 'Macedonians', 'Մակեդոնացիներ'),
(264, 'Madi', 'Մադի'),
(265, 'Madurese', 'Մադուրես'),
(266, 'Mafa', 'Մաֆա'),
(267, 'Magahi', 'Մագահի'),
(268, 'Maguindanao', 'Մագուինդանաո'),
(269, 'Makassarese', 'Մակասարեսե'),
(270, 'Makonde', 'Մակոնդե'),
(271, 'Makua', 'Մակուա'),
(272, 'Malagasy', 'Մալագասերեն'),
(273, 'Malays', 'Մալայացիներ'),
(274, 'Malayali', 'Մալայալու'),
(275, 'Maldivians', 'Մալդիվացիներ'),
(276, 'Maltese', 'Մալթերեն'),
(277, 'Mambila', 'Մամբիլա'),
(278, 'Manchu', 'Մանչու'),
(279, 'Mandarese', 'Մանդարեզե'),
(280, 'Mandinka', 'Մանդինկա'),
(281, 'Manggarai', 'Մանգարայ'),
(282, 'Manjak', 'Մանջակ'),
(283, 'Manx', 'Մանսական'),
(284, 'Māori', 'Մաորի'),
(285, 'Mapuche', 'Մապուչե'),
(286, 'Maranao', 'Մարանաո'),
(287, 'Marathi', 'Մարաթի'),
(288, 'Mari', 'Մարի'),
(289, 'Masa', 'Մասա'),
(290, 'Masalit', 'Մասալիտ'),
(291, 'Maya', 'Մայա'),
(292, 'Mazandarani', 'Մազանդարանին'),
(293, 'Mazahua', 'Մազահուա'),
(294, 'Mbaka', 'Մբակա'),
(295, 'Megleno-Romanians', 'Մեգլենո-ռումինացիներ'),
(296, 'Mehri', 'Մեհրի'),
(297, 'Meitei', 'Մեյտեյ'),
(298, 'Melanau', 'Մելանաու'),
(299, 'Mende', 'Մենդե'),
(300, 'Miꞌkmaq', 'Միքմաք'),
(301, 'Mien', 'Միեն'),
(302, 'Minahasan', 'Մինասասան'),
(303, 'Minangkabau', 'Մինանգկաբաու'),
(304, 'Mising', 'Միզինգ'),
(305, 'Miskito', 'Միսկիտո'),
(306, 'Mixe', 'Խառնուրդ'),
(307, 'Mixtec', 'Խառնուրդ'),
(308, 'Mon', 'Երկ'),
(309, 'Mongo', 'Մոնգո'),
(310, 'Mongols', 'Մոնղոլներ'),
(311, 'Mongondow', 'Մանգոնդոու'),
(312, 'Montenegrins', 'Չեռնոգորցիներ'),
(313, 'Mordvins', 'Մորդվինս'),
(314, 'Mossi', 'Մոսսի'),
(315, 'Mumuye', 'Մումյե'),
(316, 'Munanese', 'Մունանացի'),
(317, 'Mundas', 'Մունդաս'),
(318, 'Murut', 'Մուրութ'),
(319, 'Muscogee', 'Մոսկվացի'),
(320, 'Musgum', 'Մուսգում'),
(321, 'Naga', 'Նագա'),
(322, 'Nagpuri', 'Նագպուրի'),
(323, 'Nahuas', 'Նահուաս'),
(324, 'Nama', 'Նամա'),
(325, 'Nauruans', 'Նաուրուացիներ'),
(326, 'Navajo', 'Նավահո'),
(327, 'Newar', 'Նյուար'),
(328, 'Ngaju', 'Նգաջու'),
(329, 'Ngalop', 'Նգալոպ'),
(330, 'Ngbandi', 'Նգբանդի'),
(331, 'Nias', 'Նիաս'),
(332, 'Nogais', 'Նոգաիս'),
(333, 'Norwegians', 'Նորվեգացիներ'),
(334, 'Nubians', 'Նուբյաններ'),
(335, 'Nuer', 'Նուեր'),
(336, 'Nuristanis', 'Նուրիստանիս'),
(337, 'Nyishi', 'Նյիշի'),
(338, 'Occitans', 'Օքսիտաններ'),
(339, 'Odia', 'Օդիա'),
(340, 'Ogoni', 'Օգոնին'),
(341, 'Ojibwe', 'Օջիբվե'),
(342, 'Oromo', 'Օրոմո'),
(343, 'Ossetians', 'Օսերը'),
(344, 'Otomi', 'Օտոմի'),
(345, 'Ovambo', 'Օվամբո'),
(346, 'Ovimbundu', 'Օվիմբունդու'),
(347, 'Pamiris', 'Պամիրիս'),
(348, 'Pangasinese', 'Պանգասիներեն'),
(349, 'Papel', 'Պապել'),
(350, 'Pare', 'Պարիր'),
(351, 'Pashayi', 'Փաշայ'),
(352, 'Pashtuns', 'Պաշթուններ'),
(353, 'Pedi', 'Պեդի'),
(354, 'Pende', 'Պենդե'),
(355, 'Persians', 'Պարսիկ'),
(356, 'Pitcairn Islanders', 'Պիտկիրնի կղզիներ'),
(357, 'Poles', 'Լեհեր'),
(358, 'Portuguese', 'Պորտուգալերեն'),
(359, 'Punjabis', 'Փունջաբիս'),
(360, 'Qashqai', 'Քաշքայ'),
(361, 'Qiang', 'Քիանգ'),
(362, 'Quechua', 'Քեչուա'),
(363, 'Rade', 'Ռադե'),
(364, 'Rajasthanis', 'Ռաջաստանիս'),
(365, 'Rajbongshi', 'Ռաջբոնգշի'),
(366, 'Rakhine', 'Ռախայն'),
(367, 'Rejangese', 'Ռեժանգեզե'),
(368, 'Rohingyas', 'Ռոհինգյասներ'),
(369, 'Roma', 'Ռոմա'),
(370, 'Romanians', 'Ռումինացիներ'),
(371, 'Russians', 'Ռուսներ'),
(372, 'Ryukyuans', 'Ռյուկյուանները'),
(373, 'Rusyns', 'Ռուսիններ'),
(374, 'Saho', 'Սահո'),
(375, 'Salar', 'Սալար'),
(376, 'Sama-Bajau', 'Սամա-Բաջաու'),
(377, 'Sambal', 'Սամբալ'),
(378, 'Sámi', 'Սամի'),
(379, 'Samoans', 'Սամոացիներ'),
(380, 'Sangirese', 'Սանգիրեսցի'),
(381, 'Santal', 'Սանտալ'),
(382, 'Sara', 'Սառա'),
(383, 'Sardinians', 'Սարդինացիներ'),
(384, 'Sasak', 'Սասակ'),
(385, 'Savu', 'Սավու'),
(386, 'Scots', 'Շոտլանդացիներ'),
(387, 'Senufo', 'Սենուֆո'),
(388, 'Serbs', 'Սերբեր'),
(389, 'Serer', 'Սերեր'),
(390, 'Shan', 'Շան'),
(391, 'Sherbro', 'Շերբրո'),
(392, 'Shilluk', 'Շիլլուկ'),
(393, 'Shona', 'Շոնա'),
(394, 'Sibe', 'Սիբե'),
(395, 'Sidama', 'Սիդամա'),
(396, 'Siddi', 'Սիդդի'),
(397, 'Sika', 'Սիկա'),
(398, 'Silesians', 'Սիլեզիացիներ'),
(399, 'Silte', 'Սիլտե'),
(400, 'Sindhis', 'Սինդհիս'),
(401, 'Sinhalese', 'Սինհալերեն'),
(402, 'Sioux', 'Սիո'),
(403, 'Slovaks', 'Սլովակ'),
(404, 'Slovenes', 'Սլովենացիներ'),
(405, 'Soga', 'Սոգա'),
(406, 'Somalis', 'Սոմալիացիներ'),
(407, 'Songhai', 'Սոնգհայ'),
(408, 'Soninke', 'Սոնինկե'),
(409, 'Sorbs', 'Սորբեր'),
(410, 'Sotho', 'Սոտո'),
(411, 'Spaniards', 'Իսպանացիներ'),
(412, 'Sui', 'Սուի'),
(413, 'Sumba', 'Սումբա'),
(414, 'Sundanese', 'Սունդաներեն'),
(415, 'Sukuma', 'Սուկումա'),
(416, 'Sumbawa', 'Սումբավա'),
(417, 'Surma', 'Սուրմա'),
(418, 'Susu', 'Սուսու'),
(419, 'Swahili', 'Սուահիլի'),
(420, 'Swazi', 'Սվազի'),
(421, 'Swedes', 'Շվեդներ'),
(422, 'Tabasaran', 'Թաբասարան'),
(423, 'Tagalogs', 'Տագալոգներ'),
(424, 'Tahitians', 'Թահիտցիներ'),
(425, 'Tajiks', 'Տաջիկներ'),
(426, 'Talysh', 'Թալիշ'),
(427, 'Tama', 'Տամա'),
(428, 'Tamils', 'Թամիլներ'),
(429, 'Tarok', 'Թարոկ'),
(430, 'Tatars', 'Թաթարներ'),
(431, 'Tboli', 'Տբոլի'),
(432, 'Telugu', 'Թելուգու'),
(433, 'Temne', 'Թեմնե'),
(434, 'Thais', 'Թաիլանդցիներ'),
(435, 'Tibetans', 'Տիբեթցիներ'),
(436, 'Tigrayans', 'Տիգրայաններ'),
(437, 'Tiv', 'Թիվ'),
(438, 'Tiwa', 'Թիվա'),
(439, 'Tlapanec', 'Տլապանեկ'),
(440, 'Tokelauan people', 'Տոկելաուանցիներ'),
(441, 'Toraja', 'Թորաջա'),
(442, 'Toubou', 'Տուբու'),
(443, 'Toucouleur', 'Տուկուլեր'),
(444, 'Tripuri', 'Տրիպուրի'),
(445, 'Tsonga', 'Ցոնգա'),
(446, 'Tswana', 'Ցվանա'),
(447, 'Tujia', 'Տուժիա'),
(448, 'Tuluvas', 'Թուլուավաս'),
(449, 'Tupuri', 'Տուպուրի'),
(450, 'Turkana', 'Թուրկանա'),
(451, 'Turks', 'Թուրքեր'),
(452, 'Turkmens', 'Թուրքմեններ'),
(453, 'Tutsi', 'Տուտսի'),
(454, 'Tuvans', 'Տուվաններ'),
(455, 'Udmurts', 'Ուդմուրտական'),
(456, 'Urhobos', 'Ուրհոբոս'),
(457, 'Ukrainians', 'Ուկրաինացիներ'),
(458, 'Uyghurs', 'Ույղուրներ'),
(459, 'Uzbeks', 'Ուզբեկներ'),
(460, 'Venda', 'Վենդա'),
(461, 'Vietnamese', 'Վիետնամերեն'),
(462, 'Visayans', 'Վիսայաններ'),
(463, 'Wa', 'Վա'),
(464, 'Walloons', 'Վալոններ'),
(465, 'Waxiang', 'Վաքսիանգ'),
(466, 'Welayta', 'Վելետա'),
(467, 'Welsh', 'Ուելսերեն'),
(468, 'Wolof', 'Վոլոֆ'),
(469, 'Xhosa', 'Քսոսա'),
(470, 'Yakan', 'Յական'),
(471, 'Yakö', 'Յակի'),
(472, 'Yakuts', 'Յակուտներ'),
(473, 'Yao', 'Յաո'),
(474, 'Yi', 'Յի'),
(475, 'Yoruba', 'Յորուբա'),
(476, 'Zande', 'Զանդե'),
(477, 'Zhuang', 'Զուանգ'),
(478, 'Zomi', 'Զոմի'),
(479, 'Zulu', 'Զուլուսերեն'),
(480, 'UNKNOWN', 'ԱՆՀԱՅՏ');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_file_type`
--

CREATE TABLE `tb_file_type` (
  `file_type_id` int(11) NOT NULL,
  `file_type` varchar(250) NOT NULL,
  `file_filter` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tb_file_type`
--

INSERT INTO `tb_file_type` (`file_type_id`, `file_type`, `file_filter`) VALUES
(1, 'Դիմումի բնօրինակ', 1),
(2, 'Դիմումի թարգմանություն', 1),
(3, 'Նախնական նախագիծ', 3),
(4, 'Որոշման նախագիծ', 3),
(5, 'Ժամկետի երկարաձգման որոշում', 3),
(6, 'Ուղեգրի նախագիծ', 3),
(7, 'Ուղեգիր (հաստատված)', 3),
(8, 'Ուղեգրի չեղարկման հայտ', 3),
(9, 'Ուղգրի չեղարկման նախագիծ', 3),
(10, 'ՈՒղեգրի չեղարկման որոշում', 3),
(11, 'Զեկուցագիր տեղավորման վերաբերյալ', 3),
(12, 'Զեկուցագիր դուրս գրման վերաբերյալ', 3),
(13, 'Տեղավորման մերժում', 3),
(14, 'անձնագիր', 2),
(15, 'ծննդական', 2),
(16, 'Հարցազրույցի ձայնագրություն', 2),
(17, 'Հարցազրույցի արձաագրություն', 1),
(18, 'Ծանուցում դատարան դիմելու վերաբերյալ', 1),
(19, 'Վարույթ ընդունելու մասին որոշում /դատարան/', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tb_lawyer`
--

CREATE TABLE `tb_lawyer` (
  `lawyer_id` int(11) NOT NULL,
  `lawyer_name` varchar(50) NOT NULL,
  `lawyer_surname` varchar(50) NOT NULL,
  `lawyer_organization` varchar(100) DEFAULT NULL,
  `lawyer_tel` varchar(25) DEFAULT NULL,
  `lawyer_address` varchar(150) DEFAULT NULL,
  `lawyer_email` varchar(100) DEFAULT NULL,
  `case_id` int(11) NOT NULL,
  `actual` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_marz`
--

CREATE TABLE `tb_marz` (
  `marz_id` int(11) NOT NULL,
  `ADM1_ARM` varchar(20) NOT NULL,
  `ADM1_EN` varchar(20) NOT NULL,
  `ADM1_PCODE` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_marz`
--

INSERT INTO `tb_marz` (`marz_id`, `ADM1_ARM`, `ADM1_EN`, `ADM1_PCODE`) VALUES
(1, 'Երևան', 'Yerevan', 'AM01'),
(2, 'Արագածոտն', 'Aragatsotn', 'AM02'),
(3, 'Արարատ', 'Ararat', 'AM03'),
(4, 'Արմավիր', 'Armavir', 'AM04'),
(5, 'Գեղարքունիք', 'Gegharkunik', 'AM05'),
(6, 'Լոռի', 'Lori', 'AM06'),
(7, 'Կոտայք', 'Kotayk', 'AM07'),
(8, 'Շիրակ', 'Shirak', 'AM08'),
(9, 'Սյունիք', 'Syunik', 'AM09'),
(10, 'Վայոց ձոր', 'Vayots Dzor', 'AM10'),
(11, 'Տավուշ', 'Tavush', 'AM11');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_members`
--

CREATE TABLE `tb_members` (
  `member_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `f_name_arm` varchar(50) NOT NULL,
  `f_name_eng` varchar(50) NOT NULL,
  `l_name_arm` varchar(50) NOT NULL,
  `l_name_eng` varchar(50) NOT NULL,
  `m_name_arm` varchar(50) DEFAULT NULL,
  `m_name_eng` varchar(50) DEFAULT NULL,
  `b_day` varchar(2) NOT NULL,
  `b_month` varchar(2) NOT NULL,
  `b_year` varchar(4) NOT NULL,
  `sex` int(11) NOT NULL,
  `citizenship` int(11) DEFAULT NULL,
  `residence` int(11) DEFAULT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_notifications`
--

CREATE TABLE `tb_notifications` (
  `comment_id` int(11) NOT NULL,
  `comment_subject` varchar(250) NOT NULL,
  `comment_text` text DEFAULT NULL,
  `comment_status` int(11) NOT NULL,
  `comment_from` int(11) NOT NULL,
  `comment_to` int(11) NOT NULL,
  `case_id` int(11) DEFAULT NULL,
  `coi_id` int(11) DEFAULT NULL,
  `request_id` int(10) DEFAULT NULL,
  `note_type` int(10) DEFAULT NULL,
  `draft_id` int(11) DEFAULT NULL,
  `order_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_orders`
--

CREATE TABLE `tb_orders` (
  `order_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `order_status` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_order_process`
--

CREATE TABLE `tb_order_process` (
  `order_process_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_from` int(11) DEFAULT NULL,
  `order_to` int(11) DEFAULT NULL,
  `process_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_status` int(11) NOT NULL,
  `order_actual` int(11) NOT NULL,
  `order_comment` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_order_process_status`
--

CREATE TABLE `tb_order_process_status` (
  `order_status_id` int(11) NOT NULL,
  `order_status_arm` varchar(50) NOT NULL,
  `order_status_eng` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_order_process_status`
--

INSERT INTO `tb_order_process_status` (`order_status_id`, `order_status_arm`, `order_status_eng`) VALUES
(1, 'սպասում է բաժնի պետի հաստատմանը', 'pending devhead approve'),
(2, 'սպասում է տեղավորման', 'pending check in'),
(3, 'տեղավորված', 'checked in'),
(4, 'անվավեր ճանաչելու հայտ', 'pending cencelation'),
(5, 'ճանաչվել է անվավեր', 'cenceled'),
(6, 'դուրս է գրվել', 'checked out'),
(7, 'տեղավարման մերժում', 'reject to checkin'),
(8, 'չեղարկման որոշում (նախագիծ)', 'cancel decision (draft)'),
(9, 'Ի կատարում (ուղեգրի չեղարկում)', 'Asign to draft cencelation ');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_order_status`
--

CREATE TABLE `tb_order_status` (
  `order_status_id` int(11) NOT NULL,
  `order_status` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_order_status`
--

INSERT INTO `tb_order_status` (`order_status_id`, `order_status`) VALUES
(1, 'Ընթացիկ'),
(2, 'Ավարտված');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_person`
--

CREATE TABLE `tb_person` (
  `personal_id` int(10) NOT NULL,
  `case_id` int(10) NOT NULL,
  `f_name_arm` varchar(50) NOT NULL,
  `f_name_eng` varchar(50) NOT NULL,
  `l_name_arm` varchar(50) NOT NULL,
  `l_name_eng` varchar(50) NOT NULL,
  `m_name_arm` varchar(50) DEFAULT NULL,
  `m_name_eng` varchar(50) DEFAULT NULL,
  `b_day` varchar(2) NOT NULL,
  `b_month` varchar(2) NOT NULL,
  `b_year` varchar(4) NOT NULL,
  `sex` int(1) NOT NULL,
  `citizenship` int(10) NOT NULL,
  `previous_residence` int(10) DEFAULT NULL,
  `citizen_adr` varchar(100) DEFAULT NULL,
  `residence_adr` varchar(100) DEFAULT NULL,
  `departure_from_citizen` varchar(10) DEFAULT NULL,
  `departure_from_residence` varchar(10) DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `doc_num` varchar(20) DEFAULT NULL,
  `etnicity` int(10) DEFAULT NULL,
  `religion` int(10) DEFAULT NULL,
  `preferred_traslator_sex` int(1) NOT NULL,
  `preferred_interviewer_sex` int(1) NOT NULL,
  `invalid` int(1) NOT NULL DEFAULT 0,
  `pregnant` int(1) NOT NULL DEFAULT 0,
  `seriously_ill` int(1) NOT NULL DEFAULT 0,
  `trafficking_victim` int(1) NOT NULL DEFAULT 0,
  `violence_victim` int(1) NOT NULL DEFAULT 0,
  `comment` varchar(255) DEFAULT NULL,
  `illegal_border` int(10) NOT NULL DEFAULT 0,
  `wanted_moj` int(10) NOT NULL DEFAULT 0,
  `wanted_court` int(10) NOT NULL DEFAULT 0,
  `role` int(10) NOT NULL,
  `image` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_process`
--

CREATE TABLE `tb_process` (
  `process_id` int(10) NOT NULL,
  `case_id` int(10) NOT NULL,
  `sign_status` int(10) NOT NULL,
  `sign_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sign_by` int(10) NOT NULL,
  `processor` int(11) NOT NULL,
  `comment_to` varchar(250) DEFAULT NULL,
  `actual` int(11) NOT NULL,
  `comment_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_religions`
--

CREATE TABLE `tb_religions` (
  `religion_id` int(11) NOT NULL,
  `religion_arm` varchar(120) NOT NULL,
  `religion_eng` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tb_religions`
--

INSERT INTO `tb_religions` (`religion_id`, `religion_arm`, `religion_eng`) VALUES
(1, 'Աթեիզմ / Ագնոստիզմ', 'Atheism/Agnosticism'),
(2, 'Բահաի', 'Bahá’í'),
(3, 'Բուդդիզմ', 'Buddhism'),
(4, 'Քրիստոնեություն', 'Christianity'),
(5, 'Կոնֆուցիականություն', 'Confucianism'),
(6, 'Դրուզ', 'Druze'),
(7, 'Գնոստիցիզմ', 'Gnosticism'),
(8, 'Հինդուիզմ', 'Hinduism'),
(9, 'Իսլամ', 'Islam'),
(10, 'Ջայնիզմ', 'Jainism'),
(11, 'Հուդայականություն', 'Judaism'),
(12, 'Ռաստաֆարիանիզմ', 'Rastafarianism'),
(13, 'Սինտո', 'Shinto'),
(14, 'Սիկհիզմ', 'Sikhism'),
(15, 'Զրադաշտականություն', 'Zoroastrianism'),
(16, 'Բուշոնգոյի դիցաբանություն (Կոնգո)', 'Bushongo mythology (Congo)'),
(17, 'Լուգբարայի դիցաբանություն (Կոնգո)', 'Lugbara mythology (Congo)'),
(18, 'Բալուբայի դիցաբանություն (Կոնգո)', 'Baluba mythology (Congo)'),
(19, 'Մբուտի դիցաբանություն (Կոնգո)', 'Mbuti mythology (Congo)'),
(20, 'Ակամբայի դիցաբանություն (Քենիա)', 'Akamba mythology (Kenya)'),
(21, 'Լոզի դիցաբանություն (ambամբիա)', 'Lozi mythology (Zambia)'),
(22, 'Թումբուկայի դիցաբանություն (Մալավի)', 'Tumbuka mythology (Malawi)'),
(23, 'Զուլուսական դիցաբանություն (Հարավային Աֆրիկա)', 'Zulu mythology (South Africa)'),
(24, 'Դինկա կրոն (Հարավային Սուդան)', 'Dinka religion (South Sudan)'),
(25, 'Հաուսա անիմիզմ (Չադ, Գաբոն)', 'Hausa animism (Chad, Gabon)'),
(26, 'Լոտուկոյի դիցաբանություն (Հարավային Սուդան)', 'Lotuko mythology (South Sudan)'),
(27, 'Մաասայի դիցաբանություն (Քենիա, Տանզանիա, Ուեբերիա)', 'Maasai mythology (Kenya, Tanzania, Ouebian)'),
(28, 'Կալենջինյան կրոն (Քենիա, Ուգանդա, Տանզանիա)', 'Kalenjin religion(Kenya, Uganda, Tanzania)'),
(29, 'Դինի Յա Մսամբվա (Բունգոմա, Trans Nzoia, Քենիա)', 'Dini Ya Msambwa (Bungoma, Trans Nzoia, Kenya)'),
(30, 'Սան կրոն (Հարավային Աֆրիկա)', 'San religion (South Africa)'),
(31, 'Հարավային Աֆրիկայի ավանդական բուժիչներ', 'Traditional healers of South Africa'),
(32, 'Զիմբաբվեի Չիտունգվիզայի բուժիչներ Մանջոնջո', 'Manjonjo Healers of Chitungwiza of Zimbabwe'),
(33, 'Ականական կրոն (Գանա, Փղոսկրի Ափ)', 'Akan religion (Ghana, Ivory Coast)'),
(34, 'Դահոմեական կրոն (Բենին, Տոգո)', 'Dahomean religion (Benin, Togo)'),
(35, 'Էֆիկի դիցաբանություն (Նիգերիա, Կամերուն)', 'Efik mythology (Nigeria, Cameroon)'),
(36, 'Էդո կրոն (Բենինի թագավորություն, Նիգերիա)', 'Edo religion (Benin kingdom, Nigeria)'),
(37, 'Հաուսա անիմիզմ (Բենին, Բուրկինա Ֆասո, Կամերուն, Կոտ դ՛Իվուար, Գանա, Նիգեր, Նիգերիա, Տոգո)', 'Hausa animism (Benin, Burkina Faso, Cameroon, Côte d’Ivoire, Ghana, Niger, Nigeria, Togo)'),
(38, 'Օդինանի (Igbo մարդիկ, Նիգերիա)', 'Odinani (Igbo people, Nigeria)'),
(39, 'Սերերական կրոն (Սենեգալ, Գամբիա, Մավրիտանիա)', 'Serer religion (Senegal, Gambia, Mauritania)'),
(40, 'Յորուբայի կրոն (Նիգերիա, Բենին, Տոգո)', 'Yoruba religion (Nigeria, Benin, Togo)'),
(41, 'Արևմտյան Աֆրիկայի Վոդուն (Գանա, Բենին, Տոգո, Նիգերիա)', 'West African Vodun (Ghana, Benin, Togo, Nigeria)'),
(42, 'Դոգոն կրոն (Մալի)', 'Dogon religion (Mali)'),
(43, 'Վոդուն (Բենին)', 'Vodun (Benin)'),
(44, 'ԱՆՀԱՅՏ', 'UNKNOWN');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_requests`
--

CREATE TABLE `tb_requests` (
  `request_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `body` int(11) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `request_comment` text NOT NULL,
  `request_user` int(11) NOT NULL,
  `response` int(10) DEFAULT NULL,
  `response_text` text DEFAULT NULL,
  `response_date` date DEFAULT NULL,
  `response_file` varchar(250) DEFAULT NULL,
  `request_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `tb_role`
--

CREATE TABLE `tb_role` (
  `role_id` int(11) NOT NULL,
  `der` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_role`
--

INSERT INTO `tb_role` (`role_id`, `der`) VALUES
(1, 'գլխավոր'),
(2, 'հայրը'),
(3, 'մայրը'),
(4, 'կինը'),
(5, 'որդին'),
(6, 'դուստրը'),
(7, 'քույրը'),
(8, 'եղբայրը'),
(9, 'տատը'),
(10, 'պապը'),
(11, 'այլ'),
(12, 'ամուսինը'),
(13, 'թոռ'),
(14, 'հարս'),
(15, 'ամուսնու ծնող'),
(16, 'կնոջ ծնողը'),
(17, 'փեսան');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_settlement`
--

CREATE TABLE `tb_settlement` (
  `settlement_id` int(11) NOT NULL,
  `com_id` int(11) NOT NULL,
  `ADM3_CODE` varchar(10) NOT NULL,
  `ADM4_PCODE` varchar(10) NOT NULL,
  `ADM4_ARM` varchar(25) NOT NULL,
  `ADM4_ENG` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_settlement`
--

INSERT INTO `tb_settlement` (`settlement_id`, `com_id`, `ADM3_CODE`, `ADM4_PCODE`, `ADM4_ARM`, `ADM4_ENG`) VALUES
(1001013, 1001, 'AM01001', 'AM01001013', 'Աջափնյակ', 'Ajapnyak'),
(1001023, 1001, 'AM01001', 'AM01001023', 'Ավան', 'Avan'),
(1001033, 1001, 'AM01001', 'AM01001033', 'Արաբկիր', 'Arabkir'),
(1001043, 1001, 'AM01001', 'AM01001043', 'Դավթաշեն', 'Davtashen'),
(1001053, 1001, 'AM01001', 'AM01001053', 'Էրեբունի', 'Erebuni'),
(1001063, 1001, 'AM01001', 'AM01001063', 'Կենտրոն', 'Kentron'),
(1001073, 1001, 'AM01001', 'AM01001073', 'Մալաթիա-Սեբաստիա', 'Malatia-Sebastia'),
(1001083, 1001, 'AM01001', 'AM01001083', 'Նոր Նորք', 'Nor Nork'),
(1001093, 1001, 'AM01001', 'AM01001093', 'Նորք-Մարաշ', 'Nork-Marash'),
(1001103, 1001, 'AM01001', 'AM01001103', 'Նուբարաշեն', 'Nubarashen'),
(1001113, 1001, 'AM01001', 'AM01001113', 'Շենգավիթ', 'Shengavit'),
(1001123, 1001, 'AM01001', 'AM01001123', 'Քանաքեռ-Զեյթուն', 'Kanaker-Zeytun'),
(2001011, 2001, 'AM02001', 'AM02001011', 'Աշտարակ', 'Ashtarak'),
(2001022, 2001, 'AM02001', 'AM02001022', 'Մուղնի', 'Mughni'),
(2002011, 2002, 'AM02002', 'AM02002011', 'Ապարան', 'Aparan'),
(2002022, 2002, 'AM02002', 'AM02002022', 'Արագած', 'Aragats'),
(2002032, 2002, 'AM02002', 'AM02002032', 'Արայի', 'Arayi'),
(2002042, 2002, 'AM02002', 'AM02002042', 'Ափնագյուղ', 'Apnagyugh'),
(2002052, 2002, 'AM02002', 'AM02002052', 'Եղիպատրուշ', 'Yeghipatrush'),
(2002062, 2002, 'AM02002', 'AM02002062', 'Երնջատափ', 'Yernjatap'),
(2002072, 2002, 'AM02002', 'AM02002072', 'Թթուջուր', 'Ttujur'),
(2002082, 2002, 'AM02002', 'AM02002082', 'Լուսագյուղ', 'Lusagyugh'),
(2002092, 2002, 'AM02002', 'AM02002092', 'Ծաղկաշեն', 'Tsaghkashen'),
(2002102, 2002, 'AM02002', 'AM02002102', 'Կայք', 'Kayk'),
(2002112, 2002, 'AM02002', 'AM02002112', 'Հարթավան', 'Hartavan'),
(2002122, 2002, 'AM02002', 'AM02002122', 'Ձորագլուխ', 'Dzoraglukh'),
(2002132, 2002, 'AM02002', 'AM02002132', 'Նիգավան', 'Nigavan'),
(2002142, 2002, 'AM02002', 'AM02002142', 'Շենավան', 'Shenavan'),
(2002152, 2002, 'AM02002', 'AM02002152', 'Շողակն', 'Shoghakn'),
(2002162, 2002, 'AM02002', 'AM02002162', 'Չքնաղ', 'Chknagh'),
(2002172, 2002, 'AM02002', 'AM02002172', 'Ջրամբար', 'Jrambar'),
(2002182, 2002, 'AM02002', 'AM02002182', 'Սարալանջ', 'Saralanj'),
(2002192, 2002, 'AM02002', 'AM02002192', 'Վարդենիս', 'Vardenis'),
(2002202, 2002, 'AM02002', 'AM02002202', 'Վարդենուտ', 'Vardenut'),
(2002212, 2002, 'AM02002', 'AM02002212', 'Քուչակ', 'Kuchak'),
(2003011, 2003, 'AM02003', 'AM02003011', 'Թալին', 'Talin'),
(2004012, 2004, 'AM02004', 'AM02004012', 'Ագարակ', 'Agarak'),
(2005012, 2005, 'AM02005', 'AM02005012', 'Ագարակավան', 'Agarakavan'),
(2006012, 2006, 'AM02006', 'AM02006012', 'Ալագյազ', 'Alagyaz'),
(2006022, 2006, 'AM02006', 'AM02006022', 'Ավշեն', 'Avshen'),
(2006032, 2006, 'AM02006', 'AM02006032', 'Կանիաշիր', 'Kaniashir'),
(2006042, 2006, 'AM02006', 'AM02006042', 'Ճարճակիս', 'Charchakis'),
(2006052, 2006, 'AM02006', 'AM02006052', 'Միջնատուն', 'Mijnatun'),
(2006062, 2006, 'AM02006', 'AM02006062', 'Միրաք', 'Mirak'),
(2006072, 2006, 'AM02006', 'AM02006072', 'Շենկանի', 'Shenkani'),
(2006082, 2006, 'AM02006', 'AM02006082', 'Ջամշլու', 'Jamshlu'),
(2006092, 2006, 'AM02006', 'AM02006092', 'Ռյա թազա', 'Rya Taza'),
(2006102, 2006, 'AM02006', 'AM02006102', 'Սադունց', 'Sadunts'),
(2006112, 2006, 'AM02006', 'AM02006112', 'Սիփան', 'Sipan'),
(2007012, 2007, 'AM02007', 'AM02007012', 'Ակունք', 'Akunk'),
(2008012, 2008, 'AM02008', 'AM02008012', 'Աղձք', 'Aghdzk'),
(2010012, 2010, 'AM02010', 'AM02010012', 'Անտառուտ', 'Antarut'),
(2011012, 2011, 'AM02011', 'AM02011012', 'Աշնակ', 'Ashnak'),
(2012012, 2012, 'AM02012', 'AM02012012', 'Ավան', 'Avan'),
(2012022, 2012, 'AM02012', 'AM02012022', 'Խնուսիկ', 'Khnusik'),
(2013012, 2013, 'AM02013', 'AM02013012', 'Մեծաձոր', 'Metsadzor'),
(2016012, 2016, 'AM02016', 'AM02016012', 'Արագածավան', 'Aragatsavan'),
(2016022, 2016, 'AM02016', 'AM02016022', 'Արտենի', 'Arteni'),
(2016032, 2016, 'AM02016', 'AM02016032', 'Գետափ', 'Getap'),
(2016042, 2016, 'AM02016', 'AM02016042', 'Լուսակն', 'Lusak'),
(2017012, 2017, 'AM02017', 'AM02017012', 'Արագածոտն', 'Aragatsotn'),
(2019012, 2019, 'AM02019', 'AM02019012', 'Թաթուլ', 'Tatul'),
(2020012, 2020, 'AM02020', 'AM02020012', 'Արտաշատավան', 'Artashatavan'),
(2020022, 2020, 'AM02020', 'AM02020022', 'Լուսաղբյուր', 'Lusaghbyur'),
(2020032, 2020, 'AM02020', 'AM02020032', 'Նիգատուն', 'Nigatun'),
(2022012, 2022, 'AM02022', 'AM02022012', 'Արուճ', 'Aruch'),
(2024012, 2024, 'AM02024', 'AM02024012', 'Բազմաղբյուր', 'Bazmaghbyur'),
(2025012, 2025, 'AM02025', 'AM02025012', 'Օթևան', 'Otevan'),
(2026012, 2026, 'AM02026', 'AM02026012', 'Արևուտ', 'Arevut'),
(2028012, 2028, 'AM02028', 'AM02028012', 'Բյուրական', 'Byurakan'),
(2029012, 2029, 'AM02029', 'AM02029012', 'Գառնահովիտ', 'Garnahovit'),
(2034012, 2034, 'AM02034', 'AM02034012', 'Կանչ', 'Kanch'),
(2035012, 2035, 'AM02035', 'AM02035012', 'Դաշտադեմ', 'Dashtadem'),
(2036012, 2036, 'AM02036', 'AM02036012', 'Դավթաշեն', 'Davtashen'),
(2038012, 2038, 'AM02038', 'AM02038012', 'Դիան', 'Dian'),
(2039012, 2039, 'AM02039', 'AM02039012', 'Դպրևանք', 'Dprevank'),
(2041012, 2041, 'AM02041', 'AM02041012', 'Եղնիկ', 'Yeghnik'),
(2044012, 2044, 'AM02044', 'AM02044012', 'Զարինջա', 'Zarinja'),
(2045012, 2045, 'AM02045', 'AM02045012', 'Զովասար', 'Zovasar'),
(2047012, 2047, 'AM02047', 'AM02047012', 'Թլիկ', 'Tlik'),
(2048012, 2048, 'AM02048', 'AM02048012', 'Իրինդ', 'Irind'),
(2050012, 2050, 'AM02050', 'AM02050012', 'Լեռնարոտ', 'Lernarot'),
(2053012, 2053, 'AM02053', 'AM02053012', 'Ծաղկահովիտ', 'Tsaghkahovit'),
(2053022, 2053, 'AM02053', 'AM02053022', 'Բերքառատ', 'Berkarat'),
(2053032, 2053, 'AM02053', 'AM02053032', 'Գեղադիր', 'Geghadir'),
(2053042, 2053, 'AM02053', 'AM02053042', 'Գեղաձոր', 'Geghadzor'),
(2053052, 2053, 'AM02053', 'AM02053052', 'Գեղարոտ', 'Gegharot'),
(2053062, 2053, 'AM02053', 'AM02053062', 'Լեռնապար', 'Lernapar'),
(2053072, 2053, 'AM02053', 'AM02053072', 'Ծիլքար', 'Tsilkar'),
(2053082, 2053, 'AM02053', 'AM02053082', 'Հնաբերդ', 'Hnaberd'),
(2053092, 2053, 'AM02053', 'AM02053092', 'Նորաշեն', 'Norashen'),
(2053102, 2053, 'AM02053', 'AM02053102', 'Վարդաբլուր', 'Vardablur'),
(2055012, 2055, 'AM02055', 'AM02055012', 'Ծաղկասար', 'Tsaghkasar'),
(2057012, 2057, 'AM02057', 'AM02057012', 'Կաթնաղբյուր', 'Katnaghbyur'),
(2058012, 2058, 'AM02058', 'AM02058012', 'Կարբի', 'Karbi'),
(2059012, 2059, 'AM02059', 'AM02059012', 'Կարմրաշեն', 'Karmrashen'),
(2060012, 2060, 'AM02060', 'AM02060012', 'Կաքավաձոր', 'Kakavadzor'),
(2061012, 2061, 'AM02061', 'AM02061012', 'Կոշ', 'Kosh'),
(2062012, 2062, 'AM02062', 'AM02062012', 'Հակո', 'Hako'),
(2064012, 2064, 'AM02064', 'AM02064012', 'Հացաշեն', 'Hatsashen'),
(2067012, 2067, 'AM02067', 'AM02067012', 'Դդմասար', 'Ddmasar'),
(2068012, 2068, 'AM02068', 'AM02068012', 'Ղազարավան', 'Ghazaravan'),
(2069012, 2069, 'AM02069', 'AM02069012', 'Մաստարա', 'Mastara'),
(2069022, 2069, 'AM02069', 'AM02069022', 'Ձորագյուղ', 'Dzoragyugh'),
(2070012, 2070, 'AM02070', 'AM02070012', 'Մելիքգյուղ', 'Melikgyugh'),
(2073012, 2073, 'AM02073', 'AM02073012', 'Ներքին Բազմաբերդ', 'Nerkin Bazmaberd'),
(2074012, 2074, 'AM02074', 'AM02074012', 'Ներքին Սասնաշեն', 'Nerkin Sasnashen'),
(2076012, 2076, 'AM02076', 'AM02076012', 'Նոր Ամանոս', 'Nor Amanos'),
(2079012, 2079, 'AM02079', 'AM02079012', 'Նոր Արթիկ', 'Nor Artik'),
(2080012, 2080, 'AM02080', 'AM02080012', 'Նոր Եդեսիա', 'Nor Edesia'),
(2081012, 2081, 'AM02081', 'AM02081012', 'Շաﬕրամ', 'Shamiram'),
(2084012, 2084, 'AM02084', 'AM02084012', 'Շղարշիկ', 'Shgharshik'),
(2085012, 2085, 'AM02085', 'AM02085012', 'Ոսկեթաս', 'Vosketas'),
(2086012, 2086, 'AM02086', 'AM02086012', 'Ոսկեհատ', 'Voskehat'),
(2087012, 2087, 'AM02087', 'AM02087012', 'Ոսկեվազ', 'Voskevaz'),
(2089012, 2089, 'AM02089', 'AM02089012', 'Պարտիզակ', 'Partizak'),
(2093012, 2093, 'AM02093', 'AM02093012', 'Սաղմոսավան', 'Saghmosavan'),
(2095012, 2095, 'AM02095', 'AM02095012', 'Սասունիկ', 'Sasunik'),
(2095022, 2095, 'AM02095', 'AM02095022', 'Կարին', 'Karin'),
(2098012, 2098, 'AM02098', 'AM02098012', 'Սորիկ', 'Sorik'),
(2099012, 2099, 'AM02099', 'AM02099012', 'Սուսեր', 'Suser'),
(2103012, 2103, 'AM02103', 'AM02103012', 'Վերին Բազմաբերդ', 'Verin Bazmaberd'),
(2104012, 2104, 'AM02104', 'AM02104012', 'Վերին Սասնաշեն', 'Verin Sasnashen'),
(2105012, 2105, 'AM02105', 'AM02105012', 'Վերին Սասունիկ', 'Verin Sasunik'),
(2106012, 2106, 'AM02106', 'AM02106012', 'Տեղեր', 'Tegher'),
(2107012, 2107, 'AM02107', 'AM02107012', 'Ցամաքասար', 'Tsamakasar'),
(2108012, 2108, 'AM02108', 'AM02108012', 'Ուշի', 'Ushi'),
(2109012, 2109, 'AM02109', 'AM02109012', 'Ուջան', 'Ujan'),
(2110012, 2110, 'AM02110', 'AM02110012', 'Փարպի', 'Parpi'),
(2112012, 2112, 'AM02112', 'AM02112012', 'Օհանավան', 'Ohanavan'),
(2113012, 2113, 'AM02113', 'AM02113012', 'Օշական', 'Oshakan'),
(2114012, 2114, 'AM02114', 'AM02114012', 'Օրգով', 'Orgov'),
(3001011, 3001, 'AM03001', 'AM03001011', 'Արտաշատ', 'Artashat'),
(3002011, 3002, 'AM03002', 'AM03002011', 'Արարատ (քաղաք)', 'Ararat (town)'),
(3003011, 3003, 'AM03003', 'AM03003011', 'Մասիս (քաղաք)', 'Masis (town)'),
(3004011, 3004, 'AM03004', 'AM03004011', 'Վեդի', 'Vedi'),
(3005012, 3005, 'AM03005', 'AM03005012', 'Աբովյան', 'Abovyan'),
(3006012, 3006, 'AM03006', 'AM03006012', 'Ազատաշեն', 'Azatashen'),
(3007012, 3007, 'AM03007', 'AM03007012', 'Ազատավան', 'Azatavan'),
(3008012, 3008, 'AM03008', 'AM03008012', 'Այգավան', 'Aygavan'),
(3009012, 3009, 'AM03009', 'AM03009012', 'Այգեզարդ', 'Aygezard'),
(3010012, 3010, 'AM03010', 'AM03010012', 'Այգեպատ', 'Aygepat'),
(3011012, 3011, 'AM03011', 'AM03011012', 'Այգեստան', 'Aygestan'),
(3012012, 3012, 'AM03012', 'AM03012012', 'Այնթապ', 'Ayntap'),
(3013012, 3013, 'AM03013', 'AM03013012', 'Ավշար', 'Avshar'),
(3014012, 3014, 'AM03014', 'AM03014012', 'Արալեզ', 'Aralez'),
(3015012, 3015, 'AM03015', 'AM03015012', 'Արարատ (գյուղ)', 'Ararat (village)'),
(3016012, 3016, 'AM03016', 'AM03016012', 'Արաքսավան', 'Araksavan'),
(3017012, 3017, 'AM03017', 'AM03017012', 'Արբաթ', 'Arbat'),
(3018012, 3018, 'AM03018', 'AM03018012', 'Արգավանդ', 'Argavand'),
(3019012, 3019, 'AM03019', 'AM03019012', 'Արմաշ', 'Armash'),
(3020012, 3020, 'AM03020', 'AM03020012', 'Արևաբույր', 'Arevabuyr'),
(3021012, 3021, 'AM03021', 'AM03021012', 'Արևշատ', 'Arevshat'),
(3022012, 3022, 'AM03022', 'AM03022012', 'Բաղրամյան', 'Baghramyan'),
(3023012, 3023, 'AM03023', 'AM03023012', 'Բարձրաշեն', 'Bardzrashen'),
(3023022, 3023, 'AM03023', 'AM03023022', 'Կաքավաբերդ', 'Kakavaberd'),
(3024012, 3024, 'AM03024', 'AM03024012', 'Բերդիկ', 'Berdik'),
(3025012, 3025, 'AM03025', 'AM03025012', 'Բերքանուշ', 'Berkanush'),
(3026012, 3026, 'AM03026', 'AM03026012', 'Բյուրավան', 'Byuravan'),
(3027012, 3027, 'AM03027', 'AM03027012', 'Բուրաստան', 'Burastan'),
(3028012, 3028, 'AM03028', 'AM03028012', 'Գեղանիստ', 'Geghanist'),
(3029012, 3029, 'AM03029', 'AM03029012', 'Գետազատ', 'Getazat'),
(3030012, 3030, 'AM03030', 'AM03030012', 'Գետափնյա', 'Getapnya'),
(3031012, 3031, 'AM03031', 'AM03031012', 'Գոռավան', 'Goravan'),
(3032012, 3032, 'AM03032', 'AM03032012', 'Դալար', 'Dalar'),
(3033012, 3033, 'AM03033', 'AM03033012', 'Դաշտավան', 'Dashtavan'),
(3034012, 3034, 'AM03034', 'AM03034012', 'Դաշտաքար', 'Dashtakar'),
(3035012, 3035, 'AM03035', 'AM03035012', 'Դարակերտ', 'Darakert'),
(3036012, 3036, 'AM03036', 'AM03036012', 'Դարբնիկ', 'Darbnik'),
(3037012, 3037, 'AM03037', 'AM03037012', 'Դեղձուտ', 'Deghdzut'),
(3038012, 3038, 'AM03038', 'AM03038012', 'Դիﬕտրով', 'Dimitrov'),
(3039012, 3039, 'AM03039', 'AM03039012', 'Դիտակ', 'Ditak'),
(3040012, 3040, 'AM03040', 'AM03040012', 'Դվին', 'Dvin'),
(3041012, 3041, 'AM03041', 'AM03041012', 'Եղեգնավան', 'Yeghegnavan'),
(3042012, 3042, 'AM03042', 'AM03042012', 'Երասխ', 'Yeraskh'),
(3043012, 3043, 'AM03043', 'AM03043012', 'Զանգակատուն', 'Zangakatun'),
(3044012, 3044, 'AM03044', 'AM03044012', 'Զորակ', 'Zorak'),
(3045012, 3045, 'AM03045', 'AM03045012', 'Լանջազատ', 'Lanjazat'),
(3047012, 3047, 'AM03047', 'AM03047012', 'Լանջառ', 'Lanjar'),
(3048012, 3048, 'AM03048', 'AM03048012', 'Լուսաշող', 'Lusashogh'),
(3049012, 3049, 'AM03049', 'AM03049012', 'Լուսառատ', 'Lusarat'),
(3050012, 3050, 'AM03050', 'AM03050012', 'Խաչփառ', 'Khachpar'),
(3051012, 3051, 'AM03051', 'AM03051012', 'Կանաչուտ', 'Kanachut'),
(3052012, 3052, 'AM03052', 'AM03052012', 'Հայանիստ', 'Hayanist'),
(3053012, 3053, 'AM03053', 'AM03053012', 'Հնաբերդ', 'Hnaberd'),
(3054012, 3054, 'AM03054', 'AM03054012', 'Հովտաշատ', 'Hovtashat'),
(3055012, 3055, 'AM03055', 'AM03055012', 'Հովտաշեն', 'Hovtashen'),
(3056012, 3056, 'AM03056', 'AM03056012', 'Ղուկասավան', 'Ghukasavan'),
(3057012, 3057, 'AM03057', 'AM03057012', 'Մասիս (գյուղ)', 'Masis (village)'),
(3058012, 3058, 'AM03058', 'AM03058012', 'Մարմարաշեն', 'Marmarashen'),
(3059012, 3059, 'AM03059', 'AM03059012', 'Մխչյան', 'Mkhchyan'),
(3060012, 3060, 'AM03060', 'AM03060012', 'Մրգանուշ', 'Mrganush'),
(3061012, 3061, 'AM03061', 'AM03061012', 'Մրգավան', 'Mrgavan'),
(3062012, 3062, 'AM03062', 'AM03062012', 'Մրգավետ', 'Mrgavet'),
(3063012, 3063, 'AM03063', 'AM03063012', 'Նարեկ', 'Narek'),
(3064012, 3064, 'AM03064', 'AM03064012', 'Նիզաﬕ', 'Nizami'),
(3065012, 3065, 'AM03065', 'AM03065012', 'Նշավան', 'Nshavan'),
(3066012, 3066, 'AM03066', 'AM03066012', 'Նոյակերտ', 'Noyakert'),
(3067012, 3067, 'AM03067', 'AM03067012', 'Նորաբաց', 'Norabats'),
(3068012, 3068, 'AM03068', 'AM03068012', 'Նորամարգ', 'Noramarg'),
(3069012, 3069, 'AM03069', 'AM03069012', 'Նորաշեն', 'Norashen'),
(3070012, 3070, 'AM03070', 'AM03070012', 'Նոր Խարբերդ', 'Nor Kharberd'),
(3071012, 3071, 'AM03071', 'AM03071012', 'Նոր կյանք', 'Nor Kyank'),
(3072012, 3072, 'AM03072', 'AM03072012', 'Նոր Կյուրին', 'Nor Kyurin'),
(3073012, 3073, 'AM03073', 'AM03073012', 'Նոր ուղի', 'Nor Ughi'),
(3074012, 3074, 'AM03074', 'AM03074012', 'Շահումյան', 'Shahumyan'),
(3076012, 3076, 'AM03076', 'AM03076012', 'Ոսկետափ', 'Vosketap'),
(3077012, 3077, 'AM03077', 'AM03077012', 'Ոստան', 'Vostan'),
(3078012, 3078, 'AM03078', 'AM03078012', 'Պարույր Սևակ', 'Paruyr Sevak'),
(3078022, 3078, 'AM03078', 'AM03078022', 'Տիգրանաշեն', 'Tigranashen'),
(3079012, 3079, 'AM03079', 'AM03079012', 'Ջրահովիտ', 'Jrahovit'),
(3080012, 3080, 'AM03080', 'AM03080012', 'Ջրաշեն', 'Jrashen'),
(3081012, 3081, 'AM03081', 'AM03081012', 'Ռանչպար', 'Ranchpar'),
(3082012, 3082, 'AM03082', 'AM03082012', 'Սայաթ-Նովա', 'Sayat-Nova'),
(3083012, 3083, 'AM03083', 'AM03083012', 'Սիս', 'Sis'),
(3084012, 3084, 'AM03084', 'AM03084012', 'Սիսավան', 'Sisavan'),
(3085012, 3085, 'AM03085', 'AM03085012', 'Սիփանիկ', 'Sipanik'),
(3086012, 3086, 'AM03086', 'AM03086012', 'Սուրենավան', 'Surenavan'),
(3087012, 3087, 'AM03087', 'AM03087012', 'Վանաշեն', 'Vanashen'),
(3088012, 3088, 'AM03088', 'AM03088012', 'Վարդաշատ', 'Vardashat'),
(3089012, 3089, 'AM03089', 'AM03089012', 'Վարդաշեն', 'Vardashen'),
(3090012, 3090, 'AM03090', 'AM03090012', 'Գինեվետ', 'Ginevet'),
(3091012, 3091, 'AM03091', 'AM03091012', 'Վերին Արտաշատ', 'Verin Artashat'),
(3092012, 3092, 'AM03092', 'AM03092012', 'Վերին Դվին', 'Verin Dvin'),
(3093012, 3093, 'AM03093', 'AM03093012', 'Տափերական', 'Taperakan'),
(3094012, 3094, 'AM03094', 'AM03094012', 'Ուրցալանջ', 'Urtsalanj'),
(3095012, 3095, 'AM03095', 'AM03095012', 'Ուրցաձոր', 'Urtsadzor'),
(3095022, 3095, 'AM03095', 'AM03095022', 'Լանջանիստ', 'Lanjanist'),
(3095032, 3095, 'AM03095', 'AM03095032', 'Շաղափ', 'Shaghaph'),
(3096012, 3096, 'AM03096', 'AM03096012', 'Փոքր Վեդի', 'Pokr Vedi'),
(3097012, 3097, 'AM03097', 'AM03097012', 'Քաղցրաշեն', 'Kaghtsrashen'),
(4001011, 4001, 'AM04001', 'AM04001011', 'Արմավիր (քաղաք)', 'Armavir (town)'),
(4002011, 4002, 'AM04002', 'AM04002011', 'Վաղարշապատ', 'Vagharshapat'),
(4003011, 4003, 'AM04003', 'AM04003011', 'Մեծամոր (քաղաք)', 'Metsamor (town)'),
(4004012, 4004, 'AM04004', 'AM04004012', 'Ակնալիճ', 'Aknalich'),
(4005012, 4005, 'AM04005', 'AM04005012', 'Ակնաշեն', 'Aknashen'),
(4006012, 4006, 'AM04006', 'AM04006012', 'Աղաﬖատուն', 'Aghavnatun'),
(4007012, 4007, 'AM04007', 'AM04007012', 'Ամասիա', 'Amasia'),
(4008012, 4008, 'AM04008', 'AM04008012', 'Ամբերդ', 'Amberd'),
(4009012, 4009, 'AM04009', 'AM04009012', 'Այգեկ', 'Aygek'),
(4010012, 4010, 'AM04010', 'AM04010012', 'Այգեշատ', 'Aygeshat'),
(4011012, 4011, 'AM04011', 'AM04011012', 'Այգեշատ', 'Aygeshat'),
(4012012, 4012, 'AM04012', 'AM04012012', 'Ապագա', 'Apaga'),
(4013012, 4013, 'AM04013', 'AM04013012', 'Առատաշեն', 'Aratashen'),
(4014012, 4014, 'AM04014', 'AM04014012', 'Արագած', 'Aragats'),
(4015012, 4015, 'AM04015', 'AM04015012', 'Արազափ', 'Arazap'),
(4016012, 4016, 'AM04016', 'AM04016012', 'Արաքս', 'Araks'),
(4017012, 4017, 'AM04017', 'AM04017012', 'Արաքս', 'Araks'),
(4018012, 4018, 'AM04018', 'AM04018012', 'Արգավանդ', 'Argavand'),
(4019012, 4019, 'AM04019', 'AM04019012', 'Արգինա', 'Argina'),
(4020012, 4020, 'AM04020', 'AM04020012', 'Արմավիր (գյուղ)', 'Armavir (village)'),
(4021012, 4021, 'AM04021', 'AM04021012', 'Արշալույս', 'Arshaluys'),
(4022012, 4022, 'AM04022', 'AM04022012', 'Արտաﬔտ', 'Artamet'),
(4023012, 4023, 'AM04023', 'AM04023012', 'Արտիﬔտ', 'Artimet'),
(4024012, 4024, 'AM04024', 'AM04024012', 'Արտաշար', 'Artashar'),
(4025012, 4025, 'AM04025', 'AM04025012', 'Արևադաշտ', 'Arevadasht'),
(4026012, 4026, 'AM04026', 'AM04026012', 'Արևաշատ', 'Arevashat'),
(4027012, 4027, 'AM04027', 'AM04027012', 'Արևիկ', 'Arevik'),
(4028012, 4028, 'AM04028', 'AM04028012', 'Բագարան', 'Bagaran'),
(4029012, 4029, 'AM04029', 'AM04029012', 'Բաղրամյան', 'Baghramyan'),
(4030012, 4030, 'AM04030', 'AM04030012', 'Բաղրամյան', 'Baghramyan'),
(4031012, 4031, 'AM04031', 'AM04031012', 'Բամբակաշատ', 'Bambakashat'),
(4032012, 4032, 'AM04032', 'AM04032012', 'Բերքաշատ', 'Berkashat'),
(4033012, 4033, 'AM04033', 'AM04033012', 'Գայ', 'Gai'),
(4034012, 4034, 'AM04034', 'AM04034012', 'Գետաշեն', 'Getashen'),
(4035012, 4035, 'AM04035', 'AM04035012', 'Գրիբոյեդով', 'Griboyedov'),
(4036012, 4036, 'AM04036', 'AM04036012', 'Դալարիկ', 'Dalarik'),
(4037012, 4037, 'AM04037', 'AM04037012', 'Դաշտ', 'Dasht'),
(4038012, 4038, 'AM04038', 'AM04038012', 'Դողս', 'Doghs'),
(4039012, 4039, 'AM04039', 'AM04039012', 'Եղեգնուտ', 'Yeghegnut'),
(4040012, 4040, 'AM04040', 'AM04040012', 'Երասխահուն', 'Yeraskhahun'),
(4041012, 4041, 'AM04041', 'AM04041012', 'Երվանդաշատ', 'Yervandashat'),
(4042012, 4042, 'AM04042', 'AM04042012', 'Զարթոնք', 'Zartonk'),
(4043012, 4043, 'AM04043', 'AM04043012', 'Մայիսյան', 'Mayisyan'),
(4044012, 4044, 'AM04044', 'AM04044012', 'Լենուղի', 'Lenughi'),
(4045012, 4045, 'AM04045', 'AM04045012', 'Լեռնագոգ', 'Lernagog'),
(4046012, 4046, 'AM04046', 'AM04046012', 'Լեռնաﬔրձ', 'Lernamerdz'),
(4047012, 4047, 'AM04047', 'AM04047012', 'Լուկաշին', 'Lukashin'),
(4048012, 4048, 'AM04048', 'AM04048012', 'Լուսագյուղ', 'Lusagyugh'),
(4049012, 4049, 'AM04049', 'AM04049012', 'Խանջյան', 'Khanjyan'),
(4050012, 4050, 'AM04050', 'AM04050012', 'Խորոնք', 'Khoronk'),
(4051012, 4051, 'AM04051', 'AM04051012', 'Ծաղկալանջ', 'Tsaghkalanj'),
(4052012, 4052, 'AM04052', 'AM04052012', 'Ծաղկունք', 'Tsaghkunk'),
(4053002, 4053, 'AM04053', 'AM04053002', 'Ծիածան', 'Tsiatsan'),
(4054012, 4054, 'AM04054', 'AM04054012', 'Կողբավան', 'Koghbavan'),
(4055012, 4055, 'AM04055', 'AM04055012', 'Հայթաղ', 'Haytagh'),
(4056012, 4056, 'AM04056', 'AM04056012', 'Հայկաշեն', 'Haykashen'),
(4057012, 4057, 'AM04057', 'AM04057012', 'Հայկավան', 'Haykavan'),
(4058012, 4058, 'AM04058', 'AM04058012', 'Հացիկ', 'Hatsik'),
(4059012, 4059, 'AM04059', 'AM04059012', 'Սարդարապատ', 'Sardarapat'),
(4060012, 4060, 'AM04060', 'AM04060012', 'Հովտաﬔջ', 'Hovtamej'),
(4061012, 4061, 'AM04061', 'AM04061012', 'Հուշակերտ', 'Hushakert'),
(4062012, 4062, 'AM04062', 'AM04062012', 'Այգեվան', 'Aygevan'),
(4063012, 4063, 'AM04063', 'AM04063012', 'Մարգարա', 'Margara'),
(4064012, 4064, 'AM04064', 'AM04064012', 'Մեծամոր (գյուղ)', 'Metsamor (village)'),
(4065012, 4065, 'AM04065', 'AM04065012', 'Մերձավան', 'Merdzavan'),
(4066012, 4066, 'AM04066', 'AM04066012', 'Մյասնիկյան', 'Myasnikyan'),
(4067012, 4067, 'AM04067', 'AM04067012', 'Մրգաշատ', 'Mrgashat'),
(4068012, 4068, 'AM04068', 'AM04068012', 'Մրգաստան', 'Mrgastan'),
(4069012, 4069, 'AM04069', 'AM04069012', 'Մուսալեռ', 'Musaler'),
(4070012, 4070, 'AM04070', 'AM04070012', 'Նալբանդյան', 'Nalbandyan'),
(4071012, 4071, 'AM04071', 'AM04071012', 'Նոր Արմավիր', 'Nor Armavir'),
(4072012, 4072, 'AM04072', 'AM04072012', 'Նոր Արտագերս', 'Nor Artagers'),
(4073012, 4073, 'AM04073', 'AM04073012', 'Նոր Կեսարիա', 'Nor Kesaria'),
(4074012, 4074, 'AM04074', 'AM04074012', 'Նորակերտ', 'Norakert'),
(4075012, 4075, 'AM04075', 'AM04075012', 'Նորապատ', 'Norapat'),
(4076012, 4076, 'AM04076', 'AM04076012', 'Նորավան', 'Noravan'),
(4077012, 4077, 'AM04077', 'AM04077012', 'Շահումյան', 'Shahumyan'),
(4078012, 4078, 'AM04078', 'AM04078012', 'Շահումյանի թռչնաֆաբրիկա', 'Shahumyan poultry farm'),
(4079012, 4079, 'AM04079', 'AM04079012', 'Շենավան', 'Shenavan'),
(4080012, 4080, 'AM04080', 'AM04080012', 'Շենիկ', 'Shenik'),
(4081012, 4081, 'AM04081', 'AM04081012', 'Ոսկեհատ', 'Voskehat'),
(4082012, 4082, 'AM04082', 'AM04082012', 'Պտղունք', 'Ptghunk'),
(4083012, 4083, 'AM04083', 'AM04083012', 'Ջանֆիդա', 'Janfida'),
(4084012, 4084, 'AM04084', 'AM04084012', 'Ջրաշեն', 'Jrashen'),
(4085012, 4085, 'AM04085', 'AM04085012', 'Ջրառատ', 'Jrarat'),
(4086012, 4086, 'AM04086', 'AM04086012', 'Ջրարբի', 'Jrarbi'),
(4087012, 4087, 'AM04087', 'AM04087012', 'Գեղակերտ', 'Geghakert'),
(4088012, 4088, 'AM04088', 'AM04088012', 'Ալաշկերտ', 'Alashkert'),
(4089012, 4089, 'AM04089', 'AM04089012', 'Վանանդ', 'Vanand'),
(4090012, 4090, 'AM04090', 'AM04090012', 'Վարդանաշեն', 'Vardanashen'),
(4091012, 4091, 'AM04091', 'AM04091012', 'Տալվորիկ', 'Talvorik'),
(4092012, 4092, 'AM04092', 'AM04092012', 'Տանձուտ', 'Tandzut'),
(4093012, 4093, 'AM04093', 'AM04093012', 'Տարոնիկ', 'Taronik'),
(4094012, 4094, 'AM04094', 'AM04094012', 'Փարաքար', 'Parakar'),
(4094022, 4094, 'AM04094', 'AM04094022', 'Թաիրով', 'Tairov'),
(4095012, 4095, 'AM04095', 'AM04095012', 'Փշատավան', 'Pshatavan'),
(4096012, 4096, 'AM04096', 'AM04096012', 'Քարակերտ', 'Karakert'),
(4097012, 4097, 'AM04097', 'AM04097012', 'Ֆերիկ', 'Ferik'),
(5001011, 5001, 'AM05001', 'AM05001011', 'Գավառ', 'Gavar'),
(5002011, 5002, 'AM05002', 'AM05002011', 'Ճամբարակ', 'Chambarak'),
(5002022, 5002, 'AM05002', 'AM05002022', 'Այգուտ', 'Aygut'),
(5002032, 5002, 'AM05002', 'AM05002032', 'Անտառաﬔջ', 'Antaramej'),
(5002042, 5002, 'AM05002', 'AM05002042', 'Արծվաշեն', 'Artsvashen'),
(5002052, 5002, 'AM05002', 'AM05002052', 'Բարեպատ', 'Barepat'),
(5002062, 5002, 'AM05002', 'AM05002062', 'Գետիկ', 'Getik'),
(5002072, 5002, 'AM05002', 'AM05002072', 'Դպրաբակ', 'Dprabak'),
(5002082, 5002, 'AM05002', 'AM05002082', 'Թթուջուր', 'Ttujur'),
(5002092, 5002, 'AM05002', 'AM05002092', 'Կալավան', 'Kalavan'),
(5002102, 5002, 'AM05002', 'AM05002102', 'Ձորավանք', 'Dzoravank'),
(5002112, 5002, 'AM05002', 'AM05002112', 'Ճապկուտ', 'Chapkut'),
(5002122, 5002, 'AM05002', 'AM05002122', 'Մարտունի', 'Martuni'),
(5002132, 5002, 'AM05002', 'AM05002132', 'Վահան', 'Vahan'),
(5003011, 5003, 'AM05003', 'AM05003011', 'Մարտունի', 'Martuni'),
(5004011, 5004, 'AM05004', 'AM05004011', 'Սևան', 'Sevan'),
(5004022, 5004, 'AM05004', 'AM05004022', 'Գագարին', 'Gagarin'),
(5005011, 5005, 'AM05005', 'AM05005011', 'Վարդենիս', 'Vardenis'),
(5005022, 5005, 'AM05005', 'AM05005022', 'Այրք', 'Ayrk'),
(5005032, 5005, 'AM05005', 'AM05005032', 'Ներքին Շորժա', 'Nerkin Shorzha'),
(5005042, 5005, 'AM05005', 'AM05005042', 'Վերին Շորժա', 'Verin Shorzha'),
(5007012, 5007, 'AM05007', 'AM05007012', 'Ախպրաձոր', 'Akhpradzor'),
(5008012, 5008, 'AM05008', 'AM05008012', 'Ակունք', 'Akunk'),
(5013012, 5013, 'AM05013', 'AM05013012', 'Աստղաձոր', 'Astghadzor'),
(5016012, 5016, 'AM05016', 'AM05016012', 'Արծվանիստ', 'Artsvanist'),
(5020012, 5020, 'AM05020', 'AM05020012', 'Բերդկունք', 'Berdkunk'),
(5021012, 5021, 'AM05021', 'AM05021012', 'Գանձակ', 'Gandzak'),
(5023012, 5023, 'AM05023', 'AM05023012', 'Սոթք', 'Sotk'),
(5023022, 5023, 'AM05023', 'AM05023022', 'Ազատ', 'Azat'),
(5023032, 5023, 'AM05023', 'AM05023032', 'Ավազան', 'Avazan'),
(5023042, 5023, 'AM05023', 'AM05023042', 'Արեգունի', 'Areguni'),
(5023052, 5023, 'AM05023', 'AM05023052', 'Արփունք', 'Arpunk'),
(5023062, 5023, 'AM05023', 'AM05023062', 'Գեղամաբակ', 'Geghamabak'),
(5023072, 5023, 'AM05023', 'AM05023072', 'Գեղամասար', 'Geghamasar'),
(5023082, 5023, 'AM05023', 'AM05023082', 'Դարանակ', 'Daranak'),
(5023092, 5023, 'AM05023', 'AM05023092', 'Զառիվեր', 'Zariver'),
(5023102, 5023, 'AM05023', 'AM05023102', 'Կախակն', 'Kakhakn'),
(5023112, 5023, 'AM05023', 'AM05023112', 'Կութ', 'Kut'),
(5023122, 5023, 'AM05023', 'AM05023122', 'Կուտական', 'Kutakan'),
(5023132, 5023, 'AM05023', 'AM05023132', 'Նորաբակ', 'Norabak'),
(5023142, 5023, 'AM05023', 'AM05023142', 'Շատջրեք', 'Shatjrek'),
(5023152, 5023, 'AM05023', 'AM05023152', 'Շատվան', 'Shatvan'),
(5023162, 5023, 'AM05023', 'AM05023162', 'Ջաղացաձոր', 'Jaghatsadzor'),
(5023172, 5023, 'AM05023', 'AM05023172', 'Տրետուք', 'Tretuk'),
(5023182, 5023, 'AM05023', 'AM05023182', 'Փամբակ', 'Pambak'),
(5023192, 5023, 'AM05023', 'AM05023192', 'Փոքր Մասրիկ', 'Pokr Masrik'),
(5024012, 5024, 'AM05024', 'AM05024012', 'Գեղամավան', 'Geghamavan'),
(5025012, 5025, 'AM05025', 'AM05025012', 'Գեղարքունիք', 'Gegharkunik'),
(5026012, 5026, 'AM05026', 'AM05026012', 'Գեղաքար', 'Geghakar'),
(5027012, 5027, 'AM05027', 'AM05027012', 'Գեղհովիտ', 'Geghovit'),
(5027022, 5027, 'AM05027', 'AM05027022', 'Լեռնակերտ', 'Lernakert'),
(5027032, 5027, 'AM05027', 'AM05027032', 'Նշխարք', 'Nshkhark'),
(5030012, 5030, 'AM05030', 'AM05030012', 'Դդմաշեն', 'Ddmashen'),
(5033012, 5033, 'AM05033', 'AM05033012', 'Երանոս', 'Yeranos'),
(5034012, 5034, 'AM05034', 'AM05034012', 'Զոլաքար', 'Zolakar'),
(5035012, 5035, 'AM05035', 'AM05035012', 'Զովաբեր', 'Zovaber'),
(5036012, 5036, 'AM05036', 'AM05036012', 'Ծովասար', 'Tsovasar'),
(5038012, 5038, 'AM05038', 'AM05038012', 'Լանջաղբյուր', 'Lanjaghbyur'),
(5039012, 5039, 'AM05039', 'AM05039012', 'Լիճք', 'Lichk'),
(5040012, 5040, 'AM05040', 'AM05040012', 'Լճաշեն', 'Lchashen'),
(5041012, 5041, 'AM05041', 'AM05041012', 'Լճավան', 'Lchavan'),
(5042012, 5042, 'AM05042', 'AM05042012', 'Լճափ', 'Lchap'),
(5043012, 5043, 'AM05043', 'AM05043012', 'Լուսակունք', 'Lusakunk'),
(5044012, 5044, 'AM05044', 'AM05044012', 'Խաչաղբյուր', 'Khachaghbyur'),
(5045012, 5045, 'AM05045', 'AM05045012', 'Ծակքար', 'Tsakkar'),
(5046012, 5046, 'AM05046', 'AM05046012', 'Ծաղկաշեն', 'Tsaghkashen'),
(5047012, 5047, 'AM05047', 'AM05047012', 'Ծաղկունք', 'Tsaghkunk'),
(5049012, 5049, 'AM05049', 'AM05049012', 'Ծովագյուղ', 'Tsovagyugh'),
(5050012, 5050, 'AM05050', 'AM05050012', 'Ծովազարդ', 'Tsovazard'),
(5051012, 5051, 'AM05051', 'AM05051012', 'Ծովակ', 'Tsovak'),
(5052012, 5052, 'AM05052', 'AM05052012', 'Ծովինար', 'Tsovinar'),
(5055012, 5055, 'AM05055', 'AM05055012', 'Կարճաղբյուր', 'Karchaghbyur'),
(5056012, 5056, 'AM05056', 'AM05056012', 'Կարﬕրգյուղ', 'Karmirgyugh'),
(5059012, 5059, 'AM05059', 'AM05059012', 'Հայրավանք', 'Hayravank'),
(5060012, 5060, 'AM05060', 'AM05060012', 'Ձորագյուղ', 'Dzoragyugh'),
(5062012, 5062, 'AM05062', 'AM05062012', 'Մադինա', 'Madina'),
(5064012, 5064, 'AM05064', 'AM05064012', 'Մաքենիս', 'Makenis'),
(5065012, 5065, 'AM05065', 'AM05065012', 'Մեծ Մասրիկ', 'Mets Masrik'),
(5066012, 5066, 'AM05066', 'AM05066012', 'Ներքին Գետաշեն', 'Nerkin Getashen'),
(5069012, 5069, 'AM05069', 'AM05069012', 'Նորակերտ', 'Norakert'),
(5070012, 5070, 'AM05070', 'AM05070012', 'Նորաշեն', 'Norashen'),
(5071012, 5071, 'AM05071', 'AM05071012', 'Նորատուս', 'Noratus'),
(5074012, 5074, 'AM05074', 'AM05074012', 'Շողակաթ', 'Shoghakat'),
(5074022, 5074, 'AM05074', 'AM05074022', 'Արտանիշ', 'Artanish'),
(5074032, 5074, 'AM05074', 'AM05074032', 'Աղբերք', 'Aghberk'),
(5074042, 5074, 'AM05074', 'AM05074042', 'Դրախտիկ', 'Drakhtik'),
(5074052, 5074, 'AM05074', 'AM05074052', 'Ծափաթաղ', 'Tsapatagh'),
(5074062, 5074, 'AM05074', 'AM05074062', 'Ջիլ', 'Jil'),
(5075012, 5075, 'AM05075', 'AM05075012', 'Չկալովկա', 'Chkalovka'),
(5078012, 5078, 'AM05078', 'AM05078012', 'Սարուխան', 'Sarukhan'),
(5079012, 5079, 'AM05079', 'AM05079012', 'Սեմյոնովկա', 'Semyonovka'),
(5082012, 5082, 'AM05082', 'AM05082012', 'Վաղաշեն', 'Vaghashen'),
(5083012, 5083, 'AM05083', 'AM05083012', 'Վանևան', 'Vanevan'),
(5084012, 5084, 'AM05084', 'AM05084012', 'Վարդաձոր', 'Vardadzor'),
(5085012, 5085, 'AM05085', 'AM05085012', 'Վարդենիկ', 'Vardenik'),
(5086012, 5086, 'AM05086', 'AM05086012', 'Վարսեր', 'Varser'),
(5087012, 5087, 'AM05087', 'AM05087012', 'Վերին Գետաշեն', 'Verin Getashen'),
(5089012, 5089, 'AM05089', 'AM05089012', 'Տորֆավան', 'Torfavan'),
(6001011, 6001, 'AM06001', 'AM06001011', 'Վանաձոր', 'Vanadzor'),
(6002011, 6002, 'AM06002', 'AM06002011', 'Ալավերդի', 'Alaverdi'),
(6002022, 6002, 'AM06002', 'AM06002022', 'Ակներ', 'Akner'),
(6002032, 6002, 'AM06002', 'AM06002032', 'Աքորի', 'Akori'),
(6002042, 6002, 'AM06002', 'AM06002042', 'Ծաղկաշատ', 'Tsaghkashat'),
(6002052, 6002, 'AM06002', 'AM06002052', 'Կաճաճկուտ', 'Kachachkut'),
(6002062, 6002, 'AM06002', 'AM06002062', 'Հաղպատ', 'Haghpat'),
(6002072, 6002, 'AM06002', 'AM06002072', 'Ջիլիզա', 'Jiliza'),
(6003011, 6003, 'AM06003', 'AM06003011', 'Ախթալա (քաղաք)', 'Akhtala (town)'),
(6003021, 6003, 'AM06003', 'AM06003021', 'Շամլուղ', 'Shamlugh'),
(6003032, 6003, 'AM06003', 'AM06003032', 'Ախթալա (գյուղ)', 'Akhtala (village)'),
(6003042, 6003, 'AM06003', 'AM06003042', 'Առողջարանին կից', 'By sanatorium'),
(6003052, 6003, 'AM06003', 'AM06003052', 'Բենդիկ', 'Bendik'),
(6003062, 6003, 'AM06003', 'AM06003062', 'Ճոճկան', 'Chochkan'),
(6003072, 6003, 'AM06003', 'AM06003072', 'Մեծ Այրում', 'Mets Ayrum'),
(6003082, 6003, 'AM06003', 'AM06003082', 'Նեղոց', 'Neghots'),
(6003092, 6003, 'AM06003', 'AM06003092', 'Փոքր Այրում', 'Pokr Ayrum'),
(6004011, 6004, 'AM06004', 'AM06004011', 'Թումանյան', 'Tumanyan'),
(6004022, 6004, 'AM06004', 'AM06004022', 'Մարց', 'Marts'),
(6004032, 6004, 'AM06004', 'AM06004032', 'Քարինջ', 'Karinj'),
(6004042, 6004, 'AM06004', 'AM06004042', 'Լորուտ', 'Lorut'),
(6004052, 6004, 'AM06004', 'AM06004052', 'Շամուտ', 'Shamut'),
(6004062, 6004, 'AM06004', 'AM06004062', 'Աթան', 'Atan'),
(6004072, 6004, 'AM06004', 'AM06004072', 'Ահնիձոր', 'Ahnidzor'),
(6004082, 6004, 'AM06004', 'AM06004082', 'Քոբեր կայարանի գյուղ', 'Kober station'),
(6006011, 6006, 'AM06006', 'AM06006011', 'Սպիտակ', 'Spitak'),
(6007011, 6007, 'AM06007', 'AM06007011', 'Ստեփանավան', 'Stepanavan'),
(6007022, 6007, 'AM06007', 'AM06007022', 'Արմանիս', 'Armanis'),
(6007032, 6007, 'AM06007', 'AM06007032', 'Կաթնաղբյուր', 'Katnaghbyur'),
(6007042, 6007, 'AM06007', 'AM06007042', 'Ուրասար', 'Urasar'),
(6008011, 6008, 'AM06008', 'AM06008011', 'Տաշիր', 'Tashir'),
(6008022, 6008, 'AM06008', 'AM06008022', 'Բլագոդարնոյե', 'Blagodarnoye'),
(6008032, 6008, 'AM06008', 'AM06008032', 'Դաշտադեմ', 'Dashtadem'),
(6008042, 6008, 'AM06008', 'AM06008042', 'Լեռնահովիտ', 'Lernahovit'),
(6008052, 6008, 'AM06008', 'AM06008052', 'Կաթնառատ', 'Katnarat'),
(6008062, 6008, 'AM06008', 'AM06008062', 'Մեդովկա', 'Medovka'),
(6008072, 6008, 'AM06008', 'AM06008072', 'Կրուգլայա շիշկա', 'Kruglaya Shishka'),
(6008082, 6008, 'AM06008', 'AM06008082', 'Մեղվահովիտ', 'Meghvahovit'),
(6008092, 6008, 'AM06008', 'AM06008092', 'Նորամուտ', 'Noramut'),
(6008102, 6008, 'AM06008', 'AM06008102', 'Նովոսելցովո', 'Novoseltsovo'),
(6008112, 6008, 'AM06008', 'AM06008112', 'Սարատովկա', 'Saratovka'),
(6008122, 6008, 'AM06008', 'AM06008122', 'Գետավան', 'Getavan'),
(6010012, 6010, 'AM06010', 'AM06010012', 'Ազնվաձոր', 'Aznvadzor'),
(6015012, 6015, 'AM06015', 'AM06015012', 'Անտառամուտ', 'Antaramut'),
(6019012, 6019, 'AM06019', 'AM06019012', 'Արջուտ', 'Arjut'),
(6019022, 6019, 'AM06019', 'AM06019022', 'Արջուտ կայարանին կից', 'By Arjut station'),
(6021012, 6021, 'AM06021', 'AM06021012', 'Արևաշող', 'Arevashogh'),
(6023012, 6023, 'AM06023', 'AM06023012', 'Բազում', 'Bazum'),
(6026012, 6026, 'AM06026', 'AM06026012', 'Անտառաշեն', 'Antarashen'),
(6028012, 6028, 'AM06028', 'AM06028012', 'Գեղասար', 'Geghasar'),
(6029012, 6029, 'AM06029', 'AM06029012', 'Գյուլագարակ', 'Gyulagarak'),
(6029022, 6029, 'AM06029', 'AM06029022', 'Ամրակից', 'Amrakits'),
(6029032, 6029, 'AM06029', 'AM06029032', 'Գարգառ', 'Gargar'),
(6029042, 6029, 'AM06029', 'AM06029042', 'Կուրթան', 'Kurtan'),
(6029052, 6029, 'AM06029', 'AM06029052', 'Հոբարձի', 'Hobardz'),
(6029062, 6029, 'AM06029', 'AM06029062', 'Պուշկինո', 'Pushkino'),
(6029072, 6029, 'AM06029', 'AM06029072', 'Վարդաբլուր', 'Vardablur'),
(6030012, 6030, 'AM06030', 'AM06030012', 'Գոգարան', 'Gogaran'),
(6031012, 6031, 'AM06031', 'AM06031012', 'Գուգարք', 'Gugark'),
(6033012, 6033, 'AM06033', 'AM06033012', 'Դարպաս', 'Darpas'),
(6034012, 6034, 'AM06034', 'AM06034012', 'Դեբետ', 'Debet'),
(6035012, 6035, 'AM06035', 'AM06035012', 'Դսեղ', 'Dsegh'),
(6036012, 6036, 'AM06036', 'AM06036012', 'Եղեգնուտ', 'Yeghegnut'),
(6040012, 6040, 'AM06040', 'AM06040012', 'Լեռնանցք', 'Lernantsk'),
(6041012, 6041, 'AM06041', 'AM06041012', 'Լեռնապատ', 'Lernapat'),
(6042012, 6042, 'AM06042', 'AM06042012', 'Լեռնավան', 'Lernavan'),
(6043012, 6043, 'AM06043', 'AM06043012', 'Լերմոնտովո', 'Lermontovo'),
(6044012, 6044, 'AM06044', 'AM06044012', 'Լոռի Բերդ', 'Lori Berd'),
(6044022, 6044, 'AM06044', 'AM06044022', 'Ագարակ', 'Agarak'),
(6044032, 6044, 'AM06044', 'AM06044032', 'Բովաձոր', 'Bovadzor'),
(6044042, 6044, 'AM06044', 'AM06044042', 'Լեջան', 'Lejan'),
(6044052, 6044, 'AM06044', 'AM06044052', 'Կողես', 'Koghes'),
(6044062, 6044, 'AM06044', 'AM06044062', 'Հոﬖանաձոր', 'Hovnanadzor'),
(6044072, 6044, 'AM06044', 'AM06044072', 'Յաղդան', 'Yaghdan'),
(6044082, 6044, 'AM06044', 'AM06044082', 'Սվերդլով', 'Sverdlov'),
(6044092, 6044, 'AM06044', 'AM06044092', 'Ուռուտ', 'Urut'),
(6046012, 6046, 'AM06046', 'AM06046012', 'Լուսաղբյուր', 'Lusaghbyur'),
(6047012, 6047, 'AM06047', 'AM06047012', 'Խնկոյան', 'Khnkoyan'),
(6049012, 6049, 'AM06049', 'AM06049012', 'Ծաղկաբեր', 'Tsaghkaber'),
(6052012, 6052, 'AM06052', 'AM06052012', 'Կաթնաջուր', 'Katnajur'),
(6059012, 6059, 'AM06059', 'AM06059012', 'Հալավար', 'Halavar'),
(6059022, 6059, 'AM06059', 'AM06059022', 'Գյուլլուդարա', 'Gyulludara'),
(6059032, 6059, 'AM06059', 'AM06059032', 'Հայդարլի', 'Haydarli'),
(6059042, 6059, 'AM06059', 'AM06059042', 'Քիլիսա', 'Kilisa'),
(6061012, 6061, 'AM06061', 'AM06061012', 'Հարթագյուղ', 'Hartagyugh'),
(6065012, 6065, 'AM06065', 'AM06065012', 'Ձորագետ', 'Dzoraget'),
(6066012, 6066, 'AM06066', 'AM06066012', 'Ձորագյուղ', 'Dzoragyugh'),
(6068012, 6068, 'AM06068', 'AM06068012', 'Ղուրսալ', 'Ghursal'),
(6070012, 6070, 'AM06070', 'AM06070012', 'Մարգահովիտ', 'Margahovit'),
(6074012, 6074, 'AM06074', 'AM06074012', 'Մեծավան', 'Metsavan'),
(6074022, 6074, 'AM06074', 'AM06074022', 'Ձյունաշող', 'Dzyunashogh'),
(6074032, 6074, 'AM06074', 'AM06074032', 'Միխայլովկա', 'Mikhailovka'),
(6074042, 6074, 'AM06074', 'AM06074042', 'Պաղաղբյուր', 'Paghaghbyur'),
(6075012, 6075, 'AM06075', 'AM06075012', 'Մեծ Պարնի', 'Mets Parni'),
(6083012, 6083, 'AM06083', 'AM06083012', 'Նոր Խաչակապ', 'Nor Khachakap'),
(6084012, 6084, 'AM06084', 'AM06084012', 'Շահումյան', 'Shahumyan'),
(6086012, 6086, 'AM06086', 'AM06086012', 'Շենավան', 'Shenavan'),
(6087012, 6087, 'AM06087', 'AM06087012', 'Շիրակամուտ', 'Shirakamut'),
(6088012, 6088, 'AM06088', 'AM06088012', 'Շնող', 'Shnogh'),
(6088022, 6088, 'AM06088', 'AM06088022', 'Թեղուտ', 'Teghut'),
(6088032, 6088, 'AM06088', 'AM06088032', 'Քարկոփ', 'Karkop'),
(6089012, 6089, 'AM06089', 'AM06089012', 'Չկալով', 'Chkalov'),
(6095012, 6095, 'AM06095', 'AM06095012', 'Ջրաշեն', 'Jrashen'),
(6096012, 6096, 'AM06096', 'AM06096012', 'Սարալանջ', 'Saralanj'),
(6097012, 6097, 'AM06097', 'AM06097012', 'Սարահարթ', 'Sarahart'),
(6098012, 6098, 'AM06098', 'AM06098012', 'Սարաﬔջ', 'Saramej'),
(6100012, 6100, 'AM06100', 'AM06100012', 'Սարչապետ', 'Sarchapet'),
(6100022, 6100, 'AM06100', 'AM06100022', 'Ապավեն', 'Apaven'),
(6100032, 6100, 'AM06100', 'AM06100032', 'Արծնի', 'Artsni'),
(6100042, 6100, 'AM06100', 'AM06100042', 'Ձորամուտ', 'Dzoramut'),
(6100052, 6100, 'AM06100', 'AM06100052', 'Գոգավան', 'Gogavan'),
(6100062, 6100, 'AM06100', 'AM06100062', 'Պետրովկա', 'Petrovka'),
(6100072, 6100, 'AM06100', 'AM06100072', 'Պրիվոլնոյե', 'Privolnoye'),
(6100082, 6100, 'AM06100', 'AM06100082', 'Նորաշեն', 'Norashen'),
(6102012, 6102, 'AM06102', 'AM06102012', 'Վահագնաձոր', 'Vahagnadzor'),
(6103012, 6103, 'AM06103', 'AM06103012', 'Վահագնի', 'Vahagni'),
(6107012, 6107, 'AM06107', 'AM06107012', 'Փամբակ', 'Pambak'),
(6107022, 6107, 'AM06107', 'AM06107022', 'Փամբակ կայարանին կից', 'By Pambak station'),
(6108012, 6108, 'AM06108', 'AM06108012', 'Քարաբերդ', 'Karaberd'),
(6109012, 6109, 'AM06109', 'AM06109012', 'Քարաձոր', 'Karadzor'),
(6112012, 6112, 'AM06112', 'AM06112012', 'Օձուն', 'Odzun'),
(6112022, 6112, 'AM06112', 'AM06112022', 'Ամոջ', 'Amoj'),
(6112032, 6112, 'AM06112', 'AM06112032', 'Այգեհատ', 'Aygehat'),
(6112042, 6112, 'AM06112', 'AM06112042', 'Արդվի', 'Ardvi'),
(6112052, 6112, 'AM06112', 'AM06112052', 'Արևածագ', 'Arevatsag'),
(6112062, 6112, 'AM06112', 'AM06112062', 'Ծաթեր', 'Tsater'),
(6112072, 6112, 'AM06112', 'AM06112072', 'Կարﬕր Աղեկ', 'Karmir Aghek'),
(6112082, 6112, 'AM06112', 'AM06112082', 'Հագվի', 'Hagvi'),
(6112092, 6112, 'AM06112', 'AM06112092', 'Մղարթ', 'Mghart'),
(6113012, 6113, 'AM06113', 'AM06113012', 'Ֆիոլետովո', 'Fioletovo'),
(7001011, 7001, 'AM07001', 'AM07001011', 'Հրազդան', 'Hrazdan'),
(7002011, 7002, 'AM07002', 'AM07002011', 'Աբովյան', 'Abovyan'),
(7003011, 7003, 'AM07003', 'AM07003011', 'Բյուրեղավան', 'Byureghavan'),
(7003022, 7003, 'AM07003', 'AM07003022', 'Ջրաբեր', 'Jraber'),
(7003032, 7003, 'AM07003', 'AM07003032', 'Նուռնուս', 'Nurnus'),
(7004011, 7004, 'AM07004', 'AM07004011', 'Եղվարդ', 'Yeghvard'),
(7004022, 7004, 'AM07004', 'AM07004022', 'Արագյուղ', 'Aragyugh'),
(7004032, 7004, 'AM07004', 'AM07004032', 'Բուժական', 'Buzhakan'),
(7004042, 7004, 'AM07004', 'AM07004042', 'Զովունի', 'Zovuni'),
(7004052, 7004, 'AM07004', 'AM07004052', 'Զորավան', 'Zoravan'),
(7004062, 7004, 'AM07004', 'AM07004062', 'Սարալանջ', 'Saralanj'),
(7005011, 7005, 'AM07005', 'AM07005011', 'Ծաղկաձոր', 'Tsakhkadzor'),
(7006011, 7006, 'AM07006', 'AM07006011', 'Նոր Հաճն', 'Nor Hachn'),
(7007011, 7007, 'AM07007', 'AM07007011', 'Չարենցավան', 'Charentsavan'),
(7007022, 7007, 'AM07007', 'AM07007022', 'Ալափարս', 'Alapars'),
(7007032, 7007, 'AM07007', 'AM07007032', 'Արզական', 'Arzakan'),
(7007042, 7007, 'AM07007', 'AM07007042', 'Բջնի', 'Bjni'),
(7007052, 7007, 'AM07007', 'AM07007052', 'Կարենիս', 'Karenis'),
(7007062, 7007, 'AM07007', 'AM07007062', 'Ֆանտան', 'Fantan'),
(7009012, 7009, 'AM07009', 'AM07009012', 'Ակունք', 'Akunk'),
(7009022, 7009, 'AM07009', 'AM07009022', 'Զառ', 'Zar'),
(7009032, 7009, 'AM07009', 'AM07009032', 'Զովաշեն', 'Zovashen'),
(7009042, 7009, 'AM07009', 'AM07009042', 'Կապուտան', 'Kaputan'),
(7009052, 7009, 'AM07009', 'AM07009052', 'Կոտայք', 'Kotayk'),
(7009062, 7009, 'AM07009', 'AM07009062', 'Հատիս', 'Hatis'),
(7009072, 7009, 'AM07009', 'AM07009072', 'Նոր Գյուղ', 'Nor Gyugh'),
(7009082, 7009, 'AM07009', 'AM07009082', 'Սևաբերդ', 'Sevaberd'),
(7011012, 7011, 'AM07011', 'AM07011012', 'Առինջ', 'Arinj'),
(7013012, 7013, 'AM07013', 'AM07013012', 'Արամուս', 'Aramus'),
(7014012, 7014, 'AM07014', 'AM07014012', 'Արգել', 'Argel'),
(7016012, 7016, 'AM07016', 'AM07016012', 'Արզնի', 'Arzni'),
(7018012, 7018, 'AM07018', 'AM07018012', 'Բալահովիտ', 'Balahovit'),
(7021012, 7021, 'AM07021', 'AM07021012', 'Գառնի', 'Garni'),
(7022012, 7022, 'AM07022', 'AM07022012', 'Գեղադիր', 'Geghadir'),
(7023012, 7023, 'AM07023', 'AM07023012', 'Գեղաշեն', 'Geghashen'),
(7024012, 7024, 'AM07024', 'AM07024012', 'Գեղարդ', 'Geghard'),
(7025012, 7025, 'AM07025', 'AM07025012', 'Գետաﬔջ', 'Getamej'),
(7026012, 7026, 'AM07026', 'AM07026012', 'Գողթ', 'Goght'),
(7032012, 7032, 'AM07032', 'AM07032012', 'Թեղենիք', 'Teghenik'),
(7033012, 7033, 'AM07033', 'AM07033012', 'Լեռնանիստ', 'Lernanist'),
(7034012, 7034, 'AM07034', 'AM07034012', 'Կաթնաղբյուր', 'Katnaghbyur'),
(7035012, 7035, 'AM07035', 'AM07035012', 'Կամարիս', 'Kamaris'),
(7041012, 7041, 'AM07041', 'AM07041012', 'Հացավան', 'Hatsavan'),
(7043012, 7043, 'AM07043', 'AM07043012', 'Մայակովսկի', 'Mayakovsky'),
(7045012, 7045, 'AM07045', 'AM07045012', 'Մեղրաձոր', 'Meghradzor'),
(7045022, 7045, 'AM07045', 'AM07045022', 'Աղաﬖաձոր', 'Aghavnadzor'),
(7045032, 7045, 'AM07045', 'AM07045032', 'Արտավազ', 'Artavaz'),
(7045042, 7045, 'AM07045', 'AM07045042', 'Գոռգոչ', 'Gorgoch'),
(7045052, 7045, 'AM07045', 'AM07045052', 'Հանքավան', 'Hankavan'),
(7045062, 7045, 'AM07045', 'AM07045062', 'Մարմարիկ', 'Marmarik'),
(7045072, 7045, 'AM07045', 'AM07045072', 'Փյունիկ', 'Pyunik'),
(7046012, 7046, 'AM07046', 'AM07046012', 'Մրգաշեն', 'Mrgashen'),
(7047012, 7047, 'AM07047', 'AM07047012', 'Նոր Արտաﬔտ', 'Nor Artamet'),
(7048012, 7048, 'AM07048', 'AM07048012', 'Նոր Գեղի', 'Nor Geghi'),
(7050012, 7050, 'AM07050', 'AM07050012', 'Նոր Երզնկա', 'Nor Yerznka'),
(7052012, 7052, 'AM07052', 'AM07052012', 'Ողջաբերդ', 'Voghjaberd'),
(7053012, 7053, 'AM07053', 'AM07053012', 'Պռոշյան', 'Proshyan'),
(7054012, 7054, 'AM07054', 'AM07054012', 'Պտղնի', 'Ptghni'),
(7056012, 7056, 'AM07056', 'AM07056012', 'Ջրառատ', 'Jrarat'),
(7057012, 7057, 'AM07057', 'AM07057012', 'Ջրվեժ', 'Jrvezh'),
(7057022, 7057, 'AM07057', 'AM07057022', 'Զովք', 'Zovk'),
(7057032, 7057, 'AM07057', 'AM07057032', 'Ձորաղբյուր', 'Dzoraghbyur'),
(7058012, 7058, 'AM07058', 'AM07058012', 'Գետարգել', 'Getargel'),
(7060012, 7060, 'AM07060', 'AM07060012', 'Սոլակ', 'Solak'),
(7062012, 7062, 'AM07062', 'AM07062012', 'Վերին Պտղնի', 'Verin Ptghni'),
(7063012, 7063, 'AM07063', 'AM07063012', 'Քաղսի', 'Kaghsi'),
(7064012, 7064, 'AM07064', 'AM07064012', 'Քանաքեռավան', 'Kanakeravan'),
(7065012, 7065, 'AM07065', 'AM07065012', 'Քասախ', 'Kasakh'),
(7066012, 7066, 'AM07066', 'AM07066012', 'Քարաշամբ', 'Karashamb'),
(8001011, 8001, 'AM08001', 'AM08001011', 'Գյումրի', 'Gyumri'),
(8002011, 8002, 'AM08002', 'AM08002011', 'Արթիկ', 'Artik'),
(8003011, 8003, 'AM08003', 'AM08003011', 'Մարալիկ', 'Maralik'),
(8003022, 8003, 'AM08003', 'AM08003022', 'Աղին', 'Aghin'),
(8003032, 8003, 'AM08003', 'AM08003032', 'Անիավան', 'Aniavan'),
(8003042, 8003, 'AM08003', 'AM08003042', 'Անիպեմզա', 'Anipemza'),
(8003052, 8003, 'AM08003', 'AM08003052', 'Բագրավան', 'Bagravan'),
(8003062, 8003, 'AM08003', 'AM08003062', 'Բարձրաշեն', 'Bardzrashen'),
(8003072, 8003, 'AM08003', 'AM08003072', 'Գուսանագյուղ', 'Gusanagyugh'),
(8003082, 8003, 'AM08003', 'AM08003082', 'Իսահակյան', 'Isahakyan'),
(8003092, 8003, 'AM08003', 'AM08003092', 'Լանջիկ', 'Lanjik'),
(8003102, 8003, 'AM08003', 'AM08003102', 'Լուսաղբյուր', 'Lusaghbyur'),
(8003112, 8003, 'AM08003', 'AM08003112', 'Հայկաձոր', 'Haykadzor'),
(8003122, 8003, 'AM08003', 'AM08003122', 'Ձիթհանքով', 'Dzithankov'),
(8003132, 8003, 'AM08003', 'AM08003132', 'Ձորակապ', 'Dzorakap'),
(8003142, 8003, 'AM08003', 'AM08003142', 'Շիրակավան', 'Shirakavan'),
(8003152, 8003, 'AM08003', 'AM08003152', 'Նորշեն', 'Norshen'),
(8003162, 8003, 'AM08003', 'AM08003162', 'Ջրափի', 'Jrapi'),
(8003172, 8003, 'AM08003', 'AM08003172', 'Սառնաղբյուր', 'Sarnaghbyur'),
(8003182, 8003, 'AM08003', 'AM08003182', 'Սարակապ', 'Sarakap'),
(8003192, 8003, 'AM08003', 'AM08003192', 'Քարաբերդ', 'Karaberd'),
(8004012, 8004, 'AM08004', 'AM08004012', 'Ազատան', 'Azatan'),
(8006012, 8006, 'AM08006', 'AM08006012', 'Ախուրիկ', 'Akhurik'),
(8007012, 8007, 'AM08007', 'AM08007012', 'Ախուրյան', 'Akhuryan'),
(8007022, 8007, 'AM08007', 'AM08007022', 'Այգաբաց', 'Aygabats'),
(8007032, 8007, 'AM08007', 'AM08007032', 'Արևիկ', 'Arevik'),
(8007042, 8007, 'AM08007', 'AM08007042', 'Բասեն', 'Basen'),
(8007052, 8007, 'AM08007', 'AM08007052', 'Կամո', 'Kamo'),
(8007062, 8007, 'AM08007', 'AM08007062', 'Կառնուտ', 'Karnut'),
(8007072, 8007, 'AM08007', 'AM08007072', 'Հովիտ', 'Hovit'),
(8007082, 8007, 'AM08007', 'AM08007082', 'Ջրառատ', 'Jrarat'),
(8010012, 8010, 'AM08010', 'AM08010012', 'Ամասիա', 'Amasia'),
(8010022, 8010, 'AM08010', 'AM08010022', 'Արեգնադեմ', 'Aregnadem'),
(8010032, 8010, 'AM08010', 'AM08010032', 'Բանդիվան', 'Bandivan'),
(8010042, 8010, 'AM08010', 'AM08010042', 'Բյուրակն', 'Byurakn'),
(8010052, 8010, 'AM08010', 'AM08010052', 'Գտաշեն', 'Gtashen'),
(8010062, 8010, 'AM08010', 'AM08010062', 'Կաﬗուտ', 'Kamkhut'),
(8010072, 8010, 'AM08010', 'AM08010072', 'Հովտուն', 'Hovtun'),
(8010082, 8010, 'AM08010', 'AM08010082', 'Մեղրաշատ', 'Meghrashat'),
(8010092, 8010, 'AM08010', 'AM08010092', 'Ողջի', 'Voghji'),
(8010102, 8010, 'AM08010', 'AM08010102', 'Ջրաձոր', 'Jradzor'),
(8014012, 8014, 'AM08014', 'AM08014012', 'Անուշավան', 'Anushavan'),
(8015012, 8015, 'AM08015', 'AM08015012', 'Աշոցք', 'Ashotsk'),
(8015022, 8015, 'AM08015', 'AM08015022', 'Բավրա', 'Bavra'),
(8015032, 8015, 'AM08015', 'AM08015032', 'Զույգաղբյուր', 'Zuygaghbyur'),
(8015042, 8015, 'AM08015', 'AM08015042', 'Թավշուտ', 'Tavshut'),
(8015052, 8015, 'AM08015', 'AM08015052', 'Կարմրավան', 'Karmravan'),
(8015062, 8015, 'AM08015', 'AM08015062', 'Կրասար', 'Krasar'),
(8015072, 8015, 'AM08015', 'AM08015072', 'Ղազանչի', 'Ghazanchi'),
(8015082, 8015, 'AM08015', 'AM08015082', 'Մեծ Սեպասար', 'Mets Sepasar'),
(8015092, 8015, 'AM08015', 'AM08015092', 'Սարագյուղ', 'Saragyugh'),
(8015102, 8015, 'AM08015', 'AM08015102', 'Սիզավետ', 'Sizavet'),
(8015112, 8015, 'AM08015', 'AM08015112', 'Փոքր Սեպասար', 'Pokr Sepasar'),
(8016012, 8016, 'AM08016', 'AM08016012', 'Առափի', 'Arapi'),
(8021012, 8021, 'AM08021', 'AM08021012', 'Արևշատ', 'Arevshat'),
(8023012, 8023, 'AM08023', 'AM08023012', 'Բայանդուր', 'Bayandur'),
(8027012, 8027, 'AM08027', 'AM08027012', 'Բենիաﬕն', 'Beniamin'),
(8028012, 8028, 'AM08028', 'AM08028012', 'Բերդաշեն', 'Berdashen'),
(8028022, 8028, 'AM08028', 'AM08028022', 'Ալվար', 'Alvar'),
(8028032, 8028, 'AM08028', 'AM08028032', 'Աղվորիկ', 'Aghvorik'),
(8028042, 8028, 'AM08028', 'AM08028042', 'Արավետ', 'Aravet'),
(8028052, 8028, 'AM08028', 'AM08028052', 'Արդենիս', 'Ardenis'),
(8028062, 8028, 'AM08028', 'AM08028062', 'Գառնառիճ', 'Garnarich'),
(8028072, 8028, 'AM08028', 'AM08028072', 'Դարիկ', 'Darik'),
(8028082, 8028, 'AM08028', 'AM08028082', 'Եղնաջուր', 'Yeghnajur'),
(8028092, 8028, 'AM08028', 'AM08028092', 'Երիզակ', 'Yerizak'),
(8028102, 8028, 'AM08028', 'AM08028102', 'Զարիշատ', 'Zarishat'),
(8028112, 8028, 'AM08028', 'AM08028112', 'Զորակերտ', 'Zorakert'),
(8028122, 8028, 'AM08028', 'AM08028122', 'Լորասար', 'Lorasar'),
(8028132, 8028, 'AM08028', 'AM08028132', 'Ծաղկուտ', 'Tsaghkut'),
(8028142, 8028, 'AM08028', 'AM08028142', 'Շաղիկ', 'Shaghik'),
(8028152, 8028, 'AM08028', 'AM08028152', 'Պաղակն', 'Paghakn'),
(8030012, 8030, 'AM08030', 'AM08030012', 'Գեղանիստ', 'Geghanist'),
(8031012, 8031, 'AM08031', 'AM08031012', 'Գետափ', 'Getap'),
(8032012, 8032, 'AM08032', 'AM08032012', 'Գետք', 'Getk'),
(8037012, 8037, 'AM08037', 'AM08037012', 'Երազգավորս', 'Yerazgavors'),
(8046012, 8046, 'AM08046', 'AM08046012', 'Լեռնակերտ', 'Lernakert'),
(8048012, 8048, 'AM08048', 'AM08048012', 'Լուսակերտ', 'Lusakert'),
(8060012, 8060, 'AM08060', 'AM08060012', 'Հայկասար', 'Haykasar'),
(8061012, 8061, 'AM08061', 'AM08061012', 'Հայկավան', 'Haykavan'),
(8062012, 8062, 'AM08062', 'AM08062012', 'Հայրենյաց', 'Hayrenyats'),
(8063012, 8063, 'AM08063', 'AM08063012', 'Հառիճ', 'Harich'),
(8067012, 8067, 'AM08067', 'AM08067012', 'Հոռոմ', 'Horom'),
(8069012, 8069, 'AM08069', 'AM08069012', 'Հովտաշեն', 'Hovtashen'),
(8076012, 8076, 'AM08076', 'AM08076012', 'Ղարիբջանյան', 'Gharibjanyan'),
(8076022, 8076, 'AM08076', 'AM08076022', 'Ախուրյան կայարանի', 'Akhuryan station'),
(8078012, 8078, 'AM08078', 'AM08078012', 'Մայիսյան', 'Mayisyan'),
(8078022, 8078, 'AM08078', 'AM08078022', 'Լեռնուտ', 'Lernut'),
(8078032, 8078, 'AM08078', 'AM08078032', 'Կապս', 'Kaps'),
(8078042, 8078, 'AM08078', 'AM08078042', 'Կարմրաքար', 'Karmrakar'),
(8078052, 8078, 'AM08078', 'AM08078052', 'Կրաշեն', 'Krashen'),
(8078062, 8078, 'AM08078', 'AM08078062', 'Հացիկ', 'Hatsik'),
(8078072, 8078, 'AM08078', 'AM08078072', 'Հացիկավան', 'Hatsikavan'),
(8078082, 8078, 'AM08078', 'AM08078082', 'Հովունի', 'Hovuni'),
(8078092, 8078, 'AM08078', 'AM08078092', 'Մարմաշեն', 'Marmashen'),
(8078102, 8078, 'AM08078', 'AM08078102', 'Մեծ Սարիար', 'Mets Sariar'),
(8078112, 8078, 'AM08078', 'AM08078112', 'Շիրակ', 'Shirak'),
(8078122, 8078, 'AM08078', 'AM08078122', 'Ջաջուռ', 'Jajur'),
(8078132, 8078, 'AM08078', 'AM08078132', 'Ջաջուռավան', 'Jajuravan'),
(8078142, 8078, 'AM08078', 'AM08078142', 'Վահրամաբերդ', 'Vahramaberd'),
(8078152, 8078, 'AM08078', 'AM08078152', 'Փոքրաշեն', 'Pokrashen'),
(8078162, 8078, 'AM08078', 'AM08078162', 'Քեթի', 'Keti'),
(8079012, 8079, 'AM08079', 'AM08079012', 'Մեծ Մանթաշ', 'Mets Mantash'),
(8083012, 8083, 'AM08083', 'AM08083012', 'Մեղրաշեն', 'Meghrashen'),
(8086012, 8086, 'AM08086', 'AM08086012', 'Նահապետավան', 'Nahapetavan'),
(8087012, 8087, 'AM08087', 'AM08087012', 'Նոր կյանք', 'Nor Kyank'),
(8092012, 8092, 'AM08092', 'AM08092012', 'Ոսկեհասկ', 'Voskehask'),
(8093012, 8093, 'AM08093', 'AM08093012', 'Պեմզաշեն', 'Pemzashen'),
(8102012, 8102, 'AM08102', 'AM08102012', 'Սարալանջ', 'Saralanj'),
(8104012, 8104, 'AM08104', 'AM08104012', 'Թորոսգյուղ', 'Torosgyugh'),
(8104022, 8104, 'AM08104', 'AM08104022', 'Արփենի', 'Arpeni'),
(8104032, 8104, 'AM08104', 'AM08104032', 'Բաշգյուղ', 'Bashgyugh'),
(8104042, 8104, 'AM08104', 'AM08104042', 'Գոգհովիտ', 'Goghovit'),
(8104052, 8104, 'AM08104', 'AM08104052', 'Լեռնագյուղ', 'Lernagyugh'),
(8104062, 8104, 'AM08104', 'AM08104062', 'Կաքավասար', 'Kakavasar'),
(8104072, 8104, 'AM08104', 'AM08104072', 'Հարթաշեն', 'Hartashen'),
(8104082, 8104, 'AM08104', 'AM08104082', 'Հողﬕկ', 'Hoghmik'),
(8104092, 8104, 'AM08104', 'AM08104092', 'Ձորաշեն', 'Dzorashen'),
(8104102, 8104, 'AM08104', 'AM08104102', 'Մուսայելյան', 'Musayelyan'),
(8104112, 8104, 'AM08104', 'AM08104112', 'Սալուտ', 'Salut'),
(8104122, 8104, 'AM08104', 'AM08104122', 'Սարապատ', 'Sarapat'),
(8104132, 8104, 'AM08104', 'AM08104132', 'Վարդաղբյուր', 'Vardaghbyur'),
(8104142, 8104, 'AM08104', 'AM08104142', 'Ցողամարգ', 'Tsoghamarg'),
(8104152, 8104, 'AM08104', 'AM08104152', 'Փոքր Սարիար', 'Pokr Sariar'),
(8105012, 8105, 'AM08105', 'AM08105012', 'Սարատակ', 'Saratak'),
(8107012, 8107, 'AM08107', 'AM08107012', 'Սպանդարյան', 'Spandaryan'),
(8110012, 8110, 'AM08110', 'AM08110012', 'Վարդաքար', 'Vardakar'),
(8111012, 8111, 'AM08111', 'AM08111012', 'Տուֆաշեն', 'Tufashen'),
(8113012, 8113, 'AM08113', 'AM08113012', 'Փանիկ', 'Panik'),
(8115012, 8115, 'AM08115', 'AM08115012', 'Փոքր Մանթաշ', 'Pokr Mantash'),
(9001011, 9001, 'AM09001', 'AM09001011', 'Կապան', 'Kapan'),
(9001022, 9001, 'AM09001', 'AM09001022', 'Ագարակ', 'Agarak'),
(9001032, 9001, 'AM09001', 'AM09001032', 'Աղվանի', 'Aghvan'),
(9001042, 9001, 'AM09001', 'AM09001042', 'Աճանան', 'Achanan'),
(9001052, 9001, 'AM09001', 'AM09001052', 'Անտառաշատ', 'Antarashat'),
(9001062, 9001, 'AM09001', 'AM09001062', 'Առաջաձոր', 'Arajadzor'),
(9001072, 9001, 'AM09001', 'AM09001072', 'Արծվանիկ', 'Artsvanik'),
(9001082, 9001, 'AM09001', 'AM09001082', 'Բարգուշատ', 'Bargushat'),
(9001092, 9001, 'AM09001', 'AM09001092', 'Գեղանուշ', 'Geghanush'),
(9001102, 9001, 'AM09001', 'AM09001102', 'Գոմարան', 'Gomaran'),
(9001112, 9001, 'AM09001', 'AM09001112', 'Դավիթ Բեկ', 'Davit Bek'),
(9001122, 9001, 'AM09001', 'AM09001122', 'Դիցմայրի', 'Ditsmayri'),
(9001132, 9001, 'AM09001', 'AM09001132', 'Եղեգ', 'Yegheg'),
(9001142, 9001, 'AM09001', 'AM09001142', 'Եղվարդ', 'Yeghvard'),
(9001152, 9001, 'AM09001', 'AM09001152', 'Խդրանց', 'Khdrants'),
(9001162, 9001, 'AM09001', 'AM09001162', 'Խորձոր', 'Khordzor'),
(9001172, 9001, 'AM09001', 'AM09001172', 'Ծավ', 'Tsav'),
(9001182, 9001, 'AM09001', 'AM09001182', 'Կաղնուտ', 'Kaghnut'),
(9001192, 9001, 'AM09001', 'AM09001192', 'Ձորաստան', 'Dzorastan'),
(9001202, 9001, 'AM09001', 'AM09001202', 'Ճակատեն', 'Chakaten'),
(9001212, 9001, 'AM09001', 'AM09001212', 'Ներքին Խոտանան', 'Nerkin Khotanan'),
(9001222, 9001, 'AM09001', 'AM09001222', 'Ներքին Հանդ', 'Nerkin Hand'),
(9001232, 9001, 'AM09001', 'AM09001232', 'Նորաշենիկ', 'Norashenik'),
(9001242, 9001, 'AM09001', 'AM09001242', 'Շիկահող', 'Shikahogh'),
(9001252, 9001, 'AM09001', 'AM09001252', 'Շիշկերտ', 'Shishkert'),
(9001262, 9001, 'AM09001', 'AM09001262', 'Շրվենանց', 'Shrvenants');
INSERT INTO `tb_settlement` (`settlement_id`, `com_id`, `ADM3_CODE`, `ADM4_PCODE`, `ADM4_ARM`, `ADM4_ENG`) VALUES
(9001272, 9001, 'AM09001', 'AM09001272', 'Չափնի', 'Chapni'),
(9001282, 9001, 'AM09001', 'AM09001282', 'Սզնակ', 'Sznak'),
(9001292, 9001, 'AM09001', 'AM09001292', 'Սյունիք', 'Syunik'),
(9001302, 9001, 'AM09001', 'AM09001302', 'Սրաշեն', 'Srashen'),
(9001312, 9001, 'AM09001', 'AM09001312', 'Սևաքար', 'Sevakar'),
(9001322, 9001, 'AM09001', 'AM09001322', 'Վանեք', 'Vanek'),
(9001332, 9001, 'AM09001', 'AM09001332', 'Վարդավանք', 'Vardavank'),
(9001342, 9001, 'AM09001', 'AM09001342', 'Վերին Խոտանան', 'Verin Khotanan'),
(9001352, 9001, 'AM09001', 'AM09001352', 'Տանձավեր', 'Tandzver'),
(9001362, 9001, 'AM09001', 'AM09001362', 'Տավրուս', 'Tavrus'),
(9001372, 9001, 'AM09001', 'AM09001372', 'Ուժանիս', 'Uzhanis'),
(9001382, 9001, 'AM09001', 'AM09001382', 'Օխտար', 'Okhtar'),
(9003011, 9003, 'AM09003', 'AM09003011', 'Գորիս', 'Goris'),
(9003022, 9003, 'AM09003', 'AM09003022', 'Ակներ', 'Akner'),
(9003032, 9003, 'AM09003', 'AM09003032', 'Աղբուլաղ', 'Aghbulagh'),
(9003042, 9003, 'AM09003', 'AM09003042', 'Բարձրավան', 'Bardzravan'),
(9003052, 9003, 'AM09003', 'AM09003052', 'Խնձորեսկ', 'Khndzoresk'),
(9003062, 9003, 'AM09003', 'AM09003062', 'Հարթաշեն', 'Hartashen'),
(9003072, 9003, 'AM09003', 'AM09003072', 'Ձորակ', 'Dzorak'),
(9003082, 9003, 'AM09003', 'AM09003082', 'Ներքին Խնձորեսկ', 'Nerkin Khndzoresk'),
(9003092, 9003, 'AM09003', 'AM09003092', 'Շուռնուխ', 'Shurnukh'),
(9003102, 9003, 'AM09003', 'AM09003102', 'Որոտան', 'Vorotan'),
(9003112, 9003, 'AM09003', 'AM09003112', 'Վանանդ', 'Vanand'),
(9003122, 9003, 'AM09003', 'AM09003122', 'Վերիշեն', 'Verishen'),
(9003132, 9003, 'AM09003', 'AM09003132', 'Քարահունջ', 'Karahunj'),
(9005011, 9005, 'AM09005', 'AM09005011', 'Մեղրի', 'Meghri'),
(9005021, 9005, 'AM09005', 'AM09005021', 'Ագարակ', 'Agarak'),
(9005032, 9005, 'AM09005', 'AM09005032', 'Ալվանք', 'Alvank'),
(9005042, 9005, 'AM09005', 'AM09005042', 'Այգեձոր', 'Aygedzor'),
(9005052, 9005, 'AM09005', 'AM09005052', 'Գուդեﬓիս', 'Gudemnis'),
(9005062, 9005, 'AM09005', 'AM09005062', 'Թխկուտ', 'Tkhkut'),
(9005072, 9005, 'AM09005', 'AM09005072', 'Լեհվազ', 'Lehvaz'),
(9005082, 9005, 'AM09005', 'AM09005082', 'Լիճք', 'Lichk'),
(9005092, 9005, 'AM09005', 'AM09005092', 'Կարճևան', 'Karchevan'),
(9005102, 9005, 'AM09005', 'AM09005102', 'Կուրիս', 'Kuris'),
(9005112, 9005, 'AM09005', 'AM09005112', 'Նռնաձոր', 'Nrnadzor'),
(9005122, 9005, 'AM09005', 'AM09005122', 'Շվանիձոր', 'Shvanidzor'),
(9005132, 9005, 'AM09005', 'AM09005132', 'Վահրավար', 'Vahravar'),
(9005142, 9005, 'AM09005', 'AM09005142', 'Վարդանիձոր', 'Vardanidzor'),
(9005152, 9005, 'AM09005', 'AM09005152', 'Տաշտուն', 'Tashtun'),
(9006011, 9006, 'AM09006', 'AM09006011', 'Սիսիան', 'Sisian'),
(9006021, 9006, 'AM09006', 'AM09006021', 'Դաստակերտ', 'Dastakert'),
(9006032, 9006, 'AM09006', 'AM09006032', 'Ախլաթյան', 'Akhlatyan'),
(9006042, 9006, 'AM09006', 'AM09006042', 'Աղիտու', 'Aghitu'),
(9006052, 9006, 'AM09006', 'AM09006052', 'Անգեղակոթ', 'Angeghakot'),
(9006062, 9006, 'AM09006', 'AM09006062', 'Աշոտավան', 'Ashotavan'),
(9006072, 9006, 'AM09006', 'AM09006072', 'Արևիս', 'Arevis'),
(9006082, 9006, 'AM09006', 'AM09006082', 'Բալաք', 'Balak'),
(9006092, 9006, 'AM09006', 'AM09006092', 'Բնունիս', 'Bnunis'),
(9006102, 9006, 'AM09006', 'AM09006102', 'Բռնակոթ', 'Brnakot'),
(9006112, 9006, 'AM09006', 'AM09006112', 'Գետաթաղ', 'Getatagh'),
(9006122, 9006, 'AM09006', 'AM09006122', 'Դարբաս', 'Darbas'),
(9006132, 9006, 'AM09006', 'AM09006132', 'Թանահատ', 'Tanahat'),
(9006142, 9006, 'AM09006', 'AM09006142', 'Թասիկ', 'Tasik'),
(9006152, 9006, 'AM09006', 'AM09006152', 'Իշխանասար', 'Ishkhanasar'),
(9006162, 9006, 'AM09006', 'AM09006162', 'Լծեն', 'Ltsen'),
(9006172, 9006, 'AM09006', 'AM09006172', 'Լոր', 'Lor'),
(9006182, 9006, 'AM09006', 'AM09006182', 'Հացավան', 'Hatsavan'),
(9006192, 9006, 'AM09006', 'AM09006192', 'Մուցք', 'Mutsk'),
(9006202, 9006, 'AM09006', 'AM09006202', 'Նժդեհ', 'Nzhdeh'),
(9006212, 9006, 'AM09006', 'AM09006212', 'Նորավան', 'Noravan'),
(9006222, 9006, 'AM09006', 'AM09006222', 'Շաղատ', 'Shaghat'),
(9006232, 9006, 'AM09006', 'AM09006232', 'Շամբ', 'Shamb'),
(9006242, 9006, 'AM09006', 'AM09006242', 'Շաքի', 'Shaki'),
(9006252, 9006, 'AM09006', 'AM09006252', 'Շենաթաղ', 'Shenatagh'),
(9006262, 9006, 'AM09006', 'AM09006262', 'Որոտնավան', 'Vorotnavan'),
(9006272, 9006, 'AM09006', 'AM09006272', 'Սալվարդ', 'Salvard'),
(9006282, 9006, 'AM09006', 'AM09006282', 'Վաղատին', 'Vaghatin'),
(9006292, 9006, 'AM09006', 'AM09006292', 'Տոլորս', 'Tolors'),
(9006302, 9006, 'AM09006', 'AM09006302', 'Տորունիք', 'Torunik'),
(9006312, 9006, 'AM09006', 'AM09006312', 'Ցղունի', 'Tsghuni'),
(9006322, 9006, 'AM09006', 'AM09006322', 'Ույծ', 'Uyts'),
(9007011, 9007, 'AM09007', 'AM09007011', 'Քաջարան', 'Kajaran'),
(9007022, 9007, 'AM09007', 'AM09007022', 'Անդոկավան', 'Andokavan'),
(9007032, 9007, 'AM09007', 'AM09007032', 'Աջաբաջ', 'Ajabaj'),
(9007042, 9007, 'AM09007', 'AM09007042', 'Բաբիկավան', 'Babikavan'),
(9007052, 9007, 'AM09007', 'AM09007052', 'Գեղավանք', 'Geghavank'),
(9007062, 9007, 'AM09007', 'AM09007062', 'Գեղի', 'Geghi'),
(9007072, 9007, 'AM09007', 'AM09007072', 'Գետիշեն', 'Getishen'),
(9007082, 9007, 'AM09007', 'AM09007082', 'Լեռնաձոր', 'Lernadzor'),
(9007092, 9007, 'AM09007', 'AM09007092', 'Կաթնառատ', 'Katnarat'),
(9007102, 9007, 'AM09007', 'AM09007102', 'Կավճուտ', 'Kavchut'),
(9007112, 9007, 'AM09007', 'AM09007112', 'Կարդ', 'Kard'),
(9007122, 9007, 'AM09007', 'AM09007122', 'Կիցք', 'Kitsk'),
(9007132, 9007, 'AM09007', 'AM09007132', 'Ձագիկավան', 'Dzagikavan'),
(9007142, 9007, 'AM09007', 'AM09007142', 'Ներքին Գիրաթաղ', 'Nerkin Giratagh'),
(9007152, 9007, 'AM09007', 'AM09007152', 'Նոր Աստղաբերդ', 'Nor Astghaberd'),
(9007162, 9007, 'AM09007', 'AM09007162', 'Ոչեթի', 'Vocheti'),
(9007172, 9007, 'AM09007', 'AM09007172', 'Վերին Գեղավանք', 'Verin Geghavank'),
(9007182, 9007, 'AM09007', 'AM09007182', 'Վերին Գիրաթաղ', 'Verin Giratagh'),
(9007192, 9007, 'AM09007', 'AM09007192', 'Փուխրուտ', 'Pukhrut'),
(9007202, 9007, 'AM09007', 'AM09007202', 'Քաջարանց', 'Kajarants'),
(9007212, 9007, 'AM09007', 'AM09007212', 'Քարուտ', 'Karut'),
(9028012, 9028, 'AM09028', 'AM09028012', 'Գորայք', 'Gorayk'),
(9028022, 9028, 'AM09028', 'AM09028022', 'Ծղուկ', 'Tsghuk'),
(9028032, 9028, 'AM09028', 'AM09028032', 'Սառնակունք', 'Sarnakunk'),
(9028042, 9028, 'AM09028', 'AM09028042', 'Սպանդարյան', 'Spandaryan'),
(9097012, 9097, 'AM09097', 'AM09097012', 'Շինուհայր', 'Shinuhayr'),
(9097022, 9097, 'AM09097', 'AM09097022', 'Տաթև', 'Tatev'),
(9097032, 9097, 'AM09097', 'AM09097032', 'Հալիձոր', 'Halidzor'),
(9097042, 9097, 'AM09097', 'AM09097042', 'Հարժիս', 'Harzhis'),
(9097052, 9097, 'AM09097', 'AM09097052', 'Սվարանց', 'Svarants'),
(9097062, 9097, 'AM09097', 'AM09097062', 'Խոտ', 'Khot'),
(9097072, 9097, 'AM09097', 'AM09097072', 'Տանձատափ', 'Tandzatap'),
(9097082, 9097, 'AM09097', 'AM09097082', 'Քաշունի', 'Kashuni'),
(9101012, 9101, 'AM09101', 'AM09101012', 'Տեղ', 'Tegh'),
(9101022, 9101, 'AM09101', 'AM09101022', 'Արավուս', 'Aravus'),
(9101032, 9101, 'AM09101', 'AM09101032', 'Խնածախ', 'Khnatsakh'),
(9101042, 9101, 'AM09101', 'AM09101042', 'Խոզնավար', 'Khoznavar'),
(9101052, 9101, 'AM09101', 'AM09101052', 'Կոռնիձոր', 'Kornidzor'),
(9101062, 9101, 'AM09101', 'AM09101062', 'Վաղատուր', 'Vaghatur'),
(9101072, 9101, 'AM09101', 'AM09101072', 'Քարաշեն', 'Karashen'),
(10001011, 10001, 'AM10001', 'AM10001011', 'Եղեգնաձոր', 'Yeghegnadzor'),
(10002011, 10002, 'AM10002', 'AM10002011', 'Ջերմուկ', 'Jermuk'),
(10002022, 10002, 'AM10002', 'AM10002022', 'Գնդեվազ', 'Gndevaz'),
(10002032, 10002, 'AM10002', 'AM10002032', 'Կարմրաշեն', 'Karmrashen'),
(10002042, 10002, 'AM10002', 'AM10002042', 'Կեչուտ', 'Kechut'),
(10002052, 10002, 'AM10002', 'AM10002052', 'Հերհեր', 'Herher'),
(10003011, 10003, 'AM10003', 'AM10003011', 'Վայք', 'Vayk'),
(10003022, 10003, 'AM10003', 'AM10003022', 'Ազատեկ', 'Azatek'),
(10003032, 10003, 'AM10003', 'AM10003032', 'Արին', 'Arin'),
(10003042, 10003, 'AM10003', 'AM10003042', 'Զեդեա', 'Zedea'),
(10003052, 10003, 'AM10003', 'AM10003052', 'Հորադիս', 'Horadis'),
(10003062, 10003, 'AM10003', 'AM10003062', 'Փոռ', 'Por'),
(10008012, 10008, 'AM10008', 'AM10008012', 'Արենի', 'Areni'),
(10008022, 10008, 'AM10008', 'AM10008022', 'Ագարակաձոր', 'Agarakadzor'),
(10008032, 10008, 'AM10008', 'AM10008032', 'Աղաﬖաձոր', 'Aghavnadzor'),
(10008042, 10008, 'AM10008', 'AM10008042', 'Արփի', 'Arpi'),
(10008052, 10008, 'AM10008', 'AM10008052', 'Ամաղու', 'Amaghu'),
(10008062, 10008, 'AM10008', 'AM10008062', 'Գնիշիկ', 'Gnishik'),
(10008072, 10008, 'AM10008', 'AM10008072', 'Ելփին', 'Yelpin'),
(10008082, 10008, 'AM10008', 'AM10008082', 'Խաչիկ', 'Khachik'),
(10008092, 10008, 'AM10008', 'AM10008092', 'Մոզրով', 'Mozrov'),
(10008102, 10008, 'AM10008', 'AM10008102', 'Չիվա', 'Chiva'),
(10008112, 10008, 'AM10008', 'AM10008112', 'Ռինդ', 'Rind'),
(10015012, 10015, 'AM10015', 'AM10015012', 'Գլաձոր', 'Gladzor'),
(10015022, 10015, 'AM10015', 'AM10015022', 'Գետափ', 'Getap'),
(10015032, 10015, 'AM10015', 'AM10015032', 'Վերնաշեն', 'Vernashen'),
(10022012, 10022, 'AM10022', 'AM10022012', 'Զառիթափ', 'Zaritap'),
(10022032, 10022, 'AM10022', 'AM10022032', 'Ախտա', 'Akhta'),
(10022042, 10022, 'AM10022', 'AM10022042', 'Արտավան', 'Artavan'),
(10022052, 10022, 'AM10022', 'AM10022052', 'Բարձրունի', 'Bardzruni'),
(10022062, 10022, 'AM10022', 'AM10022062', 'Գոմք', 'Gomk'),
(10022072, 10022, 'AM10022', 'AM10022072', 'Խնձորուտ', 'Khndzorut'),
(10022082, 10022, 'AM10022', 'AM10022082', 'Կապույտ', 'Kapuyt'),
(10022092, 10022, 'AM10022', 'AM10022092', 'Մարտիրոս', 'Martiros'),
(10022102, 10022, 'AM10022', 'AM10022102', 'Նոր Ազնաբերդ', 'Nor Aznaberd'),
(10022112, 10022, 'AM10022', 'AM10022112', 'Ուղեձոր', 'Ughedzor'),
(10022122, 10022, 'AM10022', 'AM10022122', 'Սարավան', 'Saravan'),
(10022132, 10022, 'AM10022', 'AM10022132', 'Սերս', 'Sers'),
(10032012, 10032, 'AM10032', 'AM10032012', 'Մալիշկա', 'Malishka'),
(10035012, 10035, 'AM10035', 'AM10035012', 'Շատին', 'Shatin'),
(10035022, 10035, 'AM10035', 'AM10035022', 'Աղնջաձոր', 'Aghnjadzor'),
(10035032, 10035, 'AM10035', 'AM10035032', 'Արատես', 'Arates'),
(10035042, 10035, 'AM10035', 'AM10035042', 'Արտաբույնք', 'Artabuynk'),
(10035052, 10035, 'AM10035', 'AM10035052', 'Գետիկվանք', 'Getikvank'),
(10035062, 10035, 'AM10035', 'AM10035062', 'Գողթանիկ', 'Goghtanik'),
(10035072, 10035, 'AM10035', 'AM10035072', 'Եղեգիս', 'Yeghegis'),
(10035082, 10035, 'AM10035', 'AM10035082', 'Թառաթումբ', 'Taratumb'),
(10035092, 10035, 'AM10035', 'AM10035092', 'Կալասար', 'Kalasar'),
(10035102, 10035, 'AM10035', 'AM10035102', 'Հերմոն', 'Hermon'),
(10035112, 10035, 'AM10035', 'AM10035112', 'Հորբատեղ', 'Horbategh'),
(10035122, 10035, 'AM10035', 'AM10035122', 'Հորս', 'Hors'),
(10035132, 10035, 'AM10035', 'AM10035132', 'Սալլի', 'Salli'),
(10035142, 10035, 'AM10035', 'AM10035142', 'Սևաժայռ', 'Sevazhayr'),
(10035152, 10035, 'AM10035', 'AM10035152', 'Վարդահովիտ', 'Vardahovit'),
(10035162, 10035, 'AM10035', 'AM10035162', 'Քարագլուխ', 'Karaglukh'),
(11001011, 11001, 'AM11001', 'AM11001011', 'Իջևան', 'Ijevan'),
(11002011, 11002, 'AM11002', 'AM11002011', 'Բերդ', 'Berd'),
(11002022, 11002, 'AM11002', 'AM11002022', 'Այգեձոր', 'Aygedzor'),
(11002032, 11002, 'AM11002', 'AM11002032', 'Այգեպար', 'Aygepar'),
(11002042, 11002, 'AM11002', 'AM11002042', 'Արծվաբերդ', 'Artsvaberd'),
(11002052, 11002, 'AM11002', 'AM11002052', 'Իծաքար', 'Itsakar'),
(11002062, 11002, 'AM11002', 'AM11002062', 'Ծաղկավան', 'Tsaghkavan'),
(11002072, 11002, 'AM11002', 'AM11002072', 'Մովսես', 'Movses'),
(11002082, 11002, 'AM11002', 'AM11002082', 'Նավուր', 'Navur'),
(11002092, 11002, 'AM11002', 'AM11002092', 'Ներքին Կարﬕր աղբյուր', 'Nerkin Karmir Aghbyur'),
(11002102, 11002, 'AM11002', 'AM11002102', 'Նորաշեն', 'Norashen'),
(11002112, 11002, 'AM11002', 'AM11002112', 'Չինարի', 'Chinari'),
(11002122, 11002, 'AM11002', 'AM11002122', 'Չինչին', 'Chinchin'),
(11002132, 11002, 'AM11002', 'AM11002132', 'Չորաթան', 'Choratan'),
(11002142, 11002, 'AM11002', 'AM11002142', 'Պառավաքար', 'Paravakar'),
(11002152, 11002, 'AM11002', 'AM11002152', 'Վարագավան', 'Varagavan'),
(11002162, 11002, 'AM11002', 'AM11002162', 'Վերին Կարﬕր աղբյուր', 'Verin Karmir Aghbyur'),
(11002172, 11002, 'AM11002', 'AM11002172', 'Տավուշ', 'Tavush'),
(11003011, 11003, 'AM11003', 'AM11003011', 'Դիլիջան', 'Dilijan'),
(11003022, 11003, 'AM11003', 'AM11003022', 'Հաղարծին', 'Haghartsin'),
(11003032, 11003, 'AM11003', 'AM11003032', 'Թեղուտ', 'Teghut'),
(11003042, 11003, 'AM11003', 'AM11003042', 'Գոշ', 'Gosh'),
(11003052, 11003, 'AM11003', 'AM11003052', 'Աղաﬖավանք', 'Aghavnavank'),
(11003062, 11003, 'AM11003', 'AM11003062', 'Հովք', 'Hovk'),
(11003072, 11003, 'AM11003', 'AM11003072', 'Խաչարձան', 'Khachardzan'),
(11003082, 11003, 'AM11003', 'AM11003082', 'Ճերմակավան', 'Chermakavan'),
(11003092, 11003, 'AM11003', 'AM11003092', 'Գեղատափ', 'Geghatap'),
(11004011, 11004, 'AM11004', 'AM11004011', 'Նոյեմբերյան', 'Noyemberyan'),
(11004022, 11004, 'AM11004', 'AM11004022', 'Բաղանիս', 'Baghanis'),
(11004032, 11004, 'AM11004', 'AM11004032', 'Բարեկամավան', 'Barekamavan'),
(11004042, 11004, 'AM11004', 'AM11004042', 'Բերդավան', 'Berdavan'),
(11004052, 11004, 'AM11004', 'AM11004052', 'Դովեղ', 'Dovegh'),
(11004062, 11004, 'AM11004', 'AM11004062', 'Կոթի', 'Koti'),
(11004072, 11004, 'AM11004', 'AM11004072', 'Ոսկեպար', 'Voskepar'),
(11004082, 11004, 'AM11004', 'AM11004082', 'Ոսկեվան', 'Voskevan'),
(11004092, 11004, 'AM11004', 'AM11004092', 'Ջուջևան', 'Jujevan'),
(11005012, 11005, 'AM11005', 'AM11005012', 'Ազատամուտ', 'Azatamut'),
(11005022, 11005, 'AM11005', 'AM11005022', 'Բարխուդարլու', 'Barkhudarlu'),
(11006012, 11006, 'AM11006', 'AM11006012', 'Ակնաղբյուր', 'Aknaghbyur'),
(11008012, 11008, 'AM11008', 'AM11008012', 'Աճարկուտ', 'Acharkut'),
(11009012, 11009, 'AM11009', 'AM11009012', 'Այգեհովիտ', 'Aygehovit'),
(11009022, 11009, 'AM11009', 'AM11009022', 'Կայան', 'Kayan'),
(11013012, 11013, 'AM11013', 'AM11013012', 'Աչաջուր', 'Achajur'),
(11020012, 11020, 'AM11020', 'AM11020012', 'Բերքաբեր', 'Berkaber'),
(11021012, 11021, 'AM11021', 'AM11021012', 'Գանձաքար', 'Gandzakar'),
(11022012, 11022, 'AM11022', 'AM11022012', 'Գետահովիտ', 'Getahovit'),
(11026012, 11026, 'AM11026', 'AM11026012', 'Դիտավան', 'Ditavan'),
(11028012, 11028, 'AM11028', 'AM11028012', 'Ենոքավան', 'Yenokavan'),
(11033012, 11033, 'AM11033', 'AM11033012', 'Լուսահովիտ', 'Lusahovit'),
(11034012, 11034, 'AM11034', 'AM11034012', 'Լուսաձոր', 'Lusadzor'),
(11035012, 11035, 'AM11035', 'AM11035012', 'Խաշթառակ', 'Khashtarak'),
(11037012, 11037, 'AM11037', 'AM11037012', 'Ծաղկավան', 'Tsaghkavan'),
(11039012, 11039, 'AM11039', 'AM11039012', 'Կիրանց', 'Kirants'),
(11041012, 11041, 'AM11041', 'AM11041012', 'Կողբ', 'Koghb'),
(11041022, 11041, 'AM11041', 'AM11041022', 'Զորական', 'Zorakan'),
(11057012, 11057, 'AM11057', 'AM11057012', 'Սարիգյուղ', 'Sarigyugh'),
(11058012, 11058, 'AM11058', 'AM11058012', 'Սևքար', 'Sevkar'),
(11059012, 11059, 'AM11059', 'AM11059012', 'Վազաշեն', 'Vazashen'),
(11060011, 11060, 'AM11060', 'AM11060011', 'Այրում', 'Ayrum'),
(11060022, 11060, 'AM11060', 'AM11060022', 'Արճիս', 'Archis'),
(11060032, 11060, 'AM11060', 'AM11060032', 'Բագրատաշեն', 'Bagratashen'),
(11060042, 11060, 'AM11060', 'AM11060042', 'Դեբեդավան', 'Debedavan'),
(11060052, 11060, 'AM11060', 'AM11060052', 'Դեղձավան', 'Deghdzavan'),
(11060062, 11060, 'AM11060', 'AM11060062', 'Լճկաձոր', 'Lchkadzor'),
(11060072, 11060, 'AM11060', 'AM11060072', 'Հաղթանակ', 'Haghtanak'),
(11060082, 11060, 'AM11060', 'AM11060082', 'Պտղավան', 'Ptghavan'),
(11060083, 1500, '1500', '1500', 'անհայտ', 'UNKNOWN');

-- --------------------------------------------------------

--
-- Структура таблицы `tb_sign_status`
--

CREATE TABLE `tb_sign_status` (
  `status_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tb_sign_status`
--

INSERT INTO `tb_sign_status` (`status_id`, `status`) VALUES
(1, 'Նոր գործ'),
(2, 'Սպասում է մակագրման'),
(3, 'Մակագրված է ի կատարում'),
(4, 'ԾԵՏ հարցում'),
(5, 'ԾԵՏ պատասխան'),
(6, 'Ժամկետի երկարաձգման հայտ'),
(7, 'Ժամկետը երկարաձգվել է'),
(8, 'Որոշման նախագիծի համաձայնեցում'),
(9, 'Վերադարձվել է լրամշակման'),
(10, 'Համաձայնեցված է'),
(11, 'Հաստատված է'),
(12, 'Վերադարձվել է վերամակագրման'),
(13, 'Սպասում է հաստատման'),
(14, 'Ներկայացնել որոշումը հաստատման'),
(15, 'Բողոքարկման ժամանակահատված'),
(16, 'Կասեցում չհամագործակցության հիմքով'),
(17, 'Կասեցում հետ վերադարձի հիմքով'),
(18, 'Վարույթի դադարեցում հայցողի դիմումի հիմքով'),
(19, 'Հետ է կանչվել վերամակագրման'),
(20, 'Վերաբացված գործ');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `f_name` varchar(50) NOT NULL,
  `l_name` varchar(50) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `user_status` int(1) NOT NULL,
  `last_activity` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `f_name`, `l_name`, `user_type`, `user_status`, `last_activity`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'anun', 'azganunyan', 'admin', 1, 1629021801),
(2, 'coi', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Դիանա', 'Թումանյան', 'coispec', 1, 1627828991),
(3, 'Ruzan', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Ռուզաննա', 'Պետրոսյան', 'devhead', 1, 1629180399),
(4, 'Roza', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Ռոզալիա', 'Ղուկասյան', 'officer', 1, 1629021071),
(5, 'Armine', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Արմինե', 'Աբրահամյան', 'lawyer', 1, NULL),
(6, 'Lilit', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Լիլիթ', 'Արշակյան', 'operator', 1, 1629147816),
(7, 'Davit', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Դավիթ', 'Գալստյան', 'officer', 1, 1629180059),
(8, 'head', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Արմեն', 'Ղազարյան', 'head', 1, 1629180350),
(9, 'nss', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'ԱԱԾ', 'ԱԶԳԱՅԻՆ ԱՆՎՏԱՆԳՈՒԹՅՈՒՆ', 'nss', 1, 1627828953),
(10, 'pol', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'ՀՀ', 'ՈՍՏԻԿԱՆՈՒԹՅՈՒՆ', 'police', 1, 1628770275),
(11, 'UN', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'UN', 'HCR', 'viewer', 1, 1628771620),
(12, 'dorm', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Հասմիկ', 'Դանիելյան', 'dorm', 1, 1629179549),
(13, 'Gohar', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Գոհար', 'Մարյանյան', 'operator', 1, NULL),
(14, 'Svetik', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Սվետլանա', 'Հովհաննիսյան', 'operator', 1, NULL),
(15, 'Sevak', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Սևակ', 'Պետրոսյան', 'officer', 1, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tb_appeals`
--
ALTER TABLE `tb_appeals`
  ADD PRIMARY KEY (`appeal_id`);

--
-- Индексы таблицы `tb_appeal_types`
--
ALTER TABLE `tb_appeal_types`
  ADD PRIMARY KEY (`appeal_type_id`);

--
-- Индексы таблицы `tb_arm_com`
--
ALTER TABLE `tb_arm_com`
  ADD PRIMARY KEY (`community_id`);

--
-- Индексы таблицы `tb_calendar`
--
ALTER TABLE `tb_calendar`
  ADD PRIMARY KEY (`interview_id`);

--
-- Индексы таблицы `tb_cards`
--
ALTER TABLE `tb_cards`
  ADD PRIMARY KEY (`card_id`);

--
-- Индексы таблицы `tb_case`
--
ALTER TABLE `tb_case`
  ADD PRIMARY KEY (`case_id`);

--
-- Индексы таблицы `tb_case_status`
--
ALTER TABLE `tb_case_status`
  ADD PRIMARY KEY (`case_status_id`);

--
-- Индексы таблицы `tb_checkin`
--
ALTER TABLE `tb_checkin`
  ADD PRIMARY KEY (`checkin_id`);

--
-- Индексы таблицы `tb_coi`
--
ALTER TABLE `tb_coi`
  ADD PRIMARY KEY (`coi_id`);

--
-- Индексы таблицы `tb_country`
--
ALTER TABLE `tb_country`
  ADD PRIMARY KEY (`country_id`);

--
-- Индексы таблицы `tb_courts`
--
ALTER TABLE `tb_courts`
  ADD PRIMARY KEY (`court_id`);

--
-- Индексы таблицы `tb_court_decision_types`
--
ALTER TABLE `tb_court_decision_types`
  ADD PRIMARY KEY (`court_decision_type_id`);

--
-- Индексы таблицы `tb_deadline`
--
ALTER TABLE `tb_deadline`
  ADD PRIMARY KEY (`deadline_id`);

--
-- Индексы таблицы `tb_deadline_types`
--
ALTER TABLE `tb_deadline_types`
  ADD PRIMARY KEY (`deadline_type_id`);

--
-- Индексы таблицы `tb_decisions`
--
ALTER TABLE `tb_decisions`
  ADD PRIMARY KEY (`decision_id`);

--
-- Индексы таблицы `tb_decision_status`
--
ALTER TABLE `tb_decision_status`
  ADD PRIMARY KEY (`decision_status_id`);

--
-- Индексы таблицы `tb_decision_types`
--
ALTER TABLE `tb_decision_types`
  ADD PRIMARY KEY (`decision_type_id`);

--
-- Индексы таблицы `tb_doss`
--
ALTER TABLE `tb_doss`
  ADD PRIMARY KEY (`doss_id`),
  ADD UNIQUE KEY `doss` (`doss`);

--
-- Индексы таблицы `tb_draft`
--
ALTER TABLE `tb_draft`
  ADD PRIMARY KEY (`draft_id`);

--
-- Индексы таблицы `tb_drooms`
--
ALTER TABLE `tb_drooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Индексы таблицы `tb_education`
--
ALTER TABLE `tb_education`
  ADD PRIMARY KEY (`edu_id`);

--
-- Индексы таблицы `tb_edu_lvl`
--
ALTER TABLE `tb_edu_lvl`
  ADD PRIMARY KEY (`lvl_id`);

--
-- Индексы таблицы `tb_employment`
--
ALTER TABLE `tb_employment`
  ADD PRIMARY KEY (`employment_id`);

--
-- Индексы таблицы `tb_etnics`
--
ALTER TABLE `tb_etnics`
  ADD PRIMARY KEY (`etnic_id`);

--
-- Индексы таблицы `tb_file_type`
--
ALTER TABLE `tb_file_type`
  ADD PRIMARY KEY (`file_type_id`);

--
-- Индексы таблицы `tb_lawyer`
--
ALTER TABLE `tb_lawyer`
  ADD PRIMARY KEY (`lawyer_id`);

--
-- Индексы таблицы `tb_marz`
--
ALTER TABLE `tb_marz`
  ADD PRIMARY KEY (`marz_id`);

--
-- Индексы таблицы `tb_members`
--
ALTER TABLE `tb_members`
  ADD PRIMARY KEY (`member_id`);

--
-- Индексы таблицы `tb_notifications`
--
ALTER TABLE `tb_notifications`
  ADD PRIMARY KEY (`comment_id`);

--
-- Индексы таблицы `tb_orders`
--
ALTER TABLE `tb_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Индексы таблицы `tb_order_process`
--
ALTER TABLE `tb_order_process`
  ADD PRIMARY KEY (`order_process_id`);

--
-- Индексы таблицы `tb_order_process_status`
--
ALTER TABLE `tb_order_process_status`
  ADD PRIMARY KEY (`order_status_id`);

--
-- Индексы таблицы `tb_order_status`
--
ALTER TABLE `tb_order_status`
  ADD PRIMARY KEY (`order_status_id`);

--
-- Индексы таблицы `tb_person`
--
ALTER TABLE `tb_person`
  ADD PRIMARY KEY (`personal_id`);

--
-- Индексы таблицы `tb_process`
--
ALTER TABLE `tb_process`
  ADD PRIMARY KEY (`process_id`);

--
-- Индексы таблицы `tb_religions`
--
ALTER TABLE `tb_religions`
  ADD PRIMARY KEY (`religion_id`);

--
-- Индексы таблицы `tb_requests`
--
ALTER TABLE `tb_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Индексы таблицы `tb_role`
--
ALTER TABLE `tb_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Индексы таблицы `tb_settlement`
--
ALTER TABLE `tb_settlement`
  ADD PRIMARY KEY (`settlement_id`);

--
-- Индексы таблицы `tb_sign_status`
--
ALTER TABLE `tb_sign_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT для таблицы `tb_appeals`
--
ALTER TABLE `tb_appeals`
  MODIFY `appeal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tb_appeal_types`
--
ALTER TABLE `tb_appeal_types`
  MODIFY `appeal_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `tb_arm_com`
--
ALTER TABLE `tb_arm_com`
  MODIFY `community_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11061;

--
-- AUTO_INCREMENT для таблицы `tb_calendar`
--
ALTER TABLE `tb_calendar`
  MODIFY `interview_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `tb_cards`
--
ALTER TABLE `tb_cards`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `tb_case`
--
ALTER TABLE `tb_case`
  MODIFY `case_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT для таблицы `tb_case_status`
--
ALTER TABLE `tb_case_status`
  MODIFY `case_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `tb_checkin`
--
ALTER TABLE `tb_checkin`
  MODIFY `checkin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `tb_coi`
--
ALTER TABLE `tb_coi`
  MODIFY `coi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT для таблицы `tb_country`
--
ALTER TABLE `tb_country`
  MODIFY `country_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT для таблицы `tb_courts`
--
ALTER TABLE `tb_courts`
  MODIFY `court_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `tb_court_decision_types`
--
ALTER TABLE `tb_court_decision_types`
  MODIFY `court_decision_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `tb_deadline`
--
ALTER TABLE `tb_deadline`
  MODIFY `deadline_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT для таблицы `tb_deadline_types`
--
ALTER TABLE `tb_deadline_types`
  MODIFY `deadline_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `tb_decisions`
--
ALTER TABLE `tb_decisions`
  MODIFY `decision_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT для таблицы `tb_decision_status`
--
ALTER TABLE `tb_decision_status`
  MODIFY `decision_status_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `tb_decision_types`
--
ALTER TABLE `tb_decision_types`
  MODIFY `decision_type_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `tb_doss`
--
ALTER TABLE `tb_doss`
  MODIFY `doss_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT для таблицы `tb_draft`
--
ALTER TABLE `tb_draft`
  MODIFY `draft_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT для таблицы `tb_drooms`
--
ALTER TABLE `tb_drooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `tb_education`
--
ALTER TABLE `tb_education`
  MODIFY `edu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `tb_edu_lvl`
--
ALTER TABLE `tb_edu_lvl`
  MODIFY `lvl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `tb_employment`
--
ALTER TABLE `tb_employment`
  MODIFY `employment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `tb_etnics`
--
ALTER TABLE `tb_etnics`
  MODIFY `etnic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=481;

--
-- AUTO_INCREMENT для таблицы `tb_file_type`
--
ALTER TABLE `tb_file_type`
  MODIFY `file_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `tb_lawyer`
--
ALTER TABLE `tb_lawyer`
  MODIFY `lawyer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `tb_marz`
--
ALTER TABLE `tb_marz`
  MODIFY `marz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `tb_members`
--
ALTER TABLE `tb_members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT для таблицы `tb_notifications`
--
ALTER TABLE `tb_notifications`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=451;

--
-- AUTO_INCREMENT для таблицы `tb_orders`
--
ALTER TABLE `tb_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `tb_order_process`
--
ALTER TABLE `tb_order_process`
  MODIFY `order_process_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `tb_order_process_status`
--
ALTER TABLE `tb_order_process_status`
  MODIFY `order_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `tb_order_status`
--
ALTER TABLE `tb_order_status`
  MODIFY `order_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `tb_person`
--
ALTER TABLE `tb_person`
  MODIFY `personal_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT для таблицы `tb_process`
--
ALTER TABLE `tb_process`
  MODIFY `process_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=404;

--
-- AUTO_INCREMENT для таблицы `tb_religions`
--
ALTER TABLE `tb_religions`
  MODIFY `religion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT для таблицы `tb_requests`
--
ALTER TABLE `tb_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `tb_role`
--
ALTER TABLE `tb_role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `tb_settlement`
--
ALTER TABLE `tb_settlement`
  MODIFY `settlement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11060084;

--
-- AUTO_INCREMENT для таблицы `tb_sign_status`
--
ALTER TABLE `tb_sign_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
