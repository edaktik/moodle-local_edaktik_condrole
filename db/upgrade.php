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
 * Plugin upgrade steps are defined here.
 *
 * @package     local_edaktik_condrole
 * @category    upgrade
 * @copyright   2019 [TMPTOREPLACE]
 * @author      Ivan Gula <ivan.gula.wien@gmail.com>
 * @author      Philipp Hager <philipp.hager@edaktik.at>
 * @author      Andreas Hruska <andreas.hruska@edaktik.at>
 * @author      Thomas Schallert <thomas.schallert@fhnw.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/upgradelib.php');

/**
 * Execute local_edaktik_condrole upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_edaktik_condrole_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // For further information please read the Upgrade API documentation:
    // https://docs.moodle.org/dev/Upgrade_API
    //
    // You will also have to create the db/install.xml file by using the XMLDB Editor.
    // Documentation for the XMLDB Editor can be found at:
    // https://docs.moodle.org/dev/XMLDB_editor

    if ($oldversion < 2019091001) {

      // Define table local_edaktik_condrole_rcont to be created.
      $table = new xmldb_table('local_edaktik_condrole_rcont');

      // Adding fields to table local_edaktik_condrole_rcont.
      $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
      $table->add_field('ruleid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
      $table->add_field('contextid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

      // Adding keys to table local_edaktik_condrole_rcont.
      $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

      // Conditionally launch create table for local_edaktik_condrole_rcont.
      if (!$dbman->table_exists($table)) {
          $dbman->create_table($table);
      }

      // Define table local_edaktik_condrole_rules to be created.
      $table = new xmldb_table('local_edaktik_condrole_rules');

      // Adding fields to table local_edaktik_condrole_rules.
      $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
      $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, null, null, null);
      $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
      $table->add_field('active', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0');
      $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
      $table->add_field('roleid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
      $table->add_field('conditiontype', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
      $table->add_field('conditionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
      $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
      $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
      $table->add_field('modifierid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

      // Adding keys to table local_edaktik_condrole_rules.
      $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

      // Conditionally launch create table for local_edaktik_condrole_rules.
      if (!$dbman->table_exists($table)) {
          $dbman->create_table($table);
      }

      // Edaktik_condrole savepoint reached.
      upgrade_plugin_savepoint(true, 2019091001, 'local', 'edaktik_condrole');
    }


    return true;
}
