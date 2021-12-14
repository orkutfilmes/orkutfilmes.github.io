<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ViaCEP - Biblioteca para CodeIgniter
 * 
 * Tem como objetivo criar um meio para consultar o WebService
 * do ViaCEP e ao mesmo tempo armazenar os dados consultados
 * em uma tabela com tempo para expirar.
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html
 * @author Pablo Alexander da Rocha Gonçalves
 * @link http://www.parg.com.br/
 * @link https://github.com/parg-programador
 */

/**
 * Controller CEP
 */
class Cep extends CI_Controller {

    /**
     * Procedimento padrão do módulo
     */
    public function index() {
        // saída do ajax
        $resposta = array('status' => false);

        $consultar = $this->input->get('consultar');

        // verifica se o cep é válido
        if ($consultar && preg_match("/^[0-9]{5}-[0-9]{3}$/", $consultar)) {
            // carrega a biblioteca
            $this->load->library('viacep');
            
            // consulta no webservice no ViaCEP
            if ($this->viacep->consultar($consultar)) {
                $resposta['status'] = true;
                $resposta['cep'] = $this->viacep->getCEP();
                $resposta['logradouro'] = $this->viacep->getLogradouro();
                $resposta['complemento'] = $this->viacep->getComplemento();
                $resposta['bairro'] = $this->viacep->getBairro();
                $resposta['localidade'] = $this->viacep->getLocalidade();
                $resposta['uf'] = $this->viacep->getUF();
                $resposta['ibge'] = $this->viacep->getIBGE();
                $resposta['gia'] = $this->viacep->getGIA();
            } else {
                $resposta['erro'] = $this->viacep->getUltimoErro();
            }
        }
        
        
        // configura o tipo de conteúdo
        header('Content-Type: application/json');

        // mostra a resposta no formato JSON
        echo json_encode($resposta);
    }

}
