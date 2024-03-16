<div id="left-sidebar" class="col-auto col-md-3 col-xl-2 px-md-2 px-0 shadow <?= $component->getBackgroundColor() ?>">
    <aside class="d-flex flex-column align-items-center align-items-md-start px-4 px-md-0 pt-2 
        min-vh-100 <?= $component->getBackgroundColor() ?> <?= $component->getTextColor() ?>">
        <?php $this->insert('components/main-layout/left-menu', ['component' => $component->getMenu()]); ?>
    </aside>
</div>