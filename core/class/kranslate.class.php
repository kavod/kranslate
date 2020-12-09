<?php

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

error_reporting(-1);
ini_set('display_errors', 'On');

 require_once(__DIR__  . '/../../../../core/php/core.inc.php');
 require_once(__DIR__  . '/../php/kranslate.inc.php');


  class kranslate extends eqLogic {
    public static function getPluginList()
    {
      $plugins = plugin::listPlugin($_activateOnly = true, $_orderByCaterogy = false, $_translate = true, $_nameOnly = true);
      log::add(__CLASS__,'debug',"Plugin List: ".print_r($plugins,true));
      return $plugins;
    }

    public static function addPlugin($plugin)
    {
      $eqLogicalId = $plugin;

      $plugin_data = plugin::byId($eqLogicalId);

      $eqLogic = kkasa::byLogicalId($eqLogicalId, __CLASS__);
      if (!is_object($eqLogic)) {
        $eqLogic = new self();
        $eqLogic->setLogicalId($eqLogicalId);
        $eqLogic->setName($plugin_data->getName());
        $eqLogic->setConfiguration('author', $plugin_data->getAuthor());
				$eqLogic->setEqType_name(__CLASS__);
				$eqLogic->setIsVisible(0);
				$eqLogic->setIsEnable(1);
				$eqLogic->save();
      }
    }
  }

  class kranslateCmd extends cmd {

  }
?>
