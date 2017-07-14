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
 * @package local_jbpmhub
 * @copyright 2016, Timo Neff Timo.Neff@de.ibm.com, Robin Friedrich, Marius Böcking, Ruben Schilling
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// defined('MOODLE_INTERNAL') || die();

// Plugin Abhaehningkeiten
require("RemoteRestConnection.php");
// require_once("./FirePHPCore/FirePHP.class.php");
// require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');


// Sicherheit, Plugin nur von Moodle System erreichbar
// defined('MOODLE_INTERNAL') || die();

// Plugin Konfigurations Variablen 
const URL = "141.72.16.239:8080/jbpm-console/rest/";
const DEPLOYMENT_ID = "DHBW:WA01_PA1_anmelden:1.9";
const USERNAME = "admin";
const PASSWORD = "admin";

?>