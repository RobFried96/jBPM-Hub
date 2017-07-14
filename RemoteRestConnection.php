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

class RemoteRestConnection {
  
  // Deklaration der Eigenschaften
  public $connection;
  public $url; 
  public $deploymentId;
  public $username;
  public $password;
  //private $authenticationType;
 
 /*
  * Konstruktor 
  *
  * Erstellt eine Instanz der RemoteRestConnection
  * 
  * @attr URL - Die URL des jBPM Servers z.B. "141.72.16.237:8080/jbpm-console/rest/"
  * @attr DEPLOYMENT_ID - Die Deployment ID des jBPM Servers z.B. "DHBW:WA-Testcase:3.1"
  * @attr USERNAME - Der Benutzername zum Login der Rest API des jBPM Servers z.B. "admin"
  * @attr PASSWORD - Das Passwort zum Login der Rest API des jBPM Servers z.B. "admin"
  * @attr connection - Das Objekt welches die Verbindung repräsentiert
  * @return Ein RemoteRestConnection Objekt mit Verbindung auf der eine jBPM Serveranfrage ausgeführt werden kann
  */
  public function __construct() {

    // Übernehme die Variablen aus der Konfigurations-Datei "configuration.php" 
    $this->url = URL;
    $this->deploymentId = DEPLOYMENT_ID;
    $this->username = USERNAME;
    $this->password = PASSWORD;
  
    // Erstelle eine neue CURL Verbindung und setze Standardoptionen
    $this->connection = curl_init();
    curl_setopt($this->connection, CURLOPT_USERPWD, $this->username.":".$this->password);
    curl_setopt($this->connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->connection, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $headers = array();
    $headers[] = "Accept: application/xml";
    $headers[] = "Content-Type: application/xml";
    curl_setopt($this->connection, CURLOPT_HTTPHEADER, $headers);
    }
  
   /*
    * Execute 
    *
    * Anfrage auf den jBPM Server ausfuehren
    * 
    * @param $httpMethodPost - Post Methode z.B. true
    * @param $section - Grundlegnde Art des API Calls z.B. "runtime"
    * @param $call - Der API Call z.B. "/process/procDefID/start"
    * @return Die Antwort des jBPM Servers als XML 
    */
    public function execute($httpMethodPost, $call){     
      
    // Eigenschaft der HTTP Methode (Get oder Post)
    if($httpMethodPost) {
      curl_setopt($this->connection, CURLOPT_POST, $httpMethodPost);
    }
    // Server URL und Ressource zusammensetzen
    curl_setopt($this->connection, CURLOPT_URL, $this->url . $call);
   
    // Aufruf der Ressource
    try { 
      $response = curl_exec($this->connection);
    } 
    catch (Exception $e) {
      echo "Error: Der jBPM-Server ist nicht erreichbar.";
    }
    $xml = simplexml_load_string($response) or die ("Error: XML kann jetzt nicht erstellt werden."); 
    return $xml;
  }
 
 /*
  * Dekonstruktor
  *
  * Zum Beenden der Verbindung
  * 
  */  
  function __destruct(){
    curl_close ($this->connection);
  }
}

?>