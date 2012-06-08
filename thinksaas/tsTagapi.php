<?php 

class tsAiTag {
	function toapi($subject, $message, $list_separator) {
		$subjectenc = rawurlencode(strip_tags($subject));
		$messageenc = rawurlencode(strip_tags($message));
		$data = @implode('', file("http://keyword.discuz.com/related_kw.html?title=$subjectenc&content=$messageenc&ics=utf-8&ocs=utf-8"));
		if ($data) {
			$parser = xml_parser_create();
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, $data, $values, $index);
			xml_parser_free($parser);
			$kws = array();
			foreach($values as $valuearray) {
				if ($valuearray['tag'] == 'kw' || $valuearray['tag'] == 'ekw') {
					$kws[] =  trim($valuearray['value']);
				} 
			} 
			$return = '';
			if ($kws) {
				foreach($kws as $kw) {
					$return .= $kw . $list_separator;
				} 
				$return = trim($return);
			} 
			return $return;
		} else {
			echo ' ';
		} 
	} 
} 

