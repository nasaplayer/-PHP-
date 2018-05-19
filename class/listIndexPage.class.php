<?php



class listIndexPage{
	public $host;	
	public function __construct($host){
		$this->host = $host;
		
	}
	
	public function start($itemArr=NUll){
		$itemArr = $itemArr??$this->getNavAList();
		
		$length = count($itemArr);
		
		for($i=0;$i<$length;$i++){
			
			$item = $itemArr[$i];
			$index = $this->getIndex($item);
			$this->getOneItem($item,$index);
			$this->echoRow('$index='.$index);
		}
		
		
	}//listItem
	
	
	public function getIndex($item){
		$arr = [
			'/qcmn/'=>1,
			'/xgmn/'=>2,
			'/swmt/'=>3,
			'/rhmn/'=>4,
			'/mncm/'=>5,
			'/mnmx/'=>6,
		];
		
		return $arr[$item];
	}
	
	
	public function getOneItem($item,$index){
		
		
		$url = $this->host.$item;
		$dom = $this->getDOM($url);
		
		$this->echoRow('Send=>'.$url);
		
		$maxPageNum = $this->getMaxPageNum($dom);
		
		if($item=='/rhmn/'){//修复日韩美女主页获取不到的问题
			$maxPageNum = 24;
		}
		
		
		$allCode = [];
		
		$this->echoRow('$maxPageNum=>'.$maxPageNum);
		
		for($j=0;$j<$maxPageNum;$j++){
			$pageNow = $j+1;
			//http://www.xxx.com/qcmn/list_1_4.html
			$pageUrl = $this->host.$item.'list_'.$index.'_'.$pageNow.'.html';
			
			$codeArr = $this->getOnePage($pageUrl);
			$allCode = array_merge($allCode, $codeArr);
		}
		
		foreach($allCode as $key=>$code){
			$dirName = $item;
			$host = $this->host;
			$oneInfo = new oneInfo($host,$dirName,$code);
			$oneInfo ->start();
		}
		unset($allCode);
		
	}
	
	public function echoRow($value){
		echo $value.'<br>';
	}
	
	public function getOnePage($url){
		$codeArr = [];
		$dom = $this->getDOM($url);
		
		$aList = $dom->getElementById('container')
			->getElementsByTagName('a');
		
		$n = 1;
		foreach($aList as $key=>$a){
			$n++;
			
			if($n%3==0){//一个div包含3个a标签 取其一
				$href= $a->getAttribute('href');
				$code = $this->getCode($href);
				$codeArr[] = $code;
			}
			
		}//foreach
		
		return $codeArr;
	}
	
	public function getMaxPageNum($dom){
		
		$div =  $dom->getElementById('pager');
		
		if(is_object($div)){
			$maxPageNum = $div
				->getElementsByTagName('strong')[0]
				->nodeValue;
		}else{
			$maxPageNum= 0;
		}
		
		return $maxPageNum;
	}
	
	
	public function getDOM($url=NULL){
		$url = $url??$this->host;
		$htmlStr = @file_get_contents($url);//oneCURL($url);
		$dom = new DOMDocument();
		libxml_use_internal_errors(true);//禁用错误报告
		@$dom->loadHTML($htmlStr);
		return $dom;
	}
		
	public function getNavAList(){

		$dom = $this->getDOM();
		$arr=[];
		$aList = $dom->getElementById('nav')
		->getElementsByTagName('a');
		
		$length = count($aList);
		for($i=1;$i<$length-2;$i++){//过滤首页和最后2页
			$a=$aList[$i];
			$href= $a->getAttribute('href');
			$arr[] = $href;
		}
		
		return $arr;	
	}//
	
	public function getCode($str){
		$temp = explode('.html',$str);
		$arr = explode('/',$temp[0]);
		$length = count($arr)-1;
		return $arr[$length];
	}

}