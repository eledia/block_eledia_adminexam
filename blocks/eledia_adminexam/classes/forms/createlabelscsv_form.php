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

namespace block_eledia_adminexam\forms;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    //  It must be included from a Moodle page.
}

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/csvlib.class.php');

class createlabelscsv_form extends \moodleform
{

    public function definition()
    {
        $mform =& $this->_form;
        $courseid =& $this->_customdata['courseid'];

        // File picker
        $this->_form->addElement('header', '', get_string('uploadcsv_header', 'block_eledia_adminexam'));
        $mform->addElement('hidden', 'labelsform', 1);
        $mform->setType('labelsform', PARAM_INT);
        $mform->addElement('hidden', 'courseid', $courseid);
        $mform->setType('courseid', PARAM_INT);

        $url = new \moodle_url('Beispieldatei.csv');
        $link = \html_writer::link($url, 'Beispieldatei.csv');
        $mform->addElement('static', 'examplecsv', get_string('examplecsv', 'block_eledia_adminexam'), $link);
        $mform->addHelpButton('examplecsv', 'examplecsv', 'block_eledia_adminexam');


        $mform->addElement('filepicker', 'uploadcsv', null, null, $this->_customdata['options']);
        // $mform->addHelpButton('uploadcsv', 'uploadcsv_help', 'block_eledia_adminexam',get_string('uploadcsv_help', 'block_eledia_adminexam'));
        $mform->addRule('uploadcsv', null, 'required', null, 'client');

        $choices = \csv_import_reader::get_delimiter_list();
        $mform->addElement('select', 'delimiter_name', get_string('csvdelimiter', 'block_eledia_adminexam'), $choices);
        $mform->setDefault('delimiter_name', 'semicolon');
        $mform->addHelpButton('delimiter_name', 'csvdelimiter', 'block_eledia_adminexam');

        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('continue'));
        $buttonarray[] = &$mform->createElement('cancel');

        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }

    /**
     * Validate the submitted form data
     * @see moodleform::validation()
     */
    public function validation($data, $files)
    {
        global $USER;

        $result = array();

        $area_files = get_file_storage()->get_area_files(\context_user::instance($USER->id)->id, 'user', 'draft', $data['uploadcsv'], false, false);
        $import_file = array_shift($area_files);
        if (null == $import_file) {
            $result['uploadcsv'] = get_string('no_file', 'block_eledia_adminexam');
        }

        return $result;

    }

}

