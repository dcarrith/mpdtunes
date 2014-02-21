(function() {
  dust.register("albums", body_0);

  function body_0(chk, ctx) {
    return chk.section(ctx._get(false, ["json"]), ctx, {
      "block": body_1
    }, null);
  }
  function body_1(chk, ctx) {
    return chk.write("<li class='ui-li-has-thumb'><a href='").reference(ctx._get(false, ["href"]), ctx, "h").write("' class='ui-link-inherit' data-transition='").reference(ctx._get(false, ["transition"]), ctx, "h").write("'><img src='").reference(ctx._get(false, ["art"]), ctx, "h").write("' class='ui-li-thumb album-art-img' /><h3 class='ui-li-heading album-name-heading'>").reference(ctx._get(false, ["name"]), ctx, "h").write("</h3></a></li>");
  }
  return body_0;
})();
