<?php

namespace NFePHP\NFs\RJ;

use NFePHP\Common\Strings;
use DOMElement;
use NFePHP\Common\DOMImproved as Dom;

class Make{

	public $xml;

	public $dom;

	public $version = 1;

	public function __construct(){

		$this->dom = new Dom('1.0', 'UTF-8');
		$this->dom->preserveWhiteSpace = false;
		$this->dom->formatOutput = false;
	}

	public function clearDom(){

		$this->dom = new Dom('1.0', 'UTF-8');
		$this->dom->preserveWhiteSpace = false;
		$this->dom->formatOutput = false;

		return $this->dom;
	}
	
    public function setVersion($version){
    	$this->version = $version;
    }

	public function GenerateXMLConsultarNfseEnvio(
		$toCNPJ,
		$prCNPJ, 
		$dateInit, 
		$dateEnd, 
		$nNfs,
		$inscri
	){

		$consultarNfseEnvio = $this->dom->createElement('ConsultarNfseEnvio');
		
		$consultarNfseEnvio->setAttribute('xmlns', 'http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd');

		$prestador = $this->dom->createElement('Prestador');

		$this->dom->addChild(
            $prestador,
            "Cnpj", 
            Strings::replaceSpecialsChars(trim($prCNPJ)),
            true,
			"PrestadorCNPJ"
        );

        $this->dom->addChild(
            $prestador,
            "InscricaoMunicipal",
            Strings::replaceSpecialsChars(trim($inscri)),
            false,
			"InscricaoMunicipal"
        );

        $this->dom->appChild($consultarNfseEnvio, $prestador, 'Falta tag "Cabecalho"');

        $this->dom->addChild(
            $consultarNfseEnvio,
            "NumeroNfse",
            Strings::replaceSpecialsChars(trim($nNfs)),
            false,
			"numero nfs"
        );

		if ($dateInit != null || $dateEnd != null) {

			$periodoEmissao = $this->dom->createElement("PeriodoEmissao");
			
			$this->dom->addChild(
        	    $periodoEmissao,
        	    "DataInicial",
        	    Strings::replaceSpecialsChars(trim($dateInit)),
        	    true,
				"DataInicial"
        	);

			$this->dom->addChild(
        	    $periodoEmissao,
        	    "DataFinal",
        	    Strings::replaceSpecialsChars(trim($dateEnd)),
        	    true,
				"DataFinal"
        	);

			$this->dom->appChild($consultarNfseEnvio, $periodoEmissao, 'Falta tag "consultarNfseEnvio"');

		}

		if ($toCNPJ){
			
			$tomador = $this->dom->createElement("Tomador");
			
			$cpfcnpj = $this->dom->createElement("CpfCnpj");

			$this->dom->addChild(
	            $cpfcnpj,
	            "Cnpj",
	            Strings::replaceSpecialsChars(trim($toCNPJ)),
	            false,
				"TomadorCNPJ"
	        );

			$this->dom->appChild($tomador, $cpfcnpj, 'Falta tag "consultarNfseEnvio"');
			
			$this->dom->appChild($consultarNfseEnvio, $tomador, 'Falta tag "consultarNfseEnvio"');

	    }

		$this->dom->appendChild($consultarNfseEnvio);
 		
		return $this->dom->saveXML();
	}

}
?>