<?php ob_start() ?>

    <?= render(TEMPLATES_PATH.'/navbar/main.html.php') ?>

    <div class="container">
        <?php if ($title ?? null): ?>
            <h1 class="page-header"><?= $title ?></h1>
        <?php endif ?>
        <div class="row">
            <div class="col-md-12">
                <?= $content ?? '' ?>
            </div>
        </div>
    </div>

<?php $_PARAMETERS['document'] = ob_get_clean(); ?>
<?php echo render(TEMPLATES_PATH.'/document.html.php', $_PARAMETERS);

