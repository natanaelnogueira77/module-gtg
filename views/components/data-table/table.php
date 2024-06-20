<div class="table-responsive-lg">
    <?= $view->getPagination() ?>
    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
        <?= $view->getHeader() ?>
        <?= $view->getBody() ?>
    </table>
</div>