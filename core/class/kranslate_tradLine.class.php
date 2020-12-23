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

  class kranslate_tradLine {
    private $id;
    private $plugin;
    private $lang;
    private $file_path;
    private $from;
    private $to;
    private $unused;

    public static function byId($_id) {
      $values = array(
        'id' => $_id,
      );
      $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
      FROM `'. __CLASS__ . '`
      WHERE id=:id';
      return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byPlugin($_plugin) {
      $values = array(
        'plugin' => $_plugin,
      );
      $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
      FROM `'. __CLASS__ . '`
      WHERE plugin=:plugin';
      return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function all() {
      $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
      FROM `'.__CLASS__.'`';
      return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public function save() {
      DB::save($this);
      self::setCacheTrad('allTrad',self::all());
      return;
    }

    public function remove() {
      return DB::remove($this);
    }

  	public function getCache($_key = '', $_default = '') {
  		$cache = cache::byKey('eqLogicCacheAttr' . $this->getId())->getValue();
  		return utils::getJsonAttr($cache, $_key, $_default);
  	}

    public function setCache($_key, $_value = null) {
      cache::set('eqLogicCacheAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('eqLogicCacheAttr' . $this->getId())->getValue(), $_key, $_value));
    }

    public static function getCacheTrad($_key = '', $_default = '') {
      $cache = cache::byKey('KranslateTradLines')->getValue();
      return utils::getJsonAttr($cache, $_key, $_default);
    }

    public static function setCacheTrad($_key, $_value = null) {
      cache::set('KranslateTradLines', utils::setJsonAttr(cache::byKey('KranslateTradLines')->getValue(), $_key, $_value));
    }

    public function getId() {
      if (is_null($this->id))
      {
        $sql = "SELECT * FROM `kranslate_tradLine` WHERE `plugin` = :plugin AND `lang` = :lang AND `file_path` = :file_path AND `from` = :from";
        $values = array(
          'plugin' => $this->plugin,
          'lang' => $this->lang,
          'file_path' => $this->file_path,
          'from' => $this->from
        );
        $result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_ASSOC, null);
        if (!is_null($result))
          $this->setId($result['id']);
      }
      return $this->id;
    }

    public function setId($id) {
      $this->id = $id;
      return $this;
    }

    public function getPlugin() {
      return $this->plugin;
    }

    public function setPlugin($plugin) {
      $this->plugin = $plugin;
      return $this;
    }

    public function getLang() {
      return $this->lang;
    }

    public function setLang($lang) {
      $this->lang = $lang;
      return $this;
    }

    public function getFilePath() {
      return $this->file_path;
    }

    public function setFilePath($file_path) {
      $this->file_path = $file_path;
      return $this;
    }

    public function getFrom() {
      return $this->from;
    }

    public function setFrom($from) {
      $this->from = $from;
      return $this;
    }

    public function getTo() {
      return $this->to;
    }

    public function setTo($to) {
      $this->to = $to;
      return $this;
    }

    public function getUnused() {
      return $this->unused;
    }

    public function setUnused($unused) {
      $this->unused = $unused;
      return $this;
    }
  }

  ?>
