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
 * Settings of tiny_toolbar.
 *
 * @package    tiny_toolbar
 * @category   admin
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tiny_toolbar\local\layout;

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $builderurl = new moodle_url('/lib/editor/tiny/plugins/toolbar/builder.php');
    $ADMIN->add('editortiny', new admin_externalpage(
        'tiny_toolbar_builder',
        new lang_string('builder', 'tiny_toolbar'),
        $builderurl
    ));

    if ($ADMIN->fulltree) {
        $options = [];
        foreach (layout::get_preset_names() as $name) {
            $options[$name] = new lang_string('preset_' . $name, 'tiny_toolbar');
        }
        $settings->add(new admin_setting_configselect(
            'tiny_toolbar/preset',
            new lang_string('preset', 'tiny_toolbar'),
            new lang_string('preset_desc', 'tiny_toolbar'),
            layout::PRESET_DEFAULT,
            $options
        ));

        $settings->add(new \tiny_toolbar\admin\setting_layout(
            'tiny_toolbar/layout',
            new lang_string('layout', 'tiny_toolbar'),
            new lang_string('layout_desc', 'tiny_toolbar'),
            ''
        ));

        $settings->add(new admin_setting_description(
            'tiny_toolbar/builderlink',
            '',
            html_writer::link($builderurl, get_string('builderlink', 'tiny_toolbar'), ['class' => 'btn btn-secondary'])
        ));
    }
}
