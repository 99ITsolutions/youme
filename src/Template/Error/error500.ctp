<?php //echo "error"; die;
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';

if (Configure::read('debug')) :
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error500.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?php Debugger::dump($error->params) ?>
<?php endif; ?>
<?php if ($error instanceof Error) : ?>
        <strong>Error in: </strong>
        <?= sprintf('%s, line %s', str_replace(ROOT, 'ROOT', $error->getFile()), $error->getLine()) ?>
<?php endif; ?>
<?php
    echo $this->element('auto_table_warning');

    if (extension_loaded('xdebug')) :
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;
?>
<style>
    #header h1 , #content h2
    {
        display:none !important;
    }
    #footer, #content { text-align:center; }
    #content img { width:250px !important; }
    #content img, #content p
    {
        margin-bottom: 50px;
    }
    #footer a {
        background: #fea812;
        padding: 10px;
        color: #ffffff;
        border-radius: 5px;
    }
</style>
<h2><?= __d('cake', 'An Internal Error Has Occurred') ?></h2>
<img src="https://you-me-globaleducation.org/wp-content/uploads/2021/03/You-Me-Final-02.png" alt="You-Me Global Education" id="logo" data-height-percentage="87" data-actual-width="479" data-actual-height="184">
<p class="error" style="text-align:center">
    <!--<strong><?= __d('cake', 'Error') ?>: </strong>
    <?php //echo h($message) ?>-->
    Some error occured. Please click the back button to try again
</p>
