<?php
/**
 * PHPer by Wherein.
 * User: yzm
 * Date: 17/12/4
 * Time: 22:51
 */
namespace Customer\Controller;
use Common\Controller\CustomerBaseController;
class ClientController extends CustomerBaseController{
    public function index(){
        $this->info();
    }

    /*
     * 基本资料
     */
    public function info() {
        $customer_id = decrypt(session('customer_info.customer_id'));
        if (IS_AJAX) {
            $Customer = M('Customer');
            $Customer->telephone = I('post.telephone');
            $Customer->qq = I('post.qq');
            $Customer->wechat_id = I('post.wechat_id');

            $wechat_qrcode = I('post.wechat_qrcode');
            $wechat_qrcode_ = $this->uploadPic($wechat_qrcode, 'qrcode');
            if ($wechat_qrcode_['code'] !==  200) {
                $this->ajaxReturn(array('code' => $wechat_qrcode_['code'], 'msg' => $wechat_qrcode_['msg']));
            }
            $Customer->wechat_qrcode = $wechat_qrcode_['path'];

            $logo = I('post.logo');
            $logo_ = $this->uploadPic($logo, 'logo');
            if ($logo_['code'] !==  200) {
                $this->ajaxReturn(array('code' => $logo_['code'], 'msg' => $logo_['msg']));
            }
            $Customer->logo = $logo_['path'];

            $Customer->title = I('post.title');
            $Customer->keywords = I('post.keywords');
            $Customer->description = I('post.description');
            $Customer->beian = I('post.beian');
            $Customer->company = I('post.company');
            $Customer->address = I('post.address');
            $Customer->introduce = I('post.introduce');
            $Customer->theme = I('post.theme');
            $result = $Customer->where(array('customer_id' => $customer_id))->save();
            if ($result) {
                $this->ajaxReturn(array('code' => 200, 'msg' => '更新成功'));
            } else {
                $this->ajaxReturn(array('code' => 200, 'msg' => '更新失败'));
            }
        } else {
            $Customer = M('Customer');
            $user_data = $Customer->find($customer_id);
            $user_data['logo_display'] = !empty(tomedia($user_data['logo'])) ? tomedia($user_data['logo']) : '/Public/image/upload.png';
            $user_data['wechat_qrcode_display'] = !empty(tomedia($user_data['wechat_qrcode'])) ? tomedia($user_data['wechat_qrcode']) : '/Public/image/upload.png';
            $this->assign('user', $user_data);
            // theme
            $Theme = M('Theme');
            $theme_list = $Theme->select();
            $this->assign('theme_list', $theme_list);
            $this->display();
        }
    }

    /*
     * 修改密码
     */
    public function password() {
        if (IS_AJAX) {
            $customer_id = session('customer_info.customer_id');
            if (empty($customer_id)) {
                $this->redirect('Login/index');
            }
            if (I('post.password') !== I('post.sure_password')) {
                $this->ajaxReturn(array('code' => 304, 'msg' => '两次密码不一样'));
            }
            $Customer = M('Customer');
            $customer_data = $Customer->find(decrypt($customer_id));
            if (empty($customer_data)) {
                $this->ajaxReturn(array('code' => 400, 'msg' => '找不到数据'));
            }
            if ($customer_data['password'] === encry_pwd(I('post.password'))) {
                $this->ajaxReturn(array('code' => 304, 'msg' => '不能和原密码一样'));
            }
            $Customer->password = encry_pwd(I('post.password'));
            $result = $Customer->where(array('customer_id' => decrypt($customer_id)))->save();
            if (!is_error($result)) {
                session('openid', null);
                session('customer_info', null);
                $this->ajaxReturn(array('code' => 200, 'msg' => '修改密码成功，请重新登录'));
            } else {
                $this->ajaxReturn(array('code' => 304, 'msg' => '修改密码失败'));
            }
        } else {
            $this->display();
        }
    }
}