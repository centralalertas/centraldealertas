<?php

App::uses('HttpSocket', 'Network/Http');

class ConsultaController extends AppController {
    
    
    public function index($captcha = '') {
        
        //$HttpSocket = new HttpSocket();
        $site       = "http://getran.detran.df.gov.br";
        $cpf        = '720.873.401-10';
        
        if($captcha == '') {           
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL            => $site . '/site/Captcha.jsp?' . rand(1000, 6000),
                CURLOPT_COOKIESESSION  => true,
                CURLOPT_HEADER         => true ,
                CURLOPT_COOKIEJAR      => 'C:\\Desenvolvimento\\xampplite\\htdocs\\autoescola\\app\tmp\\cookie.txt',
                CURLOPT_COOKIEFILE     => 'C:\\Desenvolvimento\\xampplite\\htdocs\\autoescola\\app\tmp\\cookie.txt'
            ));

            $imagem = curl_exec($ch);
            
            curl_close($ch);

            $fp = \fopen( 'C:\\Desenvolvimento\\xampplite\\htdocs\\autoescola\\app\tmp\\captcha.jpg' , "a");
            fwrite($fp, $imagem);
            fclose($fp);
            
            preg_match( "/Set-Cookie:([^\n]+)/i", $imagem, $matches );
            $cookie = $matches[1];
            debug($cookie);
            
        } else {
            $ch = curl_init(); 
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL            => $site . '/site/habilitacao/consultas/consulta-resultado.jsp',
                CURLOPT_POST           => true,
                CURLOPT_COOKIESESSION  => true,
                CURLOPT_COOKIEJAR      => 'C:\\Desenvolvimento\\xampplite\\htdocs\\autoescola\\app\tmp\\cookie2.txt',
                CURLOPT_COOKIEFILE     => 'C:\\Desenvolvimento\\xampplite\\htdocs\\autoescola\\app\tmp\\cookie2.txt',
                CURLOPT_POSTFIELDS     => array('CPF'=>$cpf, 'CODSEG'=>$captcha, 'submit'=>'Anvançar')
            ));
            
            $consultaCNH = curl_exec($ch);
            curl_close($ch);
            
            $this->set('resultado', $consultaCNH);
        }
        
    }
}

?>