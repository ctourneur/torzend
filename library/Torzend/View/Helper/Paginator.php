<?php
class Torzend_View_Helper_Paginator extends Zend_View_Helper_Abstract {
	
	public function paginator($count, $url, $search, $current, $options=null) {
		$output = '<div class="paginator">'.PHP_EOL;
		if($current>1)
			$output.= '<a href="'.str_replace($search, $current-1, $url).'" class="prev">&laquo;</a>'.PHP_EOL;
		else
			$output.= '<span class="prev">&laquo;</span>'.PHP_EOL;
		for($i=1; $i<=$count; $i++)
		{
			if($i == $current)
				$output.= '<span class="current">'.$i.'</span>'.PHP_EOL;
			else
				$output.= '<a href="'.str_replace($search, $i, $url).'">'.$i.'</a>'.PHP_EOL;
		}
		if($current<$count)
			$output.= '<a href="'.str_replace($search, $current+1, $url).'" class="next">&raquo;</a>'.PHP_EOL;
		else
			$output.= '<span class="next">&raquo;</span>';
		$output.= '</div>'.PHP_EOL;
		
		return $output;
	}
}