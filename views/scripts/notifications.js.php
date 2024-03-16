<script>
    $(function () {
        $("#bell-notifications").click(function () {
            $.ajax({
                url: <?php echo json_encode($router->route('notifications.markAllAsRead')) ?>,
                type: 'patch',
                data: {},
                dataType: 'json'
            });

            $("#bell-notifications").find("#notifications-number").text('0');
            $("#bell-notifications").find("#notifications-number").hide();
        });
    });
</script>