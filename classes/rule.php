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

use coding_exception;
use core\notification;
use dml_exception;
use invalid_parameter_exception;
use local_edaktik_condrole\event\condition_updated;

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
    /** @var int active rule */
    const ACTIVE = 1;
    /** @var int inactive rule */
    const INACTIVE = 0;

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
     * @param string $title
     * @param string $description
     * @param bool $active
     * @param int $sortorder
     * @param int[] $contexts
     * @param int $role
     * @param string $conditiontype
     * @param int $conditionid
     */
    public function __construct(string $title, string $description, bool $active, int $sortorder, array $contexts,
                                int $role, string $conditiontype=null, int $conditionid=0) {
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
     * @param int $id
     * @return self
     * @throws dml_exception
     */
    public static function get_from_db(int $id) {
        global $DB;

        $data = $DB->get_record('local_edaktik_condrole_rules', ['id' => $id]);
        $contexts = $DB->get_fieldset_select('local_edaktik_condrole_rcont', 'contextid', "ruleid = :ruleid", [
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
     * @param string $type
     * @param int $id
     */
    public function set_condition(string $type, int $id) {
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
     * @throws invalid_parameter_exception
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

    /**
     * Saves current state to database
     *
     * @return int the rule's database id
     * @throws dml_exception
     */
    public function save(): int {
        global $DB, $USER;

        if (!empty($this->id)) {
            $DB->update_record('local_edaktik_condrole_rules', (object)[
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'active' => $this->active,
                'sortorder' => $this->sortorder,
                'roleid' => $this->role,
                'conditiontype' => $this->conditiontype,
                'conditionid' => $this->conditionid,
                'timemodified' => time(),
                'usermodified' => $USER->id,
            ]);
        } else {
            $time = time();
            $this->id = $DB->insert_record('local_edaktik_condrole_rules', (object)[
                'title' => $this->title,
                'description' => $this->description,
                'active' => $this->active,
                'sortorder' => $this->sortorder,
                'roleid' => $this->role,
                'conditiontype' => $this->conditiontype,
                'conditionid' => $this->conditionid,
                'timemodifified' => $time,
                'timecreated' => $time,
                'usermodified' => $USER->id,
            ]);
        }
        $this->udpate_contexts();

        return $this->id;
    }

    /**
     * Update the database entries for related contexts.
     *
     * @throws coding_exception
     * @throws dml_exception
     * @throws invalid_parameter_exception
     */
    protected function update_contexts() {
        global $DB;

        if (empty($this->id)) {
            throw new invalid_parameter_exception('This method can\'t be called if the rule was not saved to the DB!');
        }

        $contexts = $DB->get_fieldset_select('local_edaktik_condrole_rcont', 'contextid', 'ruleid = :ruleid', [
            'ruleid' => $this->id
        ]);

        $add = array_diff($this->contexts, $contexts);
        $remove = array_diff($contexts, $this->contexts);

        if (!empty($remove)) {
            list($sql, $params) = $DB->get_in_or_equal($remove, SQL_PARAMS_NAMED);
            $DB->delete_records_select('local_edaktik_condrole_rcont', 'ruleid = :ruleid AND contextid ' . $sql, $params);
        }

        if (!empty($add)) {
            foreach ($add as $cur) {
                $DB->insert_record('local_edaktik_condrole_rcont', (object)[
                    'ruleid' => $this->id,
                    'contextid' => $cur
                ]);
            }
        }
    }

    /**
     * Process rule either, for a single user, or for all applicable users!
     *
     * @param int|null $userid
     * @throws dml_exception
     * @throws invalid_parameter_exception
     */
    public function process_rule(int $userid = null) {
        global $DB;

        if ($userid !== null) {
            if ($this->condition()->result($userid)) {
                $this->process_met_condition($userid);
            } else {
                $this->process_unmet_condition($userid);
            }
            return;
        }

        $rs = $DB->get_recordset('user', [], '', 'id');
        foreach ($rs as $rec) {
            if ($this->condition()->result($rec->id)) {
                // Condition is met!
                $this->process_met_condition($rec->id);
            } else {
                // Condition is not met!
                $this->process_unmet_condition($rec->id);
            }
        }
    }

    /**
     * Process a met condition (usually by applying the defined role in all defined contexts)!
     *
     * @param $userid
     * @throws coding_exception
     */
    public function process_met_condition(int $userid) {
        // Assign role in contexts, if not already assigned!
        notification::add('try to add role automatically...'.print_r($this->contexts, true), 'info');
        foreach ($this->contexts as $ctx) {
            notification::add('Add role '.$this->role.' to context '.$ctx.' for user '.$userid.'!', 'success');
            role_assign($this->role, $userid, $ctx, 'local_edaktik_condrole');
        }
    }

    /**
     * Process an unmet condition, usually by removing the role (if there are no other rules allowing it)
     *
     * TODO: check for other rules with met conditions!
     *
     * @param $userid
     * @throws coding_exception
     */
    public function process_unmet_condition(int $userid) {
        // Remove role assignment for now.
        foreach ($this->contexts as $ctx) {
            if (!$this->other_condition_met($ctx, $userid)) {
                notification::add('Remove role '.$this->role.' from context '.$ctx.' for user '.$userid.'!', 'info');
                role_unassign($this->role, $userid, $ctx, 'local_edaktik_condrole');
            }
        }
    }

    /**
     * Return if any other condition targeting this context and this role is met for this user.
     * If so we still will leave the role there!
     *
     * @param int $userid
     * @return bool
     */
    public function other_condition_met(int $ctx, int $userid) {
        // TODO: check if other rules allow this role assignment!
        return false;
    }

    /**
     * We've been informed a condition was updated, let's see if it concerns us!
     *
     * @param condition_updated $event
     */
    public static function condition_updated(condition_updated $event) {
        global $DB;

        $conditions = $event->get_data()['other']['conditions'];

        list($sql, $params) = $DB->get_in_or_equal($conditions, SQL_PARAMS_NAMED);
        $params = ['type' => $event->get_data()['other']['conditiontype'], 'active' => self::ACTIVE] + $params;
        $rules = $DB->get_fieldset_select('local_edaktik_condrole_rules', "id",
                "active = :active AND conditiontype LIKE :type AND conditionid ".$sql, $params);

        if (empty($rules)) {
            return;
        }
notification::add(print_r($event->relateduserid, true), 'warning');
        foreach ($rules as $cur) {
            rule::get_from_db($cur)->process_rule($event->relateduserid);
        }
    }
}