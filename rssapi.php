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
		$this->checkOptionnalsChannel($rss, $unmarshalled);

		// Details of each item
		$index = 0;
		foreach ($rss->channel->item as $item) {
			if (empty($item->title) && empty($item->description)) {
				$rssErrors[] = 'One of unavoidable element of the item is not present';
			}

			if (!empty($item->title))
				$unmarshalled['items'][$index]['title'] = $item->title;
			else
				$unmarshalled['items'][$index]['desc'] = $item->description;

			$this->checkOptionnalsItem($item, $unmarshalled, $index);
			$index++;
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

	private function checkOptionnalsChannel($rss, &$unmarsh) {
		if ($rss->channel->language)
			$unmarsh['language'] = $rss->channel->language->__toString();
		if ($rss->channel->copyright)
			$unmarsh['copyright'] = $rss->channel->copyright->__toString();
		if ($rss->channel->managingEditor)
			$unmarsh['managingEditor'] = $rss->channel->managingEditor->__toString();
		if ($rss->channel->webMaster)
			$unmarsh['webMaster'] = $rss->channel->webMaster->__toString();
		if ($rss->channel->pubDate)
			$unmarsh['pubDate'] = $rss->channel->pubDate->__toString();
		if ($rss->channel->lastBuildDate)
			$unmarsh['lastBuildDate'] = $rss->channel->lastBuildDate->__toString();
		if ($rss->channel->category)
			$unmarsh['category'] = $rss->channel->category->__toString();
		if ($rss->channel->generator)
			$unmarsh['generator'] = $rss->channel->generator->__toString();
		if ($rss->channel->docs)
			$unmarsh['docs'] = $rss->channel->docs->__toString();
		if ($rss->channel->ttl)
			$unmarsh['ttl'] = $rss->channel->ttl->__toString();
		if ($rss->channel->image)
			$unmarsh['image'] = $rss->channel->image->url->__toString();
	}

	private function checkOptionnalsItem($item, &$unmarsh, $index) {
		if ($item->link)
			$unmarsh['items'][$index]['link'] = $item->link->__toString();
		if ($item->author)
			$unmarsh['items'][$index]['author'] = $item->author->__toString();
		if ($item->category)
			$unmarsh['items'][$index]['category'] = $item->category->__toString();
		if ($item->comments)
			$unmarsh['items'][$index]['comments'] = $item->comments->__toString();
		if ($item->guid)
			$unmarsh['items'][$index]['guid'] = $item->guid->__toString();
		if ($item->pubDate)
			$unmarsh['items'][$index]['pubDate'] = $item->pubDate->__toString();
	}
}
