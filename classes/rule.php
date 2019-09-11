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
 * rule.php
 *
 * @package local_edaktik_condrole
 * @copyright (c) 2019 eDaktik GmbH
 * @author    Philipp Hager <philipp.hager@edaktik.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_edaktik_condrole;

use dml_exception;
use invalid_parameter_exception;

defined('MOODLE_INTERNAL') || die();

/**
 * Class rule
 *
 * @package local_edaktik_condrole
 * @copyright (c) 2019 eDaktik GmbH
 * @author    Philipp Hager <philipp.hager@edaktik.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule {
    /** @var int database ID for the rule */
    protected $id;
    /** @var string short name describing the rule */
    protected $title;
    /** @var string text describing the rule a little further */
    protected $description;
    /** @var bool whether or not the rule is active! */
    protected $active = false;
    /** @var int currently we only support CONTEXT_COURSECAT, TODO: remove hardcoded value and support other contexts too! */
    protected $contextlevel = CONTEXT_COURSECAT; // TODO remove hardcoded!
    /** @var int order in which the rules are processed in case of mass processing (=cron) */
    protected $sortorder = 0;
    /** @var int[] array of contexts to (un)assign role */
    protected $contexts = [];
    /** @var int role id to use */
    protected $role = 0;
    /** @var null string which conditiontype is being used */
    protected $conditiontype = null;
    /** @var int database id of condition being used */
    protected $conditionid = 0;

    /**
     * rule constructor.
     * @param $title
     * @param $description
     * @param $active
     * @param $sortorder
     * @param $contexts
     * @param $role
     * @param $conditiontype
     * @param $conditionid
     */
    public function __construct($title, $description, $active, $sortorder, $contexts, $role,
                                $conditiontype=null, $conditionid=0) {
        $this->title = $title;
        $this->description = $description;
        $this->active = $active;
        $this->sortorder = $sortorder;
        $this->contexts = $contexts;
        $this->role = $role;

        if (!empty($conditiontype) && !empty($conditionid)) {
            $this->set_condition($conditiontype, $conditionid);
        }
    }

    /**
     * Get the rule from DB!
     *
     * @param $id
     * @return self
     * @throws dml_exception
     */
    public static function get_from_db($id) {
        global $DB;

        $data = $DB->get_record('local_edaktik_condrole_rules', ['id' => $id]);
        $contexts = $DB->get_fieldset_select('lcoal_edaktik_condrole_rcont', 'contextid', "ruleid = :ruleid", [
            'ruleid' => $id
        ]);

        $rule = new self($data->title, $data->description, $data->active, $data->sortorder, $contexts, $data->roleid);

        if (!empty($data->conditiontype) && !empty($data->conditionid)) {
            $rule->set_condition($data->conditiontype, $data->conditionid);
        }

        $rule->set_id($id);

        return $rule;
    }

    /**
     * Set the primary condition used for this rule!
     *
     * @param $type
     * @param $id
     */
    public function set_condition($type, $id) {
        $this->conditiontype = $type;
        $this->conditionid = $id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    /**
     * Get the condition object and return it here, singleton syntax!
     *
     * @return condition
     */
    public function condition(): condition {
        static $condition = null;

        if (!empty($condition)) {
            return $condition;
        }

        if (empty($this->conditiontype) || empty($this->conditionid)) {
            throw new invalid_parameter_exception('Condition not set!');
        }

        /** @var condition $classname */
        $classname = '\\condroletype_'.$this->conditiontype.'\\'.$this->conditiontype;

        $condition = $classname::get_from_db($this->conditionid);

        return $condition;
    }

    // SAVE
    // LOAD FROM DB

    /**
     * Process rule either, for a single user, or for all applicable users!
     *
     * @param bool $userid
     * @throws invalid_parameter_exception
     */
    public function process_rule($userid=false) {
        global $DB;

        if ($userid) {
            return "Rule ".$this->title."'s condition for user ".$userid." is".
                ($this->condition()->result($userid) ? 'true' : 'false');
        }

        $rs = $DB->get_recordset('user', [], '', 'id');
        foreach ($rs as $rec) {
            if ($this->condition()->result($rec->id)) {
                // Condition is met!
            } else {
                // Condition is not met!
            }
        }
    }
}