
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

$("#table_cmd").sortable({ axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true });
/*
 * Fonction permettant l'affichage des commandes dans l'équipement
 */
function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = { configuration: {} };
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">';
    tr += '</td>';
    tr += '<td>';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}

$(document).ready(function () {

    $(".eqLogicAttr[data-l2key=typeDesign]").change(function () {
        if ($(this).value() !== null) {
            if (($(this).value() === "menu") || ($(this).value() === "mobile")) {
                $("#typeDesignCadre").css("display", "none");
                $("#typeDesignMenu").css("display", "block");
                $("#typeDesignGraphe").css("display", "none");
            }
            else if ($(this).value() === "graphe") {
                $("#typeDesignCadre").css("display", "none");
                $("#typeDesignMenu").css("display", "none");
                $("#typeDesignGraphe").css("display", "block");
            }
            else {
                $("#typeDesignCadre").css("display", "block");
                $("#typeDesignMenu").css("display", "none");
                $("#typeDesignGraphe").css("display", "none");
            }
        }
        else {
            $("#typeDesignCadre").css("display", "block");
            $("#typeDesignMenu").css("display", "none");
            $("#typeDesignGraphe").css("display", "none");
        }
    });
    $(".listEquipementInfo").click(function () {
        var el = $(this);
        jeedom.cmd.getSelectModal({ cmd: { type: 'info' } }, function (result) {
            var calcul = el.closest('div').find('.eqLogicAttr[data-l1key=configuration][data-l2key=' + el.data('input') + ']');
            calcul.atCaret('insert', result.human);
        });
    });

    $('#idChoisirIcone').on('click', function () {
        chooseIcon(function (_icon) {
            var fin = _icon.lastIndexOf("'");
            var debut = _icon.lastIndexOf('/');
            if (fin > debut && debut != -1 && fin != -1) {
                _icon = _icon.substr(debut + 1, fin - debut - 1);
                $('#idIconeTuile').empty().value(_icon);
            } else {
                var debut = _icon.lastIndexOf(' ');
                if (fin > debut && debut != -1 && fin != -1) {
                    _icon = _icon.substr(debut + 1, fin - debut - 1);
                    $('#idIconeTuile').empty().value(_icon); 
                }  
            }
        }, { icon: false, img: true })
        modifyWithoutSave = true
    })

    $('#idChoisirIcones').on('click', function () {
        chooseIcon(function (_icon) {
            var fin = _icon.lastIndexOf("'");
            var debut = _icon.lastIndexOf('/');
            if (fin > debut && debut != -1 && fin != -1) {
                _icon = _icon.substr(debut + 1, fin - debut - 1);
                var oldIcon = $('#idIconesMenu').empty().val().trim();
                if (oldIcon.length > 0) {
                    _icon = oldIcon + ';' + _icon;
                }
                $('#idIconesMenu').empty().value(_icon)
            } else {
                var debut = _icon.lastIndexOf(' ');
                if (fin > debut && debut != -1 && fin != -1) {
                    _icon = _icon.substr(debut + 1, fin - debut - 1);
                    var oldIcon = $('#idIconesMenu').empty().val().trim();
                    if (oldIcon.length > 0) {
                        _icon = oldIcon + ';' + _icon;
                    }
                    $('#idIconesMenu').empty().value(_icon)
                }
            }
        }, { icon: false, img: true })
        modifyWithoutSave = true
    })

});