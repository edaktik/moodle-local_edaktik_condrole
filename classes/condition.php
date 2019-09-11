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
 * Basic interface for condition types
 *
 * @package local_edaktik_condrole
 * @copyright (c) 2019 eDaktik GmbH
 * @author    Philipp Hager <philipp.hager@edaktik.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_edaktik_condrole;

defined('MOODLE_INTERNAL') || die();

/**
 * Interface condition
 *
 * @package local_edaktik_condrole
 * @copyright (c) 2019 eDaktik GmbH
 * @author    Philipp Hager <philipp.hager@edaktik.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface condition
{
    /**
     * Used to return the settingsform for this condition, used for getting fragment in local_edaktik_condrole.
     *  TODO!
     * @return mixed
     */
    public function get_settings_form();

    /**
     * Return if a user meets this certain condition.
     *
     * @param int $userid The user's database id
     * @return bool the calculated condition value for this user
     */
    public function result(int $userid): bool;

    /**
     * Return if multiple users meet this certain condition.
     *
     * @param int[] $userids The user's database id
     * @return bool[] the calculated condition value for this user
     */
    public function results(array $userids): array;

    /**
     * Sends an {[[TODO define event]]} event to inform all interested plugins about the result?
     */
    public function inform_subscribers();
}