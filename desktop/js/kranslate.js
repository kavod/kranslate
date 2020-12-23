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
 var langConf = [];

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

  function selectTrad(plugin)
  {
     $.ajax({// fonction permettant de faire de l'ajax
       type: "POST", // methode de transmission des données au fichier php
       url: "plugins/kranslate/core/ajax/kranslate.ajax.php", // url du fichier php
       data: {
           action: "selectTrad",
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
        deleteAllTrad();
        file_path = '';
        data.result.forEach(function(sentence) {
          if (sentence.file_path!=file_path)
          {
            addFilePath(sentence.lang,sentence.file_path);
            file_path = sentence.file_path;
          }
          addTrad(sentence);
        });
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

  $('#btScan').on('click', function () {
  plugin = $(".eqLogicAttr[data-l1key='logicalId']").val();
   $.ajax({// fonction permettant de faire de l'ajax
       type: "POST", // methode de transmission des données au fichier php
       url: "plugins/kranslate/core/ajax/kranslate.ajax.php", // url du fichier php
       data: {
           action: "scan",
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
         selectTrad(plugin);
       }
     });
  });

  $('#btDelete').on('click', function () {
   $.ajax({// fonction permettant de faire de l'ajax
       type: "POST", // methode de transmission des données au fichier php
       url: "plugins/kranslate/core/ajax/kranslate.ajax.php", // url du fichier php
       data: {
           action: "deleteTrad",
           plugin: $(".eqLogicAttr[data-l1key='logicalId']").val()
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
         deleteAllTrad();
       }
     });
  });

  $('#btSave').on('click',function() {
    var trad = {};
    langConf.forEach( function( val ) {
      trad[val.code] = $('#table_trad_'+val.code+' .kTrad').getValues('.tradAttr');
    });
   $.ajax({// fonction permettant de faire de l'ajax
       type: "POST", // methode de transmission des données au fichier php
       url: "plugins/kranslate/core/ajax/kranslate.ajax.php", // url du fichier php
       data: {
           action: "saveTrad",
           plugin: $(".eqLogicAttr[data-l1key='logicalId']").val(),
           trad: json_encode(trad)
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
         $('.eqLogicAction[data-action=save]').click();
       }
     });
  });

  function addFilePath(lang,file_path)
  {
    var line = '<div class="form-group">';
    line += '<span class="col-lg-12 kTradFilePath">';
    line += init(file_path);
    line += '</span>';
    line += '</div>';
    $('#table_trad_'+ lang + ' fieldset').append(line);
  }

  function addTrad(tradline) {

    var line = '<div class="form-group kTrad">';
    line += '<span class="col-md-2 col-lg-1 kTradLine">';
    line += init(tradline.id);
    line += '<input class="tradAttr form-control kTradHide" data-l1key="id">';
    line += '</span>';
    // line += '<label class="col-lg-3">';
    // line += init(tradline.file_path);
    // line += '</label>';
    line += '<input class="tradAttr form-control kTradHide" data-l1key="file_path">';
    line += '<span class="col-md-10 col-lg-5 kTradLine">';
    line += init(tradline.from);
    line += '</span>';
    line += '<input class="tradAttr form-control input-sm col-md-10 col-lg-5" data-l1key="to">';
    line += '<span class="col-md-2 col-lg-1 kTradLine">';
    line += (init(tradline.unused)==0) ? 'Non' : 'Oui';
    line += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
    line += '</span>';
    line += '</div>';
    $('#table_trad_'+ tradline.lang + ' fieldset').append(line);
    $('#table_trad_'+ tradline.lang + ' fieldset>div:last').setValues(tradline, '.tradAttr');
  }

  function deleteAllTrad()
  {
     langConf.forEach( function( val ) {
       $('#table_trad_'+ val.code + ' fieldset').empty();
     });
  }

  $(document).ready(function() {
    $.ajax({// fonction permettant de faire de l'ajax
      type: "POST", // methode de transmission des données au fichier php
      url: "plugins/kranslate/core/ajax/kranslate.ajax.php", // url du fichier php
      data: {
          action: "langConf"
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
        langConf = data.result;
      }
    });

    $(".eqLogicAttr[data-l1key='logicalId']").change(function(){
      if ($(this).val()!='')
      {
        selectTrad($(this).val());
      }
    });
  });
