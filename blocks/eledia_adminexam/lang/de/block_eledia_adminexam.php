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

$string['pluginname'] = 'eLeDia E-Klausur Administration';
$string['privacy:metadata'] = 'Das eLeDia E-Klausur Administration Block Plugin speichert keine persönlichen Daten.';
$string['deactivateusers'] = 'Nutzer stilllegen';
$string['archivaldocuments'] = 'Archivdokumente';
$string['backtocourse'] = 'Zurück zum Kurs';
$string['confirmdeactivateusers'] = 'Der Status der eingeschriebener Teilnehmer/innen des Kurses \'{$a->course}\' ausschließlich mit der Rolle \'{$a->roles}\' wird auf \'Inaktiv\' gesetzt.';
$string['noticedeactivateusers'] = 'Der Status der eingeschriebener Teilnehmer/innen des Kurses \'{$a->course}\' ausschließlich mit der Rolle \'{$a->roles}\' wurde auf \'Inaktiv\' gesetzt.';
$string['configure_description'] = 'Hier können Sie die E-Klausur Administration konfigurieren.';
$string['deactivateusers_roles_title'] = 'Rolle für \'Nutzer stilllegen\'';
$string['deactivateusers_roles'] = 'Ausschließlich für eingeschriebener Teilnehmer/innen des Kurses mit diesen Rollen wird der Status auf \'Inaktiv\' gesetzt. Bei den Teilnehmer/innen, die zusätzlich eine andere Rolle auf Kurs- oder Systemebene haben, oder Administrator/innen sind, bleibt der Status unverändert';
$string['loggedquestionsteps'] = 'Protokollierte Frageschritte';
$string['configurablereportsid_questionsteps'] = 'ID für Protokollierte Frageschritte';
$string['configurablereportsid_questionsteps_desc'] = 'Bitte geben Sie die ID des konfigurierbaren Berichts ein, in dem die protokollierten Schritte der Fragen angezeigt werden.';
$string['createlabels'] = 'Etiketten erzeugen';
$string['confirmcreatelabels'] = 'Die druckbaren Etiketten als PDF-Datei werden heruntergeladen für \'{$a->course}\'';
$string['noticecreatelabels'] = 'Die druckbaren Etiketten als PDF-Datei wurden heruntergeladen für \'{$a->course}\'';
$string['pdfsubject'] = 'Etiketten';
$string['assessment_participationlist'] = 'Erstellung Teilnahmeliste';
$string['confirmparticipationlist'] = 'Erstellung und Archivierung der Teilnahmeliste als PDF-Datei für \'{$a->course}\'';
$string['noticeparticipationlist'] = 'Der Erstellungsprozess der Teilnahmeliste für \'{$a->course}\' war erfolgreich.';
$string['createuser'] = 'Nutzer/in anlegen';







