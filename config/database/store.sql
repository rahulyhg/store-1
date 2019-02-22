-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Фев 22 2019 г., 22:26
-- Версия сервера: 10.1.37-MariaDB-0+deb9u1
-- Версия PHP: 7.2.15-1+0~20190209065123.16+stretch~1.gbp3ad8c0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `store`
--

-- --------------------------------------------------------

--
-- Структура таблицы `common_languages`
--

CREATE TABLE `common_languages` (
  `language_id` int(11) NOT NULL,
  `language_name` text NOT NULL,
  `language_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `common_languages`
--

INSERT INTO `common_languages` (`language_id`, `language_name`, `language_code`) VALUES
(3, 'Russian', 'ru'),
(4, 'English', 'en'),
(5, 'Ukranian', 'ua');

-- --------------------------------------------------------

--
-- Структура таблицы `common_logs`
--

CREATE TABLE `common_logs` (
  `log_id` int(11) NOT NULL,
  `log_data` text NOT NULL,
  `log_datetime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `dashboard_users`
--

CREATE TABLE `dashboard_users` (
  `user_id` int(11) NOT NULL,
  `user_name` text NOT NULL,
  `user_email` text NOT NULL,
  `user_password` text NOT NULL,
  `user_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dashboard_users`
--

INSERT INTO `dashboard_users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_status`) VALUES
(2, 'Serj Bliznyuk', 'serjbliznyuk@gmail.com', '$2y$11$8jjE9L2qPtikgo8iRNIRyO4bg5snBR6BAIoF9WPlaziSswKkK2J7u', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `store_brands`
--

CREATE TABLE `store_brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` text NOT NULL,
  `brand_description` text NOT NULL,
  `brand_image` text NOT NULL,
  `brand_link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `store_category`
--

CREATE TABLE `store_category` (
  `category_id` int(11) NOT NULL,
  `category_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `store_metadata`
--

CREATE TABLE `store_metadata` (
  `meta_id` int(11) NOT NULL,
  `meta_name` text NOT NULL,
  `meta_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `store_metadata`
--

INSERT INTO `store_metadata` (`meta_id`, `meta_name`, `meta_content`) VALUES
(1, 'title', 'Best Cosmetics - Магазин косметики'),
(2, 'description', 'Some descriptio about this store'),
(3, 'keywords', 'some, keywords, best cosmetics, and, other'),
(4, 'about', 'Best Cosmetics - Лучший магазин косметики из Кореи');

-- --------------------------------------------------------

--
-- Структура таблицы `store_modules`
--

CREATE TABLE `store_modules` (
  `module_id` int(11) NOT NULL,
  `module_name` text NOT NULL,
  `module_data` text NOT NULL,
  `module_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `store_modules`
--

INSERT INTO `store_modules` (`module_id`, `module_name`, `module_data`, `module_status`) VALUES
(2, 'sdfsdfds', 'sdfdsfsd', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `store_products`
--

CREATE TABLE `store_products` (
  `product_id` bigint(20) NOT NULL,
  `product_name` text NOT NULL,
  `product_description` text NOT NULL,
  `product_price` float NOT NULL,
  `product_metatitle` text NOT NULL,
  `product_metadescription` text NOT NULL,
  `product_metakeywords` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `store_product_brand`
--

CREATE TABLE `store_product_brand` (
  `product_brand_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `store_product_category`
--

CREATE TABLE `store_product_category` (
  `product_category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `store_scripts`
--

CREATE TABLE `store_scripts` (
  `script_id` int(11) NOT NULL,
  `script_name` text NOT NULL,
  `script_data` text NOT NULL,
  `script_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `store_scripts`
--

INSERT INTO `store_scripts` (`script_id`, `script_name`, `script_data`, `script_status`) VALUES
(5, 'Check scripts', '<script type=\"text/javascript\">alert(\"Some alert from dashboard-scripts\");</script>', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `store_subcategory`
--

CREATE TABLE `store_subcategory` (
  `subcategory_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `common_languages`
--
ALTER TABLE `common_languages`
  ADD PRIMARY KEY (`language_id`);

--
-- Индексы таблицы `common_logs`
--
ALTER TABLE `common_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Индексы таблицы `dashboard_users`
--
ALTER TABLE `dashboard_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `store_brands`
--
ALTER TABLE `store_brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Индексы таблицы `store_category`
--
ALTER TABLE `store_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `store_metadata`
--
ALTER TABLE `store_metadata`
  ADD PRIMARY KEY (`meta_id`);

--
-- Индексы таблицы `store_modules`
--
ALTER TABLE `store_modules`
  ADD PRIMARY KEY (`module_id`);

--
-- Индексы таблицы `store_products`
--
ALTER TABLE `store_products`
  ADD PRIMARY KEY (`product_id`);

--
-- Индексы таблицы `store_product_brand`
--
ALTER TABLE `store_product_brand`
  ADD PRIMARY KEY (`product_brand_id`);

--
-- Индексы таблицы `store_product_category`
--
ALTER TABLE `store_product_category`
  ADD PRIMARY KEY (`product_category_id`);

--
-- Индексы таблицы `store_scripts`
--
ALTER TABLE `store_scripts`
  ADD PRIMARY KEY (`script_id`);

--
-- Индексы таблицы `store_subcategory`
--
ALTER TABLE `store_subcategory`
  ADD PRIMARY KEY (`subcategory_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `common_languages`
--
ALTER TABLE `common_languages`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `common_logs`
--
ALTER TABLE `common_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `dashboard_users`
--
ALTER TABLE `dashboard_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `store_brands`
--
ALTER TABLE `store_brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `store_category`
--
ALTER TABLE `store_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `store_metadata`
--
ALTER TABLE `store_metadata`
  MODIFY `meta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `store_modules`
--
ALTER TABLE `store_modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `store_products`
--
ALTER TABLE `store_products`
  MODIFY `product_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `store_product_brand`
--
ALTER TABLE `store_product_brand`
  MODIFY `product_brand_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `store_product_category`
--
ALTER TABLE `store_product_category`
  MODIFY `product_category_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `store_scripts`
--
ALTER TABLE `store_scripts`
  MODIFY `script_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `store_subcategory`
--
ALTER TABLE `store_subcategory`
  MODIFY `subcategory_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
