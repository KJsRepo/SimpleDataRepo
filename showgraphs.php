
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">

<script language="javascript">
window.onload = function() {
    var energyStored = document.getElementById("energyStored");
    var energyInput = document.getElementById("energyInput");

    function updateImage(imgToUpdate) {
        imgToUpdate.src = imgToUpdate.src.split("?")[0] + "?" + new Date().getTime();
	}


    setInterval(function (){updateImage(energyStored)}, 3000);
    setInterval(function (){updateImage(energyInput)}, 3000);

}

</script>

<title>Energy Management</title>

</head>
<body>
<img src="graphStored.php" border=0 align="left" id="energyStored"><br />
<img src="graphThroughput.php" border=0 align="left" id="energyInput">
</body>
</html>
