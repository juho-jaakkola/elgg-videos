<?php
/**
 * Display a video tag and its source tags
 *
 * @uses $vars['sources']
 * @uses $vars['poster']
 * @uses $vars['controls']
 * @uses $vars['width']
 */

$defaults = array(
	'sources' => array(),
	'poster' => '',
	'controls' => 'controls',
	'width' => '100%',
	'preload' => 'metadata',
);

$vars = array_merge($defaults, $vars);

$sources = $vars['sources'];
unset($vars['sources']);

$attributes = elgg_format_attributes($vars);

$source_tags = '';
foreach ($sources as $source) {
	$url = elgg_normalize_url($source->getURL());
	$url = elgg_format_url($url);

	$source_tags .= "<source src=\"$url\" type=\"video/$source->format\">";
}

$nosupport = elgg_echo('video:nosupport');

echo "<video $attributes>{$source_tags}<strong>{$nosupport}</strong></video>";
