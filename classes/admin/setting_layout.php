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

namespace tiny_toolbar\admin;

use tiny_toolbar\local\layout;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/adminlib.php');

/**
 * Admin setting with the custom layout as JSON, validated and stored normalised.
 *
 * @package    tiny_toolbar
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class setting_layout extends \admin_setting_configtextarea {
    /**
     * Constructor.
     *
     * @param string $name
     * @param string $visiblename
     * @param string $description
     * @param string $defaultsetting
     */
    public function __construct($name, $visiblename, $description, $defaultsetting) {
        parent::__construct($name, $visiblename, $description, $defaultsetting, PARAM_TEXT, 60, 16);
    }

    /**
     * Accepts an empty value or a valid layout.
     *
     * @param string $data
     * @return true|string True when valid, otherwise the error message.
     */
    public function validate($data) {
        if (trim((string)$data) === '') {
            return true;
        }
        [, $error] = layout::parse((string)$data);
        return $error === '' ? true : get_string($error, 'tiny_toolbar');
    }

    /**
     * Stores the layout normalised.
     *
     * @param string $data
     * @return string Empty on success, otherwise the error message.
     */
    public function write_setting($data) {
        $validated = $this->validate($data);
        if ($validated !== true) {
            return $validated;
        }
        if (trim((string)$data) !== '') {
            [$parsed] = layout::parse((string)$data);
            $data = layout::to_json($parsed);
        }
        return $this->config_write($this->name, $data) ? '' : get_string('errorsetting', 'admin');
    }
}
