<?php
namespace Home\Controller;

class HomeController extends \Think\Controller
{
	protected function _initialize()
	{
		//defined('APP_DEMO') || define('APP_DEMO', 0);

		if (!session('userId')) {
			session('userId', 0);
		}
		else if (CONTROLLER_NAME != 'Login') {
			
			$user = (QQ3479015851_DEBUG ? null : S('userinfo'.session('userId')));
			
			if(!$user){
				$user = D('user')->where('id = ' . session('userId'))->find();
				S('userinfo'.session('userId'),$user);
			}
			
			if (!$user['paypassword']) {
				redirect('/Login/paypassword');
			}

			if (!$user['truename']) {
				redirect('/Login/truename');
			}
			
			
			if($user['token']!=session('token_user')){
				//登录
				session(null);
				session('qq3479015851_already',1);
				redirect('/');
			}
			
		}

		
		
		if(WAP_URL !=""){
			$ua = @$_SERVER['HTTP_USER_AGENT'];

			if(preg_match('/(iphone|android|Windows\sPhone)/i', $ua)){
				$qq3479015851_redirect="";
				if (isset($_GET['invit'])) {
					$invit = $_GET['invit'];
					$user = M('User')->where(array('invit' => $invit))->find();

					if ($user['id']) {
						$qq3479015851_redirect = WAP_URL."/Login/register/invit/".$user['id'];
					}else{
						$qq3479015851_redirect = WAP_URL;
					}
				}else{
					$qq3479015851_redirect = WAP_URL;
				}
				header("Location:".$qq3479015851_redirect);
				die();
			} 
		}
		
		//20170511 QQ3479015851 增加获取币类型函数
		
		//C('coin_menu_qq3479015851',array('CNY','BTC','ETH'));
		
		
		
		
		if (userid()) {
			$userCoin_top = M('UserCoin')->where(array('userid' => userid()))->find();
			$userCoin_top['cny'] = round($userCoin_top['cny'], 2);
			$userCoin_top['cnyd'] = round($userCoin_top['cnyd'], 2);
			$userCoin_top['allcny'] = round($userCoin_top['cny']+$userCoin_top['cnyd'],2);
			$this->assign('userCoin_top', $userCoin_top);
		}

		if (isset($_GET['invit'])) {
			session('invit', $_GET['invit']);
		}

		
		
		$config = (QQ3479015851_DEBUG ? null : S('home_config'));

		if (!$config) {
			$config = M('Config')->where(array('id' => 1))->find();
			
			S('home_config', $config);
		}


		if (!$config['web_close']) {
			exit($config['web_close_cause']);
		}


		C($config);
		C('contact_qq', explode('|', C('contact_qq')));
		C('contact_qqun', explode('|', C('contact_qqun')));
		C('contact_bank', explode('|', C('contact_bank')));

		$coin = (QQ3479015851_DEBUG ? null : S('home_coin'));
	
		if (!$coin) {
			$coin = M('Coin')->where(array('status' => 1))->select();
			S('home_coin', $coin);
		}
		
		$coinList = array();

		foreach ($coin as $k => $v) {
			$coinList['coin'][$v['name']] = $v;

			if ($v['name'] != 'cny') {
				$coinList['coin_list'][$v['name']] = $v;
			}

			if ($v['type'] == 'rmb') {
				$coinList['rmb_list'][$v['name']] = $v;
			}
			else {
				$coinList['xnb_list'][$v['name']] = $v;
			}

			if ($v['type'] == 'rgb') {
				$coinList['rgb_list'][$v['name']] = $v;
			}

			if ($v['type'] == 'qbb') {
				$coinList['qbb_list'][$v['name']] = $v;
			}
		}

		
		
		
		C($coinList);
		
		$market = (QQ3479015851_DEBUG ? null : S('home_market'));
		
		
		
		
		$market_type = array();
		$coin_on = array();
		
		if (!$market) {
			$market = M('Market')->where(array('status' => 1))->select();
			
			S('home_market', $market);
		}
		

		
		
		

		foreach ($market as $k => $v) {
			
			if(!$v['round']){
				$v['round'] = 4;
			}
			
			$v['new_price'] = round($v['new_price'], $v['round']);
			$v['buy_price'] = round($v['buy_price'], $v['round']);
			$v['sell_price'] = round($v['sell_price'], $v['round']);
			$v['min_price'] = round($v['min_price'], $v['round']);
			$v['max_price'] = round($v['max_price'], $v['round']);
			$v['xnb'] = explode('_', $v['name'])[0];
			$v['rmb'] = explode('_', $v['name'])[1];
			$v['xnbimg'] = C('coin')[$v['xnb']]['img'];
			$v['rmbimg'] = C('coin')[$v['rmb']]['img'];
			$v['volume'] = $v['volume'] * 1;
			$v['change'] = $v['change'] * 1;
			$v['title'] = C('coin')[$v['xnb']]['title'] . '(' . strtoupper($v['xnb']) . '/' . strtoupper($v['rmb']) . ')';
			$v['navtitle'] = C('coin')[$v['xnb']]['title'] . '(' . strtoupper($v['xnb']). ')';
			
			if($v['begintrade']){
				$v['begintrade'] = $v['begintrade'];
			}else{
				$v['begintrade'] = "00:00:00";
			}
			if($v['endtrade']){
				$v['endtrade']    = $v['endtrade'];
			}else{
				$v['endtrade']    = "23:59:59";
			}
			
			
			
			$market_type[$v['xnb']]=$v['name'];
			$coin_on[]= $v['xnb'];
			$marketList['market'][$v['name']] = $v;
		}
	
		C('market_type',$market_type);
		C('coin_on',$coin_on);
	
		C($marketList);
		$C = C();

		foreach ($C as $k => $v) {
			$C[strtolower($k)] = $v;
		}

		$this->assign('C', $C);
		$this->kefu = './Application/Home/View/Kefu/' . $C['kefu'] . '/index.html';
		

		if (!S('daohang')) {
			$this->daohang = M('Daohang')->where(array('status' => 1))->order('sort asc')->select();
			S('daohang', $this->daohang);
		}
		else {
			$this->daohang = S('daohang');
		}

		$footerArticleType = (QQ3479015851_DEBUG ? null : S('footer_indexArticleType'));

		if (!$footerArticleType) {
			$footerArticleType = M('ArticleType')->where(array('status' => 1, 'footer' => 1, 'shang' => ''))->order('sort asc ,id desc')->limit(3)->select();
			S('footer_indexArticleType', $footerArticleType);
		}

		$this->assign('footerArticleType', $footerArticleType);
		$footerArticle = (QQ3479015851_DEBUG ? null : S('footer_indexArticle'));

		if (!$footerArticle) {
			foreach ($footerArticleType as $k => $v) {
				$footerArticle[$v['name']] = M('ArticleType')->where(array('shang' => $v['name'], 'footer' => 1, 'status' => 1))->order('id asc')->limit(4)->select();
			}

			S('footer_indexArticle', $footerArticle);
		}
		
		
		$this->assign('footerArticle', $footerArticle);
		
	}

    public function _empty() {
        send_http_status(404);
        $this->error();
        echo '模块不存在！';
        die();

    }
	
}

?>