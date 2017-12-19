<?php
/**
 * PHPer by Wherein.
 * User: yzm
 * Date: 17/12/18
 * Time: 22:23
 */
namespace Common\Controller;
use Think\Controller;
class BaseController extends Controller {


    /*
     * 获取offset和limit
     * return array
     */
    public function getPageInfo($total, $page = 1, $limit = 10) {
        $page_info = array();

        $page = !empty($page) ? intval($page) : 1;
        $max_page = ceil($total/$limit) < 1 ? $page : ceil($total/$limit);
        if ($page < 1) $page = 1;
        if ($page > $max_page) $page = $max_page;
        $offset = ($page - 1) * $limit;

        $page_info = array(
            'limit' => intval($limit),
            'offset' => intval($offset),
            'total' => $total,
            'page' => $page
        );
        return $page_info;
    }

    /*
     * 图片处理
     */
    public function uploadPic($pic, $path_folder) {
        if (!empty($pic)) {
            $path = C('UPLOAD_DIR') . '/' . $path_folder . '/';
            mkdirs(__ROOT__ . $path);
            if (!is_dir(__ROOT__ . $path)) {
                return array('code' => 400, 'msg' => '目录创建失败，目录不存在');
            }
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $pic, $res)){
                $ext = $res[2];
                $filename = date('YmdHi') . mt_rand(1000, 9999) . '.' . $ext;
                $fullname = __ROOT__ . $path . $filename;

                if (file_put_contents($fullname, base64_decode(str_replace($res[1], '', $pic)))){
                    return array('code' => 200, 'path' => $path_folder . '/' . $filename);
                } else {
                    return array('code' => 403, 'msg' => '生成图片失败');
                }
            } else {
                return array('code' => 200, 'path' => $pic);
            }
        } else {
            return array('code' => 200, 'path' => $pic);
        }
    }
}