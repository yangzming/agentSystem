<?php
/**
 * PHPer by Wherein.
 * User: yzm
 * Date: 17/12/7
 * Time: 0:46
 */
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class ArticleController extends AdminBaseController {
    public function index(){
        if (IS_AJAX) {
            $Article = M('Article');
            $where = array();
            if (!empty(I('post.search'))) {
                $search = I('post.search');
                $where['article_title'] = array('like', '%' . $search . '%');
            }
            $total = $Article->where($where)->count();
            $page = $this->getPageInfo($total, I('page', 1), I('limit', 10));
            $article_list = $Article->where($where)->limit($page['offset'], $page['limit'])->select();

            if (!empty($article_list)) {
                $Cate = M('ArticleCategory');
                foreach ($article_list as $key => $value) {
                    $article_list[$key]['index'] = ($key+1) + ($page['limit'] * ($page['offset']/$page['limit']));
                    $article_list[$key]['cate_name'] = $Cate->where(array('cate_id' => $value['cate_id']))->getField('cate_name');
                }
            }
            $out['code'] = 0;
            $out['msg'] = '成功';
            $out['count'] = $total;
            $out['data'] = $article_list;
            $this->ajaxReturn($out);
        } else {
            $this->display();
        }
    }

    /*
     * 文章列表
     */
    public function operate() {
        if (IS_AJAX) {
            $Article = M('Article');
            $Article->cate_id = I('post.cate_id');
            $Article->article_title = I('post.article_title');
            $Article->article_thumb = I('post.article_thumb');
            $Article->pc_pic = I('post.pc_pic');
            $Article->article_info = I('post.article_info');
            $Article->article_content = I('post.article_content');
            $Article->keywords = I('post.keywords');
            $Article->description = I('post.description');
            $Article->remarks = I('post.remarks');
            if (!empty(I('post.aid'))) {
                // edit
                $result = $Article->where(array('article_id' => I('post.aid')))->save();
                if (is_error($result)) {
                    $this->ajaxReturn(array('code' => 400, 'msg' => '更新失败'));
                } else {
                    $this->ajaxReturn(array('code' => 200, 'msg' => '更新成功'));
                }
            } else {
                // add
                $Article->add_time = time();
                $result = $Article->add();
                if (is_error($result)) {
                    unlink_pic(I('post.article_thumb'));
                    unlink_pic(I('post.pc_pic'));
                    $this->ajaxReturn(array('code' => 400, 'msg' => '添加失败'));
                } else {
                    $this->ajaxReturn(array('code' => 200, 'msg' => '添加成功'));
                }
            }
        } else {
            //分类列表
            $Cate = M('ArticleCategory');
            $cate_list = $Cate->select();
            $this->assign('cate_list', $cate_list);

            $article_data = array();
            $article_data = M('Article')->find(I('get.aid'));
            $article_data['article_thumb_display'] = tomedia($article_data['article_thumb']);
            $article_data['pc_pic_display'] = tomedia($article_data['pc_pic']);

            $this->assign('article', $article_data);
            $this->display();
        }
    }

    /*
     * 文章删除
     */
    public function del() {
        $Article = M('Article');
        $article_id = I('post.article_id');
        $old_data = $Article->field(array('article_thumb', 'pc_pic'))->find($article_id);
        $result = $Article->delete($article_id);
        if (is_error($result)) {
            $this->ajaxReturn(array('code' => 400, 'msg' => '删除失败'));
        } else {
            //删除相关图片
            unlink_pic($old_data['article_thumb']);
            unlink_pic($old_data['pc_pic']);
            $this->ajaxReturn(array('code' => 200, 'msg' => '删除成功'));
        }
    }

    /*
     * 分类列表
     */
    public function cate() {
        if (IS_AJAX) {
            $Cate = M('ArticleCategory');
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
        $Cate = M('ArticleCategory');
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
        $Cate = M('ArticleCategory');
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