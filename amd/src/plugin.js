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
 * Tiny toolbar plugin: applies the configured toolbar and menus.
 *
 * The layout is applied when TinyMCE initialises this plugin: after every plugin has added its buttons to the
 * toolbar configuration, and before the editor draws its interface. Doing it in a configure() setup callback
 * is too early, because TinyMCE has not registered the toolbar and menubar options yet.
 *
 * @module     tiny_toolbar/plugin
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {getTinyMCE} from 'editor_tiny/loader';
import * as Configuration from './configuration';
import {pluginName} from './common';
import {register, getLayout} from './options';

/**
 * Replaces the toolbar and menus of an editor with the configured layout.
 *
 * @param {TinyMCE} editor
 */
const applyLayout = (editor) => {
    const layout = getLayout(editor);
    if (!layout) {
        return;
    }
    editor.options.set('toolbar', layout.toolbar);
    if (layout.menubar !== undefined) {
        editor.options.set('menubar', layout.menubar);
    }
    // Moodle turns the selection toolbar off in small text areas: keep it off there.
    if (layout.quickbars !== undefined && editor.options.isRegistered('quickbars_selection_toolbar')
            && editor.options.get('quickbars_selection_toolbar') !== false) {
        editor.options.set('quickbars_selection_toolbar', layout.quickbars);
    }
};

export default getTinyMCE().then((tinyMCE) => {
    tinyMCE.PluginManager.add(pluginName, (editor) => {
        register(editor);
        applyLayout(editor);
        return {name: pluginName};
    });
    return [pluginName, Configuration];
});
