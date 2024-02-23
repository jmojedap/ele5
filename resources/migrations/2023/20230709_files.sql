SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mo_sicc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files`
--

CREATE TABLE `files` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `folder` varchar(20) NOT NULL,
  `is_image` tinyint(4) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL,
  `type_id` smallint(6) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `subtitle` varchar(100) NOT NULL,
  `description` varchar(280) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `url` varchar(280) NOT NULL,
  `url_thumbnail` varchar(280) NOT NULL,
  `external_link` varchar(500) NOT NULL,
  `meta` varchar(1000) NOT NULL,
  `width` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `height` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `size` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `table_id` int(5) UNSIGNED NOT NULL DEFAULT 0,
  `related_1` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `album_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `position` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `integer_1` int(11) NOT NULL,
  `qty_comments` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `qty_likes` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `updater_id` mediumint(8) UNSIGNED NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creator_id` mediumint(8) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Índices para tablas volcadas
--

--
-- Indices de la tabla `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `album_id` (`album_id`),
  ADD KEY `related_1` (`table_id`,`related_1`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `files`
--
ALTER TABLE `files`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
