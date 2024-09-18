<h1>Update User</h1>

<?= form_open('users/edit/' . $item->get_id()) ?>
  <?= form_hidden('_method', 'PUT') ?>
  <div>
    <?= form_label('Name') ?>
    <?= form_input('name', set_value('name', $item->get_name())) ?>
    <?= form_error('name', '<div><span>', '</span></div>') ?>
  </div>

  <div>
    <?= form_label('Email') ?>
    <?= form_input(['type' => 'email', 'name' => 'email', 'value' => set_value('email', $item->get_email())]) ?>
    <?= form_error('email', '<div><span>', '</span></div>') ?>
  </div>

  <div><?= isset($error) ? $error : '' ?></div>

  <?= anchor('users', 'Cancel') ?>
  <?= form_submit(null, 'Update') ?>
<?= form_close() ?>