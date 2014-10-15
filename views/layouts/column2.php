
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <?php echo $content; ?>
            </div>
            <div class="col-md-3">

            </div>
        </div>
    </div>
<?php $this->endContent(); ?>