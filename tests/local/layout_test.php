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

namespace tiny_toolbar\local;

/**
 * Tests for the layouts.
 *
 * @package    tiny_toolbar
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \tiny_toolbar\local\layout
 */
final class layout_test extends \advanced_testcase {
    /**
     * Every preset is a valid layout and has a language string.
     */
    public function test_presets(): void {
        foreach (layout::get_presets() as $name => $preset) {
            $this->assertSame('', layout::validate($preset), $name);
            $this->assertContains($name, layout::get_preset_names());
        }
        foreach (layout::get_preset_names() as $name) {
            $this->assertNotEmpty(get_string('preset_' . $name, 'tiny_toolbar'));
        }
    }

    /**
     * Invalid layouts and the error they give.
     *
     * @return array
     */
    public static function invalid_provider(): array {
        $group = ['name' => 'formatting', 'items' => ['bold']];
        return [
            'not an object' => ['"bold"', 'errornotoolbar'],
            'no toolbar' => ['{"menubar": false}', 'errornotoolbar'],
            'unknown key' => ['{"toolbar": [], "plugins": "code"}', 'errorunknownkey'],
            'toolbar as string' => ['{"toolbar": "bold italic"}', 'errortoolbar'],
            'group without items' => ['{"toolbar": [{"name": "a"}]}', 'errorgroup'],
            'group with extra key' => ['{"toolbar": [{"name": "a", "items": [], "x": 1}]}', 'errorgroup'],
            'bad group name' => ['{"toolbar": [{"name": "a b", "items": []}]}', 'errorgroupname'],
            'script as button' => ['{"toolbar": [{"name": "a", "items": ["<script>"]}]}', 'errorbutton'],
            'number as button' => ['{"toolbar": [{"name": "a", "items": [1]}]}', 'errorbutton'],
            'unknown menu' => [json_encode(['toolbar' => [$group], 'menubar' => 'edit evil']), 'errormenubar'],
            'menubar true' => [json_encode(['toolbar' => [$group], 'menubar' => true]), 'errormenubar'],
            'invalid json' => ['{"toolbar": [', 'errorjson'],
            'quickbars as list' => [json_encode(['toolbar' => [$group], 'quickbars' => ['bold']]), 'errorquickbars'],
            'quickbars with tag' => [json_encode(['toolbar' => [$group], 'quickbars' => 'bold <i>']), 'errorquickbars'],
            'empty quickbars' => [json_encode(['toolbar' => [$group], 'quickbars' => '  ']), 'errorquickbars'],
        ];
    }

    /**
     * Invalid layouts are rejected with the right error.
     *
     * @dataProvider invalid_provider
     * @param string $json
     * @param string $error
     */
    public function test_parse_invalid(string $json, string $error): void {
        [$layout, $result] = layout::parse($json);
        $this->assertNull($layout);
        $this->assertSame($error, $result);
        $this->assertNotEmpty(get_string($error, 'tiny_toolbar'));
    }

    /**
     * Too many groups are rejected.
     */
    public function test_too_many_groups(): void {
        $toolbar = array_fill(0, layout::MAX_GROUPS + 1, ['name' => 'a', 'items' => ['bold']]);
        $this->assertSame('errortoolbar', layout::validate(['toolbar' => $toolbar]));
    }

    /**
     * Valid layouts are normalised: empty groups removed, menus separated by one space.
     */
    public function test_parse_valid(): void {
        [$layout, $error] = layout::parse('{"toolbar": [{"name": "history", "items": ["undo", "redo"]},
            {"name": "empty", "items": []}], "menubar": "  edit   insert "}');
        $this->assertSame('', $error);
        $this->assertSame([
            'toolbar' => [['name' => 'history', 'items' => ['undo', 'redo']]],
            'menubar' => 'edit insert',
        ], $layout);

        [$layout] = layout::parse('{"toolbar": [], "menubar": false}');
        $this->assertSame(['toolbar' => [], 'menubar' => false], $layout);

        [$layout] = layout::parse('{"toolbar": [], "quickbars": " bold  italic |  tiny_link_link "}');
        $this->assertSame(['toolbar' => [], 'quickbars' => 'bold italic | tiny_link_link'], $layout);

        [$layout] = layout::parse('{"toolbar": [], "quickbars": false}');
        $this->assertSame(['toolbar' => [], 'quickbars' => false], $layout);
    }

    /**
     * The active layout follows the preset setting.
     */
    public function test_get_active(): void {
        $this->resetAfterTest();
        $this->assertNull(layout::get_active());

        set_config('preset', layout::PRESET_MINIMAL, 'tiny_toolbar');
        $this->assertSame(layout::get_presets()[layout::PRESET_MINIMAL], layout::get_active());

        set_config('preset', layout::PRESET_CUSTOM, 'tiny_toolbar');
        $this->assertNull(layout::get_active());
        set_config('layout', '{"toolbar": [{"name": "a", "items": ["bold"]}]}', 'tiny_toolbar');
        $this->assertSame(['toolbar' => [['name' => 'a', 'items' => ['bold']]]], layout::get_active());

        set_config('layout', '{"toolbar": "bold"}', 'tiny_toolbar');
        $this->assertNull(layout::get_active());

        set_config('preset', 'unknown', 'tiny_toolbar');
        $this->assertNull(layout::get_active());
    }
}
