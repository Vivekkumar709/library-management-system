<?= $this->extend('templates/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mt-4 mb-4"><?= $title ?></h1>

    <?php if (session('message')) : ?>
        <div class="alert alert-success"><?= session('message') ?></div>
    <?php endif; ?>

    <?php if (session('errors')) : ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="<?= base_url('classes/create') ?>" class="btn btn-primary">Create New Class</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Class Name</th>
                        <th>Class Code</th>
                        <th>Students</th>
                        <th>Teachers</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class) : ?>
                        <tr>
                            <td><?= $class['class_id'] ?></td>
                            <td><?= esc($class['class_name']) ?></td>
                            <td><?= esc($class['class_code']) ?></td>
                            <td><?= $class['student_count'] ?></td>
                            <td><?= $class['teacher_count'] ?></td>
                            <td>
                                <span class="badge <?= $class['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $class['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('classes/' . $class['class_id']) ?>" class="btn btn-sm btn-info">View</a>
                                <a href="<?= base_url('classes/edit/' . $class['class_id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form action="<?= base_url('classes/delete/' . $class['class_id']) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>