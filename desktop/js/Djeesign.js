
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

// Event Add Action
//
function eventAddAction()
{
    addAction({}, this.getAttribute('data-type'));
}

els = document.querySelectorAll(".addAction");
els.forEach(function(el) {
    el.removeEventListener('click', eventAddAction);
    el.addEventListener('click', eventAddAction);
});

// Event Select Action
//
function eventSelectAction()
{
    var type = this.getAttribute('data-type');
    var el = this.closest('.' + type).querySelector('.expressionAttr[data-l1key=cmd]');

    jeedom.cmd.getSelectModal({ cmd: { type: 'action' } }, function (result) {
        el.value = result.human;
        jeedom.cmd.displayActionOption(el.value, '', function (html) {
            el.closest('.' + type).querySelector('.actionOptions').innerHTML= html;
            let scripts = el.closest('.' + type).querySelector('.actionOptions').querySelectorAll('script');
            scripts.forEach(script => {
              let newScript = document.createElement('script');
              newScript.text = script.text;
              document.body.appendChild(newScript);
              script.remove();
            });
        });
   
    });
}

// Event Remove Action
//
function eventRemoveAction()
{
    var type = this.getAttribute('data-type');
    this.removeEventListener('click', eventRemoveAction);
    this.removeEventListener('click', eventSelectAction);
    this.closest('.' + type).remove();
}

// Event Select Info
//
function eventSelectInfo () {
    var el = this.closest('.form-group').querySelector('.eqLogicAttr');
    jeedom.cmd.getSelectModal({ cmd: { type: 'info' } }, function (result) {
        el.value = result.human;
    });
}

els = document.querySelectorAll(".listEquipementInfo");
els.forEach(function(el) {
    el.removeEventListener('click', eventSelectInfo);
    el.addEventListener('click', eventSelectInfo);
});

/*
// Event Select Info
//
function eventSelectInfo () {
    var el = this;
    jeedom.cmd.getSelectModal({ cmd: { type: 'info' } }, function (result) {
        var calcul = el.closest('div').querySelector('.eqLogicAttr[data-l1key=configuration][data-l2key=' + el.dataset.input + ']');
        calcul.innerHTML = result.human;
    });
}

els = document.querySelectorAll(".listEquipementInfo");
els.forEach(function(el) {
    el.removeEventListener('click', eventSelectInfo);
    el.addEventListener('click', eventSelectInfo);
});
*/
// Icone
//
function selectIcon() {
    jeedomUtils.chooseIcon(function (_icon) {
        var fin = _icon.lastIndexOf("'");
        var debut = _icon.lastIndexOf('/');
        if (fin > debut && debut != -1 && fin != -1) {
            _icon = _icon.substr(debut + 1, fin - debut - 1);
            document.getElementById('idIconeTuile').value = _icon;
        } else {
            var debut = _icon.lastIndexOf(' ');
            if (fin > debut && debut != -1 && fin != -1) {
                _icon = _icon.substr(debut + 1, fin - debut - 1);
                document.getElementById('idIconeTuile').value = _icon;
            }  
        }
    }, { icon: false, img: true })
    modifyWithoutSave = true
}

el = document.getElementById('idChoisirIcone');
el.removeEventListener('click', selectIcon);
el.addEventListener('click', selectIcon);

// Icones
//
function selectIcons() {
    jeedomUtils.chooseIcon(function (_icon) {
        var fin = _icon.lastIndexOf("'");
        var debut = _icon.lastIndexOf('/');
        if (fin > debut && debut != -1 && fin != -1) {
            _icon = _icon.substr(debut + 1, fin - debut - 1);
            var oldIcon = document.getElementById('idIconesMenu').value.trim();
            if (oldIcon.length > 0) {
                _icon = oldIcon + ';' + _icon;
            }
            document.getElementById('idIconesMenu').value = _icon;
        } else {
            var debut = _icon.lastIndexOf(' ');
            if (fin > debut && debut != -1 && fin != -1) {
                _icon = _icon.substr(debut + 1, fin - debut - 1);
                var oldIcon = document.getElementById('idIconesMenu').value.trim();
                if (oldIcon.length > 0) {
                    _icon = oldIcon + ';' + _icon;
                }
                document.getElementById('idIconesMenu').value = _icon;
            }
        }
    }, { icon: false, img: true })
    modifyWithoutSave = true
}

el = document.getElementById('idChoisirIcones');
el.removeEventListener('click', selectIcons);
el.addEventListener('click', selectIcons);

// Change design type
//
function eventChangeDesignType() {
    changeDesignType(this.value);
}

el = document.querySelector('.eqLogicAttr[data-l2key=typeDesign]');
el.removeEventListener('change', eventChangeDesignType);
el.addEventListener('change', eventChangeDesignType);

// Add command
//
function addCmdToTable(_cmd) {

    if (document.getElementById('table_cmd') == null) return
    if (document.querySelector('#table_cmd thead') == null) {
      table = '<thead>'
      table += '<tr>'
      table += '<th>{{Id}}</th>'
      table += '<th>{{Nom}}</th>'
      table += '<th>{{Type}}</th>'
      table += '<th>{{Paramètres}}</th>'
      table += '<th>{{Etat}}</th>'
      table += '<th>{{Action}}</th>'
      table += '</tr>'
      table += '</thead>'
      table += '<tbody>'
      table += '</tbody>'
      document.getElementById('table_cmd').insertAdjacentHTML('beforeend', table)
    }
    if (!isset(_cmd)) {
      var _cmd = { configuration: {} }
    }
    if (!isset(_cmd.configuration)) {
      _cmd.configuration = {}
    }
    var tr = ''
    tr += '<td style="min-width:50px;width:70px;">'
    tr += '<span class="cmdAttr" data-l1key="id"></span>'
    tr += '</td>'
    tr += '<td>'
    tr += '<div class="row">'
    tr += '<div class="col-sm-6">'
    tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icône</a>'
    tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>'
    tr += '</div>'
    tr += '<div class="col-sm-6">'
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">'
    tr += '</div>'
    tr += '</div>'
    tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="{{La valeur de la commande vaut par défaut la commande}}">'
    tr += '<option value="">Aucune</option>'
    tr += '</select>'
    tr += '</td>'
    tr += '<td>'
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>'
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>'
    tr += '</td>'
    tr += '<td>'
    tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;display:inline-block;">'
    tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;display:inline-block;">'
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" placeholder="{{Unité}}" title="{{Unité}}" style="width:30%;display:inline-block;margin-left:2px;">'
    tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="listValue" placeholder="{{Liste de valeur|texte séparé par ;}}" title="{{Liste}}">'
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> '
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> '
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> '
    tr += '</td>'
    tr += '<td>'
    tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>'
    tr += '</td>'
    tr += '<td>'
    if (is_numeric(_cmd.id)) {
      tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> '
      tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>'
    }
    tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>'
    tr += '</td>'

    let newRow = document.createElement('tr')
    newRow.innerHTML = tr
    newRow.addClass('cmd')
    newRow.setAttribute('data-cmd_id', init(_cmd.id))
    document.getElementById('table_cmd').querySelector('tbody').appendChild(newRow)

    jeedom.eqLogic.buildSelectCmd({
      id: document.querySelector('.eqLogicAttr[data-l1key="id"]').jeeValue(),
      filter: { type: 'info' },
      error: function(error) {
        jeedomUtils.showAlert({ message: error.message, level: 'danger' })
      },
        success: function(result) {
            newRow.querySelector('.cmdAttr[data-l1key="value"]').insertAdjacentHTML('beforeend', result)
            newRow.setJeeValues(_cmd, '.cmdAttr')
            jeedom.cmd.changeType(newRow, init(_cmd.subType))
        }
    })
}

// Add Action
//
function addAction(_action, _type) {

    if (!isset(_action)) {
      _action = {}
    }
    if (!isset(_action.options)) {
      _action.options = {}
    }
  
    var div = '<div class="' + _type + '">'
    div += '<div class="form-group">';
    div += '<label class="col-sm-1 control-label">Action</label>';  
    div += '<div class="col-sm-3">';
    div += '<div class="input-group">';
    div += '<span class="input-group-btn">';
    div += '<a class="btn btn-default bt_removeAction roundedLeft" data-type="' + _type + '"><i class="fas fa-minus-circle"></i></a>';
    div += '</span>';
    div += '<input class="expressionAttr form-control cmdAction" data-l1key="cmd" data-type="' + _type + '" />';
    div += '<span class="input-group-btn">';
    div += '<a class="btn btn-default listCmdAction roundedRight" data-type="' + _type + '"><i class="fas fa-list-alt"></i></a>';
    div += '</span>';
    div += '</div>';
    div += '</div>';
    div += '<div class="col-sm-4 actionOptions">';
    div += jeedom.cmd.displayActionOption(init(_action.cmd, ''), _action.options);
    div += '</div>';
    div += '</div>';
    div += '</div>';
    
    document.getElementById('div_' + _type).insertAdjacentHTML('beforeend', div);
    let scripts = document.getElementById('div_' + _type).querySelectorAll('script');
    scripts.forEach(script => {
      let newScript = document.createElement('script');
      newScript.text = script.text;
      document.body.appendChild(newScript);
      script.remove();
    });
  
    var newRow = document.querySelectorAll('#div_' + _type + ' .' + _type + '').last();
  
    newRow.setJeeValues(_action, '.expressionAttr');
  
    let el = newRow.querySelector(".bt_removeAction");
    el.addEventListener('click', eventRemoveAction);
  
    el = newRow.querySelector(".listCmdAction");
    el.addEventListener('click', eventSelectAction); 
    
}
    
// Save 
//
function saveEqLogic(_eqLogic) {

    if (!isset(_eqLogic.configuration)) {
        _eqLogic.configuration = {};
    }

    _eqLogic.configuration.csg_on_conf = document.querySelectorAll('#div_csg_on .csg_on').getJeeValues('.expressionAttr');
    _eqLogic.configuration.csg_off_conf = document.querySelectorAll('#div_csg_off .csg_off').getJeeValues('.expressionAttr');

    return _eqLogic;
}

// Change design type
//
function changeDesignType(_type) {

  document.getElementById("typeDesignCadre").style.display = "none";
  document.getElementById("typeDesignMenu").style.display = "none";
  document.getElementById("typeDesignGraphe").style.display = "none";
  document.getElementById("typeDesignWidgetTemp").style.display = "none";
  document.getElementById("typeDesignWidgetLumi").style.display = "none";
  document.getElementById("typeDesignWidgetMeteo").style.display = "none";

  if (_type !== null) {
    if (_type === "menu" || _type === "mobile") {
      document.getElementById("typeDesignMenu").style.display = "block";
    } else if (_type === "graphe") {
      document.getElementById("typeDesignGraphe").style.display = "block";
    } else if (_type === "widget_temp") {
      document.getElementById("typeDesignWidgetTemp").style.display = "block";
    } else if (_type === "widget_lumi") {
        document.getElementById("typeDesignWidgetLumi").style.display = "block";
    } else if (_type === "widget_meteo") {
        document.getElementById("typeDesignWidgetMeteo").style.display = "block";
        } else {
        document.getElementById("typeDesignCadre").style.display = "block";
      }
  } else {
    document.getElementById("typeDesignCadre").style.display = "block";
  }
}

// Print
//
function printEqLogic(_eqLogic) {

    document.getElementById('div_csg_on').innerHTML = '';
    document.getElementById('div_csg_off').innerHTML = '';

    if (isset(_eqLogic.configuration)) {
         if (isset(_eqLogic.configuration.csg_on_conf)) {
            for (var i in _eqLogic.configuration.csg_on_conf) {
                addAction(_eqLogic.configuration.csg_on_conf[i], 'csg_on');
            }
        }
        if (isset(_eqLogic.configuration.csg_off_conf)) {
            for (var i in _eqLogic.configuration.csg_off_conf) {
                addAction(_eqLogic.configuration.csg_off_conf[i], 'csg_off');
            }
        }
        changeDesignType(_eqLogic.configuration.typeDesign);
    }
}
