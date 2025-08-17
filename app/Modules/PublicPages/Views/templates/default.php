<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($page['title']) ?> - <?= env('app.name', 'Mi Sitio') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/app.min.css') ?>" rel="stylesheet">
    <!-- Icons -->
    <link href="<?= base_url('assets/css/icons.min.css') ?>" rel="stylesheet">
    
    <?php if (!empty($page['custom_css'])): ?>
        <style><?= $page['custom_css'] ?></style>
    <?php endif; ?>
</head>
<body class="public-page">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <article class="page-content">
                    <!-- Page Header -->
                    <header class="page-header mb-4">
                        <h1 class="page-title"><?= esc($page['title']) ?></h1>
                        
                        <?php if ($page['show_date'] || $page['show_author']): ?>
                            <div class="page-meta text-muted mb-3">
                                <?php if ($page['show_date']): ?>
                                    <span class="page-date">
                                        <i class="bx bx-calendar"></i>
                                        <?= date('d \d\e F \d\e Y', strtotime($page['published_at'] ?: $page['created_at'])) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($page['show_author'] && isset($page['author'])): ?>
                                    <span class="page-author ms-3">
                                        <i class="bx bx-user"></i>
                                        <?= esc($page['author']['first_name'] . ' ' . $page['author']['last_name']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Page Stats -->
                        <div class="page-stats mb-3">
                            <span class="stat-item me-3">
                                <i class="bx bx-show"></i>
                                <span id="viewsCount"><?= number_format($page['views_count']) ?></span> vistas
                            </span>
                            <span class="stat-item">
                                <i class="bx bx-heart"></i>
                                <span id="likesCount"><?= number_format($page['likes_count']) ?></span> likes
                            </span>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    <?php if (!empty($page['featured_image'])): ?>
                        <div class="featured-image mb-4">
                            <img src="<?= esc($page['featured_image']) ?>" 
                                 alt="<?= esc($page['title']) ?>" 
                                 class="img-fluid rounded">
                        </div>
                    <?php endif; ?>

                    <!-- Page Content -->
                    <div class="page-body">
                        <?php echo $page['content']; ?>
                    </div>

                    <!-- Page Actions -->
                    <div class="page-actions mt-4 pt-4 border-top">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <!-- Like Button -->
                                <button type="button" class="btn btn-outline-danger like-btn <?= $hasLiked ? 'active' : '' ?>" 
                                        onclick="toggleLike(<?= $page['id'] ?>)">
                                    <i class="bx bx-heart"></i>
                                    <span class="like-text"><?= $hasLiked ? 'Te gusta' : 'Me gusta' ?></span>
                                </button>
                            </div>
                            
                            <?php if ($page['social_sharing']): ?>
                                <div class="col-md-6 text-md-end">
                                    <div class="social-share">
                                        <span class="me-2">Compartir:</span>
                                        <a href="<?= base_url('p/share/' . $page['slug'] . '/facebook') ?>" 
                                           class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="bx bxl-facebook"></i>
                                        </a>
                                        <a href="<?= base_url('p/share/' . $page['slug'] . '/twitter') ?>" 
                                           class="btn btn-sm btn-outline-info" target="_blank">
                                            <i class="bx bxl-twitter"></i>
                                        </a>
                                        <a href="<?= base_url('p/share/' . $page['slug'] . '/whatsapp') ?>" 
                                           class="btn btn-sm btn-outline-success" target="_blank">
                                            <i class="bx bxl-whatsapp"></i>
                                        </a>
                                        <a href="<?= base_url('p/qr/' . $page['slug']) ?>" 
                                           class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="bx bx-qr"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <?php if (isset($showComments) && $showComments): ?>
                        <div class="comments-section mt-5 pt-4 border-top">
                            <h4>Comentarios</h4>
                            <div class="comment-form mb-4">
                                <textarea class="form-control" placeholder="Escribe tu comentario..." rows="3"></textarea>
                                <button type="button" class="btn btn-primary mt-2">Comentar</button>
                            </div>
                            <div class="comments-list">
                                <!-- Comments will be loaded here via AJAX -->
                            </div>
                        </div>
                    <?php endif; ?>
                </article>
            </div>
        </div>
    </div>

    <!-- Back to Top -->
    <button type="button" class="btn btn-primary btn-sm position-fixed bottom-0 end-0 m-3" 
            id="backToTop" style="display: none;" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="bx bx-up-arrow-alt"></i>
    </button>

    <!-- Scripts -->
    <script src="<?= base_url('assets/libs/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        // Like functionality
        function toggleLike(pageId) {
            fetch('<?= base_url('p/like') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `page_id=${pageId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likeBtn = document.querySelector('.like-btn');
                    const likeText = document.querySelector('.like-text');
                    const likesCount = document.getElementById('likesCount');
                    
                    if (data.liked) {
                        likeBtn.classList.add('active');
                        likeText.textContent = 'Te gusta';
                    } else {
                        likeBtn.classList.remove('active');
                        likeText.textContent = 'Me gusta';
                    }
                    
                    likesCount.textContent = new Intl.NumberFormat().format(data.likes_count);
                } else {
                    alert(data.message || 'Error al procesar la acción');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión');
            });
        }

        // Back to top button
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });

        // Copy link functionality
        function copyPageLink() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Enlace copiado al portapapeles');
            });
        }
    </script>

    <?php if (!empty($page['custom_js'])): ?>
        <script><?= $page['custom_js'] ?></script>
    <?php endif; ?>

    <style>
        .public-page {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .page-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .page-meta {
            font-size: 0.9rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }

        .page-stats {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .stat-item i {
            margin-right: 0.25rem;
        }

        .featured-image img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }

        .page-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }

        .page-body h1, .page-body h2, .page-body h3, 
        .page-body h4, .page-body h5, .page-body h6 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .page-body p {
            margin-bottom: 1.5rem;
        }

        .page-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .page-body blockquote {
            border-left: 4px solid #007bff;
            padding-left: 1rem;
            margin: 2rem 0;
            font-style: italic;
            color: #6c757d;
        }

        .like-btn.active {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .social-share a {
            margin-left: 0.5rem;
        }

        .comments-section {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .public-page {
                padding: 1rem 0;
            }

            .page-content {
                padding: 1.5rem;
                margin: 0 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .page-meta {
                flex-direction: column;
            }

            .page-meta .page-author {
                margin-left: 0 !important;
                margin-top: 0.5rem;
            }
        }
    </style>
</body>
</html>
