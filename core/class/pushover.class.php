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
        public function postSave() {
                $sms = $this->getCmd(null, 'status');
                if (!is_object($sms)) {
                        $sms = new pushoverCmd();
                        $sms->setLogicalId('status');
                        $sms->setIsVisible(0);
                        $sms->setName(__('Statut', __FILE__));
                }
                $sms->setType('info');
                $sms->setSubType('string');
                $sms->setEqLogic_id($this->getId());
                $sms->save();

                $sender = $this->getCmd(null, 'sender');
                if (!is_object($sender)) {
                        $sender = new pushoverCmd();
                        $sender->setLogicalId('sender');
                        $sender->setIsVisible(0);
                        $sender->setName(__('Expediteur', __FILE__));
                }
                $sender->setType('info');
                $sender->setSubType('string');
                $sender->setEqLogic_id($this->getId());
                $sender->save();

                $cmdid = $this->getCmd(null, 'cmdid');
                if (!is_object($cmdid)) {
                        $cmdid = new pushoverCmd();
                        $cmdid->setLogicalId('cmdid');
                        $cmdid->setIsVisible(0);
                        $cmdid->setName(__('Commande Id', __FILE__));
                }
                $cmdid->setType('info');
                $cmdid->setSubType('string');
                $cmdid->setEqLogic_id($this->getId());
                $cmdid->save();

                $receiptid = $this->getCmd(null, 'receiptid');
                if (!is_object($receiptid)) {
                        $receiptid = new pushoverCmd();
                        $receiptid->setLogicalId('receiptid');
                        $receiptid->setIsVisible(0);
                        $receiptid->setName(__('Receipt Id', __FILE__));
                }
                $receiptid->setType('info');
                $receiptid->setSubType('string');
                $receiptid->setEqLogic_id($this->getId());
                $receiptid->save();

                $devicename = $this->getCmd(null, 'devicename');
                if (!is_object($devicename)) {
                        $devicename = new pushoverCmd();
                        $devicename->setLogicalId('devicename');
                        $devicename->setIsVisible(0);
                        $devicename->setName(__('Nom Device', __FILE__));
                }
                $devicename->setType('info');
                $devicename->setSubType('string');
                $devicename->setEqLogic_id($this->getId());
                $devicename->save();


        }

        public function execCurlWithFields($fields){
            curl_setopt_array($ch = curl_init(), array(
                CURLOPT_URL => "https://api.pushover.net/1/messages.json",
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_SAFE_UPLOAD => true,
            ));
            curl_exec($ch);
            curl_close($ch);
        }

    
}

class pushoverCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    public function preSave() {
/*        if ($this->getConfiguration('token') == '') {
            throw new Exception('token ne peut etre vide');
        }
        if ($this->getConfiguration('user') == '') {
            throw new Exception('user ne peut etre vide');
        }
*/
    }

    public function execute($_options = null) {
        $eqLogic = $this->getEqLogic();
        if ($_options === null) {
            throw new Exception(__('Les options de la fonction ne peuvent etre null', __FILE__));
        }
        if ($_options['message'] == '' ) {
            throw new Exception(__('Le message et le sujet ne peuvent etre vide', __FILE__));
        }
		
		$fields = array(
            "token" => $this->getConfiguration('token'),
            "user" =>  $this->getConfiguration('user'),
            "message" => $_options['message'] ,
            "title"   => $_options['title'] ,
            "priority" =>  $this->getConfiguration('priority' ) , 
            "sound"    =>  $this->getConfiguration('sound') , 
            "device"   =>  $this->getConfiguration('device') , 
            "retry"   =>  $this->getConfiguration('retry') ,
            "expire"   =>  $this->getConfiguration('expire') ,
            "callback" => network::getNetworkAccess('external') . '/plugins/pushover/core/php/jeePushover.php?apikey=' . config::byKey('api') . '&id='. $this->getEqLogic_id() . '&cmdid=' . $this->getId() ,
            "html"     =>  1, 
        );

        /*  Ajout d'une image depuis le plugin camera : pour le moment, il n'est possible d'ajouter qu'une seule image par notification
            on boucle donc sur toutes les images et on envoi une notification par tour de boucle
            Extrait doc. pushover :
            Each message may only include one attachment, and attachments are currently limited to 2,621,440 bytes (2.5 megabytes)
        */
        if (array_key_exists('files', $_options) && is_array($_options['files']) && is_file($_options['files'][0])) {
            foreach ($_options['files'] as $file) {
                log::add('pushover', 'debug', 'Fichier detecte : ' . $file);
                $size = number_format(filesize($file) / 1048576, 2);
                log::add('pushover', 'debug', 'Taille du fichier en Mo : ' . $size . ' (limite pushover 2,5 Mo)');
                $fields["attachment"] = new CURLFile($file);
                $eqLogic->execCurlWithFields($fields);
            }
        } else {
            // image dans LocalFile 
            $fichier_local = trim ( jeedom::evaluateExpression($this->getConfiguration('localfile')), '"');
            log::add('pushover', 'debug', 'Fichier detecte : ' . $fichier_local) ;
            if (file_exists($fichier_local)) {
               $fields["attachment"] =  new CURLFile($fichier_local); 
            }
            // Si il n'y a pas d'image
            $eqLogic->execCurlWithFields($fields);
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>
