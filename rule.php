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
 * Implementaton of the quizaccess_insertjs plugin.
 *
 * @package   quizaccess_insertjs
 * @author    Prof. P Sunthar, Kashmira N
 * @copyright 2020 Indian Institute Of Technology Bombay, India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined ( 'MOODLE_INTERNAL' ) || die ();

require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');


/**
 * A rule implementing insertion of js for assessment portal
 *
 * @copyright  2020 Indian Institute Of Technology Bombay, India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_insertjs extends quiz_access_rule_base {
    
    public static function make(quiz $quizobj, $timenow, $canignoretimelimits) {
        // This rule is always used, even if the quiz has no open or close date.
        return new self ( $quizobj, $timenow );
    }
    
    public function setup_attempt_page($page) {
    // public function end_time($attempt) { // Limitation: Ends quiz as timer text is replaced by result msg
    // public function prevent_access() {   // Limitation: Prevents access
    // public function prevent_new_attempt($numprevattempts, $lastattempt) {	
        global $CFG, $PAGE, $_SESSION, $DB, $USER, $HBCFG;
        $PAGE->requires->jquery();
        
        $id = optional_param ( 'id', 0, PARAM_INT );
        $cm = get_coursemodule_from_id ('quiz', $id);
    	$result = "";
        $flag = 0;
        
        // Log stmts
        echo '<br><br><br>';
        $fn = 'setup_attempt_page';
        $this->debuglog($fn, "begin ---");
        
        // User details.
        $sessionkey = sesskey();
        $sessionkeyJS = json_encode($sessionkey);
        $userid     = $USER->id;
        $username   = $USER->username;

        // Quiz details.
        $quiz       = $this->quizobj->get_quiz();
        $quizid     = $this->quizobj->get_quizid();
        $cmid       = $this->quizobj->get_cmid();

        if ($unfinishedattempt = quiz_get_user_attempt_unfinished($quiz->id, $USER->id)) {
            $unfinishedattemptid = $unfinishedattempt->id;
            $unfinished = $unfinishedattempt->state == quiz_attempt::IN_PROGRESS;

            if ($unfinished) {
                $attemptid  = $unfinishedattempt->id;
                $attemptobj = quiz_attempt::create($attemptid);

                // Check that this attempt belongs to this user.
                if ($attemptobj->get_userid() != $USER->id) {
                    throw new moodle_quiz_exception($attemptobj->get_quizobj(), 'notyourattempt');
                } else {
                    // $flag = 1;
                    // echo "<br><br><br>in if unfinished block ---";
                    $result .= "<script>
                        function insertJS() {
                            $(document).ready(function() {
                                
                                $('.submitbtns').prepend(
                                    $('</br>'),
                                    $('<p>', {
                                        'id':'demo'
                                    }),
                                    $('</br>')
                                );
                                document.getElementById('demo').innerHTML = 'Demo text: ' + '$username';                                
                            });                        
                        }
                        </script>";
                    $result .= "<script type='text/javascript'>insertJS();</script>";
                }
            }
        } else {
            $this->debuglog($fn, "fresh attempt");
            // $flag = 1;
        }
        $this->debuglog($fn, "end ---");

        // Comment: Only gets inserted during admin preview and not actual student attempt
        // as it prevents attempt
        // return $result;  // used as a prevent message
        // return false;  
    }  

    public function current_attempt_finished() {
        $fn = 'current_attempt_finished';
        $this->debuglog($fn, "begin ---");
        $this->debuglog($fn, "end ---");
    }

    public function debuglog($fn = '', $msgarg = '', $obj = null) {
        global $CFG;
        $msg = $msgarg;
        $log = null;
        $result = '';
        // $logs_temp = $CFG->dirroot . "/mod/quiz/accessrule/insertjs/logs_temp.text";
        // $logs = $CFG->dirroot . "/mod/quiz/accessrule/insertjs/logs.text";

        // $fp = fopen($logs_temp,"a+");
        // if( $fp == false ) {
        //     echo ( "Error in opening file" );
        //     exit();
        // }

        $log = "<br>debug: " . date('D M d Y H:i:s'). " " . (microtime(True)*10000) . ", " . "rule.php | " . $fn;
        if ($msg !== '')
            $log .= ", " . $msg;

        if (!empty($obj)) {
//             echo '<br><br><br>in debuglog record obj ---';
//             print_object($obj);
            foreach ($obj as $key => $value) {
                $log .= "; $key => $value";
            }
        }
        echo $log;
    }
}