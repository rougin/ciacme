<h1>Create New User</h1>

<?= form_open('users/create') ?>
  <div>
    <?= form_label('Name') ?>
    <?= form_input('name', set_value('name')) ?>
    <?= form_error('name', '<div><span>', '</span></div>') ?>
  </div>

  <div>
    <?= form_label('Email') ?>
    <?= form_input(['type' => 'email', 'name' => 'email', 'value' => set_value('email')]) ?>
    <?= form_error('email', '<div><span>', '</span></div>') ?>
  </div>

  <div><?= isset($error) ? $error : '' ?></div>

  <?= anchor('users', 'Cancel') ?>
  <?= form_submit(null, 'Create') ?>
<?= form_close() ?>