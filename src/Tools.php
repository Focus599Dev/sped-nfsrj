<?php 

namespace NFePHP\NFs\RJ;

/**
 * @category   NFePHP
 * @package    NFePHP\NFs\RJ
 * @copyright  Copyright (c) 2008-2017
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Marlon O.Barbosa <marlon.academi@gmail.com>
 * @link       http://github.com/nfephp-org/sped-nfsrj for the canonical source repository
 */

use NFePHP\NFs\RJ\Common\Tools as ToolsCommon;
use NFePHP\Common\Signer;
use NFePHP\Common\Strings;
use NFePHP\NFs\RJ\Make;

class Tools extends ToolsCommon{
  
  /**
     * Serviço de distribuição de informações de documentos eletronicos
     * de interesse do remetente
     * @param string $CPFCNPJRemet CPF/CNPJ do Remetente autorizado a enviar a mensagem XML.
     * @param string $CPFCNPJ Informe o CPF/CNPJ do tomador da NF-e.
     * @param date   $dateIni Data início da consulta.
     * @param date   $dateEnd Data fim da consulta.
     * @param date   $page Data fim da consulta.
     * @return string
     */
    public function consultaNFs(
        $prCNPJ,  
        $nNfs
    ) {

        $servico = 'ConsultarNfse';

        $this->servico(
            $servico,
            'RJ',
            $this->tpAmb,
            true
        );

        $makeXML = new Make();

        $request = $makeXML->GenerateXMLConsultarNfseEnvio($this->config->cnpj, $prCNPJ, '', '', $nNfs, '10808502');

        $this->isValid($this->urlVersion, $request, 'nfse_pcrj');

        var_dump($request);

        $body = $this->makeBody('ConsultarNfse', $request);

        $parameters = [
            'ConsultarNfse' => $request
        ];
        //este webservice não requer cabeçalho
        $this->objHeader = null;
        
        $this->lastResponse = $this->sendRequest($body, $parameters);

        var_dump($this->lastResponse);

        return $this->lastResponse;

    }
}
?>