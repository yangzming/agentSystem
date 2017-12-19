-- ----------------------------
-- Table structure for db_customer
-- ----------------------------
DROP TABLE IF EXISTS `db_customer`;
CREATE TABLE `db_customer` (
  `customer_id` MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `username`  VARCHAR(35) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` CHAR(20) NOT NULL DEFAULT '' COMMENT '密码',
  `telephone` CHAR(12) NOT NULL DEFAULT '' COMMENT '电话',
  `qq` VARCHAR(13) NOT NULL DEFAULT '' COMMENT 'QQ',
  `website` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '网址',
  `wechat_id` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '微信号',
  `wechat_qrcode` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '二维码',
  `introduce` TEXT COMMENT '介绍',
  `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '网站名称',
  `keywords` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '网站关键词',
  `description` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '网站描述',
  `beian` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '网站备案',
  `company` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '公司名',
  `address` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '地址',
  `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '2' COMMENT '是否授权 1已授权; 2禁止登录',
  `add_time` CHAR(12) NOT NULL DEFAULT '' COMMENT '添加时间',
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户表';

-- ----------------------------
-- Table structure for db_admin
-- ----------------------------
DROP TABLE IF EXISTS `db_admin`;
CREATE TABLE `db_admin` (
  `admin_id` MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_name`  VARCHAR(35) NOT NULL DEFAULT '' COMMENT '用户名',
  `admin_pwd` CHAR(20) NOT NULL DEFAULT '' COMMENT '密码',
  `add_time` CHAR(12) NOT NULL DEFAULT '' COMMENT '添加时间',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Table structure for db_product_type
-- ----------------------------
DROP TABLE IF EXISTS `db_product_category`;
CREATE TABLE `db_product_category` (
  `cate_id` MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `cate_name`  VARCHAR(100) NOT NULL DEFAULT '' COMMENT '产品类型名',
  `update_time` INT(13) NOT NULL DEFAULT '0' COMMENT '最后编辑时间',
  PRIMARY KEY (`cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品分类表';

-- ----------------------------
-- Table structure for db_product
-- ----------------------------
DROP TABLE IF EXISTS `db_product`;
CREATE TABLE `db_product` (
  `product_id` MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `cate_id` MEDIUMINT(9) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类id',
  `product_title`  VARCHAR(100) NOT NULL DEFAULT '' COMMENT '产品标题',
  `product_number`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '产品编号',
  `product_thumb`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '产品缩略图',
  `pc_pic`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'pc端图片',
  `wap_pic`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'wap端图片',
  `product_info`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '产品简介',
  `product_content` TEXT COMMENT '产品内容',
  `keywords` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '关键词',
  `description` VARCHAR(255) COMMENT '描述',
  `add_time` CHAR(12) NOT NULL DEFAULT '' COMMENT '添加时间',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品表';

-- ----------------------------
-- Table structure for db_article_type
-- ----------------------------
DROP TABLE IF EXISTS `db_article_category`;
CREATE TABLE `db_article_category` (
  `cate_id` MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `cate_name`  VARCHAR(100) NOT NULL DEFAULT '' COMMENT '文章类型名',
  `update_time` INT(13) NOT NULL DEFAULT '0' COMMENT '最后编辑时间',
  PRIMARY KEY (`cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类表';

-- ----------------------------
-- Table structure for db_article
-- ----------------------------
DROP TABLE IF EXISTS `db_article`;
CREATE TABLE `db_article` (
  `article_id` MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `cate_id` MEDIUMINT(9) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类id',
  `article_title`  VARCHAR(100) NOT NULL DEFAULT '' COMMENT '文章标题',
  `article_thumb`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '文章缩略图',
  `pc_pic`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'pc端图片',
  `article_info`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '文章简介',
  `article_content` TEXT COMMENT '产品内容',
  `remarks` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '备注',
  `keywords` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '关键词',
  `description` VARCHAR(255) COMMENT '描述',
  `add_time` CHAR(12) NOT NULL DEFAULT '' COMMENT '添加时间',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';

DROP TABLE IF EXISTS `db_theme`;
CREATE TABLE `db_theme` (
  `theme_id` MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `theme_name`  VARCHAR(100) NOT NULL DEFAULT '' COMMENT '主题名称',
  PRIMARY KEY (`theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='主题表';

ALTER TABLE `db_product` ADD `price` VARCHAR(20) NOT NULL DEFAULT '0' COMMENT '价格';
ALTER TABLE `db_product` ADD `demourl` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '演示地址';

ALTER TABLE `db_customer` ADD `theme` VARCHAR(50) NOT NULL DEFAULT 'default' COMMENT '主题';

ALTER TABLE `db_customer` ADD `logo` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'logo';

# ALTER TABLE `db_article` ADD `is_recommend` TINYINT(1) NOT NULL DEFAULT '2' COMMENT '是否推荐';

