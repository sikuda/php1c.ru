<?php
	require_once('src/php1C__code.php');

	//$str = 'ВидСубконто = ?(ТипЗнч(ИмяСубконто) = Тип("ПланВидовХарактеристикСсылка.ВидыСубконтоХозрасчетные"), ИмяСубконто, ПланыВидовХарактеристик.ВидыСубконтоХозрасчетные[ИмяСубконто]);';
	$str = "Результат = 1 + "+Символы.ПС+" 1;";
	//$str = 'result = 1;'; 
	$result = php1C\makeCode($str, "Результат");
	
	echo $result;
?>