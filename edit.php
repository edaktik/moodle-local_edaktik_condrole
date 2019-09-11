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
 * Conditional Role plugin frontend.
 *
 * @package     local_edaktik_condrole
 * @copyright   (c) 2019 eDaktik GmbH
 * @author      Andreas Hruska <andreas.hruska@edaktik.at>
 * @author      Philipp Hager <philipp.hager@edaktik.at>
 * @author      Thomas Schallert <thomas.schallert@fhnw.ch>
 * @author      Ivan Gula <ivan.gula.wien@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("../../config.php");
require_once("./classes/ruleform.php");

$PAGE->set_heading(get_string('rule_add', 'local_edaktik_condrole'));

echo $OUTPUT->header();




//global $OUTPUTHEADING;
$form = new ruleform();
$form->display();


//$templatable = new \tool_analytics\output\models_list($models);
//echo $PAGE->get_renderer('tool_analytics')->render($templatable);

echo $OUTPUT->footer();