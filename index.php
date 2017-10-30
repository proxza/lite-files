<?php

// Вывод заголовка с данными о кодировке страницы
header('Content-Type: text/html; charset=utf-8');
// Настройка локали
setlocale(LC_ALL, 'ru_RU.65001', 'rus_RUS.65001', 'Russian_Russia. 65001', 'russian');

?>
<html>
<head>
	<title>LF</title>
	<link rel="stylesheet" href="style.css">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
<body>

<p>
	Lite-Files v.0.2a
</p>
<table class="main" >
<?php


define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI']); // Корневой путь

// Проверка и получение файла
if(isset($_GET['type']) && !empty($_GET['file'])) {
	get_files($_GET['file'], $_GET['type']);
}

$file_list = scandir(BASE_DIR);
array_splice($file_list, 0, 2); // Вырезаем первых два элемента (точки)
$counts = count($file_list); // Подсчитываем общее количество файлов в директории

// Сначала вывод массива с папками
foreach ($file_list as $key) {

	$key = explode('.', $key);

	if ($key[1] == "") {
		echo '<tr><td>' . $key[0] . '</td>';
		echo '<td class=td_name2>folder</td></tr>';
	}

}

// Потом вывод остальных файлов
foreach ($file_list as $key) {

	$key = explode('.', $key);

	switch ($key[1]) {
		case "htaccess":
			echo '<tr><td>' . $key[0] . '.' . $key[1] . '</td>';
			echo '<td class=td_name2><a href="?type=' . $key[1] . '&file=' . $key[1] . '" class=a_view>VIEW</a> <a href="#" class=a_del>COPY</a> <a href="#" class=a_del>DEL</a></td></tr>';
			break;

		case "":
			break;
		
		default:
			echo '<tr><td>' . $key[0] . '.' . $key[1] . '</td>';
			echo '<td class=td_name2><a href="?type=' . $key[1] . '&file=' . $key[0] . '" class=a_view>VIEW</a> <a href="#" class=a_del>COPY</a> <a href="#" class=a_del>DEL</a></td></tr>';
			break;
	}

// Старый вариант вывода (без проверки на htaccess и прочие "удивительные" файлы)
/*	if ($key[1] != "") {
		echo '<tr><td>' . $key[0] . '.' . $key[1] . '</td>';
		echo '<td class=td_name2><a href="?type=' . $key[1] . '&file=' . $key[0] . '" class=a_view>VIEW</a> <a href="#" class=a_del>COPY</a> <a href="#" class=a_del>DEL</a></td></tr>';
	}*/


}

// Счетчик
file_count_name($counts);

?>
</table>

</body>
</html>

<?php

// Functions
// 
// 

// Функция получения файла
function get_files ($file, $type) {
	$complite_file = $file . "." . $type;

	switch ($type) {
		case "jpg":
		case "jpeg":
		case "png":
		case "bmp":
			echo '<tr><td class=td_name>(Image) ' . $file . '.' . $type . '</td></tr>';
			echo '<tr><td class=td_name><img src="' . $complite_file . '" class=img> </td></tr>';
			break;

		case "php":
			echo '<tr><td class=td_name>(Source) ' . $complite_file . '</td></tr>';
			echo file_edit($complite_file);
			break;

		case "css":
			echo '<tr><td class=td_name>(Source) ' . $complite_file . '</td></tr>';
			echo file_edit($complite_file);
			break;

		case "":
			echo '<tr><td class=td_name>' . $file . ' папка</td></tr>';
			break;

		case "zip":
		case "rar":
			echo '<tr><td class=td_name>(Archive) ' . $complite_file . '</td></tr>';
			echo '<tr><td>content</td></tr>';
			break;

		case "htaccess":
			echo '<tr><td class=td_name>' . $file . ' тут будет редактор</td></tr>';
			break;

		case "txt":
			echo '<tr><td class=td_name>(Text) ' . $complite_file . '</td></tr>';
			echo file_edit($complite_file);
			break;
		
		default:
			echo '<tr><td class=td_name>' . $complite_file . '</td></tr>';
			echo '<tr><td>Unknown format file</td></tr>';
			break;
	}

	echo '<tr><td class=td_name><a href="./" class=a_view>RETURN</a></td></tr>';
	exit;
}


// Функция определяющая букву в счетчике файлов
function file_count_name($counts){
	switch ($counts) {
		case 1:
		case 21:
			echo "<tr><td colspan=2 align=center>Всего: $counts файл</td></tr>";
			break;

		case 2:
		case 3:
		case 4:
		case 22:
			echo "<tr><td colspan=2 align=center>Всего: $counts файла</td></tr>";
			break;
		
		default:
			echo "<tr><td colspan=2 align=center>Всего: $counts файлов</td></tr>";
			break;
	}
}


// Функция редактирования текстовых файлов
function file_edit($file){

	if ($_POST) {
  	file_put_contents($file, $_POST['text']);
  	//header ("Location: ".$_SERVER['PHP_SELF']);
  	//exit;
	}

	$text = htmlspecialchars(file_get_contents($file));

	echo '<tr><td><form method="POST">';
	echo '<textarea name="text" cols="60" rows="40" class="txta">'.$text.'</textarea></td></tr>';
	echo '<tr><td class=td_name><input type="submit" value="SAVE" class="btn">';
	echo '</form></td></tr>';
}

?>