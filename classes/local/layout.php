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
 * Toolbar and menu layouts: presets, validation and the layout in use.
 *
 * A layout is an array with:
 * - 'toolbar': list of groups, each one ['name' => string, 'items' => string[]] with TinyMCE button names;
 * - 'menubar': false (no menu) or a space-separated list of menu names;
 * - 'quickbars': optional, false (no selection toolbar) or the buttons of the toolbar shown over selected text,
 *   separated by spaces, with '|' between groups.
 *
 * @package    tiny_toolbar
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class layout {
    /** @var string Keep the Moodle default toolbar and menu. */
    const PRESET_DEFAULT = 'default';

    /** @var string Preset with only the essential buttons. */
    const PRESET_MINIMAL = 'minimal';

    /** @var string Preset for simple writing, such as forum posts. */
    const PRESET_SIMPLE = 'simple';

    /** @var string Preset with the usual buttons of course authoring. */
    const PRESET_STANDARD = 'standard';

    /** @var string Preset focused on accessible content. */
    const PRESET_ACCESSIBILITY = 'accessibility';

    /** @var string Layout written by the administrator. */
    const PRESET_CUSTOM = 'custom';

    /** @var string[] Menus TinyMCE knows. */
    const MENUS = ['file', 'edit', 'view', 'insert', 'format', 'tools', 'table', 'help'];

    /** @var int Maximum number of toolbar groups. */
    const MAX_GROUPS = 30;

    /** @var int Maximum number of buttons in a group. */
    const MAX_ITEMS = 50;

    /** @var string Pattern of group and button names. */
    const NAME_PATTERN = '/^[a-z0-9][a-z0-9_-]{0,79}$/i';

    /**
     * Preset names, in display order.
     *
     * @return string[]
     */
    public static function get_preset_names(): array {
        return [
            self::PRESET_DEFAULT,
            self::PRESET_MINIMAL,
            self::PRESET_SIMPLE,
            self::PRESET_STANDARD,
            self::PRESET_ACCESSIBILITY,
            self::PRESET_CUSTOM,
        ];
    }

    /**
     * Layouts of the presets that define one.
     *
     * @return array Preset name => layout.
     */
    public static function get_presets(): array {
        $history = ['name' => 'history', 'items' => ['undo', 'redo']];
        $lists = ['name' => 'lists', 'items' => ['bullist', 'numlist']];
        return [
            self::PRESET_MINIMAL => [
                'toolbar' => [
                    $history,
                    ['name' => 'formatting', 'items' => ['bold', 'italic']],
                    $lists,
                    ['name' => 'content', 'items' => ['tiny_link_link']],
                ],
                'menubar' => false,
                'quickbars' => 'bold italic',
            ],
            self::PRESET_SIMPLE => [
                'toolbar' => [
                    $history,
                    ['name' => 'formatting', 'items' => ['bold', 'italic', 'underline']],
                    $lists,
                    ['name' => 'content', 'items' => ['tiny_link_link', 'tiny_media_image', 'tiny_media_video']],
                    ['name' => 'advanced', 'items' => ['tiny_equation']],
                ],
                'menubar' => false,
                'quickbars' => 'bold italic | h3 h4 blockquote',
            ],
            self::PRESET_STANDARD => [
                'toolbar' => [
                    $history,
                    ['name' => 'style', 'items' => ['blocks']],
                    ['name' => 'formatting', 'items' => ['bold', 'italic', 'underline', 'strikethrough']],
                    ['name' => 'alignment', 'items' => ['alignleft', 'aligncenter', 'alignright']],
                    $lists,
                    ['name' => 'indentation', 'items' => ['outdent', 'indent']],
                    ['name' => 'content', 'items' => ['tiny_link_link', 'tiny_link_unlink', 'tiny_media_image',
                        'tiny_media_video', 'tiny_h5p']],
                    ['name' => 'advanced', 'items' => ['tiny_equation', 'table']],
                    ['name' => 'view', 'items' => ['fullscreen']],
                ],
                'menubar' => 'edit view insert format tools table help',
                'quickbars' => 'bold italic | h3 h4 h5 h6 blockquote',
            ],
            self::PRESET_ACCESSIBILITY => [
                'toolbar' => [
                    $history,
                    ['name' => 'style', 'items' => ['blocks']],
                    ['name' => 'formatting', 'items' => ['bold', 'italic']],
                    $lists,
                    ['name' => 'content', 'items' => ['tiny_link_link', 'tiny_media_image']],
                    ['name' => 'accessibility', 'items' => ['tiny_accessibilitychecker']],
                ],
                'menubar' => 'edit insert format tools help',
                'quickbars' => 'bold italic | h3 h4 h5 h6',
            ],
        ];
    }

    /**
     * Checks a layout decoded from JSON.
     *
     * @param mixed $layout
     * @return string Empty when valid, otherwise the identifier of the error string.
     */
    public static function validate($layout): string {
        if (!is_array($layout) || !array_key_exists('toolbar', $layout)) {
            return 'errornotoolbar';
        }
        $unknown = array_diff(array_keys($layout), ['toolbar', 'menubar', 'quickbars']);
        if ($unknown) {
            return 'errorunknownkey';
        }
        if (
            !is_array($layout['toolbar']) || !array_is_list($layout['toolbar'])
                || count($layout['toolbar']) > self::MAX_GROUPS
        ) {
            return 'errortoolbar';
        }
        foreach ($layout['toolbar'] as $group) {
            if (!is_array($group) || !isset($group['name'], $group['items']) || count($group) !== 2) {
                return 'errorgroup';
            }
            if (!is_string($group['name']) || !preg_match(self::NAME_PATTERN, $group['name'])) {
                return 'errorgroupname';
            }
            if (!is_array($group['items']) || !array_is_list($group['items']) || count($group['items']) > self::MAX_ITEMS) {
                return 'errorgroup';
            }
            foreach ($group['items'] as $item) {
                if (!is_string($item) || !preg_match(self::NAME_PATTERN, $item)) {
                    return 'errorbutton';
                }
            }
        }
        if (array_key_exists('menubar', $layout)) {
            $menubar = $layout['menubar'];
            if ($menubar !== false) {
                if (!is_string($menubar)) {
                    return 'errormenubar';
                }
                $menus = preg_split('/\s+/', trim($menubar), -1, PREG_SPLIT_NO_EMPTY);
                if (!$menus || array_diff($menus, self::MENUS)) {
                    return 'errormenubar';
                }
            }
        }
        if (array_key_exists('quickbars', $layout) && $layout['quickbars'] !== false) {
            if (!is_string($layout['quickbars'])) {
                return 'errorquickbars';
            }
            $items = preg_split('/\s+/', trim($layout['quickbars']), -1, PREG_SPLIT_NO_EMPTY);
            if (!$items || count($items) > self::MAX_ITEMS) {
                return 'errorquickbars';
            }
            foreach ($items as $item) {
                if ($item !== '|' && !preg_match(self::NAME_PATTERN, $item)) {
                    return 'errorquickbars';
                }
            }
        }
        return '';
    }

    /**
     * Decodes and checks a layout written as JSON.
     *
     * @param string $json
     * @return array [layout or null, error string identifier or '']
     */
    public static function parse(string $json): array {
        $layout = json_decode($json, true, 8);
        if ($layout === null && json_last_error() !== JSON_ERROR_NONE) {
            return [null, 'errorjson'];
        }
        $error = self::validate($layout);
        return $error === '' ? [self::normalise($layout), ''] : [null, $error];
    }

    /**
     * Normalises a valid layout: empty groups removed, menus separated by single spaces.
     *
     * @param array $layout Valid layout.
     * @return array
     */
    public static function normalise(array $layout): array {
        $toolbar = [];
        foreach ($layout['toolbar'] as $group) {
            if ($group['items']) {
                $toolbar[] = ['name' => $group['name'], 'items' => array_values($group['items'])];
            }
        }
        $result = ['toolbar' => $toolbar];
        if (array_key_exists('menubar', $layout)) {
            $result['menubar'] = $layout['menubar'] === false ? false :
                implode(' ', preg_split('/\s+/', trim($layout['menubar']), -1, PREG_SPLIT_NO_EMPTY));
        }
        if (array_key_exists('quickbars', $layout)) {
            $result['quickbars'] = $layout['quickbars'] === false ? false :
                implode(' ', preg_split('/\s+/', trim($layout['quickbars']), -1, PREG_SPLIT_NO_EMPTY));
        }
        return $result;
    }

    /**
     * Encodes a layout as readable JSON, with one toolbar group per line.
     *
     * @param array $layout
     * @return string
     */
    public static function to_json(array $layout): string {
        $flags = JSON_UNESCAPED_SLASHES;
        $groups = array_map(fn($group) => '        ' . json_encode($group, $flags), $layout['toolbar']);
        $lines = ['    "toolbar": ' . ($groups ? "[\n" . implode(",\n", $groups) . "\n    ]" : '[]')];
        foreach (['menubar', 'quickbars'] as $key) {
            if (array_key_exists($key, $layout)) {
                $lines[] = '    "' . $key . '": ' . json_encode($layout[$key], $flags);
            }
        }
        return "{\n" . implode(",\n", $lines) . "\n}";
    }

    /**
     * The layout to apply on the site.
     *
     * @return array|null Null keeps the Moodle default.
     */
    public static function get_active(): ?array {
        $preset = get_config('tiny_toolbar', 'preset') ?: self::PRESET_DEFAULT;
        if ($preset === self::PRESET_CUSTOM) {
            [$layout] = self::parse((string)get_config('tiny_toolbar', 'layout'));
            return $layout;
        }
        return self::get_presets()[$preset] ?? null;
    }
}
