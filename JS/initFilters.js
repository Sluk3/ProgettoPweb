$(function () {
    $("#price-range").slider({
        range: true,
        min: 0,
        max: 1000,
        values: [0, 1000],
        slide: function (event, ui) {
            $("#pricelab").val("€" + ui.values[0] + " - €" + ui.values[1]);
        }
    });
    $("#pricelab").val("€" + $("#price-range").slider("values", 0) +
        " - €" + $("#price-range").slider("values", 1));
});
$(function () {
    $("#bpm-range").slider({
        range: true,
        min: 60,
        max: 200,
        values: [60, 200],
        slide: function (event, ui) {
            $("#bpmlab").val(ui.values[0] + " BPM - " + ui.values[1] + " BPM");
        }
    });
    $("#bpmlab").val($("#bpm-range").slider("values", 0) + " BPM - " +
        $("#bpm-range").slider("values", 1) + " BPM");
});