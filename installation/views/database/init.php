<?php
$this->title = 'Initialize database';
?>


<h3>Initialize database</h3>
<p>
The connection is now set up. This script will test if the database is set up correctly. 
This will create the Yii migrate table and import the default core modules of Cii
</p>


<?php if ($installed) { ?>
    <div class="alert alert-success">
        <strong>Congratulations! Your database is set up correctly.</strong>
    </div>
<?php }else { ?>
    <div class="alert alert-warning">
        <strong>Your database is not set up correctly.</strong>
    </div>
<?php } ?>


<div class="container-fluid">
    <div class="row">
        <a href="<?php $url; ?>index.php?r=database/index" class="pull-left btn btn-default">Previous</a>
        
        <a
         href="<?php $url; ?>index.php?r=database/user"
         class="<?php if(!$installed) {echo 'disabled ';} ?>pull-right btn btn-primary"
        >Next</a>

        <a
         class="<?php if($installed) {echo 'disabled ';} ?>btn btn-default pull-right"
         href="<?php $url; ?>index.php?r=database/install"
         style="margin-right: 10px;"
        >Install database</a>
    </div>
</div>
