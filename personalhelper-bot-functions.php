<?php
$access_token = '189169874:AAHkaEKeRePpy7mR3Le0UhKoMhK-tK26EoQ';
$api = 'https://api.telegram.org/bot' . $access_token;

$output = json_decode(file_get_contents('php://input'), TRUE);
$chat_id = $output['message']['chat']['id'];
$first_name = $output['message']['chat']['first_name'];
$message = $output['message']['text'];

switch($message) 
{
	case "Новости":
	case "/news":
		GetNewsTomsk($chat_id);
		break;
	case "Погода":
	case "/weather":
		GetWeatherTomsk($chat_id);
		break;
	case "/start_kbrd":
	case "/start":
		StartGenericMenu ($chat_id);
		break;
	case "/stop_kbrd":
	case "Убрать клавиатуру":
		DeleteGenericMenu ($chat_id);
		break;
	case "Баш":
	case "/bash":
		GetBash($chat_id);
		break;
	case "Курс валют":
	case "/currency":
		GetCurrentCurrency($chat_id);
		break;

	case "Гороскоп":
	case "/horoscope":
		StartHoroscopeMenu($chat_id);
		break;
	case "Овен":
		GetCurrentHoroscope($chat_id,0);
		break;
	case "Телец":
		GetCurrentHoroscope($chat_id,1);
		break;
	case "Близнецы":
		GetCurrentHoroscope($chat_id,2);
		break;
	case "Рак":
		GetCurrentHoroscope($chat_id,3);
		break;
	case "Лев":
		GetCurrentHoroscope($chat_id,4);
		break;
	case "Дева":
		GetCurrentHoroscope($chat_id,5);
		break;
	case "Весы":
		GetCurrentHoroscope($chat_id,6);
		break;
	case "Скорпион":
		GetCurrentHoroscope($chat_id,7);
		break;
	case "Стрелец":
		GetCurrentHoroscope($chat_id,8);
		break;
	case "Козерог":
		GetCurrentHoroscope($chat_id,9);
		break;
	case "Водолей":
		GetCurrentHoroscope($chat_id,10);
		break;
	case "Рыбы":
		GetCurrentHoroscope($chat_id,11);
		break;
	case "Праздники":
		GetCurrentHolyday($chat_id);
		break;
	case "Анекдот":
		GetCurrentAnek($chat_id);
		break;
	case "Афоризм":
		GetCurrentZitata($chat_id);
		break;
	case "Демотиватор":
		GetCurrentDemo($chat_id);
		break;
		
	default:
  //sendMessage($chat_id, $message);
		break;
}
if ($chat_id != 112476048)
{
	$message = '@' . $first_name . ': "' . $message . '" id' . $chat_id;
	sendMessage(112476048, $message);
}

/********************************
 *          Функции             *
 ********************************/
// Отправка сообщения пользователю
function sendMessage($chat_id, $message) 
{
	file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}
// Отправка фото пользователю
function	sendPhoto($chat_id,$urlphoto)
{
	$url = $GLOBALS['api'] . "/sendPhoto?chat_id=" . $chat_id ;
	$post_fields = array('chat_id'   => $chat_id,
		'photo'     => new CURLFile($urlphoto));
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
	$output = curl_exec($ch);
}

// Убрать виртуальную доп клавиатуру
function DeleteGenericMenu ($chat_id) 
{
	$text="Убираю клавиатуру категорий";
	global $access_token;
	$replyMarkup = array(
		'hide_keyboard' => true,
		);
	$encodedMarkup = json_encode($replyMarkup);
	$content = array(
			'chat_id' => $chat_id,
			'reply_markup' => $encodedMarkup,
			'text' => "$text"
	);
	$ch = curl_init();
	$url="https://api.telegram.org/bot$access_token/SendMessage";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
	var_dump($server_output);
}

// Запустить виртуальную доп клавиатуру
function StartGenericMenu ($chat_id) 
{
	$lista=
		[ 
			["Новости", "Погода", "Курс валют"],
			["Праздники", "Баш", "Гороскоп"],
			["Анекдот", "Афоризм", "Демотиватор"],
		];
	$text="Выберите интересующий Вас раздел";
	global $access_token;
	$replyMarkup = array(
			'keyboard' => $lista,
			'resize_keyboard' => true,
			);
	$encodedMarkup = json_encode($replyMarkup);
	$content = array(
			'chat_id' => $chat_id,
			'reply_markup' => $encodedMarkup,
			'text' => "$text"
			);
	$ch = curl_init();
	$url="https://api.telegram.org/bot$access_token/SendMessage";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
	var_dump($server_output);
}

// Случайный БАШ
function GetBash($chat_id)
{
	//$ch = curl_init();
	//$url= 'https://bash.im/forweb/?u';
	//curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_POST, 1);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/javascript'));
	//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//$server_output = curl_exec ($ch);
	//curl_close ($ch);
	
	$message = 'https://bash.im/forweb/?u';
	//$message = strip_tags($server_output);
	
	//$message = str_replace('&quot;', '"',$message);
	//$message = str_replace('&', '"',$message);
	//$message = str_replace("Больше на bash.im!';
//document.write(borq);", ' ',$message);
	//$message = str_replace("var borq='';
//borq += '#", '',$message);
	//$message = substr($message,15);
	//$message = str_replace("]", "",$message);
	sendMessage($chat_id, $message);
}

// Последние 3 новости Томска
function GetNewsTomsk($chat_id)
{
	$ch = curl_init();
	// $url= 'http://www.tv2.tomsk.ru/rss/news/3'; // не работает
	// $url = 'https://news.vtomske.ru/rss/js/5/5/tomsk'; // 5 новостей томска
	$url= 'https://tv2.today/rss.xml'; // новости томска
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
	$xml = new SimpleXMLElement($server_output);
	foreach ($xml->channel->item as $it) 
	{
		$otvet =  $it->pubDate ."\n". $it->title . ' ' . $it->link ;
		$otvet = str_replace('+0600', '',$otvet);
		sendMessage($chat_id, $otvet);
	}
}

// Прогноз погоды
function GetWeatherTomsk($chat_id)
{
	$otvet='По данным Гидрометцентра России, по городу Томску';
	$cur_weather = simplexml_load_string(file_get_contents ("http://meteoinfo.ru/rss/forecasts/29430"));
	foreach ($cur_weather->channel->item as $it)
	$otvet = $otvet ."\n\t". $it->title . ': ' . $it->description;
	$otvet = str_replace('Томск, ', '* на ',$otvet);
	sendMessage($chat_id, $otvet);
}

// Текущий курс валют 
function GetCurrentCurrency($chat_id)
{
	$cur_cb = file_get_contents ("http://www.cbr.ru/scripts/XML_daily.asp");
	preg_match_all("#<ValCurs Date=\"(.*)\" name=\"Foreign Currency Market\">#sU", $cur_cb, $_cur_date); 
	preg_match_all("#<Valute ID=\"R01235\">.*<CharCode>(.*)</CharCode>.*<Value>(.*)</Value>.*</Valute>#sU", $cur_cb, $_usd); 
	preg_match_all("#<Valute ID=\"R01239\">.*<CharCode>(.*)</CharCode>.*<Value>(.*)</Value>.*</Valute>#sU", $cur_cb, $_eur); 
	$cur_date = $_cur_date[1][0];
	$usd = $_usd[2][0];
	$eur = $_eur[2][0];
	$otvet = 'Курсы валют ЦБ РФ на '.$cur_date."\n".'  1 USD: '.$usd.' руб.'."\n".'  1 EUR: '.$eur.' руб.';
	sendMessage($chat_id, $otvet); 
}

// Гороскоп
function GetCurrentHoroscope($chat_id, $znak_id)
{
	$typeofznak = array (aries,taurus,gemini,cancer,leo,virgo,libra,scorpio,sagittarius,capricorn,aquarius,pisces);
	$znak_ru = array("Овна", "Тельца", "Близнецов","Рака", "Льва", "Девы","Весов","Скорпиона","Стрелеца","Козерога","Водолея","Рыб");
	$horo = simplexml_load_string(file_get_contents('http://img.ignio.com/r/daily/index.xml'));
	sendMessage($chat_id, 'Гороскоп для '.$znak_ru[$znak_id]); 
	sendMessage($chat_id, 'Сегодня, '.$horo->date['today'] .$horo->$typeofznak[$znak_id]->today); 
	sendMessage($chat_id, 'Завтра, '.$horo->date['tomorrow'] .$horo->$typeofznak[$znak_id]->tomorrow); 
	StartGenericMenu ($chat_id);
}

// Меню гороскопа
function StartHoroscopeMenu($chat_id)
{
		$lista=
		[ 
			["Овен", "Телец", "Близнецы"],
			["Рак", "Лев", "Дева"],
			["Весы","Скорпион","Стрелец"],
			["Козерог","Водолей","Рыбы"],
		];
	$text="Выберите знак зодиака";
	global $access_token;
	$replyMarkup = array(
			'keyboard' => $lista,
			'resize_keyboard' => false,
			);
	$encodedMarkup = json_encode($replyMarkup);
	$content = array(
			'chat_id' => $chat_id,
			'reply_markup' => $encodedMarkup,
			'text' => "$text"
			);
	$ch = curl_init();
	$url="https://api.telegram.org/bot$access_token/SendMessage";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
}

// Праздник
function GetCurrentHolyday($chat_id)
{
	$otvet='Праздники сегодня, праздники завтра...';
	$holyday = simplexml_load_string(file_get_contents('http://www.calend.ru/img/export/calend.rss'));
	foreach ($holyday->channel->item as $it)
		$otvet = $otvet ."\n". $it->title;
	sendMessage($chat_id, $otvet);
}

// Анекдот
function GetCurrentAnek($chat_id)
{
	$xml = simplexml_load_string('http://rzhunemogu.ru/Rand.aspx?CType=1');
	$message = $xml->Content;
	sendMessage($chat_id, $message);
}
// Анекдот
function GetCurrentZitata($chat_id)
{
	$url='http://api.forismatic.com/api/1.0/?method=getQuote&format=html';
	$Citata = file_get_contents($url);
	$message = strip_tags($Citata);
	sendMessage($chat_id, $message);
}

//Демотиватор
function GetCurrentDemo($chat_id)
{ 
	$url='http://hotdem.ru/widgets/hotdem_rnd.js';
	$cur_ph = file_get_contents ($url);
	preg_match_all('#src="([^"]+)"#i', $cur_ph, $_curURL); 
	foreach($_curURL[1] as $link)
		 sendMessage($chat_id, $link);
	//sendPhoto($chat_id,$link); //неработает!!!
}

?>




