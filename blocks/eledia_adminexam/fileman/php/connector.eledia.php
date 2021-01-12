<?php
require_once(dirname(__FILE__) . '/../../../../config.php');
require_login();
require_capability('moodle/site:config', context_system::instance());
$courseid = required_param('courseid', PARAM_INT);
global $DB;
$rootname =  $DB->get_record('course', array('id' => $courseid), 'shortname', MUST_EXIST)->shortname;
error_reporting(0); // Set E_ALL for debuging

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elFinderConnector.class.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elFinder.class.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param string $attr attribute name (read|write|locked|hidden)
 * @param string $path file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume)
{
    return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
        ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
        : null;                                    // else elFinder decide it itself
}

$exportdir = get_config('local_quizattemptexport_kassel', 'pdfexportdir');

if (!is_dir($exportdir)) {
    throw new \moodle_exception('except_dirmissing', 'local_quizattemptexport_kassel', '', $exportdir);
}

$dirname = $courseid;
$path = $exportdir . '/' . $dirname . '/';

if (!is_dir($path)) {
    if (!mkdir($path)) {
        throw new \moodle_exception('except_dirnotwritable', 'local_quizattemptexport_kassel', '', $exportdir);
    }
}


// Documentation for connector options:
// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
$opts = array(
    'roots' => array(
        array(
            'driver' => 'LocalFileSystem',
            'alias' => $rootname,
            'path' => $path,
            'tmbPath' => '.tmb',
            'utf8fix' => true,
            'tmbCrop' => false,
            'tmbBgColor' => 'transparent',
            'accessControl' => 'access',
            // 'acceptedName'    => '/^[^\.].*$/',
            'acceptedName' => '/^((?!\.ht).)/',
            // 'disabled' => array('extract', 'archive', 'mkdir'),
            'tmbSize' => 128,
            'attributes' => array(
                array(
                    'pattern' => '/^\/[^\/]*\.pdf$/',
                    'read' => true,
                    'write' => false,
                    'locked' => true
                ),
                array(
                    'pattern' => '/^\/icons$/',
                    'read' => true,
                    'write' => false
                ),
                array(
                    'pattern' => '/\.php$/',
                    'hidden' => true,
                    'read' => false
                ),
                array(
                    'pattern' => '/\.zip$/',
                    'read' => true,
                    'write' => true
                )
            ),
            'uploadDeny' => array('text/php'),
        )
    )
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

