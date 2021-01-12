<?php
require_once('../../../config.php');
require_login();
require_capability('moodle/site:config', context_system::instance());
global $CFG, $DB;
$courseid = required_param('courseid', PARAM_INT);
$coursename = $DB->get_record('course', array('id' => $courseid), 'fullname', MUST_EXIST)->fullname;
$courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
$connectorurl = 'php/connector.eledia.php?courseid='.$courseid;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.0</title>

    <!-- jQuery and jQuery UI (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="jquery/jqueryui/1.8.21/themes/smoothness/jquery-ui.css">
    <script src="jquery/jquery/1.7.2/jquery.min.js"></script>
    <script src="jquery/jqueryui/1.8.21/jquery-ui.min.js"></script>

    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="css/elfinder.min.css">
    <link rel="stylesheet" type="text/css" href="css/theme.css">

    <!-- elFinder JS (REQUIRED) -->
    <script src="js/elfinder.min.js"></script>

    <!-- elFinder translation (OPTIONAL) -->
    <script src="js/i18n/elfinder.de.js"></script>

    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
        // Documentation for client options:
        // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
        $().ready(function () {
            $('#elfinder').elfinder({
                url: '<?php echo $connectorurl; ?>',  // connector URL (REQUIRED)
                lang: 'de',                     // language (OPTIONAL)
            });
        });
    </script>
</head>
<body>
<h3 style="font-family: Arial, Helvetica, sans-serif;"><?php echo get_string('archivaldocuments','block_eledia_adminexam');?>: '<?php echo $coursename; ?>'</h3>
<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>
<p>&nbsp;</p>
<div id="logout">
    <form action="<?php echo $CFG->wwwroot; ?>/login/logout.php" method="post">
        <input type="hidden" name="sesskey" value="<?php echo sesskey(); ?>"/>
        <button type="button" class="btn btn-primary"
                onclick="document.location.href='<?php echo $courseurl; ?>'">
            <?php echo get_string('backtocourse', 'block_eledia_adminexam'); ?></button>
        <button type="submit"><?php echo get_string('logout'); ?></button>
    </form>
</div>
</body>
</html>
