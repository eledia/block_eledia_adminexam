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
    public static function download_labels_pdf($courseid)
    {
        global $DB, $CFG;
        require_once("$CFG->libdir/pdflib.php");
        $course = $DB->get_record("course", array("id" => $courseid), '*', MUST_EXIST);
        $coursecontext = \context_course::instance($courseid);
        $users = get_enrolled_users($coursecontext);

        $content_html =
            '<h2>' . get_string('pdfsubject', 'block_eledia_adminexam') . '</h2><br><h3>' . $course->shortname . '</h3><br>';
        foreach ($users as $user) {
            $roles = array_column(get_user_roles($coursecontext, $user->id, true), 'shortname');
            if (!is_siteadmin($user->id) && (count(array_diff($roles, ['student']))) === 0) {
                $content_html .= self::get_labels_content_table($user, $course);
            }
        }

        // Create new PDF document.
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information.
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle(get_string('pdfsubject', 'block_eledia_adminexam') . ' ' . $course->shortname);
        $pdf->SetSubject(get_string('pdfsubject', 'block_eledia_adminexam') . ' ' . $course->shortname);
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
        // set font
        //$pdf->SetFont('helvetica', '', 12);

        $pdf->AddPage();
        //$pdf->Image('@' . $img);

        // $pdf->Write(0, $text, '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 10);

        $pdf->writeHTML($content_html, true, false, true, false, '');
        $filename = get_string('pdfsubject', 'block_eledia_adminexam') . ' ' . $course->shortname . '.pdf<';
        //$loc = $CFG->dataroot . '/' . $filename;

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
    public static function get_labels_content_table($user, $course)
    {
        for ($password = mt_rand(1, 9), $i = 1; $i < 8; $i++) {
            $password .= mt_rand(0, 9);
        }

        update_internal_user_password($user, $password);
        $groups = implode(', ', array_column(groups_get_all_groups($course->id, $user->id), 'name'));
        $html = '<div style="border: solid #333 1px;"><table cellspacing="0" cellpadding="5" border="1">
        <tr style="font-weight: bold;">
            <td>' . $user->lastname . ', ' . $user->firstname . '</td>
        </tr>
        <tr style="font-weight: bold;">
            <td>Klausur: ' . $course->shortname . '</td>
        </tr>
        <tr style="font-weight: bold;">
            <td>Moodlezugang: ' . $password . '</td>
        </tr>
        <tr style="font-weight: bold;">
            <td>Vouchercode:</td>
        </tr>
        <tr style="font-weight: bold;">
            <td>Gruppe: ' . $groups . '</td>
        </tr>
        </div>
        ';

        return $html;
    }

    /**
     * Get the reminder data as pdf content.
     *
     * @param stdClass $user the user.
     * @param array $data reminder data.
     * @return string Reminder as pdf string.
     */
    public static function get_completion_content_pdf($user, $data)
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

    public static function save_participationlist_pdf($course)
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

        $filename = $course->shortname . '_' . date('YmdHis', time()) . '.pdf';

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
}

