<?php
$dec1array=array("comm_precip","vol_hours","csw_hours","river_level_high","river_level_low","lake_level");// treat decimal fields differently

//if($parkcode=="GRMO"){print_r($modArray);}


// Column 1 Week 1
echo "<table border='1'><tr><td align='right'>";
for($i=0;$i<count($fieldName);$i++)
	{
	$key="$fieldName[$i]";
	// Get Modifier values
	if(strpos($key,"da_")>-1)
		{
		// determine if modifier is > 0 and therefore will use
		if($modArray[$key]>0){
				$modVal=$modArray[$key];// get value of modifier
				$subMod=$submodArray[$key];// get any of subField modifier
				$test=1;// set var so modifier will be used
				
				$key2=$key.$year.$monthpad."01";
				$key3="attend_tot".$year.$monthpad."01";
				@$test_val=$keyName[$key2];
				// set var so calc flds get added to table
				if(@$keyName[$key3]>0 AND $test_val<1)				
					{$use_mod=1;} else {$use_mod="";}
				}
				else
				{
				$modVal="<font color='blue'>actual</font>";$test="";
				}
		$mod="(".$modVal.")";// add parens to modifier
		}
	else{$mod="";$test="";}
	
	$title=$titleArray[$key];
	$pos = strpos($title, "Inventory");
	if($pos>0){$title="<font color='brown'>$title</font>";}
	$name=$key."[".$testYMW1."]";
	$key2=$key.$year.$monthpad."01";
	//echo "k=$key2";
	
//	if($key=="da_hike" AND $parkcode=="GRMO")
//		{echo "k = $key2  j = $keyName[$key2]";}
		
	// Get Value
	if($test)
		{// get value using modifier and attend_tot
		$key2="attend_tot".$year.$monthpad."01";
		@$val=round(($keyName[$key2])*$modArray[$key]);// modified attend_tot
		if($subMod)
			{
			$key2=$subMod.$year.$monthpad."01";
			@$val=round(($keyName[$key2])*$modArray[$key]);// subMod da_"field"
			}
		}
	else
		{// use value actually entered on form
	//	if($key=="da_hike"){echo "k=$keyName k2=$key2";}
		$val=@$keyName[$key2];
		}
		//echo "$key2=$j ";
	
	// LANO had no Biking prior to Jan04
	if(@$passPark=="LANO" AND $key=="da_bike" AND ($year.$monthpad<"200404"))
	{$val="";}
	
	if($key=="lake_level"){if($val>0){@$varLake++;}}// get denomin for average
	if($key=="river_level_high"){if($val>0){@$varRiverHi++;}}
	if($key=="river_level_low"){if($val>0){@$varRiverLo++;}}
	if($key=="comm_low_temp"){if($val!=""){@$varTempLo++;}}
	if($key=="comm_hi_temp"){if($val!=""){@$varTempHi++;}}
	
	@${$key."_total"}+=$val;// variable variable to hold totals for week 1
	
	if($key=="comm_low_temp"||$key=="comm_hi_temp"){
	// else don't format so a blank
	}else{
	if(in_array($key,$dec1array)){
	if($key=="vol_hours"||$key=="csw_hours"){$val=number_format($val,1);}else{$val=number_format($val,2);}
	}
	else{$val=number_format($val);}
	}// end if not Temperature
	
	@$subMod="<font color='orange'>$subMod</font>";
	echo "$title $mod $subMod<input type='text' name='$name' value='$val' size='7'><br>";
	}
echo "</td>";

// ***************** Columns > 1 *****************
// Makes columns 2 thru $zz
for($zz=2;$zz<=$weeks_of_month;$zz++)
	{
	$padZ=str_pad($zz,2,"0",STR_PAD_LEFT);
	echo "<td align='right'>";
	for($i=0;$i<count($fieldName);$i++)
		{
		$key="$fieldName[$i]";// Get Modifier values
		if(strpos($key,"da_")>-1)
			{
			// determine if modifier is > 0 and therefore will used
			if($modArray[$key]>0)
				{
				$modVal=$modArray[$key];// get value of modifier
				$subMod=$submodArray[$key];// get any of subField modifier
				$test=1;// set var so modifier will be used
				
				$key2=$key.$year.$monthpad.$padZ;
				$key3="attend_tot".$year.$monthpad.$padZ;
				@$test_val=$keyName[$key2];
				// set var so calc flds get added to table
				@$get_wk_total=$keyName[$key3];
				if($get_wk_total>0 AND $test_val<1)
					{$use_mod=1;} else {$use_mod="";}
				}
				else
				{
				$modVal="<font color='blue'>actual</font>";
				$test="";
			//	$use_mod="";
				}
			$mod="(".$modVal.")";// add parens to modifier
			}
		else{$mod="";$test="";}
		
		$temp="testYMW".$zz;
		$VAR=${$temp};
		$name=$key."[".$VAR."]";
		$key2=$key.$year.$monthpad.$padZ;
		
		// Get Value
		if($test)
			{// get value using modifier and attend_tot
			$key2="attend_tot".$year.$monthpad.$padZ;
			@$val=round(($keyName[$key2])*$modArray[$key]);// modified attend_tot
			if($subMod)
				{
				$key2=$subMod.$year.$monthpad.$padZ;
				@$val=round(($keyName[$key2])*$modArray[$key]);// subMod da_"field"
				}
			}
		else
			{// use value actually entered on form
			@$val=$keyName[$key2];
			}
		// LANO had no Biking prior to Jan04
		if(@$passPark=="LANO" AND $key=="da_bike" AND ($year.$monthpad<"200404"))
		{$val="";}
		
		if($key=="lake_level")
			{if($val>0){$varLake++;}}// get denomin for average
		if($key=="river_level_high")
			{if($val>0)
				{
				@$varRiverHi++;
				}
			}
		if($key=="river_level_low")
			{if($val>0)
				{
				@$varRiverLo++;
				}
			}
		if($key=="comm_low_temp")
			{if($val!="")
				{
				@$varTempLo++;
				}
			}
		if($key=="comm_hi_temp")
			{if($val!="")
				{
				@$varTempHi++;
				}
			}
		
		${$key."_total"}+=$val;// variable variable to hold totals for week $zz
		if($key=="comm_low_temp"||$key=="comm_hi_temp"){
		// else don't format so a blank
		}else{
		if(in_array($key,$dec1array)){
		if($key=="vol_hours"||$key=="csw_hours"){$val=number_format($val,1);}else{$val=number_format($val,2);}
		}
		else{$val=number_format($val);}
		}// end if not Temperature
		
		$pos=strpos($key,"_inv");
		if($pos===false){$z="";}else{$z="DISABLED";}// disable Inventory after week 1
		//$val=number_format($val);
		echo "<input type='text' name='$name' value='$val' size='7' $z><br>";
		//$get_wk_total $test_val
		}
	echo "</td>";
	}


// Week Totals
//if($count>0){
echo "<td align='right'>";
for($i=0;$i<count($fieldName);$i++)
	{
	$key="$fieldName[$i]";
	$name=$key."_total";
	$val=${$name};
	if(in_array($key,$dec1array))
		{
		if($key=="vol_hours"||$key=="csw_hours")
			{$val=number_format($val,1);}
			else
			{$val=number_format($val,2);}
		}
	else
		{
		$val=number_format($val);
		}
	
	if($key=="lake_level" && @$varLake>0){@$val=($val/$varLake);}
	if($key=="comm_hi_temp"&&$val>0){@$val=round(($val/$varTempHi),1);}
	if($key=="comm_low_temp"&&$val>0){@$val=round(($val/$varTempLo),1);}
	if($key=="river_level_high"&&$val>0){@$val=round(($val/$varRiverHi),1);}
	if($key=="river_level_low"&&$val>0){@$val=round(($val/$varRiverLo),1);}
	
	echo "<input type='text' name='$name' value='$val' size='9' DISABLED><br>";
	}
echo "</td>";
//}

echo "</tr></table>";

?>