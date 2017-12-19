<?php
namespace Customer\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        if (IS_POST) {
            $Customer = M('Customer');
            $data = array();
            $data['username'] = I('post.username');
            $data['password'] = encry_pwd(I('post.password'));
            $result = $Customer->where($data)->find();
            if ($result) {
                if ($result['status'] == 2) {
                    $this->ajaxReturn(array('code' => 304, 'msg' => '该用户还没通过审核'));
                }
                // 登录处理
                session(array('expire' => 3600));
                session('openid', create_session_id($result['username']));
                session('customer_info', array(
                    'customer_id' => encrypt($result['customer_id']),
                    'username' => encrypt($result['username']),
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
     * 注册页面
     */
    public function register() {
        if (IS_POST) {
            $Customer = M('Customer');
            $data = array();
            if ($Customer->where(array('username' => I('post.username')))->find()) {
                $this->ajaxReturn(array('code' => 304, 'msg' => '该用户名[' . I('post.username') . ']已经存在'));
            }
            if ($Customer->where(array('website' => I('post.website')))->find()) {
                $this->ajaxReturn(array('code' => 304, 'msg' => '该网址[' . I('post.website') . ']已经存在'));
            }
            $data['username'] = I('post.username');
            if ($data['username'] === 'admin') {
                $this->ajaxReturn(array('code' => 304, 'msg' => 'admin不可用，请更换用户名'));
            }
            $data['password'] = encry_pwd(I('post.password'));
            $data['telephone'] = I('post.telephone');
            $data['qq'] = I('post.qq');
            $data['website'] = I('post.website');
            $data['status'] = 2;
            $data['add_time'] = time();
            if ($Customer->add($data)) {
                $this->ajaxReturn(array('code' => 200, 'msg' => '注册成功：请联系QQ387855321或拨打服务热线: 4008-168-332
审核'));
            } else {
                $this->ajaxReturn(array('code' => 200, 'msg' => '注册失败：写入数据出错'));
            }
        } else {
            $this->display();
        }
    }

    /*
     * 退出登录
     */
    public function loginOut() {
        session('openid', null);
        session('customer_info', null);
        $this->ajaxReturn(array('code' => 200, 'msg' => '退出成功'));
    }
}