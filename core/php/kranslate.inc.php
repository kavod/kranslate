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

define('KRANSLATE_VERSION','0.1');
$kranslate_conf = json_decode(file_get_contents(__DIR__.'/../config/lang.json'),true);
// $kranslate_conf = array(
//   array('code' =>'en_US', 'label' => __("Anglais",__FILE__),  'short' => 'EN'),
//   array('code' =>'de_DE', 'label' => __("Allemand",__FILE__), 'short' => 'DE'),
//   array('code' =>'es_ES', 'label' => __("Espagnol",__FILE__), 'short' => 'ES'),
//   array('code' =>'it_IT', 'label' => __("Italien",__FILE__),  'short' => 'IT'),
//   array('code' =>'pt_PT', 'label' => __("Portugais",__FILE__),'short' => 'PT'),
//   array('code' =>'ru_RU', 'label' => __("Russe",__FILE__),    'short' => 'RU'),
//   array('code' =>'ja_JP', 'label' => __("Japonais",__FILE__), 'short' => 'JP'),
//   array('code' =>'id_ID', 'label' => __("Indonésien",__FILE__),'short' => 'ID'),
//   array('code' =>'tr',    'label' => __("Turc",__FILE__),     'short' => 'TR')
// );
?>
