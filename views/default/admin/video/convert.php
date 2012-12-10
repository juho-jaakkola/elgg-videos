<?php

$guid = get_input('guid');

echo elgg_view_form('video/convert', array(), array('guid' => $guid));
