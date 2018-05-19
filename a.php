<?php

$htmlStr =<<<HTML
<div id="main">
	<ul>
		<li>主页</li>
		<li>栏目1</li>
		<li>栏目2</li>
		<li><img src="download/1.jpg"></li>
	</ul>
</div>
HTML;

$dom = new DOMDocument();
$dom->loadHTML($htmlStr);
$src = $dom->getElementById('main')
->getElementsByTagName('li')[3]
->getElementsByTagName('img')[0]
->getAttribute('src');

echo $src;//输出 download/1.jpg

?>