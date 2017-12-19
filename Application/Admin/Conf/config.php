<?php
return array(
	//'配置项'=>'配置值'

    //模版替换
    'TMPL_PARSE_STRING' => array(
        '__ADMIN__' => __ROOT__.'/Public/Admin',
    ),

    'SESSION_OPTIONS' => array(
        'expire' => 3600, //过期时间为3600
    ),
);