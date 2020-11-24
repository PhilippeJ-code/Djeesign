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
          if (($typeDesign !== "cadre") && ($typeDesign !== "menu")) {
              $typeDesign = "cadre";
          }
    
          $replace["#visuel#"] = $visuel;
    
          // Cadre
          //
          $titre = $this->getConfiguration('titre');
          $icone = $this->getConfiguration('icone');
    
          $replace["#titre#"] = $titre;
          $replace["#icone#"] = $icone;

          // Menu
          //
          $listeIds = $this->getConfiguration('listeIds');
          $listeIcones = $this->getConfiguration('listeIcones');
 
          $listeNoms = '';
          $ids = explode(';', $listeIds);

          foreach ($ids as $id) {
              if ($listeNoms != "") {
                  $listeNoms .= ";";
              }

              $ph = planHeader::byId($id);
              if ($ph != null) {
                  $listeNoms .= $ph->getName();
              } else {
                  $listeNoms .= "Id Inconnu";
              }
          }
      
          $replace["#listeIds#"] = $listeIds;
          $replace["#listeIcones#"] = $listeIcones;
          $replace["#listeNoms#"] = $listeNoms;

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
