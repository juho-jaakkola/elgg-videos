<?php
/**
 * Table output
 *
 * @package Video
 *
 * @uses $vars['table_class] The classes for table element
 * @uses $vars['headers'] The column headers
 * @uses $vars['rows'] Two dimensional array with rows and cells
 *
 */

$table_class = array('class' => 'elgg-table');

if (isset($vars['table_class'])) {
	$table_class['class'] = $vars['table_class'];
}
$table_class = elgg_format_attributes($table_class);

$header = '';
if (isset($vars['headers'])) {
	foreach ($vars['headers'] as $header) {
		$headers .= "<th>$header</th>";
	}
	$headers = "<tr>$headers</tr>";
}

$rows = '';
if (isset($vars['rows'])) {
	foreach ($vars['rows'] as $row) {
		$cells = '';
		foreach ($row as $value) {
			$cells .= "<td>$value</td>";
		}
		$rows .= "<tr>$cells</tr>";
	}
}

echo "<table $table_class>{$headers}{$rows}</table>";
