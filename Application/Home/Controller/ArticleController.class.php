<?php
namespace Home\Controller;

class ArticleController extends HomeController
{
	public function index($id = 19)
	{
		if (empty($id)) {
			redirect(U('Article/detail'));
		}

		if (!check($id, 'd')) {
			redirect(U('Article/detail'));
		}

		$Articletype = QQ3479015851_DEBUG ? null : S('Articletype'.$id);
		
		if(!$Articletype){
			$Articletype = M('ArticleType')->where(array('id' => $id))->find();
			S('Articletype'.$id,$Articletype);
		}
		
		
		$ArticleTypeList = QQ3479015851_DEBUG ? null : S('ArticleTypeList_list'.$Articletype['shang']);
		if(!$ArticleTypeList){
			$ArticleTypeList = M('ArticleType')->where(array('status' => 1, 'index' => 1, 'shang' => $Articletype['shang']))->order('sort asc ,id asc')->select();
			S('ArticleTypeList_list'.$Articletype['shang'],$ArticleTypeList);
		}
		
		$Articleaa = QQ3479015851_DEBUG ? null : S('Articleaa'.$ArticleTypeList[0]['id']);
		
		if(!$Articleaa){
			$Articleaa = M('Article')->where(array('status'=>1,'id' => $ArticleTypeList[0]['id']))->find();
			S('Articleaa'.$ArticleTypeList[0]['id'],$Articleaa);
		}
		
		$this->assign('shang', $Articletype);

		foreach ($ArticleTypeList as $k => $v) {
			$ArticleTypeLista[$v['name']] = $v;
		}

		$this->assign('ArticleTypeList', $ArticleTypeLista);
		$this->assign('data', $Articleaa);
		$where = array('type' => $Articletype['name'],'status'=>1);
		$Model = M('Article');
		
		$count = QQ3479015851_DEBUG ? null : S('count'.$Articletype['id']);
		if(!$count){
			$count = $Model->where($where)->count();
			S('count'.$Articletype['id'],$count);
		}
		
		
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		
		$list = QQ3479015851_DEBUG ? null : S('list'.$Articletype['id']);
		if(!$list){
			$list = $Model->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			S('list'.$Articletype['id'],$list);
		}
		
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	
	public function upgrade(){
		$this->display();
	}
	
	public function detail($id = NULL)
	{   
		if (empty($id)) {
			$id = 1;
		}

		if (!check($id, 'd')) {
			$id = 1;
		}
		
		$data = QQ3479015851_DEBUG ? NULL : S('ArticleDetail_'.$id);
		
		
		
		if(!$data){
			$data = M('Article')->where(array('id' => $id))->find();
			 S('ArticleDetail_'.$id,$data);
		}
		
		$ArticleTypeList =  QQ3479015851_DEBUG ? NULL : S('ArticleTypeList');
		
		if(!$ArticleTypeList){
		
			$ArticleType = M('ArticleType')->where(array('status' => 1, 'index' => 1))->order('sort asc ,id desc')->select();

			foreach ($ArticleType as $k => $v) {
				$ArticleTypeList[$v['name']] = $v;
			}
			S('ArticleTypeList',$ArticleTypeList);
		}
		
		$this->assign('ArticleTypeList', $ArticleTypeList);
		$this->assign('data', $data);
		$this->assign('type', $data['type']);
		$this->display();
	}

	public function type($id = NULL)
	{
		if (empty($id)) {
			$id = 1;
		}

		if (!check($id, 'd')) {
			$id = 1;
		}
	
		$Article = QQ3479015851_DEBUG ? NULL : S('ArticleTypePage'.$id);
		if(!$Article){
			$Article = M('ArticleType')->where(array('id' => $id))->find();
			S('ArticleTypePage'.$id,$Article);
		}
		
		if ($Article['shang']) {
			
			$shang = QQ3479015851_DEBUG ? NULL : S('ArticleTypeshang'.$Article['shang']);
			if(!$shang){
				$shang = M('ArticleType')->where(array('name' => $Article['shang']))->find();
				S('ArticleTypeshang'.$Article['shang'],$shang);
			}
			$ArticleType = QQ3479015851_DEBUG ? NULL : S('ArticleTypeType'.$Article['shang']);
			if(!$ArticleType){
				$ArticleType = M('ArticleType')->where(array('status' => 1, 'shang' => $Article['shang']))->order('sort asc ,id desc')->select();
				S('ArticleTypeType'.$Article['shang'],$ArticleType);
			}
			$Articleaa = $Article;
		}
		else {
			$shang = QQ3479015851_DEBUG ? NULL : S('ArticleTypeshang'.$id);
			if(!$shang){
				$shang = M('ArticleType')->where(array('id' => $id))->find();
				S('ArticleTypeshang'.$id,$shang);
			}	
			$ArticleType = QQ3479015851_DEBUG ? NULL : S('ArticleTypeType'.$Article['name']);
			if(!$ArticleType){
				$ArticleType = M('ArticleType')->where(array('status' => 1, 'shang' => $Article['name']))->order('sort asc ,id desc')->select();
				S('ArticleTypeType'.$Article['name'],$ArticleType);
			}
			$Articleaa = QQ3479015851_DEBUG ? NULL : S('ArticleTypeTypeaa'.$ArticleType[0]['id']);
			if(!$Articleaa){
				$Articleaa = M('ArticleType')->where(array('id' => $ArticleType[0]['id']))->find();
				S('ArticleTypeTypeaa'.$ArticleType[0]['id'],$Articleaa);
			}
		}

		$this->assign('shang', $shang);
		
		
		foreach ($ArticleType as $k => $v) {
			$ArticleTypeList[$v['name']] = $v;
		}


		
		$this->assign('ArticleTypeList', $ArticleTypeList);
		$this->assign('data', $Articleaa);
		$this->display();
	}

}

?>