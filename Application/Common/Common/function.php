<?php
/**
 * PHPer by Wherein.
 * User: yzm
 * Date: 17/12/1
 * Time: 18:22
 */

/*
 * 登录密码处理
 * @params
 * return string
 */
function encry_pwd($pwd) {
    if (is_error($pwd)) {
        return '';
    }
    $encry_pwd = substr(strrev(md5($pwd)), 5, 20);
    return $encry_pwd;
}

/*
 * 数据加密
 * @params $data 需要加密的数据
 * return $data
 */
function encrypt($data) {
    $data = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, 'abcdef1234567890', $data, MCRYPT_MODE_CBC, '0987654321fedcba'));
    return $data;
}

/*
 * 数据解密
 * @params $data 需要解密的数据
 * return $data
 */
function decrypt($data) {
    $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, 'abcdef1234567890', base64_decode($data), MCRYPT_MODE_CBC, '0987654321fedcba');
    $data = rtrim($data);
    return $data;
}

/*
 * 生成登录唯一的session_id
 * return string
 */
function create_session_id($user = '') {
    $user = !empty($user) ? $user : 'wherein';
    $session_id = $user . date('YmdH');
    $session_id = substr(md5(base64_encode($session_id)), 5, 20);
    return $session_id;
}

/*
 * 判断错误
 * @params $data [string,array]
 */
function is_error($data) {
    if (empty($data) || $data === false || ($data['code'] !== 200) || $data === 0) {
        return false;
    }
    return true;
}

/*
 * 日期格式化 时间戳转为类型日期
 * @params int $timestamp
 * @params string $format
 * return string
 */
function format($timestamp, $format = 'Y-m-d H:i:s') {
    return date($format, $timestamp);
}

/*
 * 创建文件夹
 * @params string $path 路径
 * return string
 */
function mkdirs($path) {
    if (!is_dir($path)) {
        mkdirs(dirname($path));
        mkdir($path);
    }
    return is_dir($path);
}

/*
 * 图片路径
 */
function tomedia($src = '') {
    if (empty($src)) {
        return '';
    }
    if (file_exists(IA_ROOT . '/' . C('UPLOAD_DIR') . '/' . $src)) {
        $src = SITEROOT . C('UPLOAD_DIR') . '/' . $src;
    }
    return $src;
}

/*
 * 删除图片
 */
function unlink_pic($src) {
    if (file_exists(IA_ROOT . '/' . C('UPLOAD_DIR') . '/' . $src)) {
        unlink(IA_ROOT . '/' . C('UPLOAD_DIR') . '/' . $src);
    }
    return true;
}


/*
 * 测试数据
 */
function p() {
    $getArgs = func_get_args();
    if (in_array(end($getArgs), ['y', 'n'])) {
        $is_exit = end($getArgs);
        array_pop($getArgs);
    } else {
        $is_exit = 'y';
    }
    foreach ($getArgs as $key => $value) {
        echo '<pre>';
        var_dump($value);
    }
    if ($is_exit == 'y') {
        exit;
    }
}