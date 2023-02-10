<?php
$dec1array=array("comm_precip","vol_hours","csw_hours","river_level_high","river_level_low","lake_level");// treat decimal fields differently

// Column 1 Week 1
echo "<table cellpadding='-1'><tr>";
for($j=0;$j<count($fieldName);$j++)
	{
	$key="$fieldName[$j]";
	// Get Modifier values
	if(strpos($key,"da_")>-1)
		{
		// determine if modifier is > 0 and therefore will used
		if($modArray[$key]>0){
				$modVal=$modArray[$key];// get value of modifier
				$subMod=$submodArray[$key];// get any of subField modifier
				$test=1;// set var so modifier will be used
				}
				else{$modVal="<font color='blue'>actual</font>";$test="";}
		$mod="(".$modVal.")";// add parens to modifier
		}
	else{$mod="";$test="";}
	
	$title=$titleArray[$key];
	$pos = strpos($title, "Inventory");
	if($pos>0){$title="<font color='brown'>$title</font>";}
	
	IF(isset($subMod))
		{$subModF="<font color='orange'>$subMod</font>";}
		else
		{$subModF="";}
	echo "<tr><td align='right'>$title $mod $subModF</td>";
	
	for($zz=1;$zz<=$weeks_of_month;$zz++)
		{
		$padZ=str_pad($zz,2,"0",STR_PAD_LEFT);
		for($i=0;$i<count($weeks_of_month);$i++)
			{		
			$temp="testYMW".$zz;$VAR=${$temp};$name=$key."[".$VAR."]";
			$key2=$key.$year.$monthpad.$padZ;
			
			// Get Value
			if($test)
				{// get value using modifier and attend_tot
				$key2="attend_tot".$year.$monthpad.$padZ;
				@$val=round(($keyName[$key2])*$modArray[$key]);// modified attend_tot
						
				$key2=$key.$year.$monthpad.$padZ;
				$key3="attend_tot".$year.$monthpad.$padZ;
				@$test_val=$keyName[$key2];
				// set var so calc flds get added to table
				if(@$keyName[$key3]>0 AND $test_val<1)
					{$use_mod=1;} else {$use_mod="";}
				
				if($subMod)
					{
					$key2=$subMod.$year.$monthpad.$padZ;
					$val=round(($keyName[$key2])*$modArray[$key]);// subMod da_"field"
					}
				}
			else
				{
				// use value actually entered on form
				@$val=$keyName[$key2];}
			// LANO had no Biking prior to Jan04
			
			if(!isset($passPark)){$passPark="";}
			if($passPark=="LANO" AND $key=="da_bike" AND ($year.$monthpad<"200404"))
				{$val="";}
			
			if($key=="lake_level"){if($val>0){$varLake++;}}// get denomin for average
			if($key=="river_level_high"){if($val>0){$varRiverHi++;}}
			if($key=="river_level_low"){if($val>0){$varRiverLo++;}}
			if($key=="comm_low_temp"){if($val!=""){$varTempLo++;}}
			if($key=="comm_hi_temp"){if($val!=""){$varTempHi++;}}
			
			@${$key."_total"}+=$val;// variable variable to hold totals for week $zz
			
			if($key=="comm_low_temp"||$key=="comm_hi_temp"){
			// else don't format so a blank
			}else{
			if(in_array($key,$dec1array)){
			if($key=="vol_hours"||$key=="csw_hours"){$val=number_format($val,1);}else{$val=number_format($val,2);}
			}
			else{$val=number_format($val);}
			}// end if not Temperature
			
			$pos=strpos($key,"_inv");
			if($pos===false||$zz==1){$z="";}else{$z="DISABLED";}// disable Inventory after week 1
			//$val=number_format($val);
			echo "<td align='right'><input type='text' name='$name' value='$val' size='7' $z></td>";
			}// end $i
		}// end $zz
	
	// Totals
	$name=$fieldName[$j]."_total";
	$val=${$name};
	if($fieldName[$j]=="lake_level"&&$varLake>0){$val=($val/$varLake);}
	if($key=="comm_hi_temp"&&$val>0){$val=round(($val/$varTempHi),1);}
	if($key=="comm_low_temp"&&$val>0){$val=round(($val/$varTempLo),1);}
	if($key=="river_level_high"&&$val>0){$val=round(($val/$varRiverHi),1);}
	if($key=="river_level_low"&&$val>0){$val=round(($val/$varRiverLo),1);}
	if(in_array($key,$dec1array))
		{
		if($key=="vol_hours"||$key=="csw_hours")
			{$val=number_format($val,1);}
			else
			{$val=number_format($val,2);}
		}
		else
		{$val=number_format($val);}
	echo "<td><input type='text' name='$name' value='$val' size='9' DISABLED></td>";
	echo "</tr>";
	}// end $j

echo "</table>";
?>