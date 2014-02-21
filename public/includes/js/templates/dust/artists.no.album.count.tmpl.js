(function() {
  dust.register("artists", body_0);

  function body_0(chk, ctx) {
    return chk.section(ctx._get(false, ["json"]), ctx, {
      "block": body_1
    }, null);
  }
  function body_1(chk, ctx) {
    return chk.write("<li><a href='").reference(ctx._get(false, ["href"]), ctx, "h").write("' data-transition='").reference(ctx._get(false, ["transition"]), ctx, "h").write("'>").reference(ctx._get(false, ["name"]), ctx, "h").write("</a></li>");
  }
  return body_0;
})();
