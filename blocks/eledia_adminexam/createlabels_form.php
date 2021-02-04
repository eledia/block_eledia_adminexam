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
 * The createlabels Form to confirm the process.
 *
 * @package     block_eledia_adminexam
 * @copyright   2021 René Hansen <support@eledia.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    //  It must be included from a Moodle page.
}

require_once($CFG->libdir . '/formslib.php');

class createlabels_form extends moodleform
{

    public function definition()
    {
        $mform =& $this->_form;
        $courseid =& $this->_customdata['courseid'];
        $mform->addElement('header', '', get_string('confirm_header', 'block_eledia_adminexam'));

        $groups = groups_get_all_groups($courseid);
        $options = array();
        foreach ($groups as $group) {
            $options[$group->id] = $group->name;
        }

        $mform->addElement('select', 'group',
            get_string('select_groups', 'block_eledia_adminexam'), $options);
        $mform->addRule('group', null, 'required');

        $mform->addElement('text', 'emptylabels', get_string('emptylabels', 'block_eledia_adminexam'),array('size' => 10));
        $mform->setType('emptylabels', PARAM_INT);
        $mform->setDefault('emptylabels', '10');

        //////
        $options = [
            'ajax' => 'core_search/form-search-user-selector',
            'multiple' => true,
            'noselectionstring' => get_string('allusers', 'search'),
            'valuehtmlcallback' => function($value) {
                global $DB, $OUTPUT;
                $user = $DB->get_record('user', ['id' => (int)$value], '*', IGNORE_MISSING);
                if (!$user || !user_can_view_profile($user)) {
                    return false;
                }
                $details = user_get_user_details($user);
                return $OUTPUT->render_from_template(
                    'core_search/form-user-selector-suggestion', $details);
            }
        ];
        if (!empty($this->_customdata['withincourseid'])) {
            $options['withincourseid'] = $this->_customdata['withincourseid'];
        }

        $mform->addElement('autocomplete', 'userids', get_string('users'), [], $options);

        //$select->setMultiple(true);
//        print_r($groups);
//        $options = [
//            'multiple' => true
//        ];
//        if (!empty($groups)) {
//            $mform->addElement('autocomplete', 'groups', get_string('select_groups', 'block_eledia_adminexam'),
//                $groups, $options);
//            $mform->addRule('groups', null, 'required');
//        }

///
//        $config = get_config('block_eledia_adminexam');
//        $resetting = new block_eledia_adminexam\createlabels_helper();
//        $dates = $resetting->get_dates($config);
//        $studies = $resetting->get_course_of_studies($config);
//        $a = new stdClass();
//        $a->reset = '';
//
//        $mform->addElement('header', '', get_string('confirm_header', 'block_eledia_adminexam'));
//
//        $group[] = &$mform->createElement('advcheckbox', 'createlabels',
//            get_string('createlabels', 'block_eledia_adminexam'));
//        $group[] = &$mform->createElement('advcheckbox', 'enrolkey',
//            get_string('enrolkey', 'block_eledia_adminexam'));
//        $mform->addGroup($group, 'chooseresetgroup', get_string('choosereset', 'block_eledia_adminexam'), ' &nbsp; ', false);
//        $mform->addHelpButton('chooseresetgroup', 'choosereset', 'block_eledia_adminexam');
//        $mform->setDefault('createlabels', true);
//        $mform->setDefault('enrolkey', true);
//
//        $courseidsuffixlines = preg_split('/\r\n|\r|\n/', $config->courseid_suffix);
//        $options = [
//            'multiple' => true
//        ];
//        $mform->addElement('autocomplete', 'suffixes', get_string('select_suffixes', 'block_eledia_adminexam'),
//            $courseidsuffixlines, $options);
//
////        if (!empty($studies)) {
////            $mform->addElement('autocomplete', 'limitationofstudies', get_string('limitationofstudies', 'block_eledia_adminexam'),
////                $studies, $options);
////        }
//
//        $mform->addElement('header', 'choosedate', get_string('choosedate', 'block_eledia_adminexam'));
//        $semester = $config->reset_semester ? 'SoSe' : 'WiSe';
//        $semdates = date("d.m.Y H:i", $dates['startdate']) . ' - ' . date("d.m.Y H:i", $dates['enddate']);
//        $mform->addElement('static', '', '',
//            get_string('reset_semester_title', 'block_eledia_adminexam') . ': ' . $semester . ' (' . $semdates . ')');
//        $mform->addElement('date_time_selector', 'startdate', get_string('createlabels_startdate', 'block_eledia_adminexam'),
//            ['defaulttime' => $dates['startdate']]);
//        $mform->addElement('date_time_selector', 'enddate', get_string('createlabels_enddate', 'block_eledia_adminexam'),
//            ['defaulttime' => $dates['enddate']]);
//        $mform->disabledIf('startdate', 'createlabels', 'notchecked');
//        $mform->disabledIf('enddate', 'createlabels', 'notchecked');
//
//        if (!empty($studies)) {
//            $mform->addElement('header', 'enrolkeys', get_string('enrolkeys', 'block_eledia_adminexam'));
//            foreach ($studies as $study) {
//                $mform->addElement('text', 'enrol_key_study_' . $study->id, $study->name, 'maxlength="40"');
//                $mform->setType('enrol_key_study_' . $study->id, PARAM_RAW);
//                $mform->setDefault('enrol_key_study_' . $study->id,
//                    $resetting->generate_password($config->self_enrol_key_length, 9));
//                $mform->disabledIf('enrol_key_study_' . $study->id, 'enrolkey', 'notchecked');
//            }
//        }

        $mform->addElement('hidden', 'courseid', $courseid);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'noticecreatelabels', 1);
        $mform->setType('noticecreatelabels', PARAM_INT);
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('continue'));
        $buttonarray[] = &$mform->createElement('cancel');

        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }
}

