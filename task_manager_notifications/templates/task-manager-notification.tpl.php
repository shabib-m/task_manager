<?php
/**
 * @file
 * قالب عرض التنبيه الواحد.
 *
 * المتغيرات المتاحة:
 * - $notification: كائن التنبيه (يحتوي على nid, cid, uid, created, status).
 * - $node: كائن العقدة (المهمة).
 */
?>
<div class="task-notification<?php print $notification->status ? ' is-read' : ' is-unread'; ?>">

  <div class="task-notification-icon">
    <?php if ($notification->status): ?>
      <span class="icon-read">✓</span>
    <?php else: ?>
      <span class="icon-unread">🔔</span>
    <?php endif; ?>
  </div>

  <div class="task-notification-content">
    <div class="task-notification-title">
      <?php if ($node): ?>
        <a href="<?php print url('node/' . $node->nid); ?>">
          <?php print check_plain($node->title); ?>
        </a>
      <?php else: ?>
        <?php print t('مهمة محذوفة'); ?>
      <?php endif; ?>
    </div>

    <div class="task-notification-meta">
      <span class="task-notification-date">
        <span class="icon">🕒</span>
        <?php print format_date($notification->created, 'short'); ?>
      </span>

      <?php if (!$notification->status): ?>
        <span class="task-notification-badge">
          <?php print t('جديد'); ?>
        </span>
      <?php endif; ?>
    </div>
  </div>

</div>
