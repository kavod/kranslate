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
  define('KRANSLATE_ROOT_DIR','..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..');
  //mb_detect_order("ISO-8859-1,UTF-8");

  require_once(__DIR__  . '/../../../../core/php/core.inc.php');
  require_once(__DIR__  . '/../php/kranslate.inc.php');
  require_once(__DIR__  . '/kranslate_trad.class.php');


  class kranslate extends eqLogic {

    protected $_rootDir = KRANSLATE_ROOT_DIR;

    public function getDir()
    {
      $plugin = $this->getPlugin();
      return $this->_rootDir . DIRECTORY_SEPARATOR . $plugin;
    }

    public function setPlugin($plugin)
    {
      log::add(__CLASS__,'debug',"* setPlugin($plugin)");
      $this->setConfiguration('plugin',$plugin);
    }

    public function getPlugin()
    {
      log::add(__CLASS__,'debug',"* getPlugin() => ".$this->getConfiguration('plugin',''));
      return $this->getConfiguration('plugin','');
    }

    public function setRootDir($dir)
    {
      $this->_rootDir = $dir;
    }

    public static function getPluginList()
    {
      $plugins = plugin::listPlugin($_activateOnly = true, $_orderByCaterogy = false, $_translate = true, $_nameOnly = true);
      log::add(__CLASS__,'debug',"Plugin List: ".print_r($plugins,true));
      return $plugins;
    }

    public static function addPlugin($plugin)
    {
      log::add(__CLASS__,'debug',"* addPlugin($plugin)");
      $eqLogicalId = $plugin;

      $plugin_data = plugin::byId($eqLogicalId);

      $eqLogic = eqLogic::byLogicalId($eqLogicalId, __CLASS__);
      if (!is_object($eqLogic)) {
        $eqLogic = new self();
        $eqLogic->setPlugin($plugin);
        $eqLogic->setLogicalId($eqLogicalId);
        $eqLogic->setName($plugin_data->getName());
        $eqLogic->setConfiguration('author', $plugin_data->getAuthor());
				$eqLogic->setEqType_name(__CLASS__);
				$eqLogic->setIsVisible(0);
				$eqLogic->setIsEnable(1);
				$eqLogic->save();
      }
    }

    public function scanfiles($dir=null)
    {
      log::add(__CLASS__,'debug',"* scanfiles($dir)");
      $dir = (is_null($dir)) ? $this->getDir() : $dir;
      $files = scandir($dir);
      $result = array();
      foreach($files as $file_path)
      {
        if (substr($file_path,0,1)=='.')
          continue;
        $file_path = $dir.DIRECTORY_SEPARATOR.$file_path;
        if (is_dir($file_path))
        {
          $result = array_merge($result,$this->scanfiles($file_path));
        }
        if (substr($file_path,-4)=='.php' || substr($file_path,-3)=='.js')
        {
          $result[] = $file_path;
        }
      }
      return $result;
    }

    public function getSentences($file_path)
    {
      log::add(__CLASS__,'debug',"* getSentences($file_path)");
      $content = file_get_contents($file_path);
      if (preg_match_all('/\{\{([^\}]+)\}\}/',$content,$matches)!=0)
      {
        $result = array();
        foreach($matches[1] as $match)
        {
          log::add(__CLASS__,'debug',"$match found");
          //$match = utf8_encode(str_replace('\\','',$match));
          $result[$match] = $match;
        }
        return $result;
      }
      return null;
    }

    public function globalSearch()
    {
      log::add(__CLASS__,'debug',"* globalSearch");
      $files = $this->scanfiles();
      $result = array();
      foreach($files as $file_path)
      {
        $sentences = $this->getSentences($file_path);
        if (!is_null($sentences))
        {
          $file_relpath = str_replace($this->_rootDir,'plugins'.DIRECTORY_SEPARATOR.$this->getPlugin(),$file_path);
          log::add(__CLASS__,'debug',"Ajout traductions pour $file_relpath");
          $result[$file_relpath] = $sentences;
        }
      }
      return $result;
    }

    public function saveTradAll($trad)
    {
      log::add(__CLASS__,'debug',"* saveTradAll");
      $result = array();
      $plugin = $this->getConfiguration('plugin','');
      $kranslate_conf = json_decode(file_get_contents(__DIR__.'/../config/lang.json'),true);
      foreach($kranslate_conf as $lang)
      {
        log::add(__CLASS__,'debug',sprintf("Is %s activated for %s?",$lang['code'],$plugin));
        if (config::byKey($lang['code'],$_plugin=__CLASS__,$_default=-1)==1)
        {
          log::add(__CLASS__,'info',sprintf(__("Actualisation des traductions %s pour %s",__FILE__),$lang['code'],$plugin));
          $trad_obj = new kranslate_trad();
          $trad_obj->setPlugin($plugin);
          $trad_obj->loadFromArray($lang['code'],$trad,false,0);
          $trad_obj->save();
        } else {
          log::add(__CLASS__,'debug',">Nope");
        }
      }
    }

    public function saveTrad($_lang,$trad)
    {
      log::add(__CLASS__,'debug',"* saveTrad $_lang");
      $plugin = $this->getConfiguration('plugin','');
      log::add(__CLASS__,'debug',sprintf("Is %s activated for %s?",$_lang,$plugin));
      if (config::byKey($_lang,$_plugin=__CLASS__,$_default=-1)==1)
      {
        log::add(__CLASS__,'debug',sprintf(__("Actualisation des traductions %s pour %s : %s",__FILE__),$_lang,$plugin,print_r($trad,true)));
        foreach($trad as $tradline)
        {
          $trad_obj = kranslate_tradLine::byId($tradline['id']);
          log::add(__CLASS__,'debug',sprintf(__("Traduction : %s",__FILE__),$tradline['to']));
          $trad_obj->setTo($tradline['to']);
          log::add(__CLASS__,'debug',sprintf(__("Sauvegarde",__FILE__)));
          $trad_obj->save();
        }
      } else {
        log::add(__CLASS__,'debug',">Nope");
      }
    }

    public function initTrad()
    {
      log::add(__CLASS__,'debug',"* initTrad");
      $this->saveTradAll($this->globalSearch());
    }

    public function deleteTrad()
    {
      log::add(__CLASS__,'debug',"* deleteTrad");
      $sql = "DELETE FROM `kranslate_tradLine` WHERE `plugin` = :plugin";
      $values = array(
        'plugin' => $this->getPlugin()
      );
      DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public function selectTrad($lang=null)
    {
      log::add(__CLASS__,'debug',"* selectTrad");
      $sql = "SELECT * FROM `kranslate_tradLine` WHERE `plugin` = :plugin";
      $values = array(
        'plugin'  => $this->getPlugin()
      );
      return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_ASSOC, null);
    }
  }

  class kranslateCmd extends cmd {

  }
?>
