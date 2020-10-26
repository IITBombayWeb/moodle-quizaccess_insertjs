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
        
    // public function prevent_access() {
    public function prevent_new_attempt($numprevattempts, $lastattempt) {	
        global $CFG, $PAGE, $_SESSION, $DB, $USER, $HBCFG;
        $PAGE->requires->jquery();
        // echo "<br><br><br>hi";
        $id = optional_param ( 'id', 0, PARAM_INT );
        $cm = get_coursemodule_from_id ('quiz', $id);
    	$result = "";
        $flag = 0;
        
        // echo "<br><br><br>here in";
        $result .= "<script>
        function insertJS() {
            $(document).ready(function() {
                
                $('.quizattempt').prepend(
                    $('</br>'),
                    $('<p>', {
                        'id':'demo'
                    }),
                    $('</br>')
                );
                document.getElementById('demo').innerHTML = 'Hey';

            });                        
        }
        </script>";
        $result .= "<script type='text/javascript'>insertJS();</script>";
                    
    	return $result;  //used as a prevent message  
    }  
}

