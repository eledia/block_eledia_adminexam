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
 * Block eledia_adminexam is defined here.
 *
 * @package     block_eledia_adminexam
 * @copyright   2021 René Hansen <support@eledia.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * eledia_adminexam block.
 *
 * @package    block_eledia_adminexam
 * @copyright  2021 René Hansen <support@eledia.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_eledia_adminexam extends block_base
{

    /**
     * Initializes class member variables.
     */
    public function init()
    {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_eledia_adminexam');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content()
    {
        global $OUTPUT;
        $config = get_config('block_eledia_adminexam');

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        } else {
            $text = '';
            if (has_capability('moodle/site:config', context_system::instance())) {
//                $struploadusersbutton = get_string('uploadusers', 'block_eledia_adminexam');
//                $uploadusersurl = new \moodle_url('/admin/tool/uploaduser/index.php');
//                $text = html_writer::link($uploadusersurl, $struploadusersbutton, array('target' => '_blank','class' => 'btn btn-primary w-100 mb-2'));

                $strcreatelabelsbutton = get_string('uploadcsvcreatelabels', 'block_eledia_adminexam');
                $createlabelsurl = new \moodle_url('/blocks/eledia_adminexam/createlabelscsv.php', array('courseid' => $this->page->course->id));
                $text .= html_writer::link($createlabelsurl, $strcreatelabelsbutton, array('class' => 'btn btn-primary w-100 mb-2'));

                $strcreatelabelsbutton = get_string('createlabels', 'block_eledia_adminexam');
                $createlabelsurl = new \moodle_url('/blocks/eledia_adminexam/createindividuallabels.php', array('courseid' => $this->page->course->id));
                $text .= html_writer::link($createlabelsurl, $strcreatelabelsbutton, array('class' => 'btn btn-primary w-100 mb-2'));

                $strcheckuserbutton = get_string('checkuser', 'block_eledia_adminexam');
                $checkuserurl = new \moodle_url('/admin/user.php');
                $text .= html_writer::link($checkuserurl, $strcheckuserbutton, array('target' => '_blank','class' => 'btn btn-primary w-100 mb-2'));

                $strcreateuserbutton = get_string('createuser', 'block_eledia_adminexam');
                $createuserurl = new \moodle_url('/user/editadvanced.php?id=-1');
                $text .= html_writer::link($createuserurl, $strcreateuserbutton, array('target' => '_blank','class' => 'btn btn-primary w-100 mb-2'));

                $strparticipationlistbutton = get_string('assessment_participationlist', 'block_eledia_adminexam');
                $participationlisturl = new \moodle_url('/blocks/eledia_adminexam/participationlist.php', array('courseid' => $this->page->course->id));
                $text .= html_writer::link($participationlisturl, $strparticipationlistbutton, array('class' => 'btn btn-primary w-100 mb-2'));

                $strdeactivateusersbutton = get_string('archivaldocuments', 'block_eledia_adminexam');
                $deactivateusersurl = new \moodle_url('/blocks/eledia_adminexam/fileman', array('courseid' => $this->page->course->id));
                $text .= html_writer::link($deactivateusersurl, $strdeactivateusersbutton, array('target' => '_blank','class' => 'btn btn-primary w-100 mb-2'));

                $strdeactivateusersbutton = get_string('deactivateusers', 'block_eledia_adminexam');
                $deactivateusersurl = new \moodle_url('/blocks/eledia_adminexam/deactivateusers.php', array('courseid' => $this->page->course->id));
                $text .= html_writer::link($deactivateusersurl, $strdeactivateusersbutton, array('class' => 'btn btn-primary w-100 mb-2'));

                $strcoursebackupbutton = get_string('coursebackup', 'block_eledia_adminexam');
                $coursebackupurl = new \moodle_url('/blocks/eledia_adminexam/coursebackup.php', array('courseid' => $this->page->course->id));
                $text .= html_writer::link($coursebackupurl, $strcoursebackupbutton, array('class' => 'btn btn-primary w-100 mb-2'));

                $strloggedquestionstepsbutton = get_string('loggedquestionsteps', 'block_eledia_adminexam');
                $loggedquestionstepsurl = new \moodle_url('/blocks/configurable_reports/viewreport.php',
                    array('id'=>$config->configurablereportsid_questionsteps, 'courseid' => $this->page->course->id));
                $text .= html_writer::link($loggedquestionstepsurl, $strloggedquestionstepsbutton, array('class' => 'btn btn-primary w-100 mb-2'));
            }
            $this->content->text = $text;
        }

        return $this->content;
    }
    public function has_config() {
        return true;
    }

}
