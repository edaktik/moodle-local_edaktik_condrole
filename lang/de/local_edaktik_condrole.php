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
 * @copyright   (c) 2019 eDaktik GmbH
 * @author      Andreas Hruska <andreas.hruska@edaktik.at>
 * @author      Philipp Hager <philipp.hager@edaktik.at>
 * @author      Thomas Schallert <thomas.schallert@fhnw.ch>
 * @author      Ivan Gula <ivan.gula.wien@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['edaktik_condrole:add_condition'] = 'Bedingung hinzufügen';
$string['edaktik_condrole:delete_condition'] = 'Bedingung löschen';
$string['edaktik_condrole:edit_condition'] = 'Bedingung editieren';
$string['edaktik_condrole:set_status'] = 'Status der Bedingung (aktiv/inaktiv)';
$string['edaktik_condrole:view'] = 'Bedingungen anzeigen';
$string['edaktik_condrole:view_reports'] = 'Reporte der bedingten Rollenzuweisung anzeigen';
$string['pluginname'] = 'Bedingte Rollenzuweisung';
$string['pluginname_desc'] = 'Bedingte Rollenzuweisung ist ein Moodle-Plugin vom Plugintyp "local", welches die automatische regelbasierte Zuweisung von Rollen zum Kontext (z.B. Kurskategorie) durch flexibel erweiterbare Kriterien unterstützt (z.b. Userprofil Feldwerte).';
$string['title'] = 'Titel der Regel';
$string['title_help'] = 'Kurzer Hilfshinweis über den Zweck der Regel.';
$string['description'] = 'Beschreibung';
$string['description_help'] = ' Text um den Zweck der Regel zu beschreiben, z.B. Alle Benutzer welche die Bedingung B erfüllen, erhalten die Rechte des Rollenmanagers der Kurskategorie A.';
$string['active']='aktiv';
$string['active_help']='Nur für aktive Regeln werden Rollenzuweisungen ausgeführt.';
$string['contexttype']='Kontext Typ';
$string['contexttype_help']='Moodle Kontext Typ auswählen.';
$string['context']='Kontext';
$string['context_help']='Gewünschten Kontext auswählen.';
$string['role']='Rolle';
$string['role_help']='Moodle Rolle, welche zugewiesen werden soll, auswählen.';
$string['subplugintype_condrole_conditiontype'] = 'Typ der Bedingung';
$string['subplugintype_condrole_conditiontype_plural'] = 'Bedingungstypen';
