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
 * condrole_conditiontype.php
 *
 * @package local_edaktik_condrole
 * @copyright (c) 2019 eDaktik GmbH
 * @author    Philipp Hager <philipp.hager@edaktik.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_edaktik_condrole\plugininfo;

use core\plugininfo\base;
use core_plugin_manager;
use moodle_url;
use coding_exception;
use dml_exception;
use moodle_exception;

defined('MOODLE_INTERNAL') || die();

/**
 * Class condrole_conditiontype
 *
 * @package local_edaktik_condrole
 * @copyright (c) 2019 eDaktik GmbH
 * @author    Philipp Hager <philipp.hager@edaktik.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class condrole_conditiontype extends base {
    /**
     * Finds all enabled plugins, the result may include missing plugins.
     * @return array|null of enabled plugins $pluginname=>$pluginname, null means unknown
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function get_enabled_plugins() {
        global $DB;

        $plugins = core_plugin_manager::instance()->get_installed_plugins('condrole_conditiontype');
        if (!$plugins) {
            return array();
        }
        $installed = array();
        foreach ($plugins as $plugin => $version) {
            $installed[] = 'condrole_conditiontype_'.$plugin;
        }

        list($installed, $params) = $DB->get_in_or_equal($installed, SQL_PARAMS_NAMED);
        $disabled = $DB->get_records_select('config_plugins', "plugin $installed AND name = 'disabled'", $params, 'plugin ASC');
        foreach ($disabled as $conf) {
            if (empty($conf->value)) {
                continue;
            }
            list($type, $name) = explode('_', $conf->plugin, 2);
            unset($plugins[$name]);
        }

        $enabled = array();
        foreach ($plugins as $plugin => $version) {
            $enabled[$plugin] = $plugin;
        }

        return $enabled;
    }

    public function is_uninstall_allowed() {
        return true;
    }

    /**
     * Return URL used for management of plugins of this type.
     * @return moodle_url
     * @throws moodle_exception
     */
    public static function get_manage_url() {
        return new moodle_url('/local/edaktik_condrole/adminmanageplugins.php');
    }

    public function get_settings_section_name() {
        return $this->type . '_' . $this->name;
    }
}