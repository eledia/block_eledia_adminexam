<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     block_eledia_adminexam
 * @copyright   2021 René Hansen <support@eledia.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
$plugin->version = 2021020900;
$plugin->requires = 2015111000;
$plugin->component = 'block_eledia_adminexam';
$plugin->dependencies = array(
    'local_quizattemptexport_kassel' => 2020120300,
    'report_eledia_assessment' => 2020110500,
    'block_eledia_multikeys' => 2020090200,
    'enrol_elediamultikeys' => 2020090200,
);
