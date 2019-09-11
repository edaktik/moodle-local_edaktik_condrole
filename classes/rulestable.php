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
 * RULE Table . Definition  for plugin.
 *
 * @package     local_edaktik_condrole
 * @copyright   (c) 2019 eDaktik GmbH
 * @author      Andreas Hruska <andreas.hruska@edaktik.at>
 * @author      Philipp Hager <philipp.hager@edaktik.at>
 * @author      Thomas Schallert <thomas.schallert@fhnw.ch>
 * @author      Ivan Gula <ivan.gula.wien@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_edaktik_condrole;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/tablelib.php");
 
use  table_sql;
use  html_writer;

class rulestable extends table_sql {
	
	/**
     * Sets up the table.
     *
     * 
     * @throws coding_exception
     */
    public function __construct() {
        global $PAGE;

        parent::__construct('condrole_rulestable');
        $this->define_baseurl($PAGE->url->out(false));

        // Define columns in the table.
        $this->define_table_columns();

        // Define configs.
        $this->define_table_configs();

        // TODO die folgenden Variablen!
		$from = '{local_edaktik_condrole_rules}';
		$fields = ['id','sortorder','title','active','conditionid','roleid'];
        $this->set_sql(implode(', ', $fields), $from,'1=1');
    }
    
	  /**
     * Column sortorder.
     *
     * @param  object $row
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    protected function col_sortorder($row) {
        return $row->sortorder;
    }
	
	/**
     * Column title.
     *
     * @param  object $row
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    protected function col_title($row) {
        return $row->title;
    }
	
	
	
	/**
     * Column for active checkboxes.
     *
     * @param  object $row
     * @return string
     */
    protected function col_active($row) {
        $active_symbol = "empty";
		if($row->active==0){
			$active_symbol= "Not Active";
		}else{
			$active_symbol= "Active";
		}
		return $active_symbol;
    }
	
	/**
     * Column conditionid.
     *
     * @param  object $row
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    protected function col_context($row) {
		global $DB;
		$contextids = $DB->get_fieldset_select('local_edaktik_condrole_rcont', 'contextid','ruleid=?', [$row->id]);
		$contexts= $DB->get_records_list('context','id', $contextids);
		$contextnames = [];
		foreach($contexts as $id=>$context){
			switch ($context->contextlevel){
						case CONTEXT_COURSECAT: 
							$contextnames[] = $DB->get_field('course_categories', 'name',['id'=>$context->instanceid]);
							break;
			}
		}
		
        return implode(', ',$contextnames);
    }
	
		/**
     * Column roleid.
     *
     * @param  object $row
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    protected function col_role($row) {
		static $rolenames = null;
		if(empty($row->roleid)){
			return '';
		}
		if($rolenames===null){
			$rolenames = role_get_names(null,ROLENAME_ALIAS,true);
		}
			
        return $rolenames[$row->roleid];
    }
	/**
     * Column actions.
     *
     * @param  object $row
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    protected function col_actions($row) {
		//$row->id;
        return "edit | delete | up | down ";
    }

	/**
     * Returns all columns shown in this table!
     *
     * @return array
     * @throws coding_exception
     */
    protected function get_cols() {
        return [
			'title' => get_string('title', 'local_edaktik_condrole'),
            'active' => get_string('active', 'local_edaktik_condrole'),
            'context' => get_string('context', 'local_edaktik_condrole'),
            'role' => get_string('role', 'local_edaktik_condrole'),
            'actions' => get_string('actions')
        ];
    }
	
    /**
     * Setup the headers for the table.
     *
     * @throws coding_exception
     */
    protected function define_table_columns() {
        // Define headers and columns.
        $cols = $this->get_cols();

        $this->define_columns(array_keys($cols));
        $this->define_headers(array_values($cols));
    }

    /**
     * Define table configs.
     */
    protected function define_table_configs() {
        $this->collapsible(true);
        $this->sortable(false, 'sortorder', SORT_ASC);
        $this->pageable(true);
        $this->no_sorting('sel');
    }
}