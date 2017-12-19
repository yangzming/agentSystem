<?php
return array(
	//'配置项'=>'配置值'

    //模版替换
    'TMPL_PARSE_STRING' => array(
        '__CUSTOMER__' => __ROOT__.'/Public/Customer',
    ),
    'SESSION_OPTIONS' => array(
        'expire' => 3600, //过期时间为3600
    ),
);