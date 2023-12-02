<div class="table-responsive-lg" style="overflow-y: visible;">
    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
        <?php 
            $this->insert('_components/data-table-thead', [
                'headers' => $headers,
                'order' => [
                    'selected' => $order,
                    'type' => $orderType
                ]
            ]);
            
            $this->insert('_components/data-table-tbody', [
                'headers' => $headers,
                'data' => $data
            ]);
        ?>
    </table>
</div>