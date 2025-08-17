<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Páginas Públicas<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Páginas Públicas</li>
                    </ol>
                </div>
                <h4 class="page-title">Páginas Públicas</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Lista de Páginas Disponibles</h4>
                    <p class="text-muted">Explora las páginas públicas disponibles en el sistema.</p>

                    <?php if (empty($pages)): ?>
                        <div class="text-center py-5">
                            <i class="bx bx-file-blank display-1 text-muted"></i>
                            <h5 class="mt-3">No hay páginas públicas disponibles</h5>
                            <p class="text-muted">Aún no se han publicado páginas en el sistema.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($pages as $page): ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <?php if (!empty($page['featured_image'])): ?>
                                            <img src="<?= esc($page['featured_image']) ?>" class="card-img-top" alt="<?= esc($page['title']) ?>" style="height: 200px; object-fit: cover;">
                                        <?php endif; ?>
                                        
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?= esc($page['title']) ?></h5>
                                            
                                            <?php if (!empty($page['excerpt'])): ?>
                                                <p class="card-text text-muted"><?= esc($page['excerpt']) ?></p>
                                            <?php endif; ?>
                                            
                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <small class="text-muted">
                                                        <i class="bx bx-calendar"></i>
                                                        <?= date('d/m/Y', strtotime($page['published_at'] ?: $page['created_at'])) ?>
                                                    </small>
                                                    
                                                    <?php if ($page['show_author'] && isset($page['author'])): ?>
                                                        <small class="text-muted">
                                                            <i class="bx bx-user"></i>
                                                            <?= esc($page['author']['first_name'] . ' ' . $page['author']['last_name']) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <small class="text-muted">
                                                        <i class="bx bx-show"></i> <?= number_format($page['views_count']) ?> vistas
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="bx bx-heart"></i> <?= number_format($page['likes_count']) ?> likes
                                                    </small>
                                                </div>
                                                
                                                <a href="<?= base_url('p/' . $page['slug']) ?>" class="btn btn-primary btn-sm w-100">
                                                    <i class="bx bx-link-external"></i> Ver Página
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if (isset($pager)): ?>
                            <div class="d-flex justify-content-center mt-4">
                                <?= $pager->links() ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.card-img-top {
    border-radius: 0.375rem 0.375rem 0 0;
}

.btn-sm {
    font-size: 0.875rem;
}

.display-1 {
    font-size: 6rem;
}
</style>
<?= $this->endSection() ?>
