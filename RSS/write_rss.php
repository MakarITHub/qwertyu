<?php

class rssReader {
var $rssFeeds;

function rssReader() {

$this->rssFeeds = array(
0 => "https://rss.app/feeds/HxXaVOQT15Bp0Epm.xml",
);
}

function checkCache($rss_url) {

$ttl = 60*60;
$cachefilename = md5(md5($rss_url));

if (file_exists($cachefilename) && (time() - $ttl < filemtime($cachefilename)))
{

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $cachefilename);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$feed = curl_exec($ch);
curl_close($ch);
}
else
{


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $rss_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$feed = curl_exec($ch);
curl_close($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $cachefilename);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch);


}
return $feed;

}

function createHtmlFromFeed($feedid, $howmany) {

$rss_url = $this->rssFeeds[$feedid];
if (!isset($rss_url)) $rss_url = $this->rssFeeds[$feedid];
$howmany = intval($howmany);

$this->createHtmlFromRSSUrl( $rss_url, $howmany );

}

function createHtmlFromRSSUrl( $rss_url, $howmany )
{

$rss_feed = $this->checkCache($rss_url);

$rss_feed = str_replace("<![CDATA[", "", $rss_feed);
$rss_feed = str_replace("]]>", "", $rss_feed);
$rss_feed = str_replace("\n", "", $rss_feed);

$rss_feed = preg_replace('#<image>(.*?)</image>#', '', $rss_feed, 1 );

preg_match_all('#<title>(.*?)</title>#', $rss_feed, $title, PREG_SET_ORDER);
preg_match_all('#<link>(.*?)</link>#', $rss_feed, $link, PREG_SET_ORDER);
preg_match_all('#<description>(.*?)</description>#', $rss_feed, $description, PREG_SET_ORDER);
preg_match_all('#<author>(.*?)</author>#', $rss_feed, $author, PREG_SET_ORDER);

if(count($title) <= 1)
{
echo "No news at present, please check back later.<br><br>";
}
else
{

for ($counter = 1; $counter <= $howmany; $counter++ )
{

if(!empty($title[$counter][1]))
{

$title[$counter][1] = str_replace("&amp;", "&", $title[$counter][1]);
$title[$counter][1] = str_replace("&apos;", "'", $title[$counter][1]);
$title[$counter][1] = str_replace("&pound;", "?", $title[$counter][1]);


$description[$counter][1] = html_entity_decode( $description[$counter][1]);


$row = $this->FormatEntry($title[$counter][1],$description[$counter][1],$link[$counter][1]);

echo $row;
}
}
}
}

function FormatEntry($title, $description, $link) {
return <<<HTML
<p class="feed_title">{$title}</p>
<p class="feed_description">{$description}</p>
<p class="author">{$author}</p>
<a class="feed_link" href="{$link}" rel="nofollow" target="_blank">Читать больше</a>
<p>&nbsp;</p>
<hr size=1>
HTML;
}


function GetrssFeeds() {
return $this->rssFeeds;
}

function SetrssFeeds($rssFeeds) {
$this->rssFeeds = $rssFeeds;
}


}

?>
