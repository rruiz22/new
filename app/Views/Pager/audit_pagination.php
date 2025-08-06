<?php
/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */

$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
    <ul class="pagination pagination-sm mb-0">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getFirst() ?>" class="page-link">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a href="<?= $pager->getPrevious() ?>" class="page-link">
                    <span aria-hidden="true">‹</span>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a href="<?= $link['uri'] ?>" class="page-link">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getNext() ?>" class="page-link">
                    <span aria-hidden="true">›</span>
                </a>
            </li>
            <li class="page-item">
                <a href="<?= $pager->getLast() ?>" class="page-link">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav> 