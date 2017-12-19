<?php
namespace Common\Controller;
use Common\Controller\BaseController;
class CustomerBaseController extends BaseController {
    public function _initialize() {
        if (session('openid') !== create_session_id(decrypt(session('customer_info.username')))) {
            $this->redirect('Login/index');
        }

        // 文章分类
        $Cate = M('ArticleCategory');
        $article_cate_list = $Cate->select();
        $this->assign('article_cate_list', $article_cate_list);
    }


}