<?php
/**
 * @file
 * قالب موحّد لعرض أنواع المحتوى المرتبطة بوحدة Task Manager.
 *
 * كل النصوص الصادرة من المستخدمين تمر عبر t() مع @placeholder أو check_plain().
 */

$attributes['class'][] = 'task-manager-item';
if (empty($attributes['id'])) {
  $attributes['id'] = 'node-' . $node->nid;
}

// تاريخ المهمة: القيمة مخزّنة بتوقيت UTC.
$task_date_timestamp = NULL;
if (!empty($node->field_task_date[LANGUAGE_NONE][0]['value'])) {
  try {
    $task_date = new DateTime($node->field_task_date[LANGUAGE_NONE][0]['value'], new DateTimeZone('UTC'));
    $task_date_timestamp = $task_date->getTimestamp();
  }
  catch (Exception $e) {
    $task_date_timestamp = NULL;
  }
}

// الحالة: التسميات من إعدادات الحقل.
$status_key = !empty($node->field_task_status[LANGUAGE_NONE][0]['value']) ? $node->field_task_status[LANGUAGE_NONE][0]['value'] : '';
$status_label = '';
if ($status_key !== '') {
  $status_labels = task_manager_status_labels('field_task_status');
  $status_label = isset($status_labels[$status_key]) ? $status_labels[$status_key] : $status_key;
}

// اسم المنشئ (مخزّن مؤقتًا داخل الدالة).
$author_display_name = !empty($node->uid) ? task_manager_display_name($node->uid) : '';

// عدد التعليقات المنشورة.
$task_comment_count = !empty($node->comment_count) ? (int) $node->comment_count : 0;

// إخفاء الحقول التي تُعرض يدويًا.
foreach (array('field_task_description', 'body', 'field_task_images', 'field_task_files') as $key) {
  if (isset($content[$key])) {
    hide($content[$key]);
  }
}

// الوصف: field_task_description أو body.
$description = NULL;
if (!empty($content['field_task_description'])) {
  $description = $content['field_task_description'];
}
elseif (!empty($content['body'])) {
  $description = $content['body'];
}
?>
<article<?php print backdrop_attributes($attributes); ?>>

  <header class="task-manager-header">
    <?php print render($title_prefix); ?>
    <?php if ($title): ?>
      <h1 class="task-manager-title"><?php print $title; ?></h1>
    <?php endif; ?>
    <?php print render($title_suffix); ?>

    <div class="task-manager-meta">
      <?php if ($task_date_timestamp !== NULL): ?>
        <span class="task-manager-meta-item task-manager-date">
          <span class="icon">📅</span>
          <?php print t('تاريخ المهمة: @date', array('@date' => format_date($task_date_timestamp, 'short'))); ?>
        </span>
      <?php endif; ?>

      <span class="task-manager-meta-item task-manager-created">
        <span class="icon">🕒</span>
        <?php print t('أُنشئت في @date', array('@date' => format_date($node->created, 'short'))); ?>
      </span>

      <?php if ($status_label !== ''): ?>
        <span class="task-manager-meta-item task-manager-status status-<?php print backdrop_html_class($status_key); ?>">
          <span class="icon">🏷️</span>
          <?php print t('الحالة: @status', array('@status' => $status_label)); ?>
        </span>
      <?php endif; ?>

      <?php if ($author_display_name !== ''): ?>
        <span class="task-manager-meta-item task-manager-author">
          <span class="icon">👤</span>
          <?php print t('بواسطة @name', array('@name' => $author_display_name)); ?>
        </span>
      <?php endif; ?>

      <?php if ($task_comment_count > 0): ?>
        <span class="task-manager-meta-item task-manager-comments-count">
          <span class="icon">💬</span>
          <?php print format_plural($task_comment_count, 'تعليق واحد', '@count تعليقات'); ?>
        </span>
      <?php endif; ?>
    </div>
  </header>

  <div class="task-manager-content">
    <?php if ($description): ?>
      <section class="task-manager-section task-manager-description">
        <h2 class="task-manager-section-title">
          <span class="icon">📝</span>
          <?php print t('وصف المهمة'); ?>
        </h2>
        <div class="task-manager-section-content">
          <?php print render($description); ?>
        </div>
      </section>
    <?php endif; ?>

    <?php if (!empty($content['field_task_images'])): ?>
      <section class="task-manager-section task-manager-images">
        <h2 class="task-manager-section-title">
          <span class="icon">🖼️</span>
          <?php print t('صور المهمة'); ?>
          <span class="task-manager-badge"><?php print !empty($node->field_task_images[LANGUAGE_NONE]) ? count($node->field_task_images[LANGUAGE_NONE]) : 0; ?></span>
        </h2>
        <div class="task-manager-gallery">
          <?php print render($content['field_task_images']); ?>
        </div>
      </section>
    <?php endif; ?>

    <?php if (!empty($content['field_task_files'])): ?>
      <section class="task-manager-section task-manager-files">
        <h2 class="task-manager-section-title">
          <span class="icon">📎</span>
          <?php print t('ملفات المهمة'); ?>
          <span class="task-manager-badge"><?php print !empty($node->field_task_files[LANGUAGE_NONE]) ? count($node->field_task_files[LANGUAGE_NONE]) : 0; ?></span>
        </h2>
        <div class="task-manager-file-list">
          <?php print render($content['field_task_files']); ?>
        </div>
      </section>
    <?php endif; ?>

    <?php
      // أي حقول إضافية لم نخفها .
      print render($content);
    ?>
  </div>

  <?php if (!empty($comments)): ?>
    <footer class="task-manager-footer task-manager-comments">
      <h2 class="task-manager-section-title">
        <span class="icon">💬</span>
        <?php print t('التعليقات'); ?>
      </h2>
      <?php print render($comments); ?>
    </footer>
  <?php endif; ?>

</article>
