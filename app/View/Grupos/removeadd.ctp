<br>
<div class="row">
        <div class="col-xs-12">
           <table class="table table-striped table-bordered table-hover" id="list_crud">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Mover para o grupo?</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                	$string = "";
                	foreach ($query as $a => $b) {
                        //echo "<pre>";print_r($b);echo "</pre>";
                		$string .= " 
                			<tr>
                				<td width='478'>{$b['c']['nome']} {$b['c']['sobrenome']}</td>
	                			<td>
                                    <form action='{$this->base}/grupos/changer' method='post' accept-charset='utf-8' id='form-{$b['c']['id']}'>
                                        <input type='hidden' name='code_contato' id='code_contato' value='{$b['c']['id']}'>
                                        <input type='hidden' name='code_grupo' id='code_grupo' value='{$b['g']['id']}'>
    	                				<select class='form-control chosen-select-{$b['c']['id']}' name='move'>";
	               		foreach ($options as $c => $d) {
                			$string .= "     <option value='{$c}'>{$d}</option>";
	               		}

                		$string .= " 
    	                				</select>
    	                				<script>
                                            $('.chosen-select-{$b['c']['id']}').chosen(); 
                                            $('.chosen-select-{$b['c']['id']}').chosen().change(function(){
                                                $('#form-{$b['c']['id']}').submit();
                                            });                                       
                                        </script>
                                    </form>
	                			</td>";
                		$string .= " 
                			</tr>
                		";
                	}
                	echo $string;
                 ?>
                </tbody>
        	</table>
        </div>
</div>

<?php
    echo $this->element('rodape_tabela', array('threaded'=>true));
?>