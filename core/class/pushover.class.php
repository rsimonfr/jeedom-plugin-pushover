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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

define('PUSHOVERADDR', 'https://api.pushover.net/1/messages.json');

class pushover extends eqLogic {
    
}

class pushoverCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    public function preSave() {
        if ($this->getConfiguration('token') == '') {
            throw new Exception('token ne peut etre vide');
        }
        if ($this->getConfiguration('user') == '') {
            throw new Exception('user ne peut etre vide');
        }

    }

    public function execute($_options = null) {
        if ($_options === null) {
            throw new Exception(__('Les options de la fonction ne peuvent etre null', __FILE__));
        }
        if ($_options['message'] == '' ) {
            throw new Exception(__('Le message et le sujet ne peuvent être vide', __FILE__));
        }


        curl_setopt_array($ch = curl_init(), array(
        CURLOPT_URL => "https://api.pushover.net/1/messages.json",
        CURLOPT_POSTFIELDS => array(
            "token" => $this->getConfiguration('token'),
            "user" =>  $this->getConfiguration('user'),
            "message" => $_options['message'] ,
            "title"   => $_options['title'] , 
            "priority" =>  $this->getConfiguration('priority' ) , 
            "sound"    =>  $this->getConfiguration('sound') , 
            "device"   =>  $this->getConfiguration('device') , 
            "retry"   =>  $this->getConfiguration('retry') ,
            "expire"   =>  $this->getConfiguration('expire') ,

             ),
        CURLOPT_SAFE_UPLOAD => true,

        ));
        curl_exec($ch);
        curl_close($ch);
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>
