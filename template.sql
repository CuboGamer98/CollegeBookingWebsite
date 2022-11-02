-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-11-2022 a las 20:24:13
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `account`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autobooks`
--

CREATE TABLE `autobooks` (
  `weekday` text NOT NULL,
  `start` text NOT NULL,
  `end` text NOT NULL,
  `book` text NOT NULL,
  `class` text NOT NULL,
  `grade` text NOT NULL,
  `email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `autobooks`
--

INSERT INTO `autobooks` (`weekday`, `start`, `end`, `book`, `class`, `grade`, `email`) VALUES
('Martes', '8:00', '9:00', 'chromebook', 'Biología/CCNN', '1º A ESO', 'h.sanchez@alexia.cnsfatima.es'),
('Lunes', '13:00', '14:00', 'chromebook', 'Lengua', '2º B EP', 'h.sanchez@alexia.cnsfatima.es'),
('Miércoles', '16:00', '17:00', 'chromebook', 'Tecnología', '2º B ESO', 'h.sanchez@alexia.cnsfatima.es'),
('Jueves', '14:00', '15:00', 'chromebook', 'Música', '2º A ESO', 'h.sanchez@alexia.cnsfatima.es'),
('Viernes', '9:00', '10:00', 'chromebook', 'Religión', '6º B EP', 'h.sanchez@alexia.cnsfatima.es'),
('Lunes', '9:00', '10:00', 'chromebook', 'Francés', '4º A EP', 'j.vega@alexia.cnsfatima.es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bookings`
--

CREATE TABLE `bookings` (
  `id` text NOT NULL,
  `start` text NOT NULL,
  `end` text NOT NULL,
  `name` text NOT NULL,
  `class` text NOT NULL,
  `grade` text NOT NULL,
  `book` text NOT NULL,
  `date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `bookings`
--

INSERT INTO `bookings` (`id`, `start`, `end`, `name`, `class`, `grade`, `book`, `date`) VALUES
('136f8866eb26de32dd2d7a59383af2c2a56804eb', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '11/07/2022'),
('80158b954ce91a4a4dee8c9491264e821ae48f05', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '11/02/2022'),
('094705d5c2a8b38068672c0d3c388e5eb516931a', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '11/30/2022'),
('c0851925757bd03ffa7382a8aecd21639e3999f9', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '11/03/2022'),
('dba6cfee9b6e62867d0aca5487ce9a1e2a5fc611', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '11/24/2022'),
('7234f3bf145c9fadbe3a91114b7eef5c739827ed', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '11/04/2022'),
('cd6d25afbecf0e1d18be557d3c4aa1ffa7a22a86', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '11/07/2022'),
('32fb0a854ce5d18de8e754d1a1e9f30b69380df3', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '11/14/2022'),
('ca62d7182dde55ccf2511138743aa9d60a8dc0c0', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '11/21/2022'),
('077b1d9f0ee626fd80acade12e6ac7e325800c1f', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '11/28/2022'),
('fccb4e259f31ea870391fbed7de0f5ecf227b3b2', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '02/07/2023'),
('64404348dba39d4086d9f1169ae16652aa296fde', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '02/14/2023'),
('02ef07d84eb25c8042c2fc84301e865f6724e7b4', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '02/21/2023'),
('42e6fffb0432c1ddcd7160c40e25d7047bde9e53', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '02/28/2023'),
('9ca90079be7842596ccdb8a7e0a9ff25e2051cdd', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '02/06/2023'),
('7b6428a5a0758b9fa05266bfe3ad5e2a6a5a16a2', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '02/13/2023'),
('ec4fc741ae573903104525511661edccdddbc187', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '02/20/2023'),
('a56a8f417c286617d3d9cb1472c1619d9cb5152d', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '02/27/2023'),
('fab832dd9fa89c4f6fdb4bdc117cbce9b0074dd0', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '02/01/2023'),
('8c9c93cbcb737b7dd10246c5047131ea27aed458', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '02/08/2023'),
('acf54f83d33f4b9d61828e33b6d57d9ce38bd83d', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '02/15/2023'),
('ecf64603cf0921a425ff09b443af264ed94e071f', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '02/22/2023'),
('f7b3101db80acc073c4129daae65df849492bdba', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '02/02/2023'),
('912eacf684138f513e855a77799f08b22e5fb357', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '02/09/2023'),
('0fd1f8adddb88732bb7fcc351c08653c8cf876c1', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '02/16/2023'),
('bd5db402543dec552f7d259608fee6b986267c8c', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '02/23/2023'),
('9993924af851dde84ade01947728287fbb33b105', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '02/03/2023'),
('348ff22621350e14a08664d1581b53ed5b2b7072', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '02/10/2023'),
('fc842c685f5e1a7bf41a8aa082822f34f82ccfad', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '02/17/2023'),
('1ac92afc436b25574c4a99ec3242563249c52d45', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '02/24/2023'),
('1a6251f49b43773cd9046c68121db69671bfa9ff', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '02/06/2023'),
('b7331e4ab37db4649feb240a89e724f2da33680c', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '02/13/2023'),
('9a9b8c6c9d783218994f591f98b6132172b4949a', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '02/20/2023'),
('6e618a55216b03afd04f3a6f9f78c42d492789a6', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '02/27/2023'),
('fa4f7a76c1d8676d8930a7bd0bed8d2377dfaba5', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '05/02/2023'),
('bcb5d8bb4089af0645f168d217a25ebfc1f91be2', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '05/09/2023'),
('57e5899994645be39e32d7638b38b290c5b10943', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '05/16/2023'),
('17cf249cb0af6f16b4b6a66b46bb98074d5d05ad', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '05/23/2023'),
('0562cf5a5e16c57a0c4df5672601c5d3f26a7d45', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '05/30/2023'),
('1dfdf33d86b735cadd4b82cf264b0b0d734777e6', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '05/01/2023'),
('2089b74e0622cd59f81a40c641826f20410b3b38', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '05/08/2023'),
('cfd5bd2246c5d647195388186dca93844da248f4', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '05/15/2023'),
('d2d191c6b0a2f791e1e3f38d9c249a75ed76e14c', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '05/22/2023'),
('8e9c2d137670a4d18eb98225f125f330c6a2f8c1', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '05/29/2023'),
('4c40036bf4cd9d0d30652cc0de8741f86caafa78', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '05/03/2023'),
('38cb4a2fdd15460b960b428f119fd5bb3761cd82', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '05/10/2023'),
('3dc8bc0f2921a1253dd41950cc9663fdcb899370', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '05/17/2023'),
('67048a3f513d587e77cf1cbd03f59e165c79bf46', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '05/24/2023'),
('d6328a33cd9c4a7d45f43a2497f6f82136df0535', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '05/31/2023'),
('dbecc61e099eeb1bf8181b26eac16a72d989ffd0', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '05/04/2023'),
('e3fa0ae2468201d0983d32430f7f13858f189480', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '05/11/2023'),
('e614425183ba41d6b450c406e70e0b488e6df22d', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '05/18/2023'),
('4afa725eb41f3f669b4b34c19060e6c177faf5a2', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '05/25/2023'),
('d1c337ef021b9dfa79b389a20d292d1f983baf41', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '05/05/2023'),
('46870ace29b879c39b0a3f1d869ef486e3833ff1', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '05/12/2023'),
('8499a9a26cac417d237c2e111c99b0887ffdb582', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '05/19/2023'),
('97e8438333865a81b14181b854023860c806eb68', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '05/26/2023'),
('8b8b5d3b6233923446f93bfe2e90dde650fee402', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '05/01/2023'),
('e1d82f16df3ef8f16b07c77e96569559a8f0ae96', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '05/08/2023'),
('654e37f88254b8b81b180b772b823715102a3ac6', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '05/15/2023'),
('0ef2dd74ddf32d4f89f3f1071b4bf819a1d96b1f', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '05/22/2023'),
('5ada4cf36d19db3afe586b9f6ee116e9aa380e26', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '05/29/2023'),
('7b14df6977713ef7b32f020a8bbd2e9aeaf17122', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '06/06/2023'),
('ca4093315f48dd779016335251228891da77a214', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '06/13/2023'),
('80dc2e703c4f3539ee210dfa2fc377a094a5f891', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '06/20/2023'),
('f05ba76434fa314dc4cfa14c138468efb5060243', '8:00', '9:00', 'Héctor Sánchez', 'Biología/CCNN', '1º A ESO', 'chromebook', '06/27/2023'),
('f9a2f305d37b8cb919a8fcdb6867bc6f542a1259', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '06/05/2023'),
('686840579f6396c32504ebea977f207d715e0896', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '06/12/2023'),
('5ef182ac326b65672e1a5ab9a0cc6419ff4f368e', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '06/19/2023'),
('f6b3762b7212871d7fa5503472677a0752d6f88c', '13:00', '14:00', 'Héctor Sánchez', 'Lengua', '2º B EP', 'chromebook', '06/26/2023'),
('ca6d2f217d68195fc3f61944dd30743ac06faf3a', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '06/07/2023'),
('06f47934ff631d95ab1ba364101b85297a92f451', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '06/14/2023'),
('dbe95f7fd9851ef04b7aaf49b19af9d30b554979', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '06/21/2023'),
('107b0dcf627cb35b056e0d9ba8a5ba8014575491', '16:00', '17:00', 'Héctor Sánchez', 'Tecnología', '2º B ESO', 'chromebook', '06/28/2023'),
('db157f1bfd86d0f62d9a904c0bcd8fc54fe4cdce', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '06/01/2023'),
('03dc88fa36d59c2f26ebb0dcf2b8262655783e05', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '06/08/2023'),
('349351eb3f495a233e1f8eba028dcd206519ea13', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '06/15/2023'),
('4c91ee5af10d7ad8401da075799f37903ff119c5', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '06/22/2023'),
('a5b76a9195314701351937cc0e6605dda11a5e76', '14:00', '15:00', 'Héctor Sánchez', 'Música', '2º A ESO', 'chromebook', '06/29/2023'),
('c1e47844ab334e61779b2449d7645b23398f1ccd', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '06/02/2023'),
('50cc718ed8990d09c219c148ccd3ea261cea3ca8', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '06/09/2023'),
('9a480eb946cc495fce715715e489f8f91bdb72fb', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '06/16/2023'),
('a435e56a47b1cb58655b4e2a21840a6eeae98753', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '06/23/2023'),
('fa7e603f361321d50b78c6ad95cecb29890e2581', '9:00', '10:00', 'Héctor Sánchez', 'Religión', '6º B EP', 'chromebook', '06/30/2023'),
('c3111f2e6b15095a1816969eb6010989156fad43', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '06/05/2023'),
('18e7fdd43acbb84ee964909dd43cfc81a6f709dd', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '06/12/2023'),
('6b98afb1e3fc17b9d96e8ad5e837a12b7c373964', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '06/19/2023'),
('adc6ea030c960690077f1f790ae1ab6932f7eba0', '9:00', '10:00', 'Juan Andrés Vega', 'Francés', '4º A EP', 'chromebook', '06/26/2023');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `book_types`
--

CREATE TABLE `book_types` (
  `name` text NOT NULL,
  `img_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `book_types`
--

INSERT INTO `book_types` (`name`, `img_name`) VALUES
('Sala de informática', '../uploaded_images/5k5yf8sv9lftl64cqdeqeombhvdapd17v7tz537rnc3s4udmjv.svg'),
('Tablets carro 1', '../uploaded_images/tk0bux5603hulhm5yerr9rzsm7m2l7l3zimf2k7rvb032k2h8n.svg'),
('Tablets carro 2', '../uploaded_images/ukiz7uo4kphsx178ahls26q0t36fmkig6317bdmvkcwyvoucdv.svg'),
('Capilla', '../uploaded_images/64oxa518h506tz6by6nwjan0gjb8wo3vize37czfsbw4ww8rs6.svg'),
('Biblioteca', '../uploaded_images/drf8b33bkz3n6zbx3bo04fdb6av7yi9u9xqhn43tubja83ydih.svg'),
('Chromebook', '../uploaded_images/auwhe1pvofbqc4kgca0vcg1g9apgsf69ex4am3ga23rci4mtvg.svg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `classes`
--

CREATE TABLE `classes` (
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `classes`
--

INSERT INTO `classes` (`name`) VALUES
('Matemáticas'),
('Lengua'),
('Inglés'),
('Geografía-Historia/Sociales'),
('Biología'),
('Música'),
('Educación Física'),
('Tecnología'),
('Religión'),
('Francés'),
('Iniciativa/Economía'),
('Plástica/Arts'),
('Latín'),
('Física y Química'),
('Informática'),
('Filosofía');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidences`
--

CREATE TABLE `incidences` (
  `id` text NOT NULL,
  `by` text NOT NULL,
  `hour` text NOT NULL,
  `day` text NOT NULL,
  `sendto` text NOT NULL,
  `msg` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `incidences`
--

INSERT INTO `incidences` (`id`, `by`, `hour`, `day`, `sendto`, `msg`, `status`) VALUES
('7d8a26d654d0ac5d66452784a2529c56c0ba5ce7', 'Héctor Sánchez', '22:29', '27/10/2022', 'hectorsanchezfernandez.98@gmail.com', 'Me aburro mucho cuando estoy, no se, en verano y no se que hacer y pues me pongo a hacer operaciones matematicas.', 'En espera');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `params`
--

CREATE TABLE `params` (
  `paramName` varchar(100) NOT NULL,
  `paramValue` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `params`
--

INSERT INTO `params` (`paramName`, `paramValue`) VALUES
('canRegister', 'true'),
('incidenceEmail', 'hectorsanchezfernandez.98@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pending_users`
--

CREATE TABLE `pending_users` (
  `usersId` int(11) NOT NULL,
  `usersName` varchar(128) NOT NULL,
  `usersEmail` varchar(128) NOT NULL,
  `usersPwd` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `usersId` int(11) NOT NULL,
  `usersName` varchar(128) NOT NULL,
  `usersEmail` varchar(128) NOT NULL,
  `usersPwd` varchar(128) NOT NULL,
  `isAdmin` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`usersId`, `usersName`, `usersEmail`, `usersPwd`, `isAdmin`) VALUES
(11, 'Juan Andrés Vega', 'j.vega@alexia.cnsfatima.es', '$2y$10$4tslNPRfOtd8KROn/FZtZO/iLqMRhzNAprHHCs9p8npleX08iJJB.', 1),
(12, 'Héctor Sánchez', 'h.sanchez@alexia.cnsfatima.es', '$2y$10$qC8tLLFJZPuDnoJ8uasyc.W9YzbnLyoC7SQRX3rzdDUpjF4XOy.xm', 1),
(13, 'abs', 'abc@alexia.cnsfatima.es', '$2y$10$w3GIcWGXvhhwogOmyZUBVO7rqixcPhpATNYE50SYwgKD8ov575Cny', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pending_users`
--
ALTER TABLE `pending_users`
  ADD PRIMARY KEY (`usersId`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usersId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pending_users`
--
ALTER TABLE `pending_users`
  MODIFY `usersId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `usersId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
