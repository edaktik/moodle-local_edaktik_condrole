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
 * Plugin strings are defined here.
 *
 * @package     local_edaktik_condrole
 * @category    string
 * @copyright   2019 eDaktik.at
 * @author      Andreas Hruska <andreas.hruska@edaktik.at>
 * @author      Philipp Hager <philipp.hager@edaktik.at>
 * @author      Thomas Schallert <thomas.schallert@fhnw.ch>
 * @author      Ivan Gula <ivan.gula.wien@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['edaktik_condrole:add_condition'] = 'Add condition';
$string['edaktik_condrole:delete_condition'] = 'Delete condition';
$string['edaktik_condrole:edit_condition'] = 'Edit condition';
$string['edaktik_condrole:set_status'] = 'Set condition status (active/inactive)';
$string['edaktik_condrole:view'] = 'View conditions';
$string['edaktik_condrole:view_reports'] = 'View Conditional Role Assignment Reports';
$string['pluginname'] = 'Conditional Role Assignment';
$string['pluginname_desc'] = 'Conditional Role Assignment is a Moodle local plugin to provide automatic rule based assignment of roles to context (e.g. coursecategory) by flexible criteria e.g. userprofile field patterns.';
$string['title'] = 'Rule title';
$string['title_help'] = 'Set a title for this rule giving a hint on the general purpose of this rule.';
$string['description'] = 'Description';
$string['description_help'] = 'Add a descriptin describing the purpose of the rule e.g. Add role Manager to course category A for all users matching pattern B.';
$string['active']='Active';
$string['active_help']='Only rules set to "active" will perform role assignments';
$string['contexttype']='Context type';
$string['contexttype_help']='Select the Moodle context type';
$string['context']='Context';
$string['context_help']='Select the desired context';
$string['role']='Role';
$string['role_help']='Select the Moodle role to be assigned';
$string['subplugintype_condrole_conditiontype'] = 'Condition type';
$string['subplugintype_condrole_conditiontype_plural'] = 'Condition types';
