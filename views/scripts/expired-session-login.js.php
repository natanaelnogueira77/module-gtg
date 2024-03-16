<script>
    $(function () {
        const form = $("#<?= $formId ?>");
        const modal = $("#<?= $modalId ?>");

        var isSessionExpired = false;

        function checkSession() 
        {
            App.ajax({
                url: <?php echo json_encode($router->route('auth.expired')) ?>,
                type: 'post',
                success: function(response) {
                    if(response.success) {
                        modal.modal("show");
                        isSessionExpired = true;
                    }
                }
            });
        }
        
        var countInterval = setInterval(function () {
            checkSession();
            if(isSessionExpired == true) {
                clearInterval(countInterval);
            }
        }, 10000);

        App.getDynamicForm($("#<?= $formId ?>")).onSuccess(function (response) {
            modal.modal('toggle');
        }).setDoubleClickPrevention(
            modal.find("button[type='submit'][form='<?= $formId ?>']")
        ).apply();
    });
</script>