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

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
  }
  $plugin = plugin::byId('kranslate');
  sendVarToJS('eqType', $plugin->getId());
  $eqLogics = eqLogic::byType($plugin->getId());

  $debug = (intval(log::getLogLevel('kranslate')) <=100);
  ?>
  <div class="row row-overflow">
    <!-- Volet gauche -->
    <div class="col-lg-2 col-md-3 col-sm-4">
      <div class="bs-sidebar">
        <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
          <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
  <?php
    foreach ($eqLogics as $eqLogic) {
      $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
      echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity .'"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
    }
  ?>
        </ul>
      </div>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
      <!-- Boutons de gestion -->
      <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
      <div class="eqLogicThumbnailContainer">
       <div class="cursor eqLogicAction" id="btAdd" style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
         <i class="fa fa-plus" style="font-size : 6em;color:#767676;"></i>
         <br />
         <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">{{Ajouter un plugin}}</span>
       </div>
     </div>

     <!-- Liste des plugins -->
     <legend><i class="fa fa-table"></i> {{Mes plugins}}</legend>
     <div class="eqLogicThumbnailContainer">
       <?php
       foreach ($eqLogics as $eqLogic) {
       	$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
       	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
       	echo '<img src="' . $eqLogic->getImage() . '" height="105" width="95" />';
       	echo "<br>";
       	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogic->getHumanName(true, true) . '</span>';
       	echo '</div>';
       }
       ?>
     </div>
   </div>

   <!-- Vue équipement -->
   <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
     <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
     <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
     <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
     <ul class="nav nav-tabs" role="tablist">
       <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
       <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Plugin}}</a></li>
       <li role="presentation"><a href="#tradtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Traductions}}</a></li>
     </ul>
     <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
       <div role="tabpanel" class="tab-pane active" id="eqlogictab">
         <br/>
         <div class="col-lg-2">

         </div>
         <div class="col-lg-6">
           <form class="form-horizontal">
             <fieldset>
               <div class="form-group">
                   <label class="col-sm-4 control-label">{{Nom du plugin}}</label>
                   <div class="col-sm-8">
                       <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                       <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom du plugin}}"/>
                   </div>
               </div>
 							<?php
 							if (intval(log::getLogLevel('kkasa')) <=100)
 							{
 								?>
 							<div class="form-group">
 								 <label class="col-sm-4 control-label">{{Auteur}}</label>
 								 <div class="col-sm-8">
 										<input disabled class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="author"/>
 								 </div>
 							</div>
            <?php } ?>
             </fieldset>
 					</form>
 				</div>
 			</div>
 			<div role="tabpanel" class="tab-pane" id="tradtab">
        <table id="table_trad" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Français}}</th>
							<th>{{Anglais}}</th>
							<th>{{Inutilisé ?}}</th>
							<th>{{Supprimer}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
     </div>
   </div>
   <?php include_file('desktop', 'kranslate', 'js', 'kranslate');?>
