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

require_once __DIR__  . '/../../../../core/php/core.inc.php';

class Djeesign extends eqLogic
{
    /*     * *************************Attributs****************************** */

    /*
     * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
     * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
       public static $_widgetPossibility = array();
    */

    /*     * ***********************Methode static*************************** */


    /*     * *********************Méthodes d'instance************************* */

    // On allume
      //
    public function Allumer()
    {
        foreach ($this->getConfiguration('csg_on_conf') as $action) {
            try {
                $cmd = cmd::byId(str_replace('#', '', $action['cmd']));
                if (!is_object($cmd)) {
                    continue;
                }
                $options = array();
                if (isset($action['options'])) {
                    $options = $action['options'];
                }
                scenarioExpression::createAndExec('action', $action['cmd'], $options);
            } catch (Exception $e) {
                log::add('Djeesign', 'error', $this->getHumanName() . __(' : Erreur lors de l\'éxecution de ', __FILE__) . $action['cmd'] . __('. Détails : ', __FILE__) . $e->getMessage());
            }
        }
    }

    // On éteint
      //
    public function Eteindre()
    {
        foreach ($this->getConfiguration('csg_off_conf') as $action) {
            try {
                $cmd = cmd::byId(str_replace('#', '', $action['cmd']));
                if (!is_object($cmd)) {
                    continue;
                }
                $options = array();
                if (isset($action['options'])) {
                    $options = $action['options'];
                }
                scenarioExpression::createAndExec('action', $action['cmd'], $options);
            } catch (Exception $e) {
                log::add('Djeesign', 'error', $this->getHumanName() . __(' : Erreur lors de l\'éxecution de ', __FILE__) . $action['cmd'] . __('. Détails : ', __FILE__) . $e->getMessage());
            }
        }
    }

    // Fonction exécutée automatiquement avant la création de l'équipement
      //
    public function preInsert()
    {
    }

    // Fonction exécutée automatiquement après la création de l'équipement
      //
    public function postInsert()
    {
    }

    // Fonction exécutée automatiquement avant la mise à jour de l'équipement
      //
    public function preUpdate()
    {
    }

    // Fonction exécutée automatiquement après la mise à jour de l'équipement
      //
    public function postUpdate()
    {
    }

    // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
      //
    public function preSave()
    {
    }

    // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
      //
    public function postSave()
    {
        log::add('Djeesign', 'info', 'postSave');
        $lock = $this->getCmd(null, 'allumer');
        if (!is_object($lock)) {
            $lock = new DjeesignCmd();
            $lock->setName('Allumer');
            $lock->setIsVisible(1);
            $lock->setIsHistorized(0);
        }
        $lock->setEqLogic_id($this->getId());
        $lock->setType('action');
        $lock->setSubType('other');
        $lock->setLogicalId('allumer');
        $lock->save();

        $unlock = $this->getCmd(null, 'eteindre');
        if (!is_object($unlock)) {
            $unlock = new DjeesignCmd();
            $unlock->setName('Eteindre');
            $unlock->setIsVisible(1);
            $unlock->setIsHistorized(0);
        }
        $unlock->setEqLogic_id($this->getId());
        $unlock->setType('action');
        $unlock->setSubType('other');
        $unlock->setLogicalId('eteindre');
        $unlock->save();
    }

    // Fonction exécutée automatiquement avant la suppression de l'équipement
      //
    public function preRemove()
    {
    }

    // Fonction exécutée automatiquement après la suppression de l'équipement
      //
    public function postRemove()
    {
    }


    // Permet de modifier l'affichage du widget (également utilisable par les commandes)
      //
    public function toHtml($_version = 'dashboard')
    {
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $version = jeedom::versionAlias($_version);

        $visuel = config::byKey('visuel', 'Djeesign');
        $typeDesign = $this->getConfiguration('typeDesign');
        if (($typeDesign !== "cadre")  && ($typeDesign !== "menu") &&
            ($typeDesign !== "mobile") && ($typeDesign !== "graphe") &&
            ($typeDesign !== "widget_temp") && ($typeDesign !== "widget_lumi")) {
            $typeDesign = "cadre";
        }

        $replace["#visuel#"] = $visuel;

        // Cadre
        //
        if ($typeDesign === "cadre") {
            $titre = $this->getConfiguration('titre');
            $icone = $this->getConfiguration('icone');

            $replace["#titre#"] = $titre;
            $replace["#icone#"] = $icone;

            if ($visuel == 'Noir' ) {
                $replace["#clrTitre#"] = "white";
            } else if ($visuel == 'Blanc') {
                $replace["#clrTitre#"] = "darkgrey";
            } else {
                $replace["#clrTitre#"] = "white";
            }
        }

        // Menu
        //
        if (($typeDesign === "menu") || ($typeDesign === "mobile")) {
            $listeIds = $this->getConfiguration('listeIds');
            $listeRemplacementNoms = $this->getConfiguration('listeNoms');
            $listeIcones = $this->getConfiguration('listeIcones');
            $isIconOnly = $this->getConfiguration('isIconOnly');
            $isMenuView = $this->getConfiguration('isMenuView');

            $listeNoms = '';
            $ids = explode(';', $listeIds);
            $noms = explode(';', $listeRemplacementNoms);

            $n=0;
            foreach ($ids as $id) {
                if ($listeNoms != "") {
                    $listeNoms .= ";";
                }

                $nom = '';
                if ($n < count($noms)) {
                    $nom = str_replace(' ', '', $noms[$n]);
                }
                $n++;

                if ($nom === '') {
                    if ($isMenuView == true) {
                        $vi = view::byId($id);
                        if ($vi != null) {
                            $listeNoms .= $vi->getName();
                        } else {
                            $listeNoms .= "Id Inconnu";
                        }
                    } else {
                        $ph = planHeader::byId($id);
                        if ($ph != null) {
                            $listeNoms .= $ph->getName();
                        } else {
                            $listeNoms .= "Id Inconnu";
                        }
                    }
                } else {
                    $listeNoms .= $nom;
                }
            }

            $replace["#listeIds#"] = $listeIds;
            $replace["#listeIcones#"] = $listeIcones;
            $replace["#listeNoms#"] = $listeNoms;
            $replace["#isIconOnly#"] = $isIconOnly;
            $replace["#isMenuView#"] = $isMenuView;

            if ($_version == 'dashboard') {
                $defaultView = $_SESSION['user']->getOptions('defaultDesktopView');
            } else {
                $defaultView = $_SESSION['user']->getOptions('defaultMobileView');
            }
            $replace["#defaultView#"] = $defaultView;
        }

        // Graphe
          //
        if ($typeDesign === "graphe") {
            $unite = '';
            $cmdGraphe1 = $this->getConfiguration('cmdGraphe1');
            $cmdGraphe2 = $this->getConfiguration('cmdGraphe2');
            $startTime = date("Y-m-d H:i:s", time()-24*60*60);

            $minTime = time()-3*60*60;

            $listeHistoGraphe1 = '';
            $cmd = cmd::byId(str_replace('#', '', $cmdGraphe1));
            if (is_object($cmd)) {
                $histoGraphe1 = $cmd->getHistory($startTime);
                $lastValue = null;
                $n=0;
                foreach ($histoGraphe1 as $row) {
                    $datetime = $row->getDatetime();
                    $ts = strtotime($datetime);
                    if ($ts >= $minTime) {
                        $n++;
                        $value = $row->getValue();
                        $listeHistoGraphe1 .= "[Date.UTC(".date("Y", $ts).",".(date("m", $ts)-1).","
                          .date("d", $ts).",".date("H", $ts).",".date("i", $ts).",".date("s", $ts)."),".$value."],\n";
                    } else {
                        $lastValue = $row->getValue();
                    }
                }
                if (($n == 0) && ($lastValue != null)) {
                    $ts = $minTime;
                    $listeHistoGraphe1 .= "[Date.UTC(".date("Y", $ts).",".(date("m", $ts)-1).","
                    .date("d", $ts).",".date("H", $ts).",".date("i", $ts).",".date("s", $ts)."),".$lastValue."],\n";
                }
                $ts = time();
                $value = $cmd->execCmd();
                $listeHistoGraphe1 .= "[Date.UTC(".date("Y", $ts).",".(date("m", $ts)-1).","
                  .date("d", $ts).",".date("H", $ts).",".date("i", $ts).",".date("s", $ts)."),".$value."],\n";
                $unite = $cmd->getUnite();
            }
            $replace["#listeHistoGraphe1#"] = $listeHistoGraphe1;

            $listeHistoGraphe2 = '';
            $cmd = cmd::byId(str_replace('#', '', $cmdGraphe2));
            if (is_object($cmd)) {
                $histoGraphe2 = $cmd->getHistory($startTime);
                $lastValue = null;
                $n=0;
                foreach ($histoGraphe2 as $row) {
                    $datetime = $row->getDatetime();
                    $ts = strtotime($datetime);
                    if ($ts >= $minTime) {
                        $n++;
                        $value = $row->getValue();
                        $listeHistoGraphe2 .= "[Date.UTC(".date("Y", $ts).",".(date("m", $ts)-1).","
                      .date("d", $ts).",".date("H", $ts).",".date("i", $ts).",".date("s", $ts)."),".$value."],\n";
                    } else {
                        $lastValue = $row->getValue();
                    }
                }
                if (($n == 0) && ($lastValue != null)) {
                    $ts = $minTime;
                    $listeHistoGraphe2 .= "[Date.UTC(".date("Y", $ts).",".(date("m", $ts)-1).","
                    .date("d", $ts).",".date("H", $ts).",".date("i", $ts).",".date("s", $ts)."),".$lastValue."],\n";
                }
                $ts = time();
                $value = $cmd->execCmd();
                $listeHistoGraphe2 .= "[Date.UTC(".date("Y", $ts).",".(date("m", $ts)-1).","
                .date("d", $ts).",".date("H", $ts).",".date("i", $ts).",".date("s", $ts)."),".$value."],\n";
                $unite = $cmd->getUnite();
            }
            $replace["#listeHistoGraphe2#"] = $listeHistoGraphe2;
            $replace["#unite#"] = $unite;

            $replace["#idCmdGraphe1#"] = str_replace('#', '', $cmdGraphe1);
            $replace["#idCmdGraphe2#"] = str_replace('#', '', $cmdGraphe2);
        }

        // Widget température
          //
        if ($typeDesign === "widget_temp") {
            $temperature = 9999;
            $humidite = 9999;

            $cmdTemperature = $this->getConfiguration('tempInfoTemperature');
            $obj = cmd::byId(str_replace('#', '', $cmdTemperature));
            if (is_object($obj)) {
                $replace["#valTemperature#"] = $obj->execCmd();
                $replace["#idTemperature#"] = $obj->getId();
                $replace["#vdTemperature#"] = $obj->getValueDate();
                $replace["#cdTemperature#"] = $obj->getCollectDate();
                $replace["#idTemperature#"] = $obj->getId();
            } else {
                $replace["#valTemperature#"] = 9999;
                $replace["#idTemperature#"] = -1;
            }

            $cmdHumidite = $this->getConfiguration('tempInfoHumidite');
            $obj = cmd::byId(str_replace('#', '', $cmdHumidite));
            if (is_object($obj)) {
                $replace["#valHumidite#"] = $obj->execCmd();
                $replace["#idHumidite#"] = $obj->getId();
                $replace["#vdHumidite#"] = $obj->getValueDate();
                $replace["#cdHumidite#"] = $obj->getCollectDate();
                $replace["#idHumidite#"] = $obj->getId();
            } else {
                $replace["#valHumidite#"] = 9999;
                $replace["#idHumidite#"] = -1;
            }

            if ($visuel == 'Noir') {
                $replace["#clrTitre#"] = "white";
                $replace["#clrTemperature#"] = "white";
                $replace["#clrHumidite#"] = "white";
            } elseif ($visuel == 'Blanc') {
                $replace["#clrTitre#"] = "darkgrey";
                $replace["#clrTemperature#"] = "darkgrey";
                $replace["#clrHumidite#"] = "darkgrey";
            } else {
                $replace["#clrTitre#"] = "white";
                $replace["#clrTemperature#"] = "#3c444d";
                $replace["#clrHumidite#"] = "#3c444d";
            }

            $replace["#titreWidget#"] = $this->getConfiguration('tempTitreWidget');

            $replace["#temperatureMin#"] = $this->getConfiguration('temperature_min', 0);
            $replace["#temperatureMax#"] = $this->getConfiguration('temperature_max', 100);
            $replace["#humiditeMin#"] = $this->getConfiguration('humidite_min', 0);
            $replace["#humiditeMax#"] = $this->getConfiguration('humidite_max', 100);
        }

        // Widget lumière
          //
        if ($typeDesign === "widget_lumi") {
            $cmdStatut = $this->getConfiguration('lumiInfoStatut');
            $obj = cmd::byId(str_replace('#', '', $cmdStatut));
            if (is_object($obj)) {
                $replace["#valStatut#"] = $obj->execCmd();
                $replace["#idStatut#"] = $obj->getId();
                $replace["#vdStatut#"] = $obj->getValueDate();
                $replace["#cdStatut#"] = $obj->getCollectDate();
                $replace["#idStatut#"] = $obj->getId();
            } else {
                $replace["#valStatut#"] = 9999;
                $replace["#idStatut#"] = -1;
            }

            if ($visuel == 'Noir') {
                $replace["#clrTitre#"] = "white";
                $replace["#clrTexte#"] = "white";
            } elseif ($visuel == 'Blanc') {
                $replace["#clrTitre#"] = "darkgrey";
                $replace["#clrTemperature#"] = "darkgrey";
                $replace["#clrHumidite#"] = "darkgrey";
            } else {
                $replace["#clrTitre#"] = "white";
                $replace["#clrTexte#"] = "#3c444d";
            }

            $replace["#titreWidget#"] = $this->getConfiguration('lumiTitreWidget');

            $obj = $this->getCmd(null, 'allumer');
            $replace["#idAllumer#"] = $obj->getId();

            $obj = $this->getCmd(null, 'eteindre');
            $replace["#idEteindre#"] = $obj->getId();
        }

        return template_replace($replace, getTemplate('core', $version, 'Djeesign_' . $typeDesign, 'Djeesign'));
    }

    /*
     * Non obligatoire : permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>()
    {

    }
    */

    /*
     * Non obligatoire : permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>()
    {

    }
    */

    /*     * **********************Getteur Setteur*************************** */
}

class DjeesignCmd extends cmd
{
    /*     * *************************Attributs****************************** */

    /*
      public static $_widgetPossibility = array();
    */

    /*     * ***********************Methode static*************************** */

    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd()
      {
        return true;
      }
    */

    // Exécution d'une commande
      //
    public function execute($_options = array())
    {
        $eqLogic = $this->getEqLogic();

        if ($this->getLogicalId() == 'allumer') {
            $eqLogic->Allumer();
        } elseif ($this->getLogicalId() == 'eteindre') {
            $eqLogic->Eteindre();
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}
