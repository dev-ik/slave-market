-- CREATE DATABASE
DROP DATABASE IF EXISTS `slave_market`;
CREATE DATABASE IF NOT EXISTS `slave_market`
USE `slave_market`;

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `category_slave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11),
  `slave_id` int(11),
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_id_slave_id` (`category_id`,`slave_id`),
  KEY `FK_SLAVE` (`slave_id`),
  CONSTRAINT `FK_CATEGORY` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_SLAVE` FOREIGN KEY (`slave_id`) REFERENCES `slave` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `slave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `gender` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - муж.пол / 1 - жен. пол',
  `weight` int(11) NOT NULL COMMENT 'вес указан в граммах',
  `price_per_hour` decimal(10,2) NOT NULL COMMENT 'стоимость часа работы',
  `price` decimal(10,2) NOT NULL COMMENT 'стоимость раба',
  PRIMARY KEY (`id`),
  KEY `price` (`price`),
  KEY `weight` (`weight`),
  KEY `gender` (`gender`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- Получить минимальную, максимальную и среднюю стоимость всех рабов весом более 60 кг.
-- Вес в БД хранится в грамах, поэтому ищем более 60000 гр.
SELECT MIN(slave.price) AS min, AVG(slave.price) AS avg , MAX(slave.price) AS maximum  FROM slave WHERE slave.weight > 60000

-- Выбрать категории, в которых больше 10 рабов.
SELECT cat.name FROM category cat INNER JOIN category_slave csl ON cat.id = csl.category_id GROUP BY cat.id HAVING COUNT(cat.id) >= 10

-- Выбрать категорию с наибольшей суммарной стоимостью рабов.
SELECT t1.NAME, MAX(t1.sum) FROM (SELECT cat.NAME AS name, SUM(sl.price) AS sum FROM category cat INNER JOIN category_slave csl ON cat.id = csl.category_id INNER JOIN slave sl ON csl.slave_id = sl.id GROUP BY cat.id) AS t1

-- Выбрать категории, в которых мужчин больше чем женщин.
SELECT cat.NAME AS NAME FROM category cat INNER JOIN category_slave csl ON cat.id = csl.category_id LEFT JOIN slave sl ON csl.slave_id = sl.id AND sl.gender = 0 LEFT JOIN slave slw ON csl.slave_id = slw.id AND slw.gender = 1 GROUP BY cat.id
HAVING COUNT(sl.gender) > COUNT(slw.gender)

-- К оличество рабов в категории "Для кухни" (включая все вложенные категории).
--  Предположим что id категории для Кухни равен 1
SELECT COUNT(cs.slave_id) FROM (
  SELECT  id
  FROM    (select * from category
           order by parent_id, id) AS category,
          (select @pv := '1') initialisation
  WHERE   find_in_set(parent_id, @pv)
  AND     length(@pv := concat(@pv, ',', id))
) AS c
LEFT JOIN category_slave  cs ON cs.category_id = c.id
