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
$labelsform = optional_param('labelsform', 0, PARAM_INT);
$users = optional_param('users', '', PARAM_RAW);


$myurl = new \moodle_url($FULLME);

$PAGE->set_url($myurl);
$PAGE->set_context($context);
$PAGE->set_title(get_string('createlabels', 'block_eledia_adminexam'));
$PAGE->set_pagelayout('course');

// Set some options for the filepicker
$file_picker_options = array(
    'accepted_types' => array('.csv'),
    'maxbytes' => 51200);

$user_context = context_user::instance($USER->id);

if ($noticecreatelabels || $createlabels) {
    $mform = new \block_eledia_adminexam\forms\createlabels_form(null, array('courseid' => $courseid, 'users' => $users));
} else {
    $mform = new \block_eledia_adminexam\forms\createlabelscsv_form(null, array('courseid' => $courseid, 'options' => $file_picker_options));
}


if ($mform->is_cancelled()) {

    // POST request, but cancel button clicked, or formdata not
    // valid. Either event, clear out draft file area to remove
    // unused uploads, then send back to course view
    get_file_storage()->delete_area_files($user_context->id, 'user', 'draft', file_get_submitted_draft_itemid('uploadcsv'));
    redirect(new moodle_url('/course/view.php', array('id' => $courseid)));

} else if ($createlabels) {

    block_eledia_adminexam\util::download_labels_pdf($courseid, $group, $emptylabels, unserialize($userids));
} else if (!$mform->is_submitted() || null == ($formdata = $mform->get_data())) {

    // GET request, or POST request where data did not
    // pass validation, either case display the form
    echo $OUTPUT->header();

    // Display the form with a filepicker
    echo $OUTPUT->container_start();
    $mform->display();
    echo $OUTPUT->container_end();

    echo $OUTPUT->footer();

} else if ($noticecreatelabels) {
    $shortname = $DB->get_record('course', array('id' => $courseid), 'shortname', MUST_EXIST)->shortname;

    $message = get_string('confirmcreatelabels', 'block_eledia_adminexam'
        , ['course' => $shortname]);

    echo $OUTPUT->header();

    echo $OUTPUT->box_start('generalbox');

    $params = ['createlabels' => 1, 'courseid' => $courseid, 'group' => $group, 'emptylabels' => $emptylabels, 'userids' => $users];
    $url = new moodle_url($PAGE->url, $params);
    $downloadbutton = new single_button($url, get_string('download_labels', 'block_eledia_adminexam'), 'post');
    $cancelbutton = new single_button(new moodle_url('/course/view.php', array('id' => $courseid)), get_string('cancel'), 'get');
    echo $OUTPUT->confirm($message, $downloadbutton, $cancelbutton);

    echo $OUTPUT->box_end();

    echo $OUTPUT->footer();
} else {

    // POST request, submit button clicked and formdata
    // passed validation, first check session spoofing
    require_sesskey();

    // Leave the file in the user's draft area since we
    // will not plan to keep it after processing
    $area_files = get_file_storage()->get_area_files($user_context->id, 'user', 'draft', $formdata->{'uploadcsv'}, null, false);

    $users = serialize(block_eledia_adminexam\util::import_file($courseid, $formdata->{'delimiter_name'}, array_shift($area_files)));

    // Clean up the file area
    get_file_storage()->delete_area_files($user_context->id, 'user', 'draft', $formdata->{'uploadcsv'});
    $mform = new \block_eledia_adminexam\forms\createlabels_form(null, array('courseid' => $courseid, 'users' => $users));
    echo $OUTPUT->header();

    // Display the form with a filepicker
    echo $OUTPUT->container_start();
    $mform->display();

    echo $OUTPUT->container_end();

    echo $OUTPUT->footer();

}

