<?php
	
	class Cache {
		
		private $dom;
		private $xpath;
		
		function __construct() {
			$this->dom = new DOMDocument();
			$this->dom->load('cache.xml');
			$this->xpath = new DOMXPath($this->dom);
		}
		
		//Retrieves an array of cache values
		function retrieveCache($id) {
			if (!$this->cacheExists($id)) return false;
			$vals = array();
			foreach ($this->xpath->query('element') as $element) {
				if ($element->getElementsByTagName('id')->item(0)->nodeValue == $id) {
					$arr = $element->getElementsByTagName('values')->item(0)->getElementsByTagName('value');
					$time = $element->getElementsByTagName('time')->item(0)->nodeValue;
					$period = $element->getElementsByTagName('period')->item(0)->nodeValue;
					if (time() - $time > $period) return false;
					foreach ($arr as $a) $vals[] = $a->nodeValue;
				}
			}
			return $vals;
		}
		
		//Updates specified cache. If a cache element doesn't exist, it will be made
		function updateCache($id, $period, $values) {
			if ($this->cacheExists($id))
				$this->deleteCache($id);
			$cache = $this->xpath->query('element/..')->item(0);
			$elem = $this->dom->createElement('element');
			$elem->appendChild($this->dom->createElement('id', $id));
			$elem->appendChild($this->dom->createElement('period', $period));
			$elem->appendChild($this->dom->createElement('time', time()));
			$vals = $this->dom->createElement('values');
			foreach ($values as $value) $vals->appendChild($this->dom->createElement('value', $value));
			$elem->appendChild($vals);
			$cache->appendChild($elem);
			$this->save();
			return true;
		}
		
		//Checks if a cache element with specified id exists
		function cacheExists($id) {
			foreach ($this->xpath->query('element/id') as $child) {
				if ($id == $child->nodeValue) return true;
			}
			return false;
		}
		
		//Deletes specified cache element
		function deleteCache($id) {
			foreach ($this->xpath->query('element') as $element) {
				if ($element->getElementsByTagName('id')->item(0)->nodeValue == $id) {
					$this->xpath->query('element/..')->item(0)->removeChild($element);
					$this->save();
					break;
				}
			}
		}
		
		//Saves the XML file
		function save() {
			$this->dom->save('cache.xml');
		}
		
	}
	
?>
