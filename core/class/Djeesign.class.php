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
          if (($typeDesign !== "cadre")  && ($typeDesign !== "menu") && ($typeDesign !== "mobile") && ($typeDesign !== "graphe")) {
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
      }

      /*     * **********************Getteur Setteur*************************** */
  }
