<script>
    $(function () {
        const form = $("#login-form");
        const modal = $("#login-modal");

        var isSessionExpired = false;

        function checkSession() 
        {
            App.ajax({
                url: <?= json_encode($router->route('auth.expired')) ?>,
                type: 'post',
                success: function(response) {
                    if(response.success) {
                        modal.modal("show");
                        isSessionExpired = true;
                    }
                }
            });
        }
        
        var countInterval = setInterval(function() {
            checkSession();
            if(isSessionExpired == true) {
                clearInterval(countInterval);
            }
        }, 10000);

        App.getDynamicForm($("#login-form")).onSuccess((response) => modal.modal('toggle')).setDoubleClickPrevention(
            modal.find("button[type='submit'][form='login-form']")
        ).apply();
    });
</script>