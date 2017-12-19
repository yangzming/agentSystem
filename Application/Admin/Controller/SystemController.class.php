<?php
/**
 * PHPer by Wherein.
 * User: yzm
 * Date: 17/12/4
 * Time: 15:43
 */
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class SystemController extends AdminBaseController {
    public function index(){}

    /*
     * 修改密码
     */
    public function password() {
        if (IS_AJAX) {
            $admin_name = session('a_info.admin_name');
            if (empty($admin_name)) {
                $this->redirect('Login/index');
            }
            if (I('post.password') !== I('post.sure_password')) {
                $this->ajaxReturn(array('code' => 304, 'msg' => '两次密码不一样'));
            }
            $Admin = M('Admin');
            $Admin->admin_pwd = encry_pwd(I('post.password'));
            $result = $Admin->where(array('admin_name' => decrypt($admin_name)))->save();
            if ($result) {
                session('a_openid', null);
                session('a_info', null);
                $this->ajaxReturn(array('code' => 200, 'msg' => '修改密码成功，请重新登录'));
            } else {
                $this->ajaxReturn(array('code' => 304, 'msg' => '修改密码失败'));
            }
        } else {
            $this->display();
        }
    }

}