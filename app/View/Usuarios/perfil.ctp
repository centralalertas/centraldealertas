<?php
    echo $this->Form->create('Usuario', array('inputDefaults' => array('class'=>'form-control')) );
    echo $this->Form->hidden('id');
    echo $this->Form->hidden('cliente_id');
    echo $this->Form->hidden('role');
    echo $this->Form->input('nome');
    echo $this->Form->input('username', array('label'=>'Usuário/Email'));
    echo $this->Form->input('password', array('label'=>'Senha', 'value'=>''));
?>

    <button type="submit" class="width-20 pull-right btn btn-sm btn-primary">
            <span class="bigger-110">Alterar</span>
    </button>

<?php
    echo $this->Form->end();
?>