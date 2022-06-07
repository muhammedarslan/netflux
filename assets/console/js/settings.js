setTimeout(() => {
  var sp = $("#PlaceHolderSlt").val();
  $("#HomageCategories").select2({
    width: "100%",
    placeholder: {
      id: "-1",
      text: "--- " + sp + " ---",
      selected: "selected",
    },
    allowClear: true,
  });
}, 1000);
