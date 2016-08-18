<h3>Introducing</h3>
<p>
We are very happy that you decided to choose the CMS cii. However, you have not set up your application yet.
This script provides a guide for a simple step by step installation for Cii.
The first step checks if your server is able to run Cii
</p>

<h3>Requirements</h3>
<p>
There are two kinds of requirements being checked. Mandatory requirements are those that have to be met
to allow Cii to work as expected. There are also some optional requirements being checked which will
show you a warning when they do not meet. You can use Cii CMS without them but some specific
functionality may be not available in this case.
</p>

<h3>Conclusion</h3>
<?php if ($summary['errors'] > 0): ?>
    <div class="alert alert-danger">
        <strong>Unfortunately your server configuration does not satisfy the requirements by this application.<br>Please refer to the table below for detailed explanation.</strong>
    </div>
<?php elseif ($summary['warnings'] > 0): ?>
    <div class="alert alert-info">
        <strong>Your server configuration satisfies the minimum requirements by this application.<br>Please pay attention to the warnings listed below and check if your application will use the corresponding features.</strong>
    </div>
<?php else: ?>
    <div class="alert alert-success">
        <strong>Congratulations! Your server configuration satisfies all requirements.</strong>
    </div>
<?php endif; ?>

<h3>Details</h3>

<table class="table table-bordered">
    <tr><th>Name</th><th>Result</th><th>Required By</th><th>Memo</th></tr>
    <?php foreach ($requirements as $requirement): ?>
    <tr class="<?php echo $requirement['condition'] ? 'success' : ($requirement['mandatory'] ? 'danger' : 'warning') ?>">
        <td>
        <?php echo $requirement['name'] ?>
        </td>
        <td>
        <span class="result"><?php echo $requirement['condition'] ? 'Passed' : ($requirement['mandatory'] ? 'Failed' : 'Warning') ?></span>
        </td>
        <td>
        <?php echo $requirement['by'] ?>
        </td>
        <td>
        <?php echo $requirement['memo'] ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<div class="container-fluid">
    <div class="row">
        <a href="<?php $url; ?>index.php?r=paths" class="pull-right btn btn-primary">Next</a>
    </div>
</div>