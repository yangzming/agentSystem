<?php
namespace Common\Controller;
use Common\Controller\BaseController;
class AdminBaseController extends BaseController {
    public function _initialize() {
        $Admin = M('Admin');
        $result = $Admin->find();
        if (empty($result)) {
            //创建新用户
            $data = array();
            $data['admin_name'] = 'admin';
            $data['admin_pwd'] = encry_pwd('123456');
            $data['add_time'] = time();
            $Admin->add($data);
        }
        if (session('a_openid') !== create_session_id(decrypt(session('a_info.admin_name')))) {
            $this->redirect('Login/index');
        }
    }


    public function comUploadPic($file, $path_folder = '') {
        if (empty($path_folder)) {
            $this->ajaxReturn(array('code' => 302, 'msg' => '请设置上传的子目录'));
        }
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =  3145728 ;// 设置附件上传大小
        $upload->exts      =  array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =  './' . C('UPLOAD_DIR') . '/' . $path_folder . '/'; // 设置附件上传根目录
        $upload->savePath  =  date('Ym') . '/'; // 设置附件上传（子）目录
        $upload->autoSub   = false;
        // 上传文件
        $info   =   $upload->uploadOne($file['file']);
        if(!$info) {// 上传错误提示错误信息
            $this->ajaxReturn(array('code' => 400, 'msg' => $upload->getError()));
        }else{// 上传成功
            $this->ajaxReturn(array('code' => 200, 'msg' => '上传成功', 'path' => $path_folder . '/' . $info['savepath'] . $info['savename']));
        }
    }
}