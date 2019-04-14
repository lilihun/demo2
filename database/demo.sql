/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : demo

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-04-14 11:42:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `dm_cx_haibao`
-- ----------------------------
DROP TABLE IF EXISTS `dm_cx_haibao`;
CREATE TABLE `dm_cx_haibao` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(50) NOT NULL COMMENT '海报名称',
  `url` varchar(100) NOT NULL COMMENT '海报url',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否展示， 1展示 0不展示',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `order` tinyint(5) unsigned zerofill DEFAULT '00000' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='小程序吗海报';

-- ----------------------------
-- Records of dm_cx_haibao
-- ----------------------------
INSERT INTO `dm_cx_haibao` VALUES ('1', 'fdsafds', 'a:1:{i:0;s:31:\"/storage/app/uploads/info/1.png\";}', '1', '1555211204', '1555211236', '00000');

-- ----------------------------
-- Table structure for `dm_cx_ma`
-- ----------------------------
DROP TABLE IF EXISTS `dm_cx_ma`;
CREATE TABLE `dm_cx_ma` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '新闻媒体ID',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `category1` char(20) NOT NULL COMMENT '二级分类',
  `category2` char(20) NOT NULL,
  `xc_ma_url` varchar(100) DEFAULT '' COMMENT '小程序码',
  `haibao_url` varchar(100) DEFAULT NULL COMMENT '海报',
  `order` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示，0代表显示，1代表不显示',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  `hotel_msg` varchar(100) DEFAULT NULL COMMENT '酒店信息',
  `sales_mag` varchar(100) DEFAULT NULL COMMENT '销售人员信息',
  `left` int(10) unsigned DEFAULT '0' COMMENT '左边距',
  `right` int(10) unsigned DEFAULT '0' COMMENT '右边距',
  `top` int(10) unsigned DEFAULT '0' COMMENT '上边距',
  `bottom` int(10) unsigned DEFAULT '0' COMMENT '下边距',
  `width` int(10) unsigned DEFAULT '0' COMMENT '宽度',
  `height` int(10) unsigned DEFAULT '0' COMMENT '高度',
  `opacity` int(10) unsigned DEFAULT '0' COMMENT '透明度',
  `remark` varchar(200) DEFAULT NULL COMMENT '文字水印',
  `params` varchar(300) DEFAULT NULL COMMENT '海报参数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态  0待生成小程序码 1待生成海报 2已完成',
  `haibao_id` int(11) DEFAULT NULL COMMENT '关联海报ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='（MyISAM）小程序码';

-- ----------------------------
-- Records of dm_cx_ma
-- ----------------------------

-- ----------------------------
-- Table structure for `dm_demo`
-- ----------------------------
DROP TABLE IF EXISTS `dm_demo`;
CREATE TABLE `dm_demo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '新闻媒体ID',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `introduction` varchar(200) DEFAULT '' COMMENT '概况简介',
  `pic` varchar(100) DEFAULT '' COMMENT '图片',
  `order` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示，0代表显示，1代表不显示',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  `desc` longtext COMMENT '详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='（MyISAM）模板数据库表---图片、详情';

-- ----------------------------
-- Records of dm_demo
-- ----------------------------

-- ----------------------------
-- Table structure for `dm_group`
-- ----------------------------
DROP TABLE IF EXISTS `dm_group`;
CREATE TABLE `dm_group` (
  `gid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(50) DEFAULT '' COMMENT '分组名',
  `author` text COMMENT '权限组',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of dm_group
-- ----------------------------
INSERT INTO `dm_group` VALUES ('3', '海报', 'a:1:{s:6:\"\"cxma\"\";a:1:{s:13:\"\"cxma_haibao\"\";a:2:{i:0;s:20:\"/admin/hb_cxma/index\";i:1;s:20:\"/admin/hb_cxma/store\";}}}');

-- ----------------------------
-- Table structure for `dm_user`
-- ----------------------------
DROP TABLE IF EXISTS `dm_user`;
CREATE TABLE `dm_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_name` varchar(255) NOT NULL COMMENT '账号',
  `user_pass` varchar(255) NOT NULL COMMENT '密码',
  `gid` varchar(255) NOT NULL COMMENT '用户权限组 0 表示超级管理员',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dm_user
-- ----------------------------
INSERT INTO `dm_user` VALUES ('1', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '0');
INSERT INTO `dm_user` VALUES ('5', 'haibao', '26084457eb496df196d43e8a3cab42074e017321', '3');
