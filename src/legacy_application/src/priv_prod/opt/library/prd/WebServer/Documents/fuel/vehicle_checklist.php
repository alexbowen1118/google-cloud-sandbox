<?php

if(!empty($mileage)){$display="block";}
if(!isset($justification)){$justification="";}
if(!isset($repair_estimate)){$repair_estimate="";}
echo "<div id=\"fieldName\"><font color='red'>VEHICLE CHECKLIST REQUIRED</font> <a onclick=\"toggleDisplay('checklist');\" href=\"javascript:void('')\">show/hide</a></div>
<div id='checklist' style=\"display: $display\">
<table align='center' border='1' cellpadding='3'>
<tr>
<td>Justification:</td><td colspan='3'><textarea name='justification' cols='92' rows='2' required>$justification</textarea></td></tr>";

if(@$est_source=="park"){$ckp="checked";$cks="";}else{$ckp="";$cks="checked";}
if(empty($est_source)){$ckp=""; $cks="";}
echo "<tr>
<td>Repair Estimate:</td><td colspan='2'><textarea name='repair_estimate' cols='72' rows='7'>$repair_estimate</textarea></td>
<td><input type='radio' name='est_source' value='park' $ckp>Park estimate <input type='radio' name='est_source' value='shop' $cks>Shop estimate<br /><b>If not drivable, enter a repair cost for each item required for the vehicle to be driveable.</b> If from a mechanic, a verbal or written estimate is acceptable. If verbable, include name of repair shop.</td></tr>

<tr><td>Date:</td><td>$pasu_date</td><td>Agency:</td><td>DPR $center_code</td></tr>
<tr><td>FAS #:</td><td>$fas_num</td><td>VIN #:</td><td>$vin</td></tr>
<tr><td>Year:</td><td>$year <input type='hidden' name='year' value='$year'></td><td>Make:</td><td>$make <input type='hidden' name='make' value='$make'></td></tr>";

$odom_reading=number_format($mileage+$miles_since_start+$miles_this_year,0);
echo "<tr><td>Model:</td><td>$model</td><td>Mileage:</td><td>$odom_reading <input type='hidden' name='mileage' value='$odom_reading'><font color='red'>double check mileage before submitting</font></td></tr>";

$ckn="";$cky="";
if($keys=="Yes"){$cky="checked";}
if($keys=="No"){$cky="";$ckn="checked";}
echo "<tr><td>Keys:</td><td><input type='radio' name='keys' value='Yes' $cky required>Yes <input type='radio' name='keys' value='No' $ckn>No</td>";

$ckd="";$ckr="";
if($runs=="Drivable"){$ckd="checked";}
if($runs=="Runs"){$ckr="checked";$ckd="";$ckn="";}
if($runs=="No"){$ckd="";$ckr="";$ckn="checked";}
echo "<td>Runs:</td><td>
<input type='radio' name='runs' value='Drivable' required $ckd>Drivable 
<input type='radio' name='runs' value='Runs' $ckr>Runs, but not drivable
<input type='radio' name='runs' value='No' $ckn>Doesn't run
</td></tr>";

$ckn="";
if($wrecked=="Yes"){$cky="checked";}
if($wrecked=="No"){$cky="";$ckn="checked";}
echo "<tr><td>Wrecked:</td><td><input type='radio' name='wrecked' value='Yes' required $cky>Yes <input type='radio' name='wrecked' value='No' $ckn>No</td>";

$ckn="";
if($flooded=="Yes"){$cky="checked";}
if($flooded=="No"){$cky="";$ckn="checked";}
echo "<td>Flooded:</td><td><input type='radio' name='flooded' value='Yes' required $cky>Yes <input type='radio' name='flooded' value='No' $ckn>No</td></tr>";

$cko="";$ckm="";$ckd="";
if($seats=="OK"){$cko="checked";}
if($seats=="Missing"){$ckm="checked";}
if($seats=="Damaged"){$ckd="checked";}
echo "<tr><td>Seats:</td><td><input type='radio' name='seats' value='OK' required $cko>OK <input type='radio' name='seats' value='Missing' $ckm>Missing <input type='radio' name='seats' value='Damaged' $ckd>Damaged</td>";

$ckn="";
if($tire=="Yes"){$cky="checked";}
if($tire=="No"){$cky="";$ckn="checked";}
echo "<td>Spare Tire:</td><td><input type='radio' name='tire' value='Yes' required $cky>Yes <input type='radio' name='tire' value='No' $ckn>No</td></tr>";

$cko="";$ckm="";$ckb="";$ckn="";
if($antenna=="OK"){$cko="checked";}
if($antenna=="Missing"){$ckm="checked";}
if($antenna=="Bent"){$ckb="checked";}
if($antenna=="N/A"){$ckn="checked";}
echo "<tr><td>Antenna:</td><td><input type='radio' name='antenna' value='OK' required $cko>OK <input type='radio' name='antenna' value='Missing' $ckm>Missing <input type='radio' name='antenna' value='Bent' $ckb>Bent <input type='radio' name='antenna' value='N/A' $ckn>N/A</td>";

$cka="";$cks="";$ckno="";$ckn="";
if($hubcaps=="All"){$cka="checked";}
if($hubcaps=="Some"){$cks="checked";}
if($hubcaps=="None"){$ckno="checked";}
if($hubcaps=="N/A"){$ckn="checked";}
echo "<td>Hubcaps:</td><td><input type='radio' name='hubcaps' value='All' required $cka>All <input type='radio' name='hubcaps' value='Some' $cks>Some <input type='radio' name='hubcaps' value='None' $ckno>None <input type='radio' name='hubcaps' value='N/A' $ckn>N/A</td></tr>";

$ckn="";
if($windows=="OK"){$cky="checked";}
if($windows=="Broke"){$cky="";$ckn="checked";}
echo "<tr>
<td>Windows:</td><td><input type='radio' name='windows' value='OK' required $cky>OK <input type='radio' name='windows' value='Broke' $ckn>Broke</td>";

$cko="";$ckc="";$ckcr="";$ckb="";
if($windshield=="OK"){$cko="checked";}
if($windshield=="Chipped"){$ckc="checked";}
if($windshield=="Cracked"){$ckcr="checked";}
if($windshield=="Broke"){$ckb="checked";}
echo "<td>Windshield:</td><td><input type='radio' name='windshield' value='OK' required $cko>OK <input type='radio' name='windshield' value='Chipped' $ckc>Chipped <input type='radio' name='windshield' value='Cracked' $ckcr>Cracked <input type='radio' name='windshield' value='Broke' $ckb>Broke</td></tr>";

$cko="";$ckm="";$ckd="";
if($trim=="OK"){$cko="checked";}
if($trim=="Missing"){$ckm="checked";}
if($trim=="N/A"){$ckd="checked";}
echo "<tr>
<td>Body Trim:</td><td><input type='radio' name='trim' value='OK' required $cko>OK <input type='radio' name='trim' value='Missing' $ckm>Missing <input type='radio' name='trim' value='N/A' $ckd>N/A</td>";

$cko="";$ckmi="";$ckma="";
if($rust=="No"){$cko="checked";}
if($rust=="Minor"){$ckmi="checked";}
if($rust=="Major"){$ckma="checked";}
echo "<td>Body Rust:</td><td><input type='radio' name='rust' value='No' required $cko>No <input type='radio' name='rust' value='Minor' $ckmi>Minor <input type='radio' name='rust' value='Major' $ckma>Major</td></tr>";

$cko="";$cks="";$ckp="";$ckf="";
if(!isset($paint_OK)){$paint_OK="";}
if(!isset($paint_Scratches)){$paint_Scratches="";}
if(!isset($paint_Peeling)){$paint_Peeling="";}
if(!isset($paint_Faded)){$paint_Faded="";}

if($paint_OK=="OK"){$cko="checked";}
if($paint_Scratches=="Scratches"){$cks="checked";}
if($paint_Peeling=="Peeling"){$ckp="checked";}
if($paint_Faded=="Faded"){$ckf="checked";}
echo "<tr>
<td>Paint:</td><td>
<input type='checkbox' name='paint[]' value='OK'  $cko>OK 
<input type='checkbox' name='paint[]' value='Scratches'  $cks>Scratches 
<input type='checkbox' name='paint[]' value='Peeling'  $ckp>Peeling 
<input type='checkbox' name='paint[]' value='Faded'  $ckf>Faded</td>
<td>Former Plate</td><td>$license</td>
</tr>

<tr>
<td colspan='1'>Dents:</td><td><textarea name='dents' cols='32' rows='2' required>$dents</textarea></td>
<td colspan='1'>Other:</td><td><textarea name='other' cols='44' rows='2' required>$other</textarea></td></tr>";

if(empty($checked_by))
	{$checked_by=$_SESSION['fuel']['full_name'];}
if(empty($emid))
	{$emid=$_SESSION['fuel']['emid'];}

echo "<tr>
<td colspan='3'>Checked by: <input type='text' name='checked_by' value=\"$checked_by\" readonly>
<input type='hidden' name='emid' value='$emid'>
</td>";

if(!empty($rust))
	{echo "<td><a href='vehicle_checklist_pdf.php?vin=$vin' target='_blank'>Create Checklist</a></td>";}

echo "</tr>

</table>
</div>";

?>