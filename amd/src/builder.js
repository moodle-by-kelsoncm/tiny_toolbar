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
 * The available buttons come from the preview editor on the same page, so the list matches the buttons this
 * site really has. The layout is kept in the hidden "layout" field and checked again by the server on save.
 *
 * @module     tiny_toolbar/builder
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {getTinyMCE} from 'editor_tiny/loader';
import {getStrings} from 'core/str';
import {component} from './common';

/** @var {string[]} Strings used by the builder. */
const STRINGS = ['groupname', 'moveleft', 'moveright', 'moveup', 'movedown', 'remove', 'removegroup',
    'selectgroup', 'nobuttons', 'unknownbutton'];

/** @var {Object} Toolbar items drawn by TinyMCE itself, with their TinyMCE labels. */
const BESPOKE = {
    blocks: 'Blocks',
    styles: 'Formats',
    fontfamily: 'Fonts',
    fontsize: 'Font sizes',
    align: 'Align',
    lineheight: 'Line height',
};

/**
 * Waits for the TinyMCE instance of a textarea.
 *
 * @param {string} id Textarea id.
 * @returns {Promise<TinyMCE>}
 */
const waitForEditor = async(id) => {
    const tinyMCE = await getTinyMCE();
    return new Promise((resolve) => {
        const check = () => {
            const editor = tinyMCE.get(id);
            if (editor && editor.initialized) {
                resolve(editor);
                return true;
            }
            return false;
        };
        if (!check()) {
            const timer = setInterval(() => check() && clearInterval(timer), 200);
        }
    });
};

/**
 * Buttons registered in an editor, sorted by label.
 *
 * @param {TinyMCE} editor
 * @returns {Map<string, {label: string, icon: string}>}
 */
const getButtons = (editor) => {
    const registry = editor.ui.registry.getAll();
    // Toolbar items that TinyMCE draws itself, outside the button registry.
    const buttons = new Map(Object.entries(BESPOKE).map(([name, label]) => [name, {label: editor.translate(label), icon: ''}]));
    Object.entries(registry.buttons).forEach(([name, spec]) => {
        const label = editor.translate(spec.tooltip || spec.text || name);
        buttons.set(name, {label, icon: spec.icon ? (registry.icons[spec.icon] || '') : ''});
    });
    return new Map([...buttons.entries()].sort((a, b) => a[1].label.localeCompare(b[1].label)));
};

/**
 * Creates an element.
 *
 * @param {string} tag
 * @param {Object} attributes
 * @param {string} text
 * @returns {HTMLElement}
 */
const element = (tag, attributes = {}, text = '') => {
    const node = document.createElement(tag);
    Object.entries(attributes).forEach(([name, value]) => node.setAttribute(name, value));
    if (text) {
        node.textContent = text;
    }
    return node;
};

/**
 * Creates a small icon button.
 *
 * @param {string} label
 * @param {string} symbol
 * @param {Function} action
 * @returns {HTMLButtonElement}
 */
const iconButton = (label, symbol, action) => {
    const button = element('button', {type: 'button', 'class': 'btn btn-link btn-sm', title: label, 'aria-label': label},
        symbol);
    button.addEventListener('click', action);
    return button;
};

/**
 * Builder state and drawing.
 */
class Builder {
    /**
     * Constructor.
     *
     * @param {HTMLElement} root
     * @param {Object} config
     * @param {Object} strings
     */
    constructor(root, config, strings) {
        this.root = root;
        this.config = config;
        this.strings = strings;
        this.buttons = new Map();
        this.selected = 0;
        this.field = root.closest('form').querySelector('[data-tiny-toolbar="layout"]');
        this.setLayout(config.layout);
        this.bind();
    }

    /**
     * Replaces the layout being edited.
     *
     * @param {Object} layout
     */
    setLayout(layout) {
        this.layout = {
            toolbar: (layout.toolbar || []).map((group) => ({name: group.name, items: [...group.items]})),
            menubar: layout.menubar === undefined ? false : layout.menubar,
        };
        if (layout.quickbars !== undefined) {
            this.layout.quickbars = layout.quickbars;
        }
        this.selected = 0;
        const menus = this.layout.menubar ? this.layout.menubar.split(/\s+/) : [];
        this.root.querySelectorAll('[data-tiny-toolbar="menu"]').forEach((box) => {
            box.checked = menus.includes(box.value);
        });
        const mode = this.root.querySelector('[data-tiny-toolbar="quickbarsmode"]');
        const input = this.root.querySelector('[data-tiny-toolbar="quickbars"]');
        if (layout.quickbars === undefined) {
            mode.value = 'default';
        } else {
            mode.value = layout.quickbars === false ? 'off' : 'custom';
        }
        input.value = typeof layout.quickbars === 'string' ? layout.quickbars : '';
        input.disabled = mode.value !== 'custom';
        this.render();
    }

    /**
     * Reads the selection toolbar controls into the layout.
     */
    readQuickbars() {
        const mode = this.root.querySelector('[data-tiny-toolbar="quickbarsmode"]').value;
        const input = this.root.querySelector('[data-tiny-toolbar="quickbars"]');
        input.disabled = mode !== 'custom';
        if (mode === 'default' || (mode === 'custom' && !input.value.trim())) {
            delete this.layout.quickbars;
        } else {
            this.layout.quickbars = mode === 'off' ? false : input.value.trim();
        }
        this.save();
    }

    /**
     * Binds the static controls.
     */
    bind() {
        this.root.querySelector('[data-tiny-toolbar="preset"]').addEventListener('change', (e) => {
            const preset = this.config.presets.find((item) => item.name === e.target.value);
            if (preset) {
                this.setLayout(preset.layout);
            }
            e.target.value = '';
        });
        this.root.querySelectorAll('[data-tiny-toolbar="menu"]').forEach((box) => {
            box.addEventListener('change', () => {
                const menus = [...this.root.querySelectorAll('[data-tiny-toolbar="menu"]:checked')].map((b) => b.value);
                this.layout.menubar = menus.length ? menus.join(' ') : false;
                this.save();
            });
        });
        this.root.querySelector('[data-tiny-toolbar="quickbarsmode"]').addEventListener('change', () => this.readQuickbars());
        this.root.querySelector('[data-tiny-toolbar="quickbars"]').addEventListener('input', () => this.readQuickbars());
        this.root.querySelector('[data-tiny-toolbar="addgroup"]').addEventListener('click', () => {
            this.layout.toolbar.push({name: `group${this.layout.toolbar.length + 1}`, items: []});
            this.selected = this.layout.toolbar.length - 1;
            this.render();
        });
    }

    /**
     * Sets the buttons available on the site.
     *
     * @param {Map} buttons
     */
    setButtons(buttons) {
        this.buttons = buttons;
        this.render();
    }

    /**
     * Writes the layout to the hidden field.
     */
    save() {
        this.field.value = JSON.stringify(this.layout);
    }

    /**
     * Moves an element of a list.
     *
     * @param {Array} list
     * @param {number} from
     * @param {number} to
     */
    move(list, from, to) {
        if (to < 0 || to >= list.length) {
            return;
        }
        list.splice(to, 0, list.splice(from, 1)[0]);
        this.render();
    }

    /**
     * Creates the chip of a button.
     *
     * @param {string} name
     * @param {Object} drag Drag data.
     * @returns {HTMLElement}
     */
    chip(name, drag) {
        const info = this.buttons.get(name);
        const unknown = this.buttons.size > 0 && !info;
        const chip = element('span', {
            'class': 'tiny-toolbar-chip' + (unknown ? ' is-unknown' : ''),
            draggable: 'true',
            title: unknown ? `${name} (${this.strings.unknownbutton})` : name,
            'data-button': name,
        });
        if (info && info.icon) {
            const icon = element('span', {'aria-hidden': 'true'});
            // Icon markup comes from the TinyMCE icon registry of this page.
            icon.innerHTML = info.icon;
            chip.append(icon);
        }
        chip.append(element('span', {}, info ? info.label : name));
        chip.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', JSON.stringify({...drag, name}));
        });
        return chip;
    }

    /**
     * Makes a list of buttons accept dropped buttons.
     *
     * @param {HTMLElement} target
     * @param {number} groupindex
     */
    droppable(target, groupindex) {
        target.addEventListener('dragover', (e) => e.preventDefault());
        target.addEventListener('drop', (e) => {
            e.preventDefault();
            let data;
            try {
                data = JSON.parse(e.dataTransfer.getData('text/plain'));
            } catch (error) {
                return;
            }
            const items = this.layout.toolbar[groupindex].items;
            const before = e.target.closest('[data-index]');
            let position = before ? parseInt(before.dataset.index, 10) : items.length;
            if (data.group !== undefined) {
                const source = this.layout.toolbar[data.group].items;
                source.splice(data.index, 1);
                if (data.group === groupindex && data.index < position) {
                    position--;
                }
            }
            items.splice(position, 0, data.name);
            this.selected = groupindex;
            this.render();
        });
    }

    /**
     * Draws the group list.
     */
    renderGroups() {
        const list = this.root.querySelector('[data-tiny-toolbar="groups"]');
        list.replaceChildren();
        this.layout.toolbar.forEach((group, groupindex) => {
            const item = element('li', {'class': 'tiny-toolbar-group' + (groupindex === this.selected ? ' is-selected' : '')});
            const header = element('div', {'class': 'd-flex align-items-center mb-2'});
            const select = element('input', {type: 'radio', name: 'tiny-toolbar-selected', 'class': 'form-check-input me-2',
                'aria-label': `${this.strings.selectgroup}: ${group.name}`});
            select.checked = groupindex === this.selected;
            select.addEventListener('change', () => {
                this.selected = groupindex;
                this.render();
            });
            const name = element('input', {type: 'text', 'class': 'form-control form-control-sm w-auto me-auto',
                value: group.name, 'aria-label': this.strings.groupname, pattern: '[A-Za-z0-9][A-Za-z0-9_-]*', maxlength: '80'});
            name.addEventListener('change', () => {
                if (name.checkValidity() && name.value) {
                    group.name = name.value;
                    this.save();
                } else {
                    name.value = group.name;
                }
            });
            header.append(select, name,
                iconButton(this.strings.moveup, '↑', () => this.move(this.layout.toolbar, groupindex, groupindex - 1)),
                iconButton(this.strings.movedown, '↓', () => this.move(this.layout.toolbar, groupindex, groupindex + 1)),
                iconButton(this.strings.removegroup, '×', () => {
                    this.layout.toolbar.splice(groupindex, 1);
                    this.selected = Math.max(0, Math.min(this.selected, this.layout.toolbar.length - 1));
                    this.render();
                }));

            const items = element('div', {'class': 'tiny-toolbar-items d-flex flex-wrap'});
            if (!group.items.length) {
                items.append(element('span', {'class': 'text-muted small'}, this.strings.nobuttons));
            }
            group.items.forEach((button, index) => {
                const chip = this.chip(button, {group: groupindex, index});
                chip.dataset.index = index;
                chip.append(
                    iconButton(this.strings.moveleft, '‹', () => this.move(group.items, index, index - 1)),
                    iconButton(this.strings.moveright, '›', () => this.move(group.items, index, index + 1)),
                    iconButton(this.strings.remove, '×', () => {
                        group.items.splice(index, 1);
                        this.render();
                    }));
                items.append(chip);
            });
            this.droppable(items, groupindex);
            item.addEventListener('click', (e) => {
                if (e.target === item || e.target === items) {
                    this.selected = groupindex;
                    this.render();
                }
            });
            item.append(header, items);
            list.append(item);
        });
    }

    /**
     * Draws the available buttons.
     */
    renderAvailable() {
        const list = this.root.querySelector('[data-tiny-toolbar="available"]');
        list.replaceChildren();
        const used = new Set(this.layout.toolbar.flatMap((group) => group.items));
        this.buttons.forEach((info, name) => {
            if (used.has(name)) {
                return;
            }
            const chip = this.chip(name, {});
            chip.setAttribute('role', 'button');
            chip.setAttribute('tabindex', '0');
            const add = () => {
                if (!this.layout.toolbar.length) {
                    this.layout.toolbar.push({name: 'group1', items: []});
                    this.selected = 0;
                }
                this.layout.toolbar[this.selected].items.push(name);
                this.render();
            };
            chip.addEventListener('click', add);
            chip.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    add();
                }
            });
            list.append(chip);
        });
    }

    /**
     * Draws the builder and saves the layout.
     */
    render() {
        this.renderGroups();
        this.renderAvailable();
        this.save();
    }
}

/**
 * Starts the builder.
 *
 * @param {string} selector Builder container.
 */
export const init = async(selector) => {
    const root = document.querySelector(selector);
    if (!root) {
        return;
    }
    const keys = STRINGS.map((key) => ({key, component}));
    const values = await getStrings(keys);
    const strings = Object.fromEntries(STRINGS.map((key, index) => [key, values[index]]));
    const builder = new Builder(root, JSON.parse(root.dataset.config), strings);

    const preview = root.closest('form').querySelector('textarea[id^="id_preview"]');
    if (preview) {
        builder.setButtons(getButtons(await waitForEditor(preview.id)));
    }
};
