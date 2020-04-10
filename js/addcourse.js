$(document).ready(function () {
    bsCustomFileInput.init()
});
$(function () {
    $('#datetimepicker1').datetimepicker({
        locale: 'zh-cn',
        format: 'YYYY-MM-DD HH:mm'
    });
});

$(function () {
    $('#datetimepicker2').datetimepicker({
        locale: 'zh-cn',
        format: 'YYYY-MM-DD HH:mm'
    });
});

$.fn.datetimepicker.Constructor.Default = $.extend({},
    $.fn.datetimepicker.Constructor.Default,
    { icons:
            { time: 'fas fa-clock',
                date: 'fas fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-arrow-circle-left',
                next: 'fas fa-arrow-circle-right',
                today: 'far fa-calendar-check-o',
                clear: 'fas fa-trash',
                close: 'far fa-times' } });

// $(function () {
//     $('#datetimepicker').datetimepicker({
//         locale: 'zh-cn',
//         format: 'YYYY-MM-DD HH:mm'
//     });
// });
// $('.input-daterange input').each(function () {
//
//
//     $(this).datepicker({
//         // minView: 'minute',
//         language: 'zh-CN',
//         autoclose: true,
//         todayHighlight: true,
//         format: {
//
//             toDisplay: function (date, format, language) {
//                 var d = new Date(date);
//                 d.setDate(d.getDate() - 7);
//                 console.log(d);
//                 return d.toISOString();
//             },
//             toValue: function (date, format, language) {
//                 var d = new Date(date);
//                 d.setDate(d.getDate() + 7);
//                 return new Date(d);
//             }
//
//         },
//         // todayBtn: true,
//         startDate : new Date('2017-02-02 14:15:26') ,  // 窗口可选时间从今天开始
//         endDate : new Date('2217-02-02 14:15:26')   // 窗口最大时间直至今天
//     });
//
//     $(this).datepicker("setDate", new Date());
// });
