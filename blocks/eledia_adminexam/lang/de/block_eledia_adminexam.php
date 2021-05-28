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
$string['deactivateusers'] = 'Nutzer*in stilllegen';
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
$string['createlabels'] = 'Einzelne Etiketten erzeugen';
$string['uploadcsvcreatelabels'] = 'Etiketten erzeugen';
$string['confirmcreatelabels'] = 'Die druckbaren Etiketten als PDF-Datei werden heruntergeladen für \'{$a->course}\'';
$string['noticecreatelabels'] = 'Die druckbaren Etiketten als PDF-Datei wurden heruntergeladen für \'{$a->course}\'';
$string['pdfsubject'] = 'Etiketten';
$string['assessment_participationlist'] = 'Teilnahmeliste erzeugen';
$string['confirmparticipationlist'] = 'Erstellung und Archivierung der Teilnahmeliste als PDF-Datei für \'{$a->course}\'';
$string['noticeparticipationlist'] = 'Der Erstellungsprozess der Teilnahmeliste für \'{$a->course}\' war erfolgreich.';
$string['coursebackup'] = 'Kurssicherung';
$string['confirmcoursebackup'] = 'Erstellung und Archivierung einer Kurs-Sicherung für \'{$a->course}\'';
$string['noticecoursebackup'] = 'Der Erstellungsprozess einer Kurs-Sicherung für \'{$a->course}\' war erfolgreich.';
$string['noticeprocesscoursebackup'] = 'Der Erstellungsprozess einer Kurs-Sicherung für \'{$a->course}\' wurde gestartet.';
$string['createuser'] = 'Nutzer*in anlegen';
$string['download_labels'] = 'Etiketten herunterladen (PDF)';
$string['confirm_header'] = 'Etiketten erzeugen';
$string['select_groups'] = 'Kurs-Gruppe: ';
$string['checkuser'] = 'Nutzer*in prüfen';
$string['emptylabels'] = 'Anzahl leerer Etiketten';
$string['uploadusers'] = 'Nutzerliste hochladen';
$string['uploadcsv_header'] = 'Nutzerliste als CSV-Datei hochladen';
$string['uploadcsv_help'] = 'Nutzerliste als CSV-Datei hochladen';
$string['error_pattern_match'] = "Zeile %u: Zeileninhalt '%s' kann nicht analysiert werden\n";
$string['import_successful'] = 'Benutzerimport erfolgreich';
$string['no_file'] = 'Es wurde keine Datei für den Import ausgewählt';
$string['error_create_group'] = "Zeile %u: Gruppe '%s' kann nicht erstellt werden \ n";
$string['instancename_enrolelediamultikeys'] = 'Vouchercode Einschreibung';
$string['csvdelimiter'] = 'CSV-Trennzeichen';
$string['csvdelimiter_help'] = 'CSV-Trennzeichen der CSV-Datei.';
$string['examplecsv'] = 'CSV-Beispieldatei';
$string['examplecsv_help'] = 'Um die Beispieldatei zu verwenden, laden Sie sie herunter und öffnen Sie sie mit einem Text- oder Tabellenkalkulationseditor. Lassen Sie die erste Zeile unverändert, bearbeiten Sie die folgenden Zeilen (Datensätze), fügen Sie Ihre Benutzerdaten hinzu und fügen Sie bei Bedarf weitere Zeilen hinzu. Speichern Sie die Datei als CSV und laden Sie sie hoch.
Die Felder \'username\', \'firstname\' und \'lastname\' sind erforderlich.
Die Felder \'email\' und \'gruppe\' sind optional. Das Feld \'username\' enthält die Matrikelnummer, die E-Mail-Adresse kann automatisch generiert werden. \'gruppe\' kann den Namen einer Kursgruppe enthalten. Wenn diese oder der Benutzer nicht vorhanden ist, wird sie erstellt, und ebenfalls eine Instanz der Kurscode-Einschreibung.';
$string['executecoursebackup'] ='wird ausgeführt...';












