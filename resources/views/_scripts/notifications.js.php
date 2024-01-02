<script>
    $(function () {
        <?php if($dbNotifications): ?>
        $("#bell-notifications").click(function () {
            $.ajax({
                url: <?php echo json_encode($router->route('api.user.notifications.markAllAsRead')) ?>,
                type: 'patch',
                data: {},
                dataType: 'json'
            });

            $("#bell-notifications").find("#notifications-number").text('0');
            $("#bell-notifications").find("#notifications-number").hide();
        });
        <?php endif; ?>
    });
</script>