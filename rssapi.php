<?php

class RSSAPI {

	public function unmarshal($flux_url) {
		if (!is_string($flux_url)) {
			throw new InvalidArgumentException('Argument must be a string');
		} elseif (!filter_var($flux_url, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException('URL is not well formed');
		}

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
}