<script>
    MediaLibrary.setAddFileRoute(<?php echo json_encode($router->route('mediaLibrary.addFile')) ?>);
    MediaLibrary.setRemoveFileRoute(<?php echo json_encode($router->route('mediaLibrary.deleteFile')) ?>);
    MediaLibrary.setLoadFilesRoute(<?php echo json_encode($router->route('mediaLibrary.loadFiles')) ?>);
    MediaLibrary.setErrorMessages(<?php echo json_encode([
        'allowed_extensions' => _('A extensão deste arquivo não é permitida aqui! Extensões permitidas: '),
        'size_limit' => sprintf(_('O arquivo que você tentou enviar é maior do que %sMB!'), '{size}'),
        'failed_to_read' => _("Lamentamos, mas ocorreu um erro ao ler o arquivo!")
    ]) ?>);
</script>