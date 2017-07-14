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
 * Library of functions and constants for module description
 *
/**
 * @package local_jbpmhub
 * @copyright 2016, Timo Neff Timo.Neff@de.ibm.com, Robin Friedrich, Marius Böcking, Ruben Schilling
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// defined('MOODLE_INTERNAL') || die();

// Pfad zur Konfigurationsdatei
require "configuration.php";

/*
 * startProcess 
 *
 * Startet einen Prozess fuer einen Benutzer auf jBPM mit Übergabe der initalen Veriablen, erstellt den ersten Task und versetzt ihn
 * von "Created" auf "Reserved"
 *
 * @param $processDefinitionId - Die Prozess Definitions Identifikationsnummer des zu startenden Prozesses von jBPM 
 * @roles - PHP Standardobjekt bestehend aus n key --> value Paaren // Rolle --> UserId
 * @return - einen String "Success" bei Erfolg und "Error" bei Misserfolg
 */
function startProcess($processDefinitionId, $roles) {
    
    // Erstelle eine neue Verbindung zu JBPM
    $connection = new RemoteRestConnection();
    $httpMethodPost = true;
    
    $call = "runtime/" . DEPLOYMENT_ID . "/withvars/process/" . $processDefinitionId . "/start?";
    
    // Binden der Moodle-User in ihren Rollen an die Prozessinstanz
    foreach ($roles as $key => $value) {
        $call = $call . "map_" . $key . "Id=" . $value . "&";  
    } 
    
    try {
        $response = $connection->execute($httpMethodPost, $call);
    } catch(Exception $e) {
        echo "JBPM Server nicht erreichbar";
    }
    
    return $response->status;  
}

/* 
 * startTask
 * Startet einen Task im JBPM-Server und versetzt den Status von "Reserved" zu "InProgress"
 *
 * @param $taskId - Die Task ID des Tasks im JBPM Server 
 * @return - einen String "Success" bei Erfolg und "Error" bei Misserfolg
 */
function startTask($taskId) {
    // Erstelle eine neue Verbindung zu JBPM
    $connection = new RemoteRestConnection();
    $httpMethodPost = true;
    
    $call = "task/" . $taskId . "/start";
    
    try {
        $response = $connection->execute($httpMethodPost, $call);
    } catch(Exception $e) {
        echo "JBPM Server nicht erreichbar";
    }
    
    return $response->status; 
}

/*
 * completeTasks
 *
 * Schließt eine Aufgabe im JBPM Server ab und übergibt alle Einträge aus dem Formular aus Moodle an JBPM
 * Der Status des Task wird von * "InProgress" auf "Completed" gesetzt
 * 
 * @param $taskId - Die Task ID der Aufgabe im JBPM Server 
 * @param $vars - PHP Standard Object mit allen Einträgen aus dem Moodle Formular
 * @return - einen String "Success" bei Erfolg und "Error" bei Misserfolg
 */
function completeTask($taskId, $vars) {
    
    // Variablen werden im PHP Standardobjekt übergeben 
    $call = "task/" . $taskId . "/complete?";
    
    foreach ($vars as $key => $value) {
        $call = $call . "map_" . $key . "=" . $value . "&";     
    }  
    
    // Erstelle eine neue Verbindung zu JBPM
    $connection = new RemoteRestConnection();
    $httpMethodPost = true;
    
    try {
        $response = $connection->execute($httpMethodPost, $call);
    } catch(Exception $e) {
        echo "JBPM Server nicht erreichbar";
    }
    
    return $response->status; 
}

/*
 * getTasks
 *
 * Gibt alle nicht abgeschlossenen Taks für einen User aus
 * 
 * @param $userId - Identifikationsnummer des Users aus Moodle
 * @param $role - Rolle des Users aus Moodle
 * @return - eine komplette XML Liste mit allen Taksinformationen für einen User
 */
function getTasks($userId, $role) {
    
    // Erstelle eine neue Verbindung zu JBPM
    $connection = new RemoteRestConnection();
    $httpMethodPost = false;
    
    $call = "query/runtime/task?var_" . $role . "Id=" . $userId;
    
    try {
        $response = $connection->execute($httpMethodPost, $call);
    } catch(Exception $e) {
        echo "JBPM Server nicht erreichbar";
    }
    
    return $response;
}

/*
 * getProcessData
 *
 * Listet alle Variablen mit Wert einer Prozessinstanz auf
 * 
 * @param processDefinitionId - Die Prozess Definitions Identifikationsnummer des Prozesses von jBPM 
 * @param processInstanceId - Identifikationsnummer der Prozessinstanz aus JBPM
 * @return - eine komplette XML Liste mit allen Informationen für einen Prozess
 */
function getProcessData($processInstanceId) {
    
    // Erstelle eine neue Verbindung zu JBPM
    $connection = new RemoteRestConnection();
    $httpMethodPost = false;
    
    $call = "runtime/" . DEPLOYMENT_ID . "/withvars/process/instance/" . $processInstanceId;
    
    try {
        $response = $connection->execute($httpMethodPost, $call);
    } catch(Exception $e) {
        echo "JBPM Server nicht erreichbar";
    }
    
    return $response;
}

/*
 * getCompletedProcessInstances
 *
 * Zeigt alle abgeschlossenen Prozessinstanzen für einen User an
 * 
 * @param $userId - Identifikationsnummer des Users aus Moodle
 * @param $role - Rolle des Users aus Moodle
 * @return - eine komplette XML Liste mit allen abgeschlossenen Instanzen des Users
 */
function getCompletedProcessInstances($userId, $role) {
    // Rollen IDs müssen überarbeitet werden!
    
    // Erstelle eine neue Verbindung zu JBPM
    $connection = new RemoteRestConnection();
    $httpMethodPost = false;
    
    $call = "history/variable/" . $role . "Id/value/" . $userId;
    
    try {
        $response = $connection->execute($httpMethodPost, $call);
    } catch(Exception $e) {
        echo "JBPM Server nicht erreichbar";
    }
    
    return $response;
}

/*
 * getCompletedInstanceData
 *
 * Liefert eine XML Liste zurück, die alle Variablen für eine bereits abgeschlossene Prozessinstanz enthält
 * 
 * @param $processInstanceId - Identifikationsnummer der Prozessinstanz aus JBPM
 * @return - eine komplette xml Liste mit allen Variablen einer abgeschlossenen Prozessinstanz
 */
function getCompletedInstanceData($processInstanceId) {
    
    // Erstelle eine neue Verbindung zu JBPM
    $connection = new RemoteRestConnection();
    $httpMethodPost = false;
    
    $call = "history/instance/" . $processInstanceId . "/variable";

    try {
        $response = $connection->execute($httpMethodPost, $call);
    } catch(Exception $e) {
        echo "JBPM Server nicht erreichbar";
    }    
    
    return $response;
}

?>