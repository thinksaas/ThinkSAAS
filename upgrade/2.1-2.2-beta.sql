SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `ts_editor` (
`id`  int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID' ,
`userid`  int(11) NOT NULL DEFAULT 0 COMMENT '用户ID' ,
`type`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'photo' COMMENT '类型photo,file' ,
`title`  char(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '标题' ,
`path`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '路径' ,
`url`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图片或者文件' ,
`addtime`  int(11) NOT NULL DEFAULT 0 COMMENT '时间' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ts_group_user_isaudit` (
`userid`  int(11) NOT NULL DEFAULT 0 COMMENT '用户ID' ,
`groupid`  int(11) NOT NULL DEFAULT 0 COMMENT '小组ID' ,
UNIQUE INDEX `userid` (`userid`, `groupid`) USING BTREE ,
INDEX `groupid` (`groupid`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

ALTER TABLE `ts_user_info` ADD COLUMN `autologin`  char(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '自动登陆' AFTER `verifycode`;

ALTER TABLE `ts_user_info` ADD COLUMN `signin`  int(11) NOT NULL DEFAULT 0 COMMENT '签到时间' AFTER `autologin`;

CREATE UNIQUE INDEX `email_2` ON `ts_user_info`(`email`, `autologin`) USING BTREE ;

SET FOREIGN_KEY_CHECKS=1;

