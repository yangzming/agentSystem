<?php
/**
 * PHPer by Wherein.
 * User: yzm
 * Date: 17/12/7
 * Time: 0:46
 */
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class ProductController extends AdminBaseController {
    public function index(){
        if (IS_AJAX) {
            $Product = M('Product');
            $where = array();
            if (!empty(I('post.search'))) {
                $search = I('post.search');
                $where['product_number'] = array('like', '%' . $search . '%');
                $where['product_title'] = array('like', '%' . $search . '%');
                $where['_logic'] = 'or';
            }
            $total = $Product->where($where)->count();

            $page = $this->getPageInfo($total, I('page', 1), I('limit', 10));

            $product_list = $Product->where($where)->limit($page['offset'], $page['limit'])->select();
            if (!empty($product_list)) {
                $Cate = M('ProductCategory');
                foreach ($product_list as $key => $value) {
                    $product_list[$key]['index'] = ($key+1) + ($page['limit'] * ($page['offset']/$page['limit']));
                    $product_list[$key]['cate_name'] = $Cate->where(array('cate_id' => $value['cate_id']))->getField('cate_name');
                }
            }
            $out['code'] = 0;
            $out['msg'] = '成功';
            $out['count'] = $total;
            $out['data'] = $product_list;
            $this->ajaxReturn($out);
        } else {
            $this->display();
        }
    }

    /*
     * 产品列表
     */
    public function operate() {
        if (IS_AJAX) {
            $Product = M('Product');
            $Product->cate_id = I('post.cate_id');
            $Product->product_title = I('post.product_title');
            $Product->product_number = I('post.product_number');

            $Product->product_thumb = I('post.product_thumb');
            $Product->pc_pic = I('post.pc_pic');
            $Product->wap_pic = I('post.wap_pic');

            $Product->product_info = I('post.product_info');
            $Product->product_content = I('post.product_content');
            $Product->keywords = I('post.keywords');
            $Product->description = I('post.description');
            $Product->price = I('post.price');
            $Product->demourl = I('post.demourl');
            if (!empty(I('post.pid'))) {
                // edit
                $result = $Product->where(array('product_id' => I('post.pid')))->save();
                if (is_error($result)) {
                    $this->ajaxReturn(array('code' => 400, 'msg' => '更新失败'));
                } else {
                    $this->ajaxReturn(array('code' => 200, 'msg' => '更新成功'));
                }
            } else {
                // add
                $Product->add_time = time();
                $result = $Product->add();
                if (is_error($result)) {
                    unlink_pic(I('post.product_thumb'));
                    unlink_pic(I('post.pc_pic'));
                    unlink_pic(I('post.wap_pic'));
                    $this->ajaxReturn(array('code' => 400, 'msg' => '添加失败'));
                } else {
                    $this->ajaxReturn(array('code' => 200, 'msg' => '添加成功'));
                }
            }
        } else {
            //分类列表
            $Cate = M('ProductCategory');
            $cate_list = $Cate->select();
            $this->assign('cate_list', $cate_list);

            $product_data = array();
            $product_data = M('Product')->find(I('get.pid'));

            $this->assign('product', $product_data);
            $this->display();
        }
    }

    /*
     * 产品删除
     */
    public function del() {
        $Product = M('Product');
        $product_id = I('post.product_id');
        $old_data = $Product->field(array('product_thumb', 'pc_pic', 'wap_pic'))->find($product_id);
        $result = $Product->delete($product_id);
        if (is_error($result)) {
            $this->ajaxReturn(array('code' => 400, 'msg' => '删除失败'));
        } else {
            //删除相关图片
            unlink_pic($old_data['product_thumb']);
            unlink_pic($old_data['pc_pic']);
            unlink_pic($old_data['wap_pic']);
            $this->ajaxReturn(array('code' => 200, 'msg' => '删除成功'));
        }
    }

    /*
     * 分类列表
     */
    public function cate() {
        if (IS_AJAX) {
            $Cate = M('ProductCategory');
            $total = $Cate->count();
            $page = $this->getPageInfo($total, I('page', 1), I('limit', 10));

            $cate_list = $Cate->limit($page['offset'], $page['limit'])->select();
            if (!empty($cate_list)) {
                foreach ($cate_list as $key => $value) {
                    $cate_list[$key]['index'] = ($key+1) + ($page['limit'] * ($page['offset']/$page['limit']));
                    $cate_list[$key]['update_time'] = format($value['update_time']);
                }
            }
            $out['code'] = 0;
            $out['msg'] = '成功';
            $out['count'] = $total;
            $out['data'] = $cate_list;
            $this->ajaxReturn($out);
        }
        $this->display();
    }

    /*
     * 操作分类：添加/修改
     */
    public function cateOperate() {
        $Cate = M('ProductCategory');
        $Cate->cate_name = I('post.cate_name');
        $Cate->update_time = time();
        if (empty(I('post.cate_id'))) {
            $result = $Cate->add();
            if (is_error($result)) {
                $this->ajaxReturn(array('code' => 400, 'msg' => '添加失败'));
            } else {
                $this->ajaxReturn(array('code' => 200, 'msg' => '添加成功'));
            }
        } else {
            $result = $Cate->where(array('cate_id' => I('post.cate_id')))->save();
            if (is_error($result)) {
                $this->ajaxReturn(array('code' => 400, 'msg' => '修改失败'));
            } else {
                $this->ajaxReturn(array('code' => 200, 'msg' => '修改成功'));
            }
        }
    }

    /*
     * 分类删除
     */
    public function cateDel() {
        $Cate = M('ProductCategory');
        $cate_id = I('post.cate_id');
        $result = $Cate->delete($cate_id);
        if (is_error($result)) {
            $this->ajaxReturn(array('code' => 400, 'msg' => '删除失败'));
        } else {
            $this->ajaxReturn(array('code' => 200, 'msg' => '删除成功'));
        }
    }

    /*
     * 上传图片
     */
    public function uploadPhoto() {
        $this->comUploadPic($_FILES, strtolower(CONTROLLER_NAME));
    }

}