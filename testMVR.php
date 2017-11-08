<?php
	// $docID = '645918921';
	$docID = '645918921';

	$headers = [];
	$headers[] = 'Cookie: __cfduid=d5ac65e961875d04b8c846a55e0c85b771509100625';
	$headers[] = 'Origin: http://validni.mvr.bg';
	$headers[] = 'Accept-Encoding: gzip, deflate';
	$headers[] = 'Accept-Language: en-US,en;q=0.8,bg;q=0.6,fr;q=0.4';
	$headers[] = 'Upgrade-Insecure-Requests: 1';
	$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36';
	$headers[] = 'Content-Type: application/x-www-form-urlencoded';
	$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
	$headers[] = 'Cache-Control: max-age=0';
	$headers[] = 'Referer: http://validni.mvr.bg/nbds2/Web2.nsf/fVerification?OpenForm';
	$headers[] = 'Connection: keep-alive';

	$tuCurl = curl_init('http://validni.mvr.bg/nbds2/Web2.nsf/fVerification?OpenForm&amp;Seq=1');

	curl_setopt($tuCurl, CURLOPT_POST, 1);
	curl_setopt($tuCurl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($tuCurl, CURLOPT_HEADER, 1); 
	curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($tuCurl, CURLOPT_POSTFIELDS, '__Click=C2257D3C00584F78.8180bed4459257c6c2257d260045f196/$Body/0.16B2&%%Surrogate_TypeDoc=1&TypeDoc=6729&Number=' . $docID . '&xEGN=30.09.1973'); 

	$return = curl_exec($tuCurl); 
	curl_close($tuCurl); 
	if ($return === false)
	{
		echo 'Error: ' . curl_error($tuCurl) . PHP_EOL;

		return;
	}

	// echo 'Response: ' . $return . PHP_EOL; 
	$pageDom = new DomDocument();    
	// $encodedHTML = mb_convert_encoding($return, 'HTML-ENTITIES', "UTF-8"); 
	if (!@$pageDom->loadHTML($return))
	{
		echo 'Error while loading HTML ...';
		return;
	}

	$xpath = new DOMXPath($pageDom);
	$elements = $xpath->query('//td[@class = "system-message"]/span[@class = "system-message-ok"]');
	if ($elements->length === 1) {
		$element = $elements->item(0);
		if ($element)
		{
			$nodeContent = $element->textContent;
			if (strpos($nodeContent, '[' . $docID . ']') === false)
			{
				echo 'Document ' . $docID . ' information not found ....' . PHP_EOL;

				return;
			}

			echo 'Document ' . $docID . ' is valid ...' . PHP_EOL;

			return;
		}

		echo 'ERROR: Error while processing data for Document ' . $docID . PHP_EOL;

		return;
	}

	$elements = $xpath->query('//td[@class = "system-message"]/span[@class = "system-message-error"]');
	if ($elements->length === 1) {
		$element = $elements->item(0);
		if ($element)
		{
			$nodeContent = $element->textContent;
			if (strpos($nodeContent, '[' . $docID . ']') === false)
			{
				echo 'Document ' . $docID . ' information not found ....' . PHP_EOL;

				return;
			}

			echo 'Document ' . $docID . ' is NOT valid ...' . PHP_EOL;

			return;
		}

		echo 'ERROR: Error while processing data for Document ' . $docID . PHP_EOL;

		return;
	}
?>