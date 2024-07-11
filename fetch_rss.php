<?php
header('Content-Type: application/json');

$feed_url = $_GET['rssUrl'];

function removeCdataTags($input) {
    return str_replace(["<![CDATA[", "]]>"], "", $input);
}

function fetchRSS($url) {
    $rss = simplexml_load_file($url);
    $items = [];
    
    foreach ($rss->channel->item as $item) {
        $content_encoded = $item->children('content', true)->encoded;
        $items[] = [
            'title' => (string) html_entity_decode(removeCdataTags($item->title)),
            'link'  => (string) $item->link,
            'description' => (string) html_entity_decode(removeCdataTags($item->description)),
            'content' => (string) html_entity_decode(removeCdataTags($content_encoded)),
            'pubDate' => (string) $item->pubDate,
        ];
    }
    
    return $items;
}

echo json_encode(fetchRSS($feed_url));