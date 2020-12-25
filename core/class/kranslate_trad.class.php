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

  require_once(__DIR__  . '/../../../../core/php/core.inc.php');
  require_once(__DIR__  . '/kranslate_tradLine.class.php');

  class kranslate_trad {
    private $plugin;
    private $lines;

    public static function all($plugin) {
      $trad = new kranslate_trad();
      $trad->getPlugin($plugin);
      $trad->addLines(kranslate_tradLine::byPlugin($plugin));
    }

    public function save() {
      foreach($this->lines as $line)
      {
        $line->save();
      }
      return;
    }

    public function remove() {
      $res = true;
      foreach($lines as $line)
      {
        $res = $res && $line->remove();
      }
      return $res;
    }

  	public function getCache($_key = '', $_default = '') {
      $res = array();
      foreach($lines as $line)
      {
        $res[] = $line->getCache($_key, $_default);
      }
      return $res;
  	}

    public function setCache($_key, $_value = null) {
      foreach($lines as $line)
      {
        $line->setCache($_key, $_value);
      }
    }

    public static function getCacheTrad($_key = '', $_default = '') {
      return kranslate_tradLine::getCacheTrad($_key, $_default);
    }

    public static function setCacheTrad($_key, $_value = null) {
      return kranslate_tradLine::setCacheTrad($_key, $_value);
    }

    public function getPlugin() {
      return $this->plugin;
    }

    public function setPlugin($plugin) {
      $this->plugin = $plugin;
      return $this;
    }

    public function addLines($lines)
    {
      $this->lines += $lines;
    }

    public function loadFromArray($lang,$array,$erase=false,$unused=null)
    {
      foreach($array as $file_path => $lines)
      {
        foreach($lines as $from => $to)
        {
          $obj = new kranslate_tradLine();
          $obj->setPlugin($this->getPlugin());
          $obj->setLang($lang);
          $obj->setFilePath($file_path);
          $obj->setFrom($from);
          $id = $obj->getId();
          if (is_null($id) || $erase)
          {
            $obj->setTo($to);
            if (!is_null($unused))
              $obj->setUnused($unused);
          } else {
            $obj = kranslate_tradLine::byId($id);
          }
          $this->lines[] = $obj;
        }
      }
    }

    public function toArray($lang)
    {
      $result = array();
      foreach($this->lines as $obj)
      {
        if ($obj->getLang()== $lang)
        {
          if (!array_key_exists($obj->getFilePath(),$result))
            $result[$obj->getFilePath()] = array();
          $result[$obj->getFilePath()][$obj->getFrom()] = $obj->getTo();
        }
      }
      return $result;
    }

    public function toJson($lang)
    {
      return json_encode($this->toArray($lang));
    }

    public function toI18nFile($lang)
    {
      $i18n_dirpath = __DIR__ . '/../../resources/'.$this->getPlugin();
      if (!file_exists($i18n_dirpath))
        mkdir($i18n_dirpath);
      $i18n_filepath = $i18n_dirpath .'/'.$lang.'.json'
      $fp = fopen($i18n_filepath, 'w');
      fwrite($fp, $this->toJson($lang));
      fclose($fp);
    }
  }

  ?>
