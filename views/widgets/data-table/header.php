<thead>
    <?php  
        foreach($header as $head) {
            $this->insert('widgets/data-table/head', [
                'head' => $head, 
                'activeRecordList' => $activeRecordList
            ]);
        }
    ?>
</thead>