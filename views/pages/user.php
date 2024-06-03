<?php 
    $this->layout('layouts/dashboard', ['theme' => $theme]);
    $this->insert('widgets/layouts/dashboard/title', [
        'title' => _('Painel Principal'),
        'subtitle' => sprintf(_('Seja bem-vindo(a), %s!'), $session->getAuth()->name),
        'icon' => 'pe-7s-home',
        'iconColor' => 'bg-malibu-beach'
    ]);
?>