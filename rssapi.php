<?php

$xmlstr = file_get_contents('http://korben.info/feed');

$rss = new SimpleXMLElement($xmlstr);

// Titre du flux
echo '<strong>' . $rss->channel->title . '</strong> - ' . '<em>' . $rss->channel->description . '</em>';

echo '<br />' . '<br />';

// Détails de chaque news présente
foreach ($rss->channel->item as $item) {
	// Titre
	echo '<strong>Titre</strong> :' . $item->title . '<br />';
	// Description
	echo '<em>Descri</em> : ' . $item->description;
	// Lien
	echo '<a href="#">' . $item->link . '</a><br /><br />';
}
