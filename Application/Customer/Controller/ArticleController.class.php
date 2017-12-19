<?php
/**
 * PHPer by Wherein.
 * User: yzm
 * Date: 17/12/7
 * Time: 0:46
 */
namespace Customer\Controller;
use Common\Controller\CustomerBaseController;
class ArticleController extends CustomerBaseController {
    public function index(){
        $Article = M('Article');
        $where = array();
        if (!empty(I('get.cid'))) {
            $where['cate_id'] = I('get.cid');
        }
        $total = $Article->where($where)->count();
        $page = $this->getPageInfo($total, I('page', 1));

        $article_list = $Article->where($where)->limit($page['offset'], $page['limit'])->select();
        if (!empty($article_list)) {
            foreach ($article_list as $key => $value) {
                $article_list[$key]['add_time'] = format($value['add_time'], 'Y-m-d');
                $article_list[$key]['article_thumb'] = tomedia($value['article_thumb']);
                $article_list[$key]['pc_pic'] = tomedia($value['pc_pic']);
                $article_list[$key]['article_info'] = mb_strlen($value['article_info']) > 50 ? mb_substr($value['article_info'], 0, 50) . '...' : $value['article_info'];
            }
        }
        $this->assign('page', $page);
        $this->assign('article_list', $article_list);
        $this->display();
    }

    /*
     * 文章详情
     */
    public function detail() {
        $article_id = I('get.aid');
        $article_data = M('Article')->find($article_id);
        $article_data['add_time'] = format($article_data['add_time'], 'Y-m-d');
        $this->assign('item', $article_data);
        $this->display();
    }
}