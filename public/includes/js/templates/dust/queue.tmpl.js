(function() {
  dust.register("queue", body_0);

  function body_0(chk, ctx) {
    return chk.section(ctx._get(false, ["json"]), ctx, {
      "block": body_1
    }, null);
  }
  function body_1(chk, ctx) {
    return chk.write("<li id='").reference(ctx._get(false, ["id"]), ctx, "h").write("' style='position:relative;' class='ui-li-has-thumb' data-queue-track-index='").reference(ctx._get(false, ["index"]), ctx, "h").write("' data-queue-track-title='").reference(ctx._get(false, ["Title"]), ctx, "h").write("' data-queue-track-file='").reference(ctx._get(false, ["file"]), ctx, "h").write("' class='ui-li-has-thumb ui-btn ui-btn-icon-right'><a href='#queueTrackPopupMenu' data-rel=\"popup\" data-icon=\"none\" class=\"ui-link-inherit\"><img src='").reference(ctx._get(false, ["Art"]), ctx, "h").write("' class='ui-li-thumb track-item-image' /><h3 class='track-title-heading ui-li-heading'>").reference(ctx._get(false, ["Track"]), ctx, "h").write(" - ").reference(ctx._get(false, ["Title"]), ctx, "h").write("</h3><p class='ui-li-aside ui-li-desc'>").reference(ctx._get(false, ["length"]), ctx, "h").write("</p></a><a href='' data-icon='move' class='").reference(ctx._get(false, ["theme_icon_class"]), ctx, "h").write(" ui-li-link-alt ui-btn ui-btn-up-").reference(ctx._get(false, ["theme_buttons"]), ctx, "h").write(" move' data-theme='").reference(ctx._get(false, ["theme_buttons"]), ctx, "h").write("' title='").reference(ctx._get(false, ["anchorTitle"]), ctx, "h").write("'>").reference(ctx._get(false, ["anchorTitle"]), ctx, "h").write("</a></li>");
  }
  return body_0;
})();
