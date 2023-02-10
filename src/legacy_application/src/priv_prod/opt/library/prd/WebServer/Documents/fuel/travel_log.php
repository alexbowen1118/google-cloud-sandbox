<?php
echo "<div id='add_form' align='center'><table border='1' cellpadding='5'>";

echo "<tr><td align='center' colspan='2'>MOTOR FLEET TRAVEL LOG
</td>";

echo "<tr>
<td align='center'><form action='travel_log_form.php' method='POST'>
<input type='submit' name='submit' value='Input Data'></form></td>
<td align='center'>
<form action='menu.php?form_type=travel_log_edit' method='POST'>
<input type='submit' name='submit' value='Find Data'>
</form></td>
";

echo "</tr></table>
</div>";

?>