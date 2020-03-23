<?php
	
function amocrm_add_contact($contact ) {	
	$subdomain = 'starikovandrei'; //Поддомен нужного аккаунта
	$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

	/** Соберем данные для запроса */
	$data = [
		'client_id' => '5cd554b8-503e-4210-9c04-bdbbd65cbfd2',
		'client_secret' => 'oyz01Nw96mhHJ7nCk1z5zDaXbZTX1DoyZ1xFN9U5ZJakN7YGvFM496tm0JvrGSaN',
		'redirect_uri' => get_site_url() . '/quiz/',
		//'grant_type' => 'authorization_code',
		'grant_type' => 'refresh_token'
	];

	$access_token_file = fopen(ASTRA_THEME_DIR . "access_token.txt", 'r');
	$refresh_token_file = fopen(ASTRA_THEME_DIR . "refresh_token.txt", 'r');

	$data["access_token"] = fgets($access_token_file);
	$data["refresh_token"] = fgets($refresh_token_file);
	
	fclose($access_token_file);
	fclose($refresh_token_file);	
	
	/*if ( $data["access_token"] =='' ){
		echo 'Start get new access token<br>';
		$data['grant_type'] = 'authorization_code';

		$access = get_access_token ($subdomain, $data);
		$access_token_file  = fopen(ASTRA_THEME_DIR . "access_token.txt", "w");
		$refresh_token_file = fopen(ASTRA_THEME_DIR . "refresh_token", "w");

		// записываем в файл текст
		fwrite($access_token_file, $access['access_token']);
		fwrite($refresh_token_file, $access['refresh_token']);

		// закрываем
		fclose($access_token_file);
		fclose($refresh_token_file);
		if ( $data["refresh_token"] =='' ){
			$refresh_token_file = fopen(ASTRA_THEME_DIR . "refresh_token.txt", "w");
			$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token';
			fwrite($refresh_token_file, $access['refresh_token']);
			fclose($refresh_token_file);
		}
	}
	
	echo json_encode($data);
	*/
	$errors = [
		400 => 'Bad request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not found',
		500 => 'Internal server error',
		502 => 'Bad gateway',
		503 => 'Service unavailable',
	];
	
	$leads['add'] = array(
		array(
			'name' => $contact['name'],
			'tags' => 'Квиз' #Теги
		)
	);

	$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/leads'; //Формируем URL для запроса
	/** Формируем заголовки */
	$headers = [
		'Authorization: Bearer ' . $data["access_token"]
	];
	/**
	 * Нам необходимо инициировать запрос к серверу.
	 * Воспользуемся библиотекой cURL (поставляется в составе PHP).
	 * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
	 */
	$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
	/** Устанавливаем необходимые опции для сеанса cURL  */
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
	curl_setopt($curl,CURLOPT_URL, $link);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($leads));
	curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl,CURLOPT_HEADER, false);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
	$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
	$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	$out_encoded = json_decode($out);
	$lead_id = $out_encoded->_embedded->items[0]->id;

	$contacts['add'] = array(
		array(
			'name' => $contact["name"],
			'leads_id' => array(
				$lead_id
			),
			'custom_fields' => array(
				array(
					'id' => 216315, // телефон 245665
					'values' => array(
						array(
							'value' => trim($contact["phone"]),
							'enum' => 'WORK'
						)
					)
				)
			)
		)
	);

	$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/contacts'; //Формируем URL для запроса
	/** Формируем заголовки */
	$headers = [
		'Authorization: Bearer ' . $data["access_token"]
	];
	/**
	 * Нам необходимо инициировать запрос к серверу.
	 * Воспользуемся библиотекой cURL (поставляется в составе PHP).
	 * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
	 */
	$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
	/** Устанавливаем необходимые опции для сеанса cURL  */
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
	curl_setopt($curl,CURLOPT_URL, $link);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($contacts));
	curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl,CURLOPT_HEADER, false);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
	$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
	$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	
	if ($code < 200 || $code > 204) {
		unset($data["access_token"]);
		
		$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token';

		$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
		// Устанавливаем необходимые опции для сеанса cURL  
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
		curl_setopt($curl,CURLOPT_URL, $link);
		curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
		curl_setopt($curl,CURLOPT_HEADER, false);
		curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
		$out = curl_exec($curl);
		curl_close($curl);

		$response = json_decode($out);
		echo '<pre>';
		print_r($response);
		echo '</pre>';
		$data["access_token"] = $response->access_token; //Access токен
		$data["refresh_token"] = $response->refresh_token;

		$access_token_file  = fopen(ASTRA_THEME_DIR . "access_token.txt", "w");
		$refresh_token_file = fopen(ASTRA_THEME_DIR . "refresh_token.txt", "w");

		// записываем в файл текст
		fwrite($access_token_file, $data["access_token"]);
		fwrite($refresh_token_file, $data["refresh_token"]);

		// закрываем
		fclose($access_token_file);
		fclose($refresh_token_file);

		

		$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/leads'; //Формируем URL для запроса
		/** Формируем заголовки */
		$headers = [
			'Authorization: Bearer ' . $data["access_token"]
		];
		/**
		 * Нам необходимо инициировать запрос к серверу.
		 * Воспользуемся библиотекой cURL (поставляется в составе PHP).
		 * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
		 */
		$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
		/** Устанавливаем необходимые опции для сеанса cURL  */
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
		curl_setopt($curl,CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($leads));
		curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl,CURLOPT_HEADER, false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
		$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);


		
		$out_encoded = json_decode($out);
		$lead_id = $out_encoded->_embedded->items[0]->id;

		$contacts["add"][0]["leads_id"] = array($lead_id);



		$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/contacts'; //Формируем URL для запроса
		/** Формируем заголовки */
		$headers = [
			'Authorization: Bearer ' . $data["access_token"]
		];
		/**
		 * Нам необходимо инициировать запрос к серверу.
		 * Воспользуемся библиотекой cURL (поставляется в составе PHP).
		 * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
		 */
		$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
		/** Устанавливаем необходимые опции для сеанса cURL  */
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
		curl_setopt($curl,CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($contacts));
		curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl,CURLOPT_HEADER, false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
		$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		
	}
	
}

function get_access_token ($subdomain, $data) {
	$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса
	$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
	/** Устанавливаем необходимые опции для сеанса cURL  */
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
	curl_setopt($curl,CURLOPT_URL, $link);
	curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
	curl_setopt($curl,CURLOPT_HEADER, false);
	curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
	$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
	$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	$code = (int)$code;
	$errors = [
		400 => 'Bad request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not found',
		500 => 'Internal server error',
		502 => 'Bad gateway',
		503 => 'Service unavailable',
	];
	
	try{
			/** Если код ответа не успешный - возвращаем сообщение об ошибке  */
			if ($code < 200 && $code > 204) {
				throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
			}
	}
	catch(\Exception $e){
		$error = 'Ошибка: ' . $e->getMessage() . '; Код ошибки: ' . $e->getCode();
	}
	$response = json_decode($out, true);
	
	return array(
		'response' =>	$response,
		'error' =>	 	$error, 
		'code' =>		$code
	);
}
?>