<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Visual builder of the toolbar layout.
 *
 * @package    tiny_toolbar
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tiny_toolbar\local\layout;

require_once(__DIR__ . '/../../../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('tiny_toolbar_builder');

$url = new moodle_url('/lib/editor/tiny/plugins/toolbar/builder.php');

$presets = [];
foreach (layout::get_presets() as $name => $preset) {
    $presets[] = ['name' => $name, 'label' => get_string('preset_' . $name, 'tiny_toolbar'), 'layout' => $preset];
}
$initial = layout::get_active() ?? layout::get_presets()[layout::PRESET_STANDARD];
$config = [
    'layout' => $initial,
    'presets' => $presets,
    'menus' => array_map(
        fn($menu) => ['name' => $menu, 'label' => get_string('menu_' . $menu, 'tiny_toolbar')],
        layout::MENUS
    ),
];
$builder = $OUTPUT->render_from_template('tiny_toolbar/builder', [
    'config' => json_encode($config),
    'presets' => $presets,
    'menus' => $config['menus'],
]);

$form = new \tiny_toolbar\form\builder_form($url, ['builder' => $builder]);
$form->set_data(['layout' => json_encode($initial)]);

if ($data = $form->get_data()) {
    [$parsed] = layout::parse($data->layout);
    set_config('layout', layout::to_json($parsed), 'tiny_toolbar');
    set_config('preset', layout::PRESET_CUSTOM, 'tiny_toolbar');
    redirect($url, get_string('saved', 'tiny_toolbar'), null, \core\output\notification::NOTIFY_SUCCESS);
}

$PAGE->requires->js_call_amd('tiny_toolbar/builder', 'init', ['[data-tiny-toolbar="builder"]']);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('builder', 'tiny_toolbar'));
echo html_writer::tag('p', get_string('builder_desc', 'tiny_toolbar'));
$form->display();
echo $OUTPUT->footer();
