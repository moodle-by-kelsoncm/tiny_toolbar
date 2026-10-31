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

namespace tiny_toolbar\form;

use tiny_toolbar\local\layout;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Form of the toolbar builder: the layout built by JavaScript and an editor to preview it.
 *
 * Custom data: 'builder' (HTML of the builder).
 *
 * @package    tiny_toolbar
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class builder_form extends \moodleform {
    /**
     * Form definition.
     */
    protected function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'layout', '', ['data-tiny-toolbar' => 'layout']);
        $mform->setType('layout', PARAM_TEXT);

        $mform->addElement('html', $this->_customdata['builder']);

        $mform->addElement('editor', 'preview', get_string('preview', 'tiny_toolbar'), ['rows' => 8]);
        $mform->setType('preview', PARAM_CLEANHTML);
        $mform->addHelpButton('preview', 'preview', 'tiny_toolbar');

        $this->add_action_buttons(false);
    }

    /**
     * Checks the layout.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        [, $error] = layout::parse((string)($data['layout'] ?? ''));
        if ($error !== '') {
            $errors['preview'] = get_string($error, 'tiny_toolbar');
        }
        return $errors;
    }
}
