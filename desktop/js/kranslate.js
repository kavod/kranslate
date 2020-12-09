/* This file is part of Kranslate plugin for Jeedom by Kavod
 *
 * Copyright (C) 2020 Brice GRICHY
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

 function addPlugin(plugin)
 {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/kranslate/core/ajax/kranslate.ajax.php", // url du fichier php
        data: {
            action: "add",
            plugin: plugin
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
          if (data.state != 'ok') {
              $('#div_alert').showAlert({message: data.result, level: 'danger'});
              return;
          }
          $('#div_alert').showAlert({message: "{{Ajout du plugin réussi}}", level: 'success'});
          var vars = getUrlVars();
          var url = 'index.php?';
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&';
            }
          }
          url += 'saveSuccessFull=1';
          loadPage(url);
        }
    });
 }

  function choosePlugin(plugin_list)
  {
    var dialog_title = '{{Ajouter un plugin}}';
    var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
    dialog_message += '<label class="control-label" > {{Choisir un plugin :}} </label> ' +
    '<div><select name="plugin">';
    for (var plugin of plugin_list)
    {
      dialog_message += '<option value="' + plugin + '">' + plugin + '</option>';
    }
    dialog_message += '</select></div>';
    dialog_message += '</form>';
    bootbox.dialog({
      title: dialog_title,
      message: dialog_message,
      buttons: {
        "{{Annuler}}": {
          className: "btn-danger",
          callback: function () {}
        },
        success: {
          label: "{{Ajouter}}",
          className: "btn-success",
          callback: function () {
            plugin = $("select[name='plugin']").val();
            addPlugin(plugin);
          }
        }
      }
    });
  }

  $('#btAdd').on('click', function () {
   $.ajax({// fonction permettant de faire de l'ajax
       type: "POST", // methode de transmission des données au fichier php
       url: "plugins/kranslate/core/ajax/kranslate.ajax.php", // url du fichier php
       data: {
           action: "getPluginList"
       },
       dataType: 'json',
       error: function (request, status, error) {
           handleAjaxError(request, status, error);
       },
       success: function (data) { // si l'appel a bien fonctionné
         if (data.state != 'ok') {
             $('#div_alert').showAlert({message: data.result, level: 'danger'});
             return;
         }
         json = JSON.parse(data.result);
         console.debug(json);
         choosePlugin(json);
       }
     });
  });
