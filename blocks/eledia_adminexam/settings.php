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

defined('MOODLE_INTERNAL') || die();
if ($ADMIN->fulltree) {

    $configs = array();
    $configs[] = new admin_setting_heading('block_eledia_adminexam_header', '',
            get_string('configure_description', 'block_eledia_adminexam'));
    $roles=role_get_names();
    $roleoptions=array_combine(array_column($roles,'shortname'),array_column($roles,'localname'));
    $configs[] = new admin_setting_configmultiselect('deactivateusers_roles',
            get_string('deactivateusers_roles_title', 'block_eledia_adminexam'),
            get_string('deactivateusers_roles', 'block_eledia_adminexam'),
            ['student'],
        $roleoptions);

    foreach ($configs as $config) {
        $config->plugin = 'block_eledia_adminexam';
        $settings->add($config);
    }
}