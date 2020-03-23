<?

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

  // 
  // Получаем данные 
  //

  // $postData = file_get_contents('php://input'); // Получаем JSON от Woocommerce
  // $data = json_decode($postData, true); // Разбираем JSON в масив

  // //Информация о заказе
  // $orderId = $data["id"];
  // $totalPrice = $data["total"]; // id заказа
  // $payMethod = $data["payment_method_title"]; // способ оплаты

  // $deliveryMethod = $data["shipping_lines"][0][method_title]; // способ доставки
  // $deliveryPrice = $data["shipping_lines"][0][total]; // Стоимость доставки

  // // Информация о клиенте
  // $clientInfo = $data["billing"];
  // $clientName = $clientInfo["first_name"] . " " . $clientInfo["last_name"];
  // $clientAddress = $clientInfo["city"] . " " .$clientInfo["state"] . " " . $clientInfo["address_1"] . " " . $clientInfo["address_2"];
  // $clientPhone = $clientInfo["phone"];
  // $clientEmail = $clientInfo["email"];

  // // Информация о товарах в заказе
  // $orderItems = $data["line_items"];
  // $product1 = $orderItems[0]["sku"] . ' ' . $orderItems[0]["name"] . ' ' . $orderItems[0]["total"];

// Функция проверки ответа
function CheckCurlResponse($code)
{
    $code=(int)$code;
    $errors=array(
        301=>'Moved permanently',
        400=>'Bad request',
        401=>'Unauthorized',
        403=>'Forbidden',
        404=>'Not found',
        500=>'Internal server error',
        502=>'Bad gateway',
        503=>'Service unavailable'
    );
    try
    {
        //Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
        if($code!=200 && $code!=204)
            throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
    }
    catch(Exception $E)
    {
        die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
    }
}


$contact_name2       = filter_var ($_REQUEST['name2']);
$contact_name       = filter_var ($_REQUEST['fname']);
$contact_phone      = filter_var ($_REQUEST['phone'],FILTER_SANITIZE_NUMBER_FLOAT);

$info 	= filter_var ($_REQUEST['info']);

// $question1 	= filter_var ($_REQUEST['question1']);
// $question2 	= filter_var ($_REQUEST['question2']);
// $question3 	= filter_var ($_REQUEST['question3']);
// $question4 	= filter_var ($_REQUEST['question4']);
// $question5  = filter_var ($_REQUEST['question5']);


$utm_source = $_POST['utm_source']; if ($utm_source == '') { $utm_source = '-'; }
$utm_medium = $_POST['utm_medium']; if ($utm_medium == '') { $utm_medium = '-'; }
$utm_term = $_POST['utm_term']; if ($utm_term == '') { $utm_term = '-'; }
$utm_content = $_POST['utm_content']; if ($utm_content == '') { $utm_content = '-'; }
$utm_campaign = $_POST['utm_campaign']; if ($utm_campaign == '') { $utm_campaign = '-'; }


// echo $contact_name;
// echo "<br>";
// echo $contact_phone;
// echo "<br>";
// echo $question1;
// echo "<br>";
// echo $question2;
// echo "<br>";
// echo $question3;
// echo "<br>";
// echo $question4;
// echo "<br>";
// echo $question5;
// echo "<br>";
// echo $utm_source;
// echo "<br>";
// echo $utm_medium;
// echo "<br>";
// echo $utm_term;
// echo "<br>";
// echo $utm_content;
// echo "<br>";
// echo $utm_campaign;
// echo "<br>";



// $contact_name = $cname; //Название добавляемого контакта
// $contact_phone = $cphone; //Телефон контакта
// $contact_email = $cemail; //Емейл контакта

//Служебные данные

$responsible_user_id = 1; //id ответственного по сделке, контакту, компании
//$lead_status_id = '2245699'; //id этапа продаж, куда помещать сделку


//АВТОРИЗАЦИЯ
$user=array(
  'USER_LOGIN'=>'galyna.shostak.consulting@gmail.com', #Ваш логин (электронная почта)
  'USER_HASH'=>'cac91f46c1b96cd986e41785748ca28f5cce701d' #Хэш для доступа к API (смотрите в профиле пользователя)
);
$subdomain='galynashostakconsulting'; #Поддомен напирмер MYSHOP.amocrm.ru
#Формируем ссылку для запроса
$link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';

$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_POST,true);
curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($user));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
curl_close($curl);  #Завершаем сеанс cURL
$Response=json_decode($out,true);
//echo '<b>Авторизация:</b>'; echo '<pre>'; print_r($Response); echo '</pre>';


//ПОЛУЧАЕМ ДАННЫЕ АККАУНТА
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/accounts/current'; #$subdomain уже объявляли выше
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
curl_close($curl);
$Response=json_decode($out,true);
$account=$Response['response']['account'];
// echo '<b>Данные аккаунта:</b>'; echo '<pre>'; print_r($Response); echo '</pre>';

//ПОЛУЧАЕМ СУЩЕСТВУЮЩИЕ ПОЛЯ
$amoAllFields = $account['custom_fields']; //Все поля
$amoConactsFields = $account['custom_fields']['contacts']; //Поля контактов
//echo '<b>Поля из амо:</b>'; echo '<pre>'; print_r($amoConactsFields); echo '</pre>';
//ФОРМИРУЕМ МАССИВ С ЗАПОЛНЕННЫМИ ПОЛЯМИ КОНТАКТА
//Стандартные поля амо:
$sFields = array_flip(array(
    'PHONE', //Телефон. Варианты: WORK, WORKDD, MOB, FAX, HOME, OTHER
    'EMAIL' //Email. Варианты: WORK, PRIV, OTHER
  )
);
//Проставляем id этих полей из базы амо
foreach($amoConactsFields as $afield) {
  if(isset($sFields[$afield['code']])) {
    $sFields[$afield['code']] = $afield['id'];
  }
}


//ДОБАВЛЯЕМ СДЕЛКУ
$leads['request']['leads']['add']=array(
  array(
    'name' => $contact_phone,
    'status_id' => $lead_status_id, //id статуса
    // 'responsible_user_id' => $responsible_user_id, //id ответственного по сделке
    //'date_create'=>1298904164, //optional
    // 'price'=>$totalPrice, #Полная стоимость заказа 
    'pipeline_id' => 2296180,
    'tags' => 'с сайта квиз', #Теги
    'custom_fields'=>array(
      
      array(
        "id"=>123879,   
        "values"=> array(
          array(
            "value"=>$info
          )
        )
      ),
      // array(
      //   "id"=>585519,   
      //   "values"=> array(
      //     array(
      //       "value"=>$question1
      //     )
      //   )
      // ),
      // array(
      //   "id"=>585521,   
      //   "values"=> array(
      //     array(
      //       "value"=>$question2
      //     )
      //   )
      // ),

      // array(
      //   "id"=>585523,   
      //   "values"=> array(
      //     array(
      //       "value"=>$question3
      //     )
      //   )
      // ),

      // array(
      //   "id"=>585525,   
      //   "values"=> array(
      //     array(
      //       "value"=>$question4
      //     )
      //   )
      // ),

      // array(
      //   "id"=>585527,   
      //   "values"=> array(
      //     array(
      //       "value"=>$question5
      //     )
      //   )
      // ),



      array(
        "id"=>128631,   
        "values"=> array(
          array(
            "value"=>$utm_source
          )
        )
      ),

      array(
        "id"=>128637,   
        "values"=> array(
          array(
            "value"=>$utm_medium
          )
        )
      ),

      array(
        "id"=>128641,   
        "values"=> array(
          array(
            "value"=>$utm_campaign
          )
        )
      ),

      array(
        "id"=>128643,   
        "values"=> array(
          array(
            "value"=>$utm_term
          )
        )
      ),

      array(
        "id"=>128649,   
        "values"=> array(
          array(
            "value"=>$utm_content
          )
        )
      ),










     
    )


  )
);
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads/set';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
$Response=json_decode($out,true);
//echo '<b>Новая сделка:</b>'; echo '<pre>'; print_r($Response); echo '</pre>';
if(is_array($Response['response']['leads']['add']))
  foreach($Response['response']['leads']['add'] as $lead) {
    $lead_id = $lead["id"]; //id новой сделки
  };
//ДОБАВЛЯЕМ СДЕЛКУ - КОНЕЦ


//ДОБАВЛЕНИЕ КОНТАКТА
$contact = array(
  'name' => $contact_name2,
  'linked_leads_id' => array($lead_id), //id сделки
  'responsible_user_id' => $responsible_user_id, //id ответственного
  'custom_fields'=>array(
    array(
      'id' => $sFields['PHONE'],
      'values' => array(
        array(
          'value' => $contact_phone,
          'enum' => 'MOB'
        )
      )
    ),
    array(
      'id' => $sFields['EMAIL'],
      'values' => array(
        array(
          'value' => $contact_email,
          'enum' => 'WORK'
        )
      )
    )
  )
);
$set['request']['contacts']['add'][]=$contact;
#Формируем ссылку для запроса
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/set';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($set));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
CheckCurlResponse($code);
$Response=json_decode($out,true);
//ДОБАВЛЕНИЕ КОНТАКТА - КОНЕЦ
//amo
//





	// header("Location: /quiz-thanks/");










?>
