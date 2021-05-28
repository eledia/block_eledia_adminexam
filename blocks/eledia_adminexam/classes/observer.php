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
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Observer for adminexam plugin.
 *
 * @package     block_eledia_adminexam
 * @copyright   2021 René Hansen <support@eledia.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Event handler for create course backup.
 *
 */
class eledia_adminexam_coursebackup_observer
{
    /**
     * Triggered when coursebackup created.
     *
     * @param \core\event\course_backup_created $event
     */
    public static function coursebackup_created(\core\event\course_backup_created $event)
    {
        $data = $event->get_data();

        $coursebackupstate = (array)unserialize(get_config('block_eledia_adminexam', 'coursebackupstate'));

        if (!empty($coursebackupstate[$data['courseid']]->backupprocessstate)) {
            $backupstate = $coursebackupstate[$data['courseid']];

            $noticesuccessbackup = (array)unserialize(get_user_preferences('noticesuccessbackup', '', $data['userid']));
            $noticesuccessbackup[$data['courseid']] = $backupstate->userid;
            set_user_preference('noticesuccessbackup', serialize($noticesuccessbackup), $backupstate->userid);

            $backupstate->backupcount = !empty($backupstate->backupcount) ? ++$backupstate->backupcount : 1;
            $backupstate->backupprocessstate = false;
            $backupstate->userid = null;
            $coursebackupstate[$data['courseid']] = $backupstate;
            set_config('coursebackupstate', serialize($coursebackupstate), 'block_eledia_adminexam');
        }
    }
}
