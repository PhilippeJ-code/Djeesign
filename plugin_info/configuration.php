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
      <label class="col-lg-4 control-label">{{Visuel}}</label>
      <div class="col-lg-2">
        <select class="configKey form-control" data-l1key="visuel">

          <?php

            $path = '/var/www/html/plugins/Djeesign/core/template/dashboard/';
            $dp = opendir($path);
            $i=0;
            while ($file = readdir($dp)) {
                $len = strlen($file);
                if ($len > 13) {
                    $prf = substr($file, 0, 9);
                    if ($prf === "Djeesign_") {
                        $ext = substr($file, $len-4, 4);
                        if ($ext === '.css') {
                            $posUnderscore = strrpos($file, '_');
                            $posPoint = strrpos($file, '.');
                            if ($posUnderscore < $posPoint) {
                                $template = substr($file, $posUnderscore+1, $posPoint-$posUnderscore-1);
                                $ListTemplates[$i]=$template;
                                $i++;
                            }
                        }
                    }
                }
            }
            
            closedir($dp);
            sort($ListTemplates);
            $i=0;
            while ($i < count($ListTemplates)) {
                echo '<option value="'.$ListTemplates[$i].'">'.$ListTemplates[$i].'</option>';
                $i++;
            }

          ?>
        </select>
      </div>
    </div>
  </fieldset>
</form>