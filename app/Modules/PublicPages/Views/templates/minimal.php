<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($page['title']) ?></title>
    
    <!-- Solo estilos mínimos para legibilidad -->
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            color: #333;
        }
        h1, h2, h3, h4, h5, h6 { 
            margin-top: 1.5em; 
            margin-bottom: 0.5em; 
        }
        p { 
            margin-bottom: 1em; 
            text-align: justify;
        }
        ul, ol { 
            margin-bottom: 1em; 
        }
        li { 
            margin-bottom: 0.25em; 
        }
        a { 
            color: #0066cc; 
            text-decoration: underline; 
        }
        a:hover { 
            text-decoration: none; 
        }
        blockquote {
            border-left: 4px solid #ccc;
            margin: 1em 0;
            padding-left: 1em;
            font-style: italic;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1em;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        code {
            background-color: #f4f4f4;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
        }
        pre {
            background-color: #f4f4f4;
            padding: 1em;
            border-radius: 5px;
            overflow-x: auto;
        }
        .page-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 1em;
            margin-bottom: 2em;
        }
        .page-meta {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 1em;
        }
        .page-stats {
            color: #888;
            font-size: 0.85em;
            margin-bottom: 1em;
        }
        .page-actions {
            border-top: 1px solid #eee;
            padding-top: 1em;
            margin-top: 2em;
        }
        .like-btn {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 0.5em 1em;
            text-decoration: none;
            color: #495057;
            display: inline-block;
            margin-right: 1em;
        }
        .like-btn:hover {
            background: #e9ecef;
        }
        .like-btn.active {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        .social-share a {
            margin-right: 0.5em;
            text-decoration: none;
            padding: 0.25em 0.5em;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Page Header -->
    <header class="page-header">
        <h1><?= esc($page['title']) ?></h1>
        
        <?php if ($page['show_date'] || $page['show_author']): ?>
            <div class="page-meta">
                <?php if ($page['show_date']): ?>
                    <span>📅 <?= date('d \d\e F \d\e Y', strtotime($page['published_at'] ?: $page['created_at'])) ?></span>
                <?php endif; ?>
                
                <?php if ($page['show_author'] && isset($page['author'])): ?>
                    <span> • 👤 <?= esc($page['author']['first_name'] . ' ' . $page['author']['last_name']) ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Page Stats -->
        <div class="page-stats">
            👁️ <span id="viewsCount"><?= number_format($page['views_count']) ?></span> vistas
            • ❤️ <span id="likesCount"><?= number_format($page['likes_count']) ?></span> likes
        </div>
    </header>

    <!-- Featured Image -->
    <?php if (!empty($page['featured_image'])): ?>
        <div style="margin-bottom: 2em;">
            <img src="<?= esc($page['featured_image']) ?>" 
                 alt="<?= esc($page['title']) ?>" 
                 style="width: 100%; height: auto;">
        </div>
    <?php endif; ?>

    <!-- Page Content (HTML Puro) -->
    <main>
        <?php echo $page['content']; ?>
    </main>

    <!-- Page Actions -->
    <footer class="page-actions">
        <div>
            <!-- Like Button -->
            <a href="#" class="like-btn <?= $hasLiked ? 'active' : '' ?>" 
               onclick="toggleLike(<?= $page['id'] ?>); return false;">
                ❤️ <span class="like-text"><?= $hasLiked ? 'Te gusta' : 'Me gusta' ?></span>
            </a>
            
            <?php if ($page['social_sharing']): ?>
                <span>Compartir: </span>
                <a href="<?= base_url('p/share/' . $page['slug'] . '/facebook') ?>" target="_blank">📘 Facebook</a>
                <a href="<?= base_url('p/share/' . $page['slug'] . '/twitter') ?>" target="_blank">🐦 Twitter</a>
                <a href="<?= base_url('p/share/' . $page['slug'] . '/whatsapp') ?>" target="_blank">💬 WhatsApp</a>
                <a href="<?= base_url('p/qr/' . $page['slug']) ?>" target="_blank">📱 QR</a>
            <?php endif; ?>
        </div>
    </footer>

    <!-- Comments Section -->
    <?php if (isset($showComments) && $showComments): ?>
        <section style="border-top: 1px solid #eee; padding-top: 2em; margin-top: 2em;">
            <h3>💬 Comentarios</h3>
            <div style="margin-bottom: 1em;">
                <textarea placeholder="Escribe tu comentario..." style="width: 100%; padding: 0.5em; border: 1px solid #ddd;" rows="3"></textarea>
                <br>
                <button type="button" style="margin-top: 0.5em; padding: 0.5em 1em; background: #007bff; color: white; border: none;">Comentar</button>
            </div>
            <div>
                <!-- Comments will be loaded here via AJAX -->
            </div>
        </section>
    <?php endif; ?>

    <!-- Scripts mínimos -->
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
    </script>
</body>
</html>
