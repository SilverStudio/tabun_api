<?php

header('Content-Type: application/xml');

function api_format_encode($data) {
	return xml_encode($data);
}

function xml_encode($mixed, $domElement=null, $DOMDocument=null) {
    if (is_null($DOMDocument)) {
        $DOMDocument = new DOMDocument;
		$DOMDocument->encoding = 'utf-8';
        $DOMDocument->formatOutput = false;
		$bodyElement = $DOMDocument->appendChild($DOMDocument->createElement('data'));
        xml_encode($mixed, $bodyElement, $DOMDocument);
        echo $DOMDocument->saveXML();
    } else {
        if (is_array($mixed)) {
            foreach ($mixed as $index => $mixedElement) {
				$node = $DOMDocument->createElement($index);
				$domElement->appendChild($node);
                xml_encode($mixedElement, $node, $DOMDocument);
            }
        }
        else {
            $domElement->appendChild($DOMDocument->createTextNode($mixed));
        }
    }
}

?>