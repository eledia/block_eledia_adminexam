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
 * Plugin strings are defined here.
 *
 * @package     block_eledia_adminexam
 * @category    string
 * @copyright   2021 René Hansen <support@eledia.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'eLeDia e-exam administration';
$string['privacy:metadata'] = 'The eLeDia e-exam administration block plugin does not store any personal data.';
$string['deactivateusers'] = 'Deactivate users';
$string['archivaldocuments'] = 'Archival documents';
$string['backtocourse'] = 'Back to the course';
$string['confirmdeactivateusers'] = 'The status of enrolled participants in the course \'{$a->course}\' exclusively with the role \'{$a->roles}\' is set to \'Inactive\'.';
$string['noticedeactivateusers'] = 'The status of enrolled participants in the course \'{$a->course}\' exclusively with the role \'{$a->roles}\' was set to \'Inactive\'.';
$string['configure_description'] = 'Here you can configure the e-exam administration.';
$string['deactivateusers_roles_title'] = 'Role for \'Deactivate users\'';
$string['deactivateusers_roles'] ='The status is set to \'Inactive\' only for enrolled participants in the course with these roles. If the participant also has a different role at course or system level or is administrator, the status remains unchanged.';
$string['loggedquestionsteps'] = 'Logged question steps';
$string['configurablereportsid_questionsteps'] = 'ID for logged question steps';
$string['configurablereportsid_questionsteps_desc'] = 'Please enter the ID of the configurable report in which the logged steps of the questions are displayed.';
$string['createlabels'] = 'Create labels';
$string['uploadcsvcreatelabels'] = 'Create individual labels';
$string['confirmcreatelabels'] = 'The printable labels as PDF files will be downloaded for \'{$a->course}\'';
$string['noticecreatelabels'] = 'The printable labels as a PDF file have been downloaded for \'{$a->course}\'';
$string['pdfsubject'] = 'Labels';
$string['assessment_participationlist'] = 'Creation of a participation list';
$string['confirmparticipationlist'] = 'Creation and archiving of the participation list as a PDF file for \'{$a->course}\'';
$string['noticeparticipationlist'] = 'The process of creating the participation list for \'{$a->course}\' was successful.';
$string['coursebackup'] = 'Course backup';
$string['confirmcoursebackup'] = 'Creation and archiving a course backup for \'{$a->course}\'';
$string['noticecoursebackup'] = 'The process of creating a course backup for \'{$a->course}\' was successful.';
$string['createuser'] = 'Add a new user';
$string['download_labels'] = 'Download labels (PDF)';
$string['confirm_header'] = 'Labels';
$string['select_groups'] = 'Course group:';
$string['checkuser'] = 'Check user';
$string['emptylabels'] = 'Number of empty labels';
$string['uploadusers'] = 'Upload user list';
$string['uploadcsv_header'] = 'Upload user list as csv file';
$string['uploadcsv_help'] = 'Upload user list as csv file';
$string['error_pattern_match'] = "Line %u: Unable to parse the line contents '%s'\n";
$string['import_successful'] = 'User import successful';
$string['no_file'] = 'No file was selected for import';
$string['error_create_group'] = "Line %u: Unable to create group '%s'\n";
$string['instancename_enrolelediamultikeys'] = 'Voucher code enrollment';
$string['csvdelimiter'] = 'CSV delimiter';
$string['csvdelimiter_help'] = 'CSV delimiter of the CSV file.';
$string['examplecsv'] = 'Example CSV file';
$string['examplecsv_help'] = 'To use the example CSV file, download it and open it with a text or spreadsheet editor. Leave the first line unchanged, edit the following lines (records), add your user data and add more lines if necessary. Save the file as a CSV and upload it.
The fields \'username\', \'firstname\' and \'lastname\' are required.
The fields \'email\' and \'gruppe\' are optional. The field \'username\' contains the matriculation number, the e-mail address can be generated automatically. \'gruppe\' can contain the name of a course group - if this or the user does not exist, it will be created, also a voucher code course enrol instance.';

