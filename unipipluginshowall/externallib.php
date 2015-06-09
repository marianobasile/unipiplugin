<?php

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
 * External Web Service Template
 *
 * @package    local
 * @subpackage unipipluginshowall
 * @copyright  2015 Mariano Basile
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("$CFG->libdir/externallib.php");

class local_unipipluginshowall_external extends external_api {

	public static function search_unipi_courses_parameters() {
        return new external_function_parameters(
            array(
                'coursename' => new external_value(PARAM_TEXT, 'String used for find courses. Search based on coursename')
            ) 
        );
    }

    public static function search_unipi_courses_returns() {
        return new external_value(PARAM_TEXT, 'Courses found with that name');
    }

    public static function search_unipi_courses($coursename) { //Don't forget to set it as static
        global $CFG, $DB;
        require_once($CFG->dirroot . "/course/lib.php");

        $params = self::validate_parameters(self::search_unipi_courses_parameters(), array('coursename'=>$coursename));

		$sql = " SELECT c.id, c.fullname, u.lastname, u.firstname
                    
    	 FROM mdl_course c
    	 JOIN mdl_context ct ON c.id = ct.instanceid
    	 JOIN mdl_role_assignments ra ON ra.contextid = ct.id
    	 JOIN mdl_user u ON u.id = ra.userid
    	 JOIN mdl_role r ON r.id = ra.roleid

    	 WHERE ct.contextlevel = 50 and c.fullname like '%$coursename%' and r.id = 3 ORDER BY c.fullname ASC";

		$coursesWithTeacher = $DB->get_records_sql($sql, $params);

        $sql = "SELECT c.id, c.fullname
                    
                    FROM mdl_course c
                    JOIN mdl_context ct ON c.id = ct.instanceid
        
                    WHERE ct.contextlevel = 50 and c.fullname like '%$coursename%' 
                    
                    and c.id not in (SELECT c.id
                    
                    FROM mdl_course c
                    JOIN mdl_context ct ON c.id = ct.instanceid
                    JOIN mdl_role_assignments ra ON ra.contextid = ct.id
                    JOIN mdl_user u ON u.id = ra.userid
                    JOIN mdl_role r ON r.id = ra.roleid

                    WHERE ct.contextlevel = 50 and c.fullname like '%$coursename%' and r.id = 3 ORDER BY c.fullname ASC)

                    ORDER BY c.fullname ASC";

        $coursesWithoutTeacher = $DB->get_records_sql($sql, $params);

        $noTeacherFound = false;
        $courses = array();

        if(empty($coursesWithTeacher)){
            $noTeacherFound = true;
            $courses = $coursesWithoutTeacher;
        }else
            $courses = array_merge($coursesWithTeacher,$coursesWithoutTeacher);
        
        $result = "";
        $rowNumber = 0;

        foreach ($courses as $course) {
            $courseNameNoBlankSpace = str_replace(" ", "*", $course->fullname);

            if($rowNumber == 0) {
                if($noTeacherFound == false)
                    $result .= $_SERVER['SERVER_NAME']." ".$course->id." ".$courseNameNoBlankSpace." ".$course->lastname." ".$course->firstname;
                else
                    $result .= $_SERVER['SERVER_NAME']." ".$course->id." ".$courseNameNoBlankSpace." ".""." "."";
            } else {
                if($noTeacherFound == false)
                    $result .= " ".$_SERVER['SERVER_NAME']." ".$course->id." ".$courseNameNoBlankSpace." ".$course->lastname." ".$course->firstname;
                else
                    $result .= " ".$_SERVER['SERVER_NAME']." ".$course->id." ".$courseNameNoBlankSpace." ".""." "."";
            }
            $rowNumber++;
		}
        return $result;
    }

    //SEARCH COURSES BY TEACHER 

    public static function search_by_teacher_parameters() {
        return new external_function_parameters(
            array(
                'teachername' => new external_value(PARAM_TEXT, 'String used for find courses. Search based on teacher lastname')
            ) 
        );
    }
    
    public static function search_by_teacher_returns() {
        return new external_value(PARAM_TEXT, 'Courses found with that teacher lastname');
    }

    public static function search_by_teacher($teachername) { //Don't forget to set it as static
        global $CFG, $DB;
        require_once($CFG->dirroot . "/course/lib.php");

        $params = self::validate_parameters(self::search_by_teacher_parameters(), array('teachername'=>$teachername));

        $sql = " SELECT c.id, c.fullname, u.lastname, u.firstname
                    
         FROM mdl_course c
         JOIN mdl_context ct ON c.id = ct.instanceid
         JOIN mdl_role_assignments ra ON ra.contextid = ct.id
         JOIN mdl_user u ON u.id = ra.userid
         JOIN mdl_role r ON r.id = ra.roleid

         WHERE ct.contextlevel = 50 and u.lastname like '%$teachername%' and r.id = 3 ORDER BY c.fullname ASC";

        $courses = $DB->get_records_sql($sql, $params);

        $result = "";
        $rowNumber = 0;

        foreach ($courses as $course) {
            $courseNameNoBlankSpace = str_replace(" ", "*", $course->fullname);

            if($rowNumber == 0)
                $result .= $_SERVER['SERVER_NAME']." ".$course->id." ".$courseNameNoBlankSpace." ".$course->lastname." ".$course->firstname;
            else
                $result .= " ".$_SERVER['SERVER_NAME']." ".$course->id." ".$courseNameNoBlankSpace." ".$course->lastname." ".$course->firstname;

            $rowNumber++;
        }
        return $result;
    }
    
}
