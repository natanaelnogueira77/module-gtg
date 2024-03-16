<thead>
    <?php  
        foreach($component->getHeads() as $head) {
            $this->insert('components/data-table/head', ['component' => $head]);
        }
    ?>
</thead>