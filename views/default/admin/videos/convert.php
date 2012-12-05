<?php

$guid = get_input('guid');

echo elgg_view_form('videos/convert', array(), array('guid' => $guid));
