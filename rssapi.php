<?php

class RSSAPI {

	public function unmarshal($flux_url) {
		$rss = new SimpleXMLElement($flux_url, 0, true);

		// Title of RSS document
		$unmarshalled['title'] = $rss->channel->title;
		// Description of RSS document
		$unmarshalled['desc'] = $rss->channel->description;

		// Details of each item
		foreach ($rss->channel->item as $item) {
			// Title
			$unmarshalled['items']['title'] = $item->title;
			// Description
			$unmarshalled['items']['desc'] = $item->description;
			// Link
			$unmarshalled['items']['link'] = $item->link;
		}

		return $unmarshalled;
	}

	private function check_integrity($to_check) {
		// Verify integrity of $flux_url
		if (!is_string($to_check)) {
			throw new InvalidArgumentException('Argument must be a string');
		} elseif (!filter_var($to_check, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException('URL is not well formed');
		}
		// Verify if it is well formed XML
		libxml_use_internal_errors(true);
		if (!simplexml_load_file($to_check)) {
			throw new DOMException('File is not well formed');
		}
	}
}