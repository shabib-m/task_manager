<?php
/**
 * @file
 * قالب التعليق لعرض اسم الموظف وحالة المهمة وقت التعليق.
 *
 * كل النصوص الصادرة من المستخدمين تمر عبر check_plain().
 */

$attributes['class'][] = 'task-manager-comment';

// اسم الكاتب: اسم الموظف (اسم الحساب)، أو اسم الزائر للتعليقات المجهولة.
if (!empty($comment->uid)) {
  $comment_author_name = task_manager_display_name($comment->uid);
}
else {
  $comment_author_name = !empty($comment->name) ? $comment->name : t('زائر');
}

// الحالة المحددة وقت كتابة التعليق.
$comment_status_label = '';
$comment_status_key = '';
$status_items = field_get_items('comment', $comment, 'field_comment_status');
if (!empty($status_items[0]['value'])) {
  $comment_status_key = $status_items[0]['value'];
  $status_labels = task_manager_status_labels('field_task_status');
  $comment_status_label = isset($status_labels[$comment_status_key]) ? $status_labels[$comment_status_key] : $comment_status_key;
}
?>
<article<?php print backdrop_attributes($attributes); ?>>

  <header class="comment-header">
    <div class="comment-author">
      <span class="icon">👤</span>
      <?php print check_plain($comment_author_name); ?>
    </div>

    <div class="comment-date">
      <span class="icon">🕒</span>
      <?php print format_date($comment->created, 'short'); ?>
    </div>

    <?php if ($comment_status_label !== ''): ?>
      <div class="comment-status status-<?php print backdrop_html_class($comment_status_key); ?>">
        <span class="icon">🏷️</span>
        <?php print t('الحالة: @status', array('@status' => $comment_status_label)); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($new)): ?>
      <span class="new"><?php print $new; ?></span>
    <?php endif; ?>

    <?php if (!empty($content['links'])): ?>
      <div class="comment-links">
        <?php print render($content['links']); ?>
      </div>
    <?php endif; ?>
  </header>

  <div class="comment-content">
    <?php
      hide($content['links']);
      // الحالة معروضة في الترويسة، فلا تُكرَّر داخل المحتوى.
      hide($content['field_comment_status']);
      print render($content);
    ?>
  </div>

</article>
