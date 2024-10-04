<h1>Update User</h1>

<?= form_open('users/edit/' . $item->id) ?>
  <?= form_hidden('_method', 'PUT') ?>

  <div class="mb-3">
    <?= form_label('Name', 'name', ['class' => 'form-label mb-0']) ?>
    <?= form_input('name', set_value('name', $item->name), 'class="form-control"') ?>
    <?= form_error('name', '<div><span class="text-danger small">', '</span></div>') ?>
  </div>

  <div class="mb-3">
    <?= form_label('Email', 'email', ['class' => 'form-label mb-0']) ?>
    <?= form_input(['type' => 'email', 'name' => 'email', 'value' => set_value('email', $item->email), 'class' => 'form-control']) ?>
    <?= form_error('email', '<div><span class="text-danger small">', '</span></div>') ?>
  </div>

  <div><?= isset($error) ? $error : '' ?></div>

  <?= anchor('users', 'Cancel', 'class="btn btn-link text-secondary text-decoration-none"') ?>
  <?= form_submit(null, 'Update', 'class="btn btn-primary"') ?>
<?= form_close() ?>