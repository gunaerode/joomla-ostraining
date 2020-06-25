<?php

/**
 * Random articles
 */
defined('_JEXEC') or die;

$catid = $params->get("catid");
$count = $params->get('count');

echo "<pre>";

print_r($catid);
echo $count;

?>
<h1>Hello buddy</h1>
<p>This is for new users</p>