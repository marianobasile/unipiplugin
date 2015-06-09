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
 * Web service local plugin template external functions and service definitions.
 *
 * @package    local
 * @subpackage unipiplugin
 * @copyright  2015 Mariano Basile
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// We defined the web service functions to install.
$functions = array(
    'local_unipipluginshowall_search_unipi_courses' => array(         //web service function name
        'classname'   => 'local_unipipluginshowall_external',  //class containing the external function
        'methodname'  => 'search_unipi_courses',          //external function name
        'classpath'   => 'local/unipipluginshowall/externallib.php',  //file containing the class/external function
        'description' => 'Search unipi courses',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
    ),'local_unipipluginshowall_search_by_teacher' => array(         //web service function name
        'classname'   => 'local_unipipluginshowall_external',  //class containing the external function
        'methodname'  => 'search_by_teacher',          //external function name
        'classpath'   => 'local/unipipluginshowall/externallib.php',  //file containing the class/external function
        'description' => 'Search unipi courses by teacher ',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
    )
    
);