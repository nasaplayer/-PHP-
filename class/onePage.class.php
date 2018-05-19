<?php


class oneInfo{
	
	public  $url;
	public  $host;
	public  $dirName;
	public  $code;
	
	public  $downUrl = '/uploads/allimg/20161015/2016101511523925284.jpg';
	
	public function __construct($host,$dirName,$code){
		
		$this->host = $host;
		$this->dirName = $dirName;// /qcmn/
		$this->code = $code;	//1663
		$this->url = $host.$dirName.$code.'.html';
	}
	
	public function start(){
		$imgUrlArr = $this->getAllImg();
		$this->printR($imgUrlArr);
		$this->download($imgUrlArr);
	}
	
	public function getAllImg(){

		$imgUrlArr = [];
		$totalNum = $this->getTotalNum();
		
		for($i=1;$i<=$totalNum;$i++){
			$imgUlr = $this->getOneImgUrl($i);
			if(strstr($imgUlr,"空的")){
				continue;
			}
			$imgUrlArr[] = $imgUlr;
		}
		return $imgUrlArr;
	}
	
	public function getOneImgUrl($i){
		$imgUrl = '';
		$url = $this->host.$this->dirName.$this->code;
		
		if($i==1){
			$web = $url.'.html';
		}else{
			$web = $url.'_'.$i.'.html';
		}
		
		$dom = $this->getDOM($web);
		$src = $this->getOneImgSrc($dom);
		$this->echoRow('Get =>'.$src.'</br>');
		
		$imgUrl = $this->host.$src;
		return $imgUrl;
	}//
	
	
	public function getDOM($setUrl = NULL){
		$url = $setUrl??$this->url;
		
		$this->echoRow('Send =>'.$url);
		$htmlStr = file_get_contents($url);//oneCURL($this->url);
		//$htmlStr = htmlentities($htmlStr);
		$dom = new DOMDocument();
		libxml_use_internal_errors(true);//禁用错误报告
		$dom->loadHTML($htmlStr);
		return $dom;
	}
	
	public function getTotalNum(){
		$totalNum = 0;
		$dom = $this->getDOM();
		
		$ul=$dom->getElementsByTagName('ul')[0];
		
		if(is_object($ul)){
			
			$a = $ul->getElementsByTagName('a')[0];
			$totalNum = $this->getNum($a->nodeValue);
		}else{
			$totalNum =0;
		}
		
		$this->echoRow('为空的内容'.$this->dirName.$this->code);
		
		return $totalNum;
	}
	
	
	public function getNum($str){
		$temp = explode('共',$str);
		$arr = explode('页',$temp[1]);
		return $arr[0];
	}

	public function getOneImgSrc($dom){
		
		$img = $dom->getElementById('bigpic')
		->getElementsByTagName('img')[0];
		
		if(is_object($img)){
			
			$src = $img->getAttribute('src');
			$src = trim($src);//多了一个空格
		}else{
			$src = '空的';
		}
		
		
		return $src;
	}

	public function getImgName($src){
		$temp = explode('/',$src);
		$length = count($temp);
		$imgName = $temp[$length-1];
		return $imgName;
	}
	
	public function echoRow($value){
		echo $value.'<br>';
	}
	
	public function printR($value){
		echo '<pre>';
		print_r($value);
		echo '</pre>';
	}

	public function download($imgUrlArr){
		
		$length = count($imgUrlArr);
		$dir= 'img'.$this->dirName.$this->code.'/';
		$this->echoRow('保存文件夹'.$dir);
		if(!is_dir($dir)){
		mkdir($dir, 0777, true);   // 如果文件夹不存在，将以递归方式创建该文件夹
		}
		for($i=0;$i<$length;$i++){
			$imgUrl = $imgUrlArr[$i];
			if(!empty($imgUrl)){
				$this->downOneImg($dir,$imgUrl);
			}
			
		}
		
	}
	
	public function downOneImg($dir,$imgUrl){
		
		$imgName = $this->getImgName($imgUrl);
		$downDir = $dir.$imgName;
		
		$img = @file_get_contents($imgUrl);
		
		if(!file_exists($downDir)){
			@file_put_contents($downDir,$img);
			$this->echoRow('已完成下载=>'.$downDir);
		}else{
			$this->echoRow('文件存在=>'.$downDir);	
		}
		
	}
	
}//
