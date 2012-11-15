<?php
class Torzend_View_Helper_Pages extends Zend_View_Helper_Abstract {
	
	public function pages($count, $url, $current, $replaceTag="%page%") {
		$output = '';
		$output .= '<ul class="pages clearfix">'.PHP_EOL;
		if($current>1)
			$output .= '<li class="prev"><a href="'.str_replace($replaceTag, $current-1, $url).'">&laquo;</a></li>'.PHP_EOL;
		else
			$output .= '<li class="prev"><span>&laquo;</span></li>'.PHP_EOL;
		for($i=1; $i<=$count; $i++)
		{
			// Création de l'url de la page en cours
			$pageUrl = str_replace($replaceTag, $i, $url);
			
			if($i == $current)
				$output .= '<li class="current"><span>'.$i.'</span></li>'.PHP_EOL;
			else
				$output .= '<li><a href="'.$pageUrl.'">'.$i.'</a></li>'.PHP_EOL;	
		}
		if($current<$count)
			$output .= '<li class="next"><a href="'.str_replace($replaceTag, $current+1, $url).'">&raquo;</a></li>'.PHP_EOL;
		else
			$output .= '<li class="next"><span>&raquo;</span></li>'.PHP_EOL;
		$output .= '</ul>'.PHP_EOL;
		return $output;
	}
}
?>