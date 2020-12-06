<?php
  if (!isConnect('admin')) {
      throw new Exception('{{401 - Accès non autorisé}}');
  }

  $plugin = plugin::byId('Djeesign');
  sendVarToJS('eqType', $plugin->getId());
  $eqLogics = eqLogic::byType($plugin->getId());

?>

<div class="row row-overflow">
  <div class="col-xs-12 eqLogicThumbnailDisplay">
    <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
    <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction logoPrimary" data-action="add">
        <i class="fas fa-plus-circle" style="color:#36b0b6;"></i>
        <br>
        <span>{{Ajouter}}</span>
      </div>
      <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
        <i class="fas fa-wrench"></i>
        <br>
        <span>{{Configuration}}</span>
      </div>
    </div>
    <legend><i class="fas fa-table"></i> {{Mes équipements}}</legend>
    <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
    <div class="eqLogicThumbnailContainer">
      <?php

        // Affiche la liste des équipements
        //
        foreach ($eqLogics as $eqLogic) {
            $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
            echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
            echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
            echo '<br>';
            echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
            echo '</div>';
        }
      ?>
    </div>
  </div>

  <div class="col-xs-12 eqLogic" style="display: none;">
    <div class="input-group pull-right" style="display:inline-flex">
      <span class="input-group-btn">
        <a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i>
          {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i
            class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction"
          data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a
          class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i
            class="fas fa-minus-circle"></i> {{Supprimer}}</a>
      </span>
    </div>
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab"
          data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
      <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i
            class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
      <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i
            class="fa fa-list-alt"></i> {{Commandes}}</a></li>
    </ul>
    <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
      <div role="tabpanel" class="tab-pane active" id="eqlogictab">
        <br />
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-wrench"></i> {{Général}}</legend>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
              <div class="col-sm-3">
                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                <input type="text" class="eqLogicAttr form-control" data-l1key="name"
                  placeholder="{{Nom de l'équipement}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Objet parent}}</label>
              <div class="col-sm-3">
                <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                  <option value="">{{Aucun}}</option>
                  <?php
                    foreach (jeeObject::all() as $object) {
                        echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                    }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Catégorie}}</label>
              <div class="col-sm-9">
                <?php
                  foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                      echo '<label class="checkbox-inline">';
                      echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                      echo '</label>';
                  }
                ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label"></label>
              <div class="col-sm-9">
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable"
                    checked />{{Activer}}</label>
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible"
                    checked />{{Visible}}</label>
              </div>
            </div>

            <!--
              Mes options
                Choix du type de design Cadre ou Menu
            -->
            <legend><i class="fas fa-cogs"></i> {{Paramètres}}</legend>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Type}}</label>
              <div class="col-sm-3">
                <select required class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="typeDesign">
                  <option value="" disabled selected>{{Sélectionnez un type de design}}</option>
                  <option value="cadre">Tuile( défaut )</option>
                  <option value="menu">Menu</option>
                  <option value="mobile">Menu Mobile</option>
                  <option value="graphe">Graphe</option>
                </select>
              </div>
            </div>

            <!--
              Options du cadre
                Titre et icone
            -->
            <div id="typeDesignCadre">
              <div class="form-group">
                <label class="col-sm-3 control-label">{{Titre}}</label>
                <div class="col-sm-3">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="titre"
                    placeholder="Titre de la tuile" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">{{Icone}}</label>
                <div class="col-sm-3">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="icone"
                    placeholder="Icone de la tuile" />
                </div>
              </div>

            </div>

            <!--
              Options du menu
                Liste Ids Design et liste icones
            -->
            <div id="typeDesignMenu">

              <div class="form-group">
                <label class="col-sm-3 control-label">{{Liste Ids}}</label>
                <div class="col-sm-3">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="listeIds"
                    placeholder="Ids des designs séparés par des ;" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">{{Liste Noms}}</label>
                <div class="col-sm-3">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="listeNoms"
                    placeholder="Noms des designs séparés par des ;" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">{{Liste Icones}}</label>
                <div class="col-sm-3">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="configuration"
                    data-l2key="listeIcones" placeholder="Nom des icones séparés par des ;" />
                </div>
              </div>

            </div>

            <!--
              Options du graphe
                Id de la commande info
            -->
            <div id="typeDesignGraphe">
              <div class="form-group">
                <label class="col-md-3 control-label">{{Commande info du graphe - 1}}</label>
                <div class="col-md-6 input-group">
                  <input class="eqLogicAttr form-control input-sm" data-l1key="configuration"
                    data-l2key="cmdGraphe1"></input>
                  <a class="btn btn-default listEquipementInfo cursor btn-sm input-group-addon"
                    data-input="cmdGraphe1"><i class="fas fa-list-alt"></i></a>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">{{Commande info du graphe - 2}}</label>
                <div class="col-md-6 input-group">
                  <input class="eqLogicAttr form-control input-sm" data-l1key="configuration"
                    data-l2key="cmdGraphe2"></input>
                  <a class="btn btn-default listEquipementInfo cursor btn-sm input-group-addon"
                    data-input="cmdGraphe2"><i class="fas fa-list-alt"></i></a>
                </div>
              </div>
            </div>

          </fieldset>
        </form>
      </div>
      <div role="tabpanel" class="tab-pane" id="commandtab">
        <a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i
            class="fa fa-plus-circle"></i> {{Commandes}}</a><br /><br />
        <table id="table_cmd" class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th>{{Nom}}</th>
              <th>{{Type}}</th>
              <th>{{Action}}</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, nom_du_plugin) -->
<?php include_file('desktop', 'Djeesign', 'js', 'Djeesign');?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');
