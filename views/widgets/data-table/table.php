<div class="table-responsive-lg">
    <?php $this->insert('widgets/data-table/pagination', ['activeRecordList' => $activeRecordList]); ?>
    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
        <?php 
            $this->insert('widgets/data-table/header', [
                'header' => $header, 
                'activeRecordList' => $activeRecordList
            ]);
            $this->insert('widgets/data-table/body', ['body' => $body]);
        ?>
    </table>
</div>