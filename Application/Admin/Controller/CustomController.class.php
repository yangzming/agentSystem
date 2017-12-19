<?php
/**
 * PHPer by Wherein.
 * User: yzm
 * Date: 17/12/3
 * Time: 21:14
 */
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class CustomController extends AdminBaseController {
    protected $status_name = array(
        1 => '已授权',
        2 => '禁止登录',
    );

    public function index(){
        if (IS_AJAX) {
            $Customer = M('Customer');
            $where = array();
            if (!empty(I('post.search'))) {
                $search = I('post.search');
                $where['username'] = array('like', '%' . $search . '%');
                $where['telephone'] = array('like', '%' . $search . '%');
                $where['website'] = array('like', '%' . $search . '%');
                $where['_logic'] = 'or';
            }
            $total = $Customer->where($where)->count();
            $page = $this->getPageInfo($total, I('page', 1), I('limit', 10));
            $customer_list = $Customer->where($where)->limit($page['offset'], $page['limit'])->select();

            if (!empty($customer_list)) {
                foreach ($customer_list as $key => $value) {
                    $customer_list[$key]['index'] = ($key+1) + ($page['limit'] * ($page['offset']/$page['limit']));
                    $customer_list[$key]['status_name'] = $this->status_name[$value['status']];
                    $customer_list[$key]['add_time'] = format($value['add_time']);
                }
            }
            $out['code'] = 0;
            $out['msg'] = '成功';
            $out['count'] = $total;
            $out['data'] = $customer_list;
            $this->ajaxReturn($out);
        }
        $this->display();
    }

    /*
     * 授权用户登录
     */
    public function auth() {
        $Customer = M('Customer');
        $customer_id = I('post.customer_id');
        $status = I('post.status');
        $Customer->status = $status == 1 ? 2 : 1;
        $result = $Customer->where(array('customer_id' => $customer_id))->save();
        if (is_error($result)) {
            $this->ajaxReturn(array('code' => 400, 'msg' => '授权失败'));
        } else {
            $this->ajaxReturn(array('code' => 200, 'msg' => '授权成功'));
        }
    }
    /*
     * 删除客户
     */
    public function del() {
        $Customer = M('Customer');
        $customer_id = I('post.customer_id');
        $result = $Customer->delete($customer_id);
        if (is_error($result)) {
            $this->ajaxReturn(array('code' => 400, 'msg' => '删除失败'));
        } else {
            $this->ajaxReturn(array('code' => 200, 'msg' => '删除成功'));
        }
    }

    /*
     * 修改字段
     */
    public function edit() {
        $Customer = M('Customer');
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
            if (!empty(I('post.password'))) {
                $Customer->password = encry_pwd(I('post.password'));
            }
            $result = $Customer->where(array('customer_id' => I('post.cid')))->save();
            if (is_error($result)) {
                $this->ajaxReturn(array('code' => 400, 'msg' => '更新失败'));
            } else {
                $this->ajaxReturn(array('code' => 200, 'msg' => '更新成功'));
            }
        } else {
            $user_data = $Customer->find(I('get.cid'));
            $user_data['logo_display'] = !empty(tomedia($user_data['logo'])) ? tomedia($user_data['logo']) : '/Public/image/upload.png';
            $user_data['wechat_qrcode_display'] = !empty(tomedia($user_data['wechat_qrcode'])) ? tomedia($user_data['wechat_qrcode']) : '/Public/image/upload.png';
            $this->assign('user', $user_data);
            $this->display();
        }
    }


}