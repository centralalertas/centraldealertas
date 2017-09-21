<?php

class RetornosController extends AppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add');
    }
    
    public function index() {
    }
    
    public function add() {
	try{
            if (isset($this->request->query['data_callback']))
            {
                $linhas =  preg_split( '/\r\n|\r|\n/', $this->request->query['data_callback']);

                $content = '';

                foreach ($linhas as $linha)
                {
                    if ($linha != '')
                    {
                        $conteudo = explode(';', $linha);

                        if (count($conteudo) == 6)
                        {
                            $retorno    = array();
                            //$retorno['Retorno']['mensagem_id'] = ;
                            $retorno['Retorno']['campanha'] = $conteudo[0];
                            $retorno['Retorno']['celular']  = $conteudo[1];
                            $retorno['Retorno']['resposta'] = $conteudo[2];
                            $retorno['Retorno']['data']     = $conteudo[3];
                            $retorno['Retorno']['status']   = $conteudo[4];
                            $retorno['Retorno']['key']      = $conteudo[5];
                            
                            $this->Retorno->create();
                            $this->Retorno->save($retorno);
                        }
                    }
                }
            }

            echo 'SUCESSO';
	}
	catch (Exception $ex)
	{
            echo $ex->getMessage();
	}
        exit ();
    }
}

?>