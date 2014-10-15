<?php
/* @var $content string */
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <div class="container">
        <div id="content">
            <?php echo $content; ?>
        </div>
    </div>
<?php $this->endContent(); ?>