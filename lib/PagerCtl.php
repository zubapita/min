<?php
/**
 * ページャを生成するためのクラス
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class PagerCtl
{
	/**
	 * ページャを生成して返す
	 * 
	 * @param array $pagerParams
	 * @return array ページャ
	 */
	public static function get($pagerParams)
	{
		$pagesInPager = 10;
		$middleOfPager = 5;

		$pager = $pagerParams;
		if (!empty($pager['maxItemsInPage'])) {
			$maxPage = (integer) ceil($pager['allItemsNum'] / $pager['maxItemsInPage']);
		} else {
			$maxPage = 1;
		}
		$pager['maxPage'] = $maxPage;
		
		for ($i=1; $i<=$maxPage; $i++) {
			$pager['pager'][$i] = $i;
		}

		if ($pager['currentPage'] <= $middleOfPager) {
			$pager['leftEndPage'] = 1;
			$pager['rightEndPage'] = $pager['leftEndPage']+$pagesInPager-1;
		} elseif ($pager['currentPage'] > $maxPage - $middleOfPager) {
			$pager['rightEndPage'] = $maxPage;
			$pager['leftEndPage'] = $pager['rightEndPage']-$pagesInPager+1;
		} else {
			$pager['leftEndPage'] = $pager['currentPage']-$middleOfPager+1;
			$pager['rightEndPage'] = $pager['leftEndPage']+$pagesInPager-1;
		}
		
		if ($pager['leftEndPage'] < 1) $pager['leftEndPage'] = 1;
		if ($pager['rightEndPage'] > $maxPage) $pager['rightEndPage'] = $maxPage;
		
		return $pager;
	}
	
}
