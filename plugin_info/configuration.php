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

 require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
 include_file('core', 'authentification', 'php');
 if (!isConnect()) {
     include_file('desktop', '404', 'php');
     die();
 }
 ?>
 <form class="form-horizontal">
   <fieldset>
     <div class="form-group">
         <label class="col-sm-4 control-label">{{Anglais}}</label>
         <div class="col-lg-2">
           <input type="checkbox" data-l1key="en_US" class="form-control configKey" />
         </div>
     </div>
     <div class="form-group">
         <label class="col-sm-4 control-label">{{Allemand}}</label>
         <div class="col-lg-2">
           <input type="checkbox" data-l1key="de_DE"  class="form-control configKey" />
         </div>
     </div>
     <div class="form-group">
         <label class="col-sm-4 control-label">{{Espagnol}}</label>
         <div class="col-lg-2">
           <input type="checkbox" data-l1key="es_ES"  class="form-control configKey" />
         </div>
     </div>
     <div class="form-group">
         <label class="col-sm-4 control-label">{{Italien}}</label>
         <div class="col-lg-2">
           <input type="checkbox" data-l1key="it_IT"  class="form-control configKey" />
         </div>
     </div>
     <div class="form-group">
         <label class="col-sm-4 control-label">{{Portugais}}</label>
         <div class="col-lg-2">
           <input type="checkbox" data-l1key="pt_PT"  class="form-control configKey" />
         </div>
     </div>
     <div class="form-group">
         <label class="col-sm-4 control-label">{{Russe}}</label>
         <div class="col-lg-2">
           <input type="checkbox" data-l1key="ru_RU"  class="form-control configKey" />
         </div>
     </div>
     <div class="form-group">
         <label class="col-sm-4 control-label">{{Japonnais}}</label>
         <div class="col-lg-2">
           <input type="checkbox" data-l1key="ja_JP"  class="form-control configKey" />
         </div>
     </div>
     <div class="form-group">
         <label class="col-sm-4 control-label">{{Indonésien}}</label>
         <div class="col-lg-2">
           <input type="checkbox" data-l1key="id_ID"  class="form-control configKey" />
         </div>
     </div>
     <div class="form-group">
         <label class="col-sm-4 control-label">{{Turc}}</label>
         <div class="col-lg-2">
           <input type="checkbox" data-l1key="tr"  class="form-control configKey" />
         </div>
     </div>
   </fieldset>
</form>
<?php include_file('desktop', 'kranslate', 'js', 'kranslate');?>
