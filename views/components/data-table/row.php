<tr>
    <?php 
        foreach($component->getCells() as $cell) {
            $this->insert('components/data-table/cell', ['component' => $cell]);
        }
    ?>
</tr>