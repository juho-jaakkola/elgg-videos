<?php
/**
 * Video river view.
 */

$object = $vars['item']->getObjectEntity();

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => elgg_view_entity_icon($object),
));