<?php

$this->title = 'Configure paths';

?><h3>Introducing</h3>
<p>
Make sure the following paths are writable, non-writable, readable, non-readable or executable. FRWE = File Readable Writable Executable
</p>


<h3>Conclusion</h3>
<?php if ($summary['errors'] > 0): ?>
    <div class="alert alert-danger">
        <strong>
            Unfortunately some path are not set up correctly for the installation script to proceed.
            Proceed manually or correct the bellow paths.
        </strong>
    </div>
<?php else: ?>
    <div class="alert alert-success">
        <strong>Congratulations! Your server configuration satisfies all requirements.</strong>
    </div>
<?php endif; ?>

<h3>Details</h3>

<table class="table table-bordered">
    <tr>
        <th>Result</th>
        <th class="text-center">Expected FRWE</th>
        <th class="text-center">Found FRWE</th>
        <th>Path</th>
    </tr>
    
    <?php foreach ($requirements as $requirement): ?>
    <tr class="<?php echo !$requirement['error'] ? 'success' : 'danger'; ?>">
        <td>
        <span class="result"><?php echo !$requirement['error'] ? 'Passed' : 'Failed'; ?></span>
        </td>
        <td class="text-center">
        <?php
        echo $requirement['isFile'] ? '√' : 'X';
        echo $requirement['isReadable'] ? '√' : 'X';
        echo $requirement['isWritable'] ? '√' : 'X';
        echo $requirement['isExecutable'] ? '√' : 'X';
        ?>
        </td>
        <td class="text-center">
        <?php
        echo $requirement['is_f'] ? '√' : 'X';
        echo $requirement['is_r'] ? '√' : 'X';
        echo $requirement['is_w'] ? '√' : 'X';
        echo $requirement['is_e'] ? '√' : 'X';
        ?>
        </td>
        <td>
        <?php echo $requirement['path'] ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<div class="container-fluid">
    <div class="row">
        <a href="<?php $url; ?>index.php?r=requirements" class="pull-left btn btn-default">Previous</a>
        <a
         href="<?php $url; ?>index.php?r=database"
         class="<?php if($summary['errors'] > 0) {echo 'disabled ';} ?>pull-right btn btn-primary"
        >Next</a>
    </div>
</div>