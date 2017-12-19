<?php
/**
 * PHPer by Wherein.
 * User: yzm
 * Date: 17/12/7
 * Time: 0:46
 */
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class ThemeController extends AdminBaseController {
    public function index(){
        if (IS_AJAX) {
            $Theme = M('Theme');
            $total = $Theme->count();

            $page = $this->getPageInfo($total, I('page', 1), I('limit', 10));

            $theme_list = $Theme->limit($page['offset'], $page['limit'])->select();

            $out['code'] = 0;
            $out['msg'] = '成功';
            $out['count'] = $total;
            $out['data'] = $theme_list;
            $this->ajaxReturn($out);
        } else {
            $this->display();
        }
    }

    /*
     * 操作主题：添加/修改
     */
    public function operate() {
        $Theme = M('Theme');
        $Theme->theme_name = I('post.theme_name');

        //查询是否存在主题
        $res = $Theme->where(array('theme_name' => I('post.theme_name')))->find();
        if ($res) {
            $this->ajaxReturn(array('code' => 302, 'msg' => '该主题已存在'));
        }

        if (empty(I('post.theme_id'))) {
            $result = $Theme->add();
            if (is_error($result)) {
                $this->ajaxReturn(array('code' => 400, 'msg' => '添加失败'));
            } else {
                $this->ajaxReturn(array('code' => 200, 'msg' => '添加成功'));
            }
        } else {
            $result = $Theme->where(array('theme_id' => I('post.theme_id')))->save();
            if (is_error($result)) {
                $this->ajaxReturn(array('code' => 400, 'msg' => '修改失败'));
            } else {
                $this->ajaxReturn(array('code' => 200, 'msg' => '修改成功'));
            }
        }
    }

    /*
     * 主题删除
     */
    public function del() {
        $Theme = M('Theme');
        $theme_id = I('post.theme_id');
        $result = $Theme->delete($theme_id);
        if (is_error($result)) {
            $this->ajaxReturn(array('code' => 400, 'msg' => '删除失败'));
        } else {
            $this->ajaxReturn(array('code' => 200, 'msg' => '删除成功'));
        }
    }

}