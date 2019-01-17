-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Янв 17 2019 г., 23:05
-- Версия сервера: 10.1.37-MariaDB-0+deb9u1
-- Версия PHP: 7.2.14-1+0~20190113100742.14+stretch~1.gbpd83c69

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
-- Структура таблицы `common_logs`
--

CREATE TABLE `common_logs` (
  `log_id` int(11) NOT NULL,
  `log_data` text NOT NULL,
  `log_datetime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `common_settings`
--

CREATE TABLE `common_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_name` text NOT NULL,
  `setting_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `common_settings`
--

INSERT INTO `common_settings` (`setting_id`, `setting_name`, `setting_data`) VALUES
(1, 'language', 'ru'),
(2, 'currency', 'грн'),
(3, 'country', 'Украина');

-- --------------------------------------------------------

--
-- Структура таблицы `dashboard_users`
--

CREATE TABLE `dashboard_users` (
  `user_id` int(11) NOT NULL,
  `user_name` text NOT NULL,
  `user_email` text NOT NULL,
  `user_password` text NOT NULL,
  `user_image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `store_analytics`
--

CREATE TABLE `store_analytics` (
  `analytics_id` int(11) NOT NULL,
  `analytics_name` text NOT NULL,
  `analytics_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Структура таблицы `store_products`
--

CREATE TABLE `store_products` (
  `product_id` int(11) NOT NULL,
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
-- Индексы таблицы `common_logs`
--
ALTER TABLE `common_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Индексы таблицы `common_settings`
--
ALTER TABLE `common_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Индексы таблицы `dashboard_users`
--
ALTER TABLE `dashboard_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `store_analytics`
--
ALTER TABLE `store_analytics`
  ADD PRIMARY KEY (`analytics_id`);

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
-- Индексы таблицы `store_subcategory`
--
ALTER TABLE `store_subcategory`
  ADD PRIMARY KEY (`subcategory_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `common_logs`
--
ALTER TABLE `common_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `common_settings`
--
ALTER TABLE `common_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `dashboard_users`
--
ALTER TABLE `dashboard_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `store_analytics`
--
ALTER TABLE `store_analytics`
  MODIFY `analytics_id` int(11) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT для таблицы `store_products`
--
ALTER TABLE `store_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT для таблицы `store_subcategory`
--
ALTER TABLE `store_subcategory`
  MODIFY `subcategory_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
