SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS  `app_category`;
CREATE TABLE `app_category` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='单词分类表';

DROP TABLE IF EXISTS  `app_category_word`;
CREATE TABLE `app_category_word` (
  `id` int(13) NOT NULL,
  `category_id` int(13) NOT NULL,
  `word` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='单词分类详细表';

DROP TABLE IF EXISTS  `app_dictionary`;
CREATE TABLE `app_dictionary` (
  `id` int(13) NOT NULL COMMENT 'id',
  `word` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='词典表';

DROP TABLE IF EXISTS  `app_study_plan`;
CREATE TABLE `app_study_plan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `day_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '每天学习计划',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` int(13) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  CONSTRAINT `uid` FOREIGN KEY (`uid`) REFERENCES `app_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `app_user`;
CREATE TABLE `app_user` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` varchar(200) CHARACTER SET utf8mb4 NOT NULL DEFAULT '某某用户' COMMENT '用户昵称',
  `password` varchar(64) DEFAULT NULL COMMENT '用户密码',
  `mobile` varchar(22) DEFAULT '' COMMENT '手机号码',
  `openid` varchar(100) DEFAULT NULL COMMENT '微信openid',
  `head` text COMMENT '用户头像',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` int(13) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-正常,-1冻结',
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户表';

DROP TABLE IF EXISTS  `app_words`;
CREATE TABLE `app_words` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `word` varchar(64) NOT NULL,
  `meaning` text NOT NULL,
  `example` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Word` (`word`)
) ENGINE=InnoDB AUTO_INCREMENT=15329 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;

