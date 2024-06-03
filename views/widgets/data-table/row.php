<tr>
    <?php 
        foreach($cells as $content) {
            $this->insert('widgets/data-table/cell', ['content' => $content]);
        }
    ?>
</tr>