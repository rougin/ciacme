<h1>Users</h1>

<div><?= isset($alert) ? $alert : '' ?></div>

<div>
  <a href="<?= base_url('users/create') ?>">Create New User</a>
</div>

<div>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= $item->name; ?></td>
          <td><?= $item->email; ?></td>
          <td>
            <a href="<?= base_url('users/edit/' . $item->id) ?>">Edit</a>
            <a href="javascript:void(0)" onclick="remove(<?= $item->id ?>)">Delete</a>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>

<script>
  remove = function (id)
  {
    console.log('users', id)
  }
</script>