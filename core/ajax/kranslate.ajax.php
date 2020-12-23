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

 try {
   require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
   include_file('core', 'authentification', 'php');

   if (!isConnect('admin')) {
       throw new Exception(__('401 - Accès non autorisé', __FILE__));
   }

   ajax::init();

 	if (init('action') == 'getPluginList') {
    $plugins = kranslate::getPluginList();
    ajax::success(json_encode($plugins,true));
  }
  elseif(init('action') == 'add') {
    kranslate::addPlugin(init('plugin'));
    ajax::success();
  }
  elseif(init('action') == 'scan') {
    $plugin = eqLogic::byLogicalId(init('plugin'),'kranslate');
    $plugin->initTrad();
    ajax::success();
  }
  elseif(init('action') == 'deleteTrad') {
    $plugin = eqLogic::byLogicalId(init('plugin'),'kranslate');
    $plugin->deleteTrad();
    ajax::success();
  }
  elseif(init('action') == 'selectTrad') {
    $plugin = eqLogic::byLogicalId(init('plugin'),'kranslate');
    $result = $plugin->selectTrad();
    ajax::success($result);
  }
  elseif(init('action') == 'langConf') {
    $kranslate_conf = json_decode(file_get_contents(__DIR__.'/../../core/config/lang.json'),true);
    ajax::success($kranslate_conf);
  }
  elseif(init('action') == 'saveTrad') {
    $kranslate_conf = json_decode(file_get_contents(__DIR__.'/../../core/config/lang.json'),true);
    $plugin = eqLogic::byLogicalId(init('plugin'),'kranslate');
    // $lang = init('lang');
    $trad_arr = array();
    $trad_form = json_decode(init('trad'),true);
    log::add('kranslate','debug',print_r($trad_form,true));
    foreach($kranslate_conf as $lang)
    {
      $plugin->saveTrad($lang['code'],$trad_form[$lang['code']]);
    }
    ajax::success();
  }

  throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
  /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
?>
