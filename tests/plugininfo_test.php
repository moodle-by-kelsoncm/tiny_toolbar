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

namespace tiny_toolbar;

use tiny_toolbar\admin\setting_layout;
use tiny_toolbar\local\layout;

/**
 * Tests for the editor configuration and the layout setting.
 *
 * @package    tiny_toolbar
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \tiny_toolbar\plugininfo
 * @covers     \tiny_toolbar\admin\setting_layout
 */
final class plugininfo_test extends \advanced_testcase {
    /**
     * The editor receives the active layout, or nothing with the Moodle default.
     */
    public function test_plugin_configuration(): void {
        $this->resetAfterTest();
        $context = \context_system::instance();

        $this->assertSame(['layout' => ''], plugininfo::get_plugin_configuration_for_context($context, [], []));

        set_config('preset', layout::PRESET_SIMPLE, 'tiny_toolbar');
        $config = plugininfo::get_plugin_configuration_for_context($context, [], []);
        $this->assertSame(layout::get_presets()[layout::PRESET_SIMPLE], json_decode($config['layout'], true));
    }

    /**
     * The editor configuration includes the plugin for users with tiny/toolbar:use only.
     */
    public function test_editor_manager(): void {
        $this->resetAfterTest();
        set_config('preset', layout::PRESET_MINIMAL, 'tiny_toolbar');
        $context = \context_system::instance();
        $manager = new \editor_tiny\manager();

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        $plugins = $manager->get_plugin_configuration($context, [], []);
        $this->assertArrayHasKey('tiny_toolbar/plugin', $plugins);
        $this->assertNotEmpty($plugins['tiny_toolbar/plugin']['config']['layout']);

        $userrole = $this->getDataGenerator()->create_role();
        assign_capability('tiny/toolbar:use', CAP_PROHIBIT, $userrole, $context->id);
        $this->getDataGenerator()->role_assign($userrole, $user->id, $context->id);
        $plugins = $manager->get_plugin_configuration($context, [], []);
        $this->assertArrayNotHasKey('tiny_toolbar/plugin', $plugins);
    }

    /**
     * The setting accepts empty or valid layouts and stores them normalised.
     */
    public function test_setting_layout(): void {
        $this->resetAfterTest();
        $setting = new setting_layout('tiny_toolbar/layout', 'Layout', '', '');

        $this->assertSame(get_string('errorjson', 'tiny_toolbar'), $setting->write_setting('{'));
        $this->assertSame(
            get_string('errorbutton', 'tiny_toolbar'),
            $setting->write_setting('{"toolbar": [{"name": "a", "items": ["<b>"]}]}')
        );

        $this->assertSame('', $setting->write_setting('{"toolbar":[{"name":"a","items":["bold"]}],"menubar":"edit  insert"}'));
        $this->assertSame(
            layout::to_json(['toolbar' => [['name' => 'a', 'items' => ['bold']]], 'menubar' => 'edit insert']),
            get_config('tiny_toolbar', 'layout')
        );

        $this->assertSame('', $setting->write_setting(''));
        $this->assertSame('', get_config('tiny_toolbar', 'layout'));
    }
}
