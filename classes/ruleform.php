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
 * RULE Form . Definition  for plugin.
 *
 * @package     local_edaktik_condrole
 * @copyright   2019 [TMPTOREPLACE]
 * @author      Ivan Gula <ivan.gula.wien@gmail.com>
 * @author      Philipp Hager <philipp.hager@edaktik.at>
 * @author      Andreas Hruska <andreas.hruska@edaktik.at>
 * @author      Thomas Schallert <thomas.schallert@fhnw.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");
 
class ruleform extends moodleform {
 
    function definition() {
        global $CFG;
 
        $mform = $this->_form; // Don't forget the underscore! 
		
		$attributes=array('size'=>'50');
        $mform->addElement('text', 'title', get_string('title', 'local_edaktik_condrole'), $attributes);
		$mform->addHelpButton('title', 'title', 'local_edaktik_condrole');
		$mform->setType('title',PARAM_TEXT);
		$mform->addElement('textarea', 'description', get_string('description', 'local_edaktik_condrole'), 'wrap="virtual" rows="5" cols="50"');
		$mform->addHelpButton('description', 'description', 'local_edaktik_condrole');
		$mform->setType('description',PARAM_TEXT);
		$mform->addElement('advcheckbox', 'active', get_string('active', 'local_edaktik_condrole'), '', array('group' => 1), array(0, 1));
		$mform->addHelpButton('active', 'active', 'local_edaktik_condrole');
		$mform->setType('active',PARAM_INT);
		// optionsCT had to be filld with correct data!
		$optionsCT=array(CONTEXT_COURSECAT=>'CONTEXT_COURSECAT');
		$mform->addElement('select', 'contexttype', get_string('contexttype', 'local_edaktik_condrole'), $optionsCT);
		$mform->addHelpButton('contexttype', 'contexttype', 'local_edaktik_condrole');
		$mform->setType('contexttype',PARAM_INT);
		// optionsC had to be filld with correct data!
		//$optionsC=array('1'=>'test1', '2'=>'test2', '3'=>'test3');
		
		$optionsC= core_course_category::make_categories_list();
		$multiSelect=$mform->addElement('autocomplete', 'context', get_string('context', 'local_edaktik_condrole'), $optionsC);
		$multiSelect->setMultiple(true);
		$mform->addHelpButton('context', 'context', 'local_edaktik_condrole');
		$mform->setType('context',PARAM_INT);
		// optionsR had to be filld with correct data!
		$optionsR=role_get_names(null,ROLENAME_ALIAS,true);
		//$optionsR=array('1'=>'role1', '2'=>'role2','3'=>'role3');
		$mform->addElement('select', 'role', get_string('role', 'local_edaktik_condrole'), $optionsR);
		$mform->addHelpButton('role', 'role', 'local_edaktik_condrole');
		$mform->setType('role',PARAM_INT);
		$this->add_action_buttons();
		
		
    }                           // Close the function
}                               // Close the class