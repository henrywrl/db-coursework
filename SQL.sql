

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



CREATE TABLE IF NOT EXISTS `anonylog` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `qid` mediumint(7) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `answers`
--

CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` mediumint(7) NOT NULL,
  `value` text NOT NULL,
  `sort` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `examlog`
--

CREATE TABLE IF NOT EXISTS `examlog` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `qid` mediumint(7) NOT NULL,
  `uid` mediumint(7) NOT NULL,
  `result` tinyint(1) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `topic` text NOT NULL,
  `correctanswer` tinyint(1) NOT NULL,
  `by` smallint(5) NOT NULL,
  `timelimit` mediumint(5) NOT NULL,
  `ownership` tinyint(1) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `value` varchar(60) NOT NULL,
  `grade` tinyint(1) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `mail` varchar(80) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `name` varchar(48) NOT NULL,
  `age` tinyint(2) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `grade` tinyint(1) NOT NULL,
  `class` tinyint(1) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
