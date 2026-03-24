<!-- Same header as students/add.php -->
<div class="white_card">
    <form action="<?= site_url('admitcard/print/'.$content_data['data']['student']['id']) ?>" method="get" target="_blank">
        <select name="exam_id" class="nice_Select2">
            <?php foreach($content_data['data']['exams'] as $e): ?>
                <option value="<?= $e['id'] ?>"><?= esc($e['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-success">🎫 Generate & Print Admit Card</button>
    </form>
</div>