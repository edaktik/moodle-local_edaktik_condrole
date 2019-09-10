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
 * Plugin capabilities are defined here.
 *
 * @package     local_edaktik_condrole
 * @category    access
 * @copyright   2019 [TMPTOREPLACE]
 * @author      Ivan Gula <ivan.gula.wien@gmail.com>
 * @author      Philipp Hager <philipp.hager@edaktik.at>
 * @author      Andreas Hruska <andreas.hruska@edaktik.at>
 * @author      Thomas Schallert <thomas.schallert@fhnw.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = [

    'local/edaktik_condrole:add_condition' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
        ],
    ],

    'local/edaktik_condrole:edit_condition' => [
        'riskbitmask' => RISK_DATALOSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
        ],
    ],

    'local/edaktik_condrole:delete_condition' => [
        'riskbitmask' => RISK_DATALOSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
        ],
    ],

    'local/edaktik_condrole:set_status' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
        ],
    ],

    'local/edaktik_condrole:view' => [
        'captype' => 'view',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
        ],
    ],

    'local/edaktik_condrole:view_reports' => [
        'captype' => 'view',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
        ],
    ],
];
