<?php

class RSSAPI {

	/**
	 * Unserializes document if it is proper XML file
	 * @param $flux_url String URL of the document
	 * @return Array Contains all informations about the RSS flow
	 */
	public function unmarshal($flux_url) {
		$this->check_integrity($flux_url);

		$rss = new SimpleXMLElement($flux_url, 0, true);

		/* Unavoidable elements */
		// Title of RSS document
		$unmarshalled['title'] = $rss->channel->title;
		// Description of RSS document
		$unmarshalled['description'] = $rss->channel->description;
		// Link to relative article
		$unmarshalled['link'] = $rss->channel->link;

		if (empty($unmarshalled['title'])
			|| empty($unmarshalled['description'])
			|| empty($unmarshalled['link']))
		{
			$rssErrors[] = 'One of unavoidable element of the channel is not present';
		}

		/* Optionnal elements (only set if exists) */
		if ($rss->channel->language)
			$unmarshalled['language'] = $rss->channel->language->__toString();
		if ($rss->channel->copyright)
			$unmarshalled['copyright'] = $rss->channel->copyright->__toString();
		if ($rss->channel->managingEditor)
			$unmarshalled['managingEditor'] = $rss->channel->managingEditor->__toString();
		if ($rss->channel->webMaster)
			$unmarshalled['webMaster'] = $rss->channel->webMaster->__toString();
		if ($rss->channel->pubDate)
			$unmarshalled['pubDate'] = $rss->channel->pubDate->__toString();
		if ($rss->channel->lastBuildDate)
			$unmarshalled['lastBuildDate'] = $rss->channel->lastBuildDate->__toString();
		if ($rss->channel->category)
			$unmarshalled['category'] = $rss->channel->category->__toString();
		if ($rss->channel->generator)
			$unmarshalled['generator'] = $rss->channel->generator->__toString();
		if ($rss->channel->docs)
			$unmarshalled['docs'] = $rss->channel->docs->__toString();
		if ($rss->channel->ttl)
			$unmarshalled['ttl'] = $rss->channel->ttl->__toString();
		if ($rss->channel->image)
			$unmarshalled['image'] = $rss->channel->image->url->__toString();

		// Details of each item
		foreach ($rss->channel->item as $item) {
			if (empty($item->title) && empty($item->description)) {
				$rssErrors[] = 'One of unavoidable element of the item is not present';
			}

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
		libxml_use_internal_errors(false);
	}
}
