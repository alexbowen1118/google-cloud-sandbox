<?php
extract(htmlentities($_GET));
echo "To surplus a vehicle/trailer, the park must complete 3 forms and send them to the Budget Office. Click on the \"Forms link\" to obtain the forms.<br /><br />
Vehicle Idenification Number: $vin<br  />
License Plate: $lp<br /><br />
<a href='/find/forum.php?forumID=397&submit=Go'>Forms</a>";
?>