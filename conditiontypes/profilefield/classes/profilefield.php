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
 * profilefield.php
 *
 * @package condrole_conditiontype_profilefield
 * @copyright (c) 2019 eDaktik GmbH
 * @author    Philipp Hager <philipp.hager@edaktik.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace condroletype_profilefield;

use local_edaktik_condrole\condition;
use local_edaktik_condrole\event\condition_updated;
use dml_exception;
use invalid_parameter_exception;
use coding_exception;
use local_edaktik_condrole\rule;
use context_system;

defined('MOODLE_INTERNAL') || die();

/**
 * Class profilefield
 *
 * @package condrole_conditiontype_profilefield
 * @copyright (c) 2019 eDaktik GmbH
 * @author    Philipp Hager <philipp.hager@edaktik.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class profilefield implements condition {
    /** @var int fieldname doesn't equal the pattern (for text LIKE is used) */
    const NOT_EQUALS = 0;
    /** @var int fieldname equals pattern (for text LIKE is used) */
    const EQUALS = 1;
    /** @var int type strict negative comparison */
    const STRICT_NOT_EQUALS = 2;
    /** @var int type strict comparison */
    const STRICT_EQUALS = 3;
    /** @var int type strict comparison */
    const GREATER_THAN = 4;
    /** @var int type strict negative comparison */
    const SMALLER_THAN = 5;
    /** @var int match against a regex pattern using preg_match() */
    const REGEX = 6;
    // TODO these operators can maybe extended to others too, like special string comparison, dates, etc.

    /** @var int id is set to the DB id, as soon as the condition is present there! */
    protected $id = 0;
    /** @var string the userfield to compare against */
    protected $fieldname = '';
    /** @var int the comparison operator to be used */
    protected $operator = self::EQUALS;
    /** @var mixed the pattern to check against, can be of any type, but will often be a string due to database */
    protected $pattern = '';

    /**
     * profilefield constructor.
     * @param string $fieldname
     * @param int $operator
     * @param string $pattern
     */
    public function __construct(string $fieldname, int $operator, string $pattern) {
        $this->fieldname = $fieldname;
        $this->operator = $operator;
        $this->pattern = $pattern;
    }

    /**
     * Get the profilefield instance right from the DB id!
     *
     * @param int $id
     * @return profilefield
     * @throws dml_exception
     */
    public static function get_from_db(int $id): condition {
        global $DB;

        $data = $DB->get_record('condroletype_profilefield', ['id' => $id]);

        $cond = new self($data->fieldname, $data->operator, $data->pattern);
        $cond->id = $id;

        return $cond;
    }

    /**
     * Return if a user meets this certain condition.
     *
     * @param int $userid The user's database id
     * @return bool the calculated condition value for this user
     * @throws dml_exception
     * @throws invalid_parameter_exception
     */
    public function result(int $userid): bool {
        global $DB;

        $userfield = $DB->get_field('user', $this->fieldname, ['id' => $userid]);

        return $this->comparison($userfield);
    }

    /**
     * Return if multiple users meet this certain condition.
     *
     * @param int[] $userids The user's database id
     * @return bool[] the calculated condition value for this user
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws coding_exception
     */
    public function results(array $userids): array {
        global $DB;

        if (empty($userids)) {
            return [];
        }

        list($sql, $params) = $DB->get_in_or_equal($userids);

        $userfields = $DB->get_records_select_menu('user', 'id '.$sql, $params, '', 'id, '.$this->fieldname);

        foreach ($userfields as $id => $userfield) {
            $return[$id] = $this->comparison($userfield);
        }

        return $return;
    }

    /**
     * @param $userfield
     * @return bool|false|int
     * @throws invalid_parameter_exception
     */
    protected function comparison($userfield) {
        switch ($this->operator) {
            case static::EQUALS:
                return $userfield == $this->pattern;
            case static::NOT_EQUALS:
                return $userfield != $this->pattern;
            case static::STRICT_EQUALS:
                return $userfield === $this->pattern;
            case static::STRICT_NOT_EQUALS:
                return $userfield !== $this->pattern;
            case static::GREATER_THAN:
                return $userfield > $this->pattern;
            case static::SMALLER_THAN:
                return $userfield < $this->pattern;
            case static::REGEX:
                return preg_match($this->pattern, $userfield);
        }

        throw new invalid_parameter_exception('Wrong comparison type!');
    }

    /**
     * Returns the settings form used by a get fragment call!
     *
     * TODO!
     *
     * @return string
     */
    public function get_settings_form(): string {
        return '';
    }

    /**
     * TODO inform subscribers via event!
     */
    public function inform_subscribers() {
        // TODO: Implement inform_subscribers() method.
    }

    /**
     * Save the condition and return it's ID!
     *
     * @return int
     * @throws dml_exception
     */
    public function save(): int {
        global $DB, $USER;
        if (!empty($this->id)) {
            $DB->update_record('condroletype_profilefield', (object)[
                'id' => $this->id,
                'fieldname' => $this->fieldname,
                'operator' => $this->operator,
                'pattern' => $this->pattern,
                'timemodified' => time(),
                'usermodified' => $USER->id
            ]);
        } else {
            $time = time();
            $this->id = $DB->insert_record('condroletype_profilefield', (object)[
                'fieldname' => $this->fieldname,
                'operator' => $this->operator,
                'pattern' => $this->pattern,
                'timecreated' => $time,
                'timemodified' => $time,
                'usermodified' => $USER->id
            ]);
        }

        return $this->id;
    }

    /**
     * Eventhandler on user update event. Just inform everyone above about change!
     *
     * @param $event
     */
    public static function user_created($event) {
        global $DB;

        $conditions = $DB->get_fieldset_select('condroletype_profilefield', 'id', '1=1');
        if (empty($conditions)) {
            return;
        }

        $event = condition_updated::create([
            'context' => context_system::instance(),
            'objectid' => $event->relateduserid,
            'relateduserid' => $event->relateduserid,
            'other' => [
                'conditions' => $conditions,
                'conditiontype' => 'profilefield',
            ]
        ]);
        $event->trigger();
    }

    /**
     * Eventhandler on user update event. Just inform everyone above about change!
     *
     * @param $event
     */
    public static function user_updated($event) {
        global $DB;

        $conditions = $DB->get_fieldset_select('condroletype_profilefield', 'id', '1=1');
        if (empty($conditions)) {
            return;
        }

        $event = condition_updated::create([
            'context' => context_system::instance(),
            'objectid' => $event->relateduserid,
            'relateduserid' => $event->relateduserid,
            'other' => [
                'conditions' => $conditions,
                'conditiontype' => 'profilefield'
            ]
        ]);
        $event->trigger();
    }
}