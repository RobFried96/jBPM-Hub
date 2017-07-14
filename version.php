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
 
$plugin->version   = 2017062801; //YYYYMMDDXX 
$plugin->requires  = 2013050100; // Moodle xx is required
$plugin->cron      = 0; // Do not execute this plugin's cron more often than every five minutes.
$plugin->component = 'local_jbpmhub';
$plugin->maturity  = MATURITY_STABLE; // This is considered as ready for production sites
$plugin->release   = 'v1';
/* 
$plugin->dependencies = array(
 'mod_forum' => ANY_VERSION,
 'mod_data'  => TODO); */