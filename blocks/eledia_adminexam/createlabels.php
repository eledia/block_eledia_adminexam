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
require_once($CFG->dirroot . '/enrol/externallib.php');
require_once('createlabels_form.php');
$context = context_system::instance();

require_login();

if (!has_capability('moodle/site:config', $context)) {
    print_error(' only admin users allowed');
}

$courseid = required_param('courseid', PARAM_INT);
$createlabels = optional_param('createlabels', 0, PARAM_INT);
$noticecreatelabels = optional_param('noticecreatelabels', 0, PARAM_INT);
$emptylabels = optional_param('emptylabels', 0, PARAM_INT);
$group = optional_param('group', 0, PARAM_INT);
$userids = optional_param('userids', 0, PARAM_RAW);


$myurl = new \moodle_url($FULLME);

$PAGE->set_url($myurl);
$PAGE->set_context($context);
$PAGE->set_title(get_string('createlabels', 'block_eledia_adminexam'));
$PAGE->set_pagelayout('course');

$mform = new createlabels_form(null, array('courseid' => $courseid));

// Execute the form.
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/course/view.php', array('id' => $courseid)));
} else {

    $formdata = $mform->get_data();

    $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

    if ($createlabels) {

        block_eledia_adminexam\util::download_labels_pdf($courseid, $group,$emptylabels,unserialize($userids));

    } else if ($noticecreatelabels) {

        $message = get_string('confirmcreatelabels', 'block_eledia_adminexam'
            , ['course' => $course->shortname]);

        echo $OUTPUT->header();

        echo $OUTPUT->box_start('generalbox');

        $params = ['createlabels' => 1, 'courseid' => $courseid, 'group' => $group, 'emptylabels' => $emptylabels, 'userids' => serialize($formdata->userids)];
        $url = new moodle_url($PAGE->url, $params);
        $downloadbutton = new single_button($url, get_string('download_labels', 'block_eledia_adminexam'), 'post');
        $cancelbutton = new single_button(new moodle_url('/course/view.php', array('id' => $courseid)), get_string('cancel'),'get');
        echo $OUTPUT->confirm($message, $downloadbutton, $cancelbutton);

    } else {
        $groups = groups_get_all_groups($courseid);
        $options = array();
        foreach ($groups as $group) {
            $options[$group->id] = $group->name;
        }
        $message = get_string('confirmcreatelabels', 'block_eledia_adminexam'
            , ['course' => $course->shortname]);

        echo $OUTPUT->header();
        echo $OUTPUT->box_start('generalbox');

        $mform->display();
    }
}
echo $OUTPUT->box_end();

echo $OUTPUT->footer();
