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
 * English strings for tiny_toolbar.
 *
 * @package    tiny_toolbar
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['addgroup'] = 'Add group';
$string['availablebuttons'] = 'Available buttons';
$string['availablebuttons_help'] = 'Buttons registered by the editor and its plugins on this site. Select a group, then a button to add it there, or drag the button to a group.';
$string['builder'] = 'Toolbar builder';
$string['builder_desc'] = 'Build the toolbar and choose the menus with the buttons this site really has. Saving makes this layout the custom layout in use.';
$string['builderlink'] = 'Open the toolbar builder';
$string['errorbutton'] = 'Every button must be a name made of letters, digits, hyphens or underscores.';
$string['errorgroup'] = 'Every group must have only a "name" and a list of "items".';
$string['errorgroupname'] = 'Every group name must be made of letters, digits, hyphens or underscores.';
$string['errorjson'] = 'The layout is not valid JSON.';
$string['errormenubar'] = 'The "menubar" must be false or a list of these menus: file, edit, view, insert, format, tools, table, help.';
$string['errornotoolbar'] = 'The layout must be a JSON object with a "toolbar" list.';
$string['errorquickbars'] = 'The "quickbars" must be false or button names separated by spaces, with | between groups, at most 50.';
$string['errortoolbar'] = 'The "toolbar" must be a list of at most 30 groups, with at most 50 buttons each.';
$string['errorunknownkey'] = 'The layout only accepts the keys "toolbar", "menubar" and "quickbars".';
$string['groupname'] = 'Group name';
$string['layout'] = 'Custom layout';
$string['layout_desc'] = 'Used when the layout in use is "Custom". JSON object with "toolbar", a list of groups such as {"name": "formatting", "items": ["bold", "italic"]}, "menubar", false or a list of menus such as "edit insert format", and optionally "quickbars", false or the buttons shown over selected text such as "bold italic | h3 h4 blockquote". The toolbar builder writes it for you.';
$string['loadpreset'] = 'Start from';
$string['menu_edit'] = 'Edit';
$string['menu_file'] = 'File';
$string['menu_format'] = 'Format';
$string['menu_help'] = 'Help';
$string['menu_insert'] = 'Insert';
$string['menu_table'] = 'Table';
$string['menu_tools'] = 'Tools';
$string['menu_view'] = 'View';
$string['menubar'] = 'Menus';
$string['movedown'] = 'Move down';
$string['moveleft'] = 'Move left';
$string['moveright'] = 'Move right';
$string['moveup'] = 'Move up';
$string['nobuttons'] = 'Empty group: select it and add buttons.';
$string['pluginname'] = 'Toolbar';
$string['preset'] = 'Layout in use';
$string['preset_accessibility'] = 'Accessibility';
$string['preset_custom'] = 'Custom';
$string['preset_default'] = 'Moodle default';
$string['preset_desc'] = 'Toolbar and menus of every TinyMCE editor on the site. "Moodle default" leaves the editor as the installed plugins arrange it.';
$string['preset_minimal'] = 'Minimal';
$string['preset_simple'] = 'Simple';
$string['preset_standard'] = 'Standard';
$string['preview'] = 'Preview';
$string['preview_help'] = 'This editor shows the layout in use. Save to see your changes here.';
$string['privacy:metadata'] = 'The Toolbar plugin stores no personal data.';
$string['quickbars'] = 'Selection toolbar';
$string['quickbars_custom'] = 'These buttons';
$string['quickbars_default'] = 'Moodle default';
$string['quickbars_help'] = 'Toolbar shown over selected text. Buttons separated by spaces, with | between groups, for example: bold italic | h3 h4 blockquote. The editor never shows it in small text areas, and some Moodle buttons, such as the link button, do not appear in it.';
$string['quickbars_off'] = 'Off';
$string['remove'] = 'Remove';
$string['removegroup'] = 'Remove group';
$string['saved'] = 'Custom layout saved and in use.';
$string['selectgroup'] = 'Select group';
$string['toolbar'] = 'Toolbar';
$string['toolbar:use'] = 'Use the toolbar and menus chosen by the administrator';
$string['unknownbutton'] = 'Not available on this site';
