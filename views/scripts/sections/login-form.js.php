<script>
    $(function () {
        const formId = <?= json_encode($widget->getFormId()) ?>;
        const dynamicForm = App.getDynamicForm(
            $(`#${formId}`)
        ).onSuccess(function(instance, response) {
            if(response.redirectURL) {
                window.location.href = response.redirectURL;
            }
        }).setDoubleClickPrevention(
            $("button[type='submit'][form='<?= $widget->getFormId() ?>']")
        ).apply();
    });

    function onSubmit(token) {
        document.getElementById(formId).submit();
    }
</script>