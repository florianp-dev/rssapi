<?php

class RSSAPI {

	public function unmarshal($flux) {
		if (!is_string($flux)) {
			throw new InvalidArgumentException('Argument must be a string');
		}

		$rss = new SimpleXMLElement($flux, 0, true);

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