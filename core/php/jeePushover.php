<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
http_response_code(200);
header("Content-Type: application/json");
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
log::add('pushover', 'debug', "appel api ");
if (init('apikey') != config::byKey('api') || config::byKey('api') == '') {
        connection::failed();
        echo 'Clef API non valide, vous n\'etes pas autorisé �|  effectuer cette action (jeeApi)';
        die();
}
$content = file_get_contents('php://input');
log::add('pushover', 'debug', $content);
$id = init('id');
$eqLogic = pushover::byId($id);
if (!is_object($eqLogic)) {
        echo json_encode(array('text' => __('Id inconnu : ', __FILE__) . init('id')));
        log::add('pushover', 'debug', "id inconnu ". $id);
        die();
}
parse_str($content, $query_params);
$devicename = $query_params["acknowledged_by_device"];
$sender =  $query_params["acknowledged_by"]; 
$receipt =  $query_params["receipt"];
$ack_status = $query_params["acknowledged"];
$eqLogic->checkAndUpdateCmd('sender', $sender );
$eqLogic->checkAndUpdateCmd('status', $ack_status );
$eqLogic->checkAndUpdateCmd('receiptid', $receipt );
$eqLogic->checkAndUpdateCmd('devicename', $devicename );
?>
