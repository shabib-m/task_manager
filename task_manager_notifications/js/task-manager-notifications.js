/**
 * @file
 * تحديث عدد التنبيهات تلقائياً.
 */
(function ($) {
  Backdrop.behaviors.taskNotificationBell = {
    attach: function (context, settings) {
      // يُنشأ مؤقت واحد فقط حتى لو استُدعيت attach مرات عديدة (مثلاً بعد طلبات AJAX).
      if (Backdrop.taskNotificationBellTimer) {
        return;
      }

      var config = settings.taskManagerNotifications || {};
      var interval = config.interval || 120000;
      // countUrl يأتي من url() في PHP ويتضمن المسار الأساسي مسبقاً،
      // لذا لا نضيف basePath مرة ثانية.
      var countUrl = config.countUrl || (Backdrop.settings.basePath + 'user/notifications/count');

      function updateCount() {
        $.ajax({
          url: countUrl,
          type: 'GET',
          dataType: 'json',
          success: function (data) {
            var count = parseInt(data.count, 10) || 0;
            var $badge = $('.task-notification-bell .bell-badge');
            var $link = $('.task-notification-bell .notification-link');

            if (count > 0) {
              if ($badge.length) {
                $badge.text(count);
              } else {
                $link.append($('<span class="bell-badge"></span>').text(count));
              }
            } else {
              $badge.remove();
            }
          }
        });
      }

      // تحديث دوري، مع تجاهل التبويبات المخفية لتقليل الضغط على الخادم.
      Backdrop.taskNotificationBellTimer = setInterval(function () {
        if (!document.hidden) {
          updateCount();
        }
      }, interval);
    }
  };
})(jQuery);
