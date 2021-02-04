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
 * @package    block_eledia_adminexam
 * @copyright  2021 René Hansen <support@eledia.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

global $USER, $CFG, $PAGE, $OUTPUT, $DB;

$context = context_system::instance();

require_login();

if (!has_capability('moodle/site:config', $context)) {
    print_error(' only admin users allowed');
}

$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$coursebackup = optional_param('coursebackup', 0, PARAM_INT);

$coursecontext = context_course::instance($courseid);

$PAGE->set_context($context);

$myurl = new \moodle_url($FULLME);

$PAGE->set_url($myurl);
$PAGE->set_title(get_string('coursebackup', 'block_eledia_adminexam'));

$PAGE->set_pagelayout('standard');


if ($coursebackup) {
    block_eledia_adminexam\util::coursebackup($course);
    echo $OUTPUT->header();

    echo $OUTPUT->box_start('generalbox');
    notice('<div style="text-align:center">'
        . get_string('noticecoursebackup', 'block_eledia_adminexam', ['course' => $course->shortname])
        . '</div>', new moodle_url('/course/view.php', array('id' => $courseid)));

} else {

    $message = get_string('confirmcoursebackup', 'block_eledia_adminexam'
        , ['course' => $course->shortname]);
    echo $OUTPUT->header();

    echo $OUTPUT->box_start('generalbox');
    echo $OUTPUT->confirm($message, $PAGE->url . '&coursebackup=1', new moodle_url('/course/view.php', array('id' => $courseid)));

}

echo $OUTPUT->box_end();

echo $OUTPUT->footer();
