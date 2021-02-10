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

class createlabels_form extends \moodleform
{

    public function definition()
    {
        $mform =& $this->_form;
        $courseid =& $this->_customdata['courseid'];
        $users =& $this->_customdata['users'];

        $mform->addElement('header', '', get_string('confirm_header', 'block_eledia_adminexam'));

        $groups = groups_get_all_groups($courseid);
        $options = array('0' => get_string('all'));
        $groupswithusers = unserialize($users);
        foreach ($groups as $group) {
            if(!empty($groupswithusers[$group->id])){
                $options[$group->id] = $group->name;
            }
        }

        $mform->addElement('select', 'group',
            get_string('select_groups', 'block_eledia_adminexam'), $options);
        $mform->addRule('group', null, 'required');

        $mform->addElement('text', 'emptylabels', get_string('emptylabels', 'block_eledia_adminexam'), array('size' => 10));
        $mform->setType('emptylabels', PARAM_INT);
        $mform->setDefault('emptylabels', '10');

        $mform->addElement('hidden', 'users',$users);
        $mform->setType('users', PARAM_RAW);

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

