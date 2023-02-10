<?php

$display="none";
if(!empty($mileage)){$display="block";}
$total_miles=$mileage+$miles_since_start+$miles_this_year;
$f_mileage=number_format($total_miles,0);
echo "<div id=\"fieldName1\"><font color='red'>VEHICLE ODOMETER/DAMAGE FORM</font> <a onclick=\"toggleDisplay('odometer');\" href=\"javascript:void('')\">show/hide</a></div>
<div id='odometer' style=\"display: $display\">
<fieldset><legend><font color='magenta'>Odometer Disclosure</font></legend>
<table align='left' border='1' cellpadding='3'>";

$cky="";$ckn="";$cka="";
if(@$not_mileage=="actual"){$cka="checked";}
if(@$not_mileage=="in_excess"){$cky="checked";}
if(@$not_mileage=="not_actual"){$ckn="checked";}
echo "<tr><td>1. <input type='radio' name='not_mileage' value='actual' $cka><b>The odometer reading is $f_mileage and reflects the actual mileage</b> unless one of the following statements is checked:<br />";

echo "2. <input type='radio' name='not_mileage' value='in_excess' $cky> The mileage stated is in excess of its mechanical limits.<br />
3. <input type='radio' name='not_mileage' value='not_actual' $ckn> The odometer reading is not the actual mileage.
</td></tr>
</table>
</fieldset>

<fieldset><legend><font color='magenta'>Damage Disclosure</font></legend>
<table align='left' border='1' cellpadding='3'>
<tr><td>1. Has this vehicle been damaged by collision or other occurrence to the extent that
damages exceed 25% of its value at the time of the collision or other occurrence? ";

$cky="";$ckn="";
if(@$damage_1=="YES"){$cky="checked";}
if(@$damage_1=="NO"){$ckn="checked";}
if(!isset($parts_damaged)){$parts_damaged="";}
echo "<input type='radio' name='damage_1' value='YES' $cky required>YES
<input type='radio' name='damage_1' value='NO' $ckn>NO
<br />If yes, list parts that were damaged. <textarea name='parts_damaged' cols='50' rows='2'>$parts_damaged</textarea>
</td></tr>";

$cky="";$ckn="";
if(@$damage_2=="YES"){$cky="checked";}
if(@$damage_2=="NO"){$ckn="checked";}
if(!isset($salvage_state)){$salvage_state="";}
echo "<tr><td>2. Was this vehicle a salvage motor vehicle? *
<input type='radio' name='damage_2' value='YES' $cky required>YES
<input type='radio' name='damage_2' value='NO' $ckn>NO
<br />If yes, in which state was it titled?  <input type='text' name='salvage_state' value=\"$salvage_state\">
</td></tr>";

$cky="";$ckn="";
if(@$damage_3=="YES"){$cky="checked";}
if(@$damage_3=="NO"){$ckn="checked";}
echo "<tr><td>3. Is this vehicle a flood vehicle? * 
<input type='radio' name='damage_3' value='YES' $cky required>YES
<input type='radio' name='damage_3' value='NO' $ckn>NO
</td></tr>";

$cky="";$ckn="";
if(@$damage_4=="YES"){$cky="checked";}
if(@$damage_4=="NO"){$ckn="checked";}
if(!isset($theft_vehicle)){$theft_vehicle="";}
echo "<tr><td>4. Is this vehicle a recovered theft vehicle?
<input type='radio' name='damage_4' value='YES' $cky required>YES
<input type='radio' name='damage_4' value='NO' $ckn>NO
<br />If yes, list parts that were damaged.  <input type='text' name='theft_vehicle' value=\"$theft_vehicle\" size='100'>
</td></tr>";

$cky="";$ckn="";
if(@$damage_5=="YES"){$cky="checked";}
if(@$damage_5=="NO"){$ckn="checked";}
echo "<tr><td>5. Has this vehicle been reconstructed? *
<input type='radio' name='damage_5' value='YES' $cky required>YES
<input type='radio' name='damage_5' value='NO' $ckn>NO
</td></tr>";

echo "<tr><td>Produce Form MVR-180A as a  <a href='mvr_180.php?vin=$vin&tempID=$tempID'target='_blank'>PDF</a>
</td></tr>
</table>
</fieldset>

</div>";

?>