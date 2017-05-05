<?php ob_start() ?>

<?php if (empty($persons)): ?>
    <p class="text-center">Nikt się jeszcze nie zadeklarował</p>
<?php else: ?>
    <h2>Kto się zadeklarował?</h2>
    <table class="table table-bordered">
        <thead>
            <th>Imię i nazwisko</th>
            <th>Co chce zjeść?</th>
        </thead>
        <tbody>
        <?php foreach ($persons as $name => $description): ?>
            <tr>
                <td><?=escape($name)?></td>
                <td><?=escape($description)?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif ?>

    <form action="" method="post">
        <fieldset>
            <legend>Wypełnij dane</legend>
            <div class="form-group">
                <label>Imię i nazwisko</label>
                <input name="name" type="name" class="form-control">
            </div>
            <div class="form-group">
                <label>Co chcesz zjeść?</label>
                <input name="description" type="description" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">
                Zadeklaruj się
            </button>
        </fieldset>
    </form>

<?php $content = ob_get_clean(); ?>
<?php echo render(TEMPLATES_PATH.'/content.html.php', [
    'content' => $content,
    'title' => 'Kulinarne piątki',
]);

