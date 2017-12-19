<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        if (IS_POST) {
            $Admin = M('Admin');
            $data = array();
            $data['admin_name'] = I('post.admin_name');
            $data['admin_pwd'] = encry_pwd(I('post.admin_pwd'));
            $result = $Admin->where($data)->find();
            if ($result) {
                // 登录处理
                session(array('expire' => 3600));
                session('a_openid', create_session_id($result['admin_name']));
                session('a_info', array(
                    'admin_id' => encrypt($result['admin_id']),
                    'admin_name' => encrypt($result['admin_name']),
                ));
                $this->ajaxReturn(array('code' => 200, 'msg' => '登录成功，正在跳转...'));
            } else {
                $this->ajaxReturn(array('code' => 404, 'msg' => '请检查用户名和密码'));
            }
        } else {
            $this->display();
        }
    }

    /*
     * 退出登录
     */
    public function loginOut() {
        session('a_openid', null);
        session('a_info', null);
        $this->ajaxReturn(array('code' => 200, 'msg' => '退出成功'));
    }
}