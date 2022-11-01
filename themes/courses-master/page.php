<?php $this->layout("_theme"); ?>

<?php $this->start("css"); ?>
<?php 
    if($styles) {
        foreach($styles as $style) {
            echo "<link rel=\"stylesheet\" href=\"{$style}\">";
        }
    }
?>
<?php $this->end(); ?>

<?php echo $template; ?>

<?php $this->start("modals"); ?>
<?php 
    if($modals) {
        foreach($modals as $modal) {
            echo $modal;
        }
    }
?>
<?php $this->end(); ?>

<?php $this->start("scripts"); ?>
<?php 
    if($scripts) {
        foreach($scripts as $script) {
            echo "<script src=\"{$script}\"></script>";
        }
    }
?>
<?php $this->end(); ?>