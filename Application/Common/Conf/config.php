<?php
return array(
	//'配置项'=>'配置值'
    'LOAD_EXT_CONFIG' => 'config.db', //加载扩展配置文件
    'URL_PATHINFO_FETCH' => 'ORIG_PATH_INFO,REDIRECT_PATH_INFO,REDIRECT_URL',
    'TMPL_FILE_DEPR' => '_', //修改模板目录层次

    // 允许访问的模块列表
    'MODULE_ALLOW_LIST' => array('Admin', 'Customer'),
    'DEFAULT_MODULE' => 'Customer',  // 默认模块

    'URL_HTML_SUFFIX' => '',

    'UPLOAD_DIR' => 'Upload', //上传文件的目录
);