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
 *
 * @package    block
 * @subpackage eledia_adminexam
 * @author     <support@eledia.de>
 * @copyright  2021 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_eledia_adminexam;

defined('MOODLE_INTERNAL') || die();

class util
{

    /**
     * Get the label data as pdf content.
     *
     * @param stdClass $userid of the user.
     * @param array $data labels data.
     * @return string Labels as pdf string.
     */
    public static function download_labels_pdf($courseid, $groupid, $emptylabels, $userids)
    {
        global $DB, $CFG;
        require_once("$CFG->libdir/pdflib.php");
        require_once($CFG->dirroot . '/enrol/externallib.php');
        require_once($CFG->dirroot . '/enrol/elediamultikeys/lib.php');

        $course = $DB->get_record("course", array("id" => $courseid), '*', MUST_EXIST);
        $coursecontext = \context_course::instance($courseid);

        $instances = enrol_get_instances($courseid, true);
        $enrolid = '';
        foreach ($instances as $instance) {
            if ($instance->enrol === 'elediamultikeys') {
                $enrolid = $instance->id;
                break;
            }
        }

        if (empty($enrolid)) {
            $studentrole = $DB->get_record('role', array('shortname' => 'student'));
            $enrolmultikeys = new \enrol_elediamultikeys_plugin();
            $enrolid = $enrolmultikeys->add_instance($course, array('status' => ENROL_INSTANCE_ENABLED,
                'name' => get_string('instancename_enrolelediamultikeys','block_eledia_adminexam'),
                'roleid' => $studentrole->id , 'customint4'=> 0));
        }

        $groups = groups_get_all_groups($course->id);

        $content_html =
            '<h1>' . get_string('pdfsubject', 'block_eledia_adminexam') . '</h1>
            <br><h2>Klausur: ' . $course->shortname . ' ' . date('d.m.Y H:i', time())
            . '</h2>';


        foreach ($userids as $group => $useridsitem) {
            if (empty($groupid) || $groupid == $group) {
                $groupname = $groups[$group]->name;
                $users = $DB->get_records_list('user', 'id', $useridsitem, 'lastname,firstname');
                $content_html .= !empty($group) ? '<p>&nbsp;</p><hr><p>&nbsp;</p><h3>Gruppe: ' . $groupname . '</h3>' : '<p>&nbsp;</p>';
                $content_html .= '<div><table cellspacing="0" cellpadding="10" border="1" style="font-weight: bold;">';
                foreach ($users as $user) {
                    $roles = array_column(get_user_roles($coursecontext, $user->id, true), 'shortname');
                    if (!is_siteadmin($user) && (count(array_diff($roles, ['student']))) === 0) {
                        // $groups = groups_get_all_groups($course->id, $user);
                        // if (array_key_exists($groupid, $groups)) {
                        $keylist = self::create_keylist($enrolid, 1, $group);
                        $content_html .= self::get_labels_content_table($user, $course, $groupname, $keylist[0]);
                        // }
                    }
                }
                $content_html .= '</table></div>';

                if (!empty($emptylabels)) {
                    $content_html .= '<h3>Leere ' . get_string('pdfsubject', 'block_eledia_adminexam');
                    $content_html .= !empty($group) ? ' Gruppe: ' . $groupname : '';
                    $content_html .= '</h3><div><table cellspacing="0" cellpadding="10" border="0" style="font-weight: bold;">';
                    $keylist = self::create_keylist($enrolid, $emptylabels, $group);
                    for ($i = 0; $i < $emptylabels; $i++) {
                        $content_html .= self::get_labels_content_table(null, $course, $groupname, $keylist[$i]);
                    }
                    $content_html .= '</table></div>';
                }
            }
        }
        // Create new PDF document.
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information.
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle(get_string('pdfsubject', 'block_eledia_adminexam') . ' ' . $course->shortname . ' Gruppe: ' . $groupname . ' ' . date('d.m.Y H:i', time()));
        $pdf->SetSubject(get_string('pdfsubject', 'block_eledia_adminexam') . ' ' . $course->shortname . ' Gruppe: ' . $groupname . ' ' . date('d.m.Y H:i', time()));
        $pdf->setPrintHeader(false);
        // set header and footer fonts

        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();
        //$pdf->Image('@' . $img);

        // $pdf->Write(0, $text, '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 10);

        $pdf->writeHTML($content_html, true, false, true, false, '');
        $groupname = !empty($groupid) ? '_' . $groups[$groupid]->name : '';
        $filename = get_string('pdfsubject', 'block_eledia_adminexam') . '_' . $course->shortname . $groupname . '_' . date('YmdHis', time()) . '.pdf<';

        ob_end_clean();
        $filecontents = $pdf->Output($filename, 'D');
        return $filecontents;
    }

    /**
     * Get the data as pdf content.
     *
     * @param stdClass $user the user.
     * @return string Labels as html string.
     */
    public
    static function get_labels_content_table($user, $course, $group, $key)
    {
        $html = '
        <tr nobr="true"><td style="width: 33%;border:1px solid #333;">';
        if (!empty($user)) {
            for ($password = mt_rand(1, 9), $i = 1; $i < 8; $i++) {
                $password .= mt_rand(0, 9);
            }
            update_internal_user_password($user, $password);
            $html .= '<div>' . $user->lastname . ', ' . $user->firstname . '</div>
          <div>Passwort: ' . $password . '</div>';
        } else {
            $html .= '<div>&nbsp;</div>
            <div>&nbsp;</div>';
        }

        $html .= '</td><td style="width: 33%;border:1px solid #333;"><div style="height: 3em;">Klausur: ' . $course->shortname . '</div>
          <div>Vouchercode: ' . $key . '</div>
        </td>
         <td  style="width: 33%;border:1px solid #333;">';
        $html .= !empty($group) ? 'Gruppe: ' . $group : '';
        $html .= '</td></tr>';

        return $html;
    }


    public
    static function create_keylist($enrolid, $count, $group)
    {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/blocks/eledia_multikeys/locallib.php');
        $mk = new \eledia_multikeys_service();
        $length = 6;

        $keystable = $DB->get_records('block_eledia_multikeys', array('userid' => null));
        $oldkeys = array();
        if ($keystable) {
            foreach ($keystable as $keyraw) {
                $oldkeys[] = $keyraw->code;
            }
        }

        $newkeys = array();
        for ($i = 0; $i < $count; $i++) {
            $newkey = $mk->generate_key($newkeys, $oldkeys, null, $count, $length, 0);

            if (!empty($prefix)) {
                $newkey = $prefix . '_' . $newkey;
            }

            $newkeys[] = $newkey;
            $newkeyobj = new \stdClass();
            $newkeyobj->enrolid = $enrolid;
            $newkeyobj->code = $newkey;
            $newkeyobj->userid = null;
            $newkeyobj->timecreated = time();
            $newkeyobj->mailedto = ' ';
            $newkeyobj->groupid = $group;

            $DB->insert_record('block_eledia_multikeys', $newkeyobj);
        }

        if (count($newkeys) > 0) {
            return $newkeys;
        }
        return false;
    }

    /**
     * Get the reminder data as pdf content.
     *
     * @param stdClass $user the user.
     * @param array $data reminder data.
     * @return string Reminder as pdf string.
     */
    public
    static function get_completion_content_pdf($user, $data)
    {
        global $DB, $CFG;
        require_once("$CFG->libdir/pdflib.php");

        $content_html = self::get_completion_content_html($user, $data);
        $filename = get_string('pluginname', 'block_eledia_adminexam') .
            ' Nutzer ' . $user->firstname . ' ' .
            $user->lastname;
        // Create new PDF document.
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information.
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle($filename);
        $pdf->SetSubject('$filename');
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 048', PDF_HEADER_STRING);

        // set header and footer fonts
        // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set font
        $pdf->SetFont('helvetica', 'B', 20);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 8);

        $pdf->writeHTML($content_html, true, false, true, false, '');

        $filecontents = $pdf->Output('', 'I');

        return $filecontents;

    }

    public
    static function coursebackup($course)
    {
        global $CFG;
        require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');

        $exportdir = get_config('local_quizattemptexport_kassel', 'pdfexportdir');

        if (!is_dir($exportdir)) {
            throw new \moodle_exception('except_dirmissing', 'local_quizattemptexport_kassel', '', $exportdir);
        }

        $dirname = $course->id;
        $path = $exportdir . '/' . $dirname . '/Kurssicherung/';

        if (!is_dir($path) || !is_writable($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new \moodle_exception('except_dirnotwritable', 'local_quizattemptexport_kassel', '', $exportdir);
            }
        }

        $admin = get_admin();
        if (!$admin) {
            throw new \moodle_exception("Error: No admin account was found");
        }

        $bc = new \backup_controller(\backup::TYPE_1COURSE, $course->id, \backup::FORMAT_MOODLE,
            \backup::INTERACTIVE_YES, \backup::MODE_GENERAL, $admin->id);
        // Set the default filename.
        $format = $bc->get_format();
        $type = $bc->get_type();
        $id = $bc->get_id();
        $users = $bc->get_plan()->get_setting('users')->get_value();
        $anonymised = $bc->get_plan()->get_setting('anonymize')->get_value();
        $filename = \backup_plan_dbops::get_default_backup_filename($format, $type, $id, $users, $anonymised);
        $bc->get_plan()->get_setting('filename')->set_value($filename);

        // Execution.
        $bc->finish_ui();
        $bc->execute_plan();
        $results = $bc->get_results();
        $file = $results['backup_destination'];; // May be empty if file already moved to target location.

        if ($file) {
            if ($file->copy_content_to($path . '/' . $filename)) {
                $file->delete();
            } else {
                throw new \moodle_exception("Destination directory does not exist or is not writable. Leaving the backup in the course backup file area.");
            }
        }

        $bc->destroy();
    }

    public
    static function save_participationlist_pdf($course)
    {

        global $CFG;

        require_once("$CFG->libdir/pdflib.php");
        require_once($CFG->libdir . '/tcpdf/tcpdf.php');
        require_once($CFG->dirroot . '/report/eledia_assessment/lib.php');

        $exportdir = get_config('local_quizattemptexport_kassel', 'pdfexportdir');

        if (!is_dir($exportdir)) {
            throw new \moodle_exception('except_dirmissing', 'local_quizattemptexport_kassel', '', $exportdir);
        }

        $dirname = $course->id;
        $path = $exportdir . '/' . $dirname . '/';

        if (!is_dir($path)) {
            if (!mkdir($path)) {
                throw new \moodle_exception('except_dirnotwritable', 'local_quizattemptexport_kassel', '', $exportdir);
            }
        }

        $filename = $course->shortname . '_' . date('YmdHis', time()) . '_Assessment_Teilnahmeliste.pdf';

        // Start new PDF, set protection and author field.
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // Set document information.
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetTitle($filename);
        $pdf->SetSubject('$filename');
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 048', PDF_HEADER_STRING);

        // set header and footer fonts
        // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set font
        $pdf->SetFont('helvetica', 'B', 20);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 8);

        // Get report header block.
        $text = report_eledia_assessment_get_course_overview_header($course) . '<br><br>';
        // Get report table.
        $data = report_eledia_assessment_get_course_overview_data($course);
        $text .= report_eledia_assessment_get_course_overview_body($data);
        $pdf->writeHTML($text, true, false, true, false, '');


        $fileHandle = fopen($path . $filename, 'w');
        if ($fileHandle === false) {
            throw new WriterException("Could not open file $path$filename for writing.");
        }

        fwrite($fileHandle, $pdf->output($filename, 'S'));
        fclose($fileHandle);
    }

    public static function import_file($courseid, $delimiter, \stored_file $import_file)
    {
        global $DB, $CFG, $PAGE;
        require_once($CFG->dirroot . '/user/lib.php');
        require_once($CFG->dirroot . '/group/lib.php');
        require_once($CFG->libdir . '/csvlib.class.php');

        $iid = \csv_import_reader::get_new_iid('createlabels');
        $csvimport = new \csv_import_reader($iid, 'createlabels');

        $readcount = $csvimport->load_csv_content($import_file->get_content(), 'utf-8', $delimiter);

        if ($readcount === false) {
            print_error('csvfileerror', 'error', $PAGE->url, $csvimport->get_error());
        } else if ($readcount == 0) {
            print_error('csvemptyfile', 'error', $PAGE->url, $csvimport->get_error());
        } else if ($readcount == 1) {
            print_error('csvnodata', 'error', $PAGE->url);
        }

        $csvimport->init();

        // make arrays of valid fields for error checking.
        $required['username'] = 1;
        $required['firstname'] = 1;
        $required['lastname'] = 1;

        $optional['gruppe'] = 1;
        $optional['email'] = 1;

        $text = $import_file->get_content();
        $text = preg_replace('!\r\n?!', "\n", $text);

        $rawlines = explode("\n", $text);

        unset($text);

        $header = explode($csvimport::get_delimiter($delimiter), array_shift($rawlines));

        // check for valid field names
        foreach ($header as $i => $h) {
            $h = strtolower(trim($h));
            $header[$i] = $h;
            if (!(isset($required[$h]) || isset($optional[$h]))) {
                print_error('invalidfieldname', 'error', $PAGE->url, $h);
            }
            if (isset($required[$h])) {
                $required[$h] = 2;
            }
        }
        // check for required fields
        foreach ($required as $key => $value) {
            if ($value < 2) {
                print_error('fieldrequired', 'error', $PAGE->url, $key);
            }
        }

        if (!$existing_groups = groups_get_all_groups($courseid)) {
            $existing_groups = array();
        }

        $users = array();
        $new_group = null;

        $line_num = 1;
        while ($line = $csvimport->next()) {
            $line_num++;

            foreach ($line as $key => $value) {
                $record[$header[$key]] = trim($value);
            }

            // Clean these up for each iteration
            unset($new_group);

            // Set the user data.
            $user = new \stdClass();
            $user->username = $record['username'];
            $user->firstname = $record['firstname'];
            $user->lastname = $record['lastname'];
            $user->email = isset($record['email']) ? $record['email'] : $record['username'] . '@scl.uni-kassel.local';
            $user->suspended = 0;
            $user->confirmed = 1;
            $user->mnethostid = 1;
            $user->maildisplay = 0;

            $group_name = isset($record['gruppe']) ? $record['gruppe'] : '';

            if ($olduser = $DB->get_record('user', array('username' => $user->username, 'deleted' => 0))) {
                $user->id = $olduser->id;
                user_update_user($user);
                $user->auth = $olduser->auth;
            } else {
                $user->auth = 'manual';
                $user->id = user_create_user($user, false, true);
            }

            $assign_group_id = 0;
            $assign_group_name = '';

            if (!empty($group_name)) {

                // If no group pre-selected, see if group from import already
                // created for that course

                foreach ($existing_groups as $existing_group) {
                    if ($existing_group->name == $group_name) {
                        $assign_group_id = $existing_group->id;
                        $assign_group_name = $existing_group->name;
                        break;
                    }
                }

                // No group by that name
                if ($assign_group_id == 0) {

                    // Make a new group for this course
                    $new_group = new \stdClass();
                    $new_group->name = addslashes($group_name);
                    $new_group->courseid = $courseid;
                    if (!$assign_group_id = groups_create_group($new_group)) {
                        throw new \moodle_exception(sprintf(get_string('error_create_group', 'block_eledia_adminexam'), $line_num, $group_name));
                    } else {
                        $new_group->name =
                        $assign_group_name = stripslashes($new_group->name);
                        $new_group->id = $assign_group_id;
                        $existing_groups[] = $new_group;
                    }
                }
            }
            $users[$assign_group_id][] = $user->id;
        }

        return $users;
    }
}

