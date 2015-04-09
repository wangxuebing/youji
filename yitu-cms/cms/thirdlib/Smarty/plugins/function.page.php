<?php
function pageUrl($urlPrefix, $pageNumber){
		return str_replace("{pg}", $pageNumber, $urlPrefix);
}
function smarty_function_page($params, &$smarty) {
	$currentPage = $params ['currentPage']; //当前页数(从1开始)
	$pageNum = $params ['pageNum']; //总页数
	if($currentPage > $pageNum) {
		$currentPage = $pageNum;
	}
	$maxDisplayNum = $params ['maxDisplayNum']; //最多显示的页数
	$urlPrefix = $params ['urlPrefix']; //每页的URL前缀（要求页数在URL的最后面）
	$firstLastPage = $params ['firstLastPage']; //是否显示“首页/末页”
	$centerFlag = $params['centerFlag'];

	if ($pageNum <= 1) {
		echo '';
		return;
	}
	
	/* 当前页面左侧能显示的页数(如果maxDisplayNum是奇数，则左右相等，都是maxDisplayNum/2；如果maxDisplayNum是偶数，则左侧较右侧多一个) */
	$numLeft = floor ( $maxDisplayNum / 2 );
	/* 起始页数 */
	$start = $currentPage - $numLeft;
	if ($start < 1) {
		$start = 1;
	}
	/* 如果起始页数到最后一页也未达到maxDisplayNum，则起始页数可以向左移动，从而尽量达到maxDisplayNum */
	if ($pageNum - $start + 1 < $maxDisplayNum) {
		$start = $pageNum - $maxDisplayNum + 1;
	}
	if ($start < 1) {
		$start = 1;
	}
//	$divPage = '<div id="page">';
    $divPage = '';
	if(isset($centerFlag) && $centerFlag==1)
	{
		$divPage .= '<div class="pg" id="pg_list" style="text-align:center;">';
	}
	else
	{
		$divPage .= '<div class="pg" id="pg_list">';
	}
	/* 如果当前页不是第一页，则有“首页”和“上一页”*/
	if ($currentPage != 1) {
		$prev_page = $currentPage - 1;
		if ($firstLastPage) {
			$divPage .= '<span class="pgpre"><a href="' .pageUrl($urlPrefix, 1).'">&#171;首页</a></span>';
		}
		$divPage .= '<a href="' . pageUrl($urlPrefix,$prev_page). '" class="pre">&lt;Previous</a>';
	}
	
	/* 从start起始页开始，循环输出maxDisplayNum个页面，或者达到最后一页 */
//	$divPage .= '<div class="pg">';
	$i = $start;
	for(; $i <= $pageNum && $i - $start < $maxDisplayNum; $i ++) {
		/* 当前页面，无链接 */
		if ($currentPage == $i) {
			$divPage .= '<span class="current">'.$i.'</span>';
		} /* 其他页面，有链接 */
		else {
			$divPage .= "<a href='".pageUrl($urlPrefix,$i)."'>$i</a>";
		}
	}
//	$divPage .= '</div>';
	/* 如果当前页不是最后一页，则有“末页”和“下一页”*/

	if ($currentPage != $pageNum) {
		$next_page = $currentPage + 1;
		$divPage .= "<a href='".pageUrl($urlPrefix,$next_page)."' class='next'>Next&gt;</a>";
		if ($firstLastPage) {
			$divPage .= "<span class='pgnext'><a href='".pageUrl($urlPrefix,$pageNum)."'>末页&#187;</a></span>";
		}
	}
	$divPage .= '</div>';

//	$divPage .= '</div>';
	echo $divPage;
}
