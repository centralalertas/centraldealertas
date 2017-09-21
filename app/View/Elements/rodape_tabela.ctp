<div class="row">
    <?php
        if(!$threaded) {
    ?>
        <div class="col-xs-6">
            <h6>&nbsp;&nbsp;
            <?php
                echo $this->Paginator->counter(array(
                    'format' => 'Página %page% de %pages%, exibindo %current% de %count% registro(s)'
                ));
            ?>
            </h6>
        </div>
    
        <div class="col-xs-6">
            <div class="pull-right">
                <ul class="pagination">
                    <?php
                        $configuracao   = array('tag' => 'li', 'separator' => '', 'currentClass' => 'active', 'currentTag' => 'span', 'escape'=>false);
                        
                        echo $this->Paginator->prev('<span><<</span>', $configuracao, null, array('class' => 'disabled'));
                        echo $this->Paginator->numbers($configuracao);
                        echo $this->Paginator->next('<span>>></span>', $configuracao, null, array('class' => 'disabled')); ?>
                </ul>
                &nbsp;&nbsp;
            </div>
        </div><!-- /.span -->
    <?php
        }
    ?>
</div><!-- /.row -->