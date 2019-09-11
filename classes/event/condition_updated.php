<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * condition_updated.php
 *
 * @package local_edaktik_condrole
 * @copyright (c) 2019 eDaktik GmbH
 * @author    Philipp Hager <philipp.hager@edaktik.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_edaktik_condrole\event;

use core\event\base;
use moodle_url;
use coding_exception;
use moodle_exception;

defined('MOODLE_INTERNAL') || die();

/**
 * Event is triggered if any condition is updated.
 *
 * @package local_edaktik_condrole
 * @copyright (c) 2019 eDaktik GmbH
 * @author    Philipp Hager <philipp.hager@edaktik.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class condition_updated extends base {
    /**
     * Init method.
     */
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'user';
    }

    /**
     * Get URL related to the action.
     *
     * @return moodle_url
     * @throws moodle_exception
     */
    public function get_url() {
        return new moodle_url("/local/edaktik_condrole/index.php");
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The condition for user with id '$this->userid' was updated.";
    }

    /**
     * Return localised event name.
     *
     * @return string
     * @throws coding_exception
     */
    public static function get_name() {
        return get_string('condition_updated', 'local_edaktik_condrole');
    }

    /**
     * Custom validation.
     *
     * @return void
     * @throws coding_exception
     */
    protected function validate_data() {
        parent::validate_data();
        // Make sure this class is never used without proper object details.
        if (empty($this->objectid) || empty($this->objecttable)) {
            throw new \coding_exception('The condition updated event must define objectid and object table.');
        }
        // Make sure the context level is set to module.
        if ($this->contextlevel != CONTEXT_SYSTEM) {
            throw new \coding_exception('Context level must be CONTEXT_SYSTEM.');
        }
        if (empty($this->data['other']['conditions'])) {
            throw new \coding_exception('Condition id must be specified.');
        }
        if (empty($this->data['other']['conditiontype'])) {
            throw new \coding_exception('Conditiontype must be specified.');
        }
    }
}