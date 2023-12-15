<?php $analytics_values = json_decode($analytics_values); ?>
<div class="row">
    <div class="col-lg-9 p-0 m-0">
        <div class="card">
            <div class="card-body p-2">
                <h4 class="header-title w-100 pb-4 mt-2">
                    <span class="float-left">
                        <?php echo get_phrase('course_progress_analytics'); ?>
                        <i onclick="showAjaxModal('<?php echo site_url('addons/course_analytics/about_of_course_analytics'); ?>', 'Course progress')" class="mdi mdi-information-outline cursor-pointer"></i>
                    </span> 
                </h4>

                <div class="course-progress-text-90 float-left text-muted text-12"><?php echo get_phrase('students'); ?></div>
                <div class="overflow-hidden">
                    <div style="height: 280px;" class="chartjs-chart pb-3 float-left h-280px">
                        <canvas id="course_progress_chart" class="float-right w-100"></canvas>
                        <div class="w-100 pb-2 pt-1 pl-4 text-muted text-12 float-right text-center">
                            <?php echo get_phrase('completion_percentage'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end card-body-->

    <div class="col-lg-3">
        <div class="card">
            <div class="card-body p-2">
                <h4 class="header-title w-100 pb-2 mt-2">
                    <?php echo get_phrase('enrolled_students'); ?> : <?php echo $total_enroll_students; ?>
                </h4>
                <table class="table-bordered w-100">
                    <thead>
                        <tr>
                            <th class="bg-secondary text-white py-1 px-2"><?php echo get_phrase('completion_percentage'); ?></th>
                            <th class="bg-secondary text-white py-1 px-2"><?php echo get_phrase('number_of_students'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-2 text-12">0-10</td>
                            <td class="px-2 text-12"><?php echo $analytics_values[0]; ?></td>
                        </tr>
                        <tr>
                            <td class="px-2 text-12">11-20</td>
                            <td class="px-2 text-12"><?php echo $analytics_values[1]; ?></td>
                        </tr>
                        <tr>
                            <td class="px-2 text-12">21-30</td>
                            <td class="px-2 text-12"><?php echo $analytics_values[2]; ?></td>
                        </tr>
                        <tr>
                            <td class="px-2 text-12">31-40</td>
                            <td class="px-2 text-12"><?php echo $analytics_values[3]; ?></td>
                        </tr>
                        <tr>
                            <td class="px-2 text-12">41-50</td>
                            <td class="px-2 text-12"><?php echo $analytics_values[4]; ?></td>
                        </tr>
                        <tr>
                            <td class="px-2 text-12">51-60</td>
                            <td class="px-2 text-12"><?php echo $analytics_values[5]; ?></td>
                        </tr>
                        <tr>
                            <td class="px-2 text-12">61-70</td>
                            <td class="px-2 text-12"><?php echo $analytics_values[6]; ?></td>
                        </tr>
                        <tr>
                            <td class="px-2 text-12">71-80</td>
                            <td class="px-2 text-12"><?php echo $analytics_values[7]; ?></td>
                        </tr>
                        <tr>
                            <td class="px-2 text-12">81-90</td>
                            <td class="px-2 text-12"><?php echo $analytics_values[8]; ?></td>
                        </tr>
                        <tr>
                            <td class="px-2 text-12">91-100</td>
                            <td class="px-2 text-12"><?php echo $analytics_values[9]; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="course_enrolment_analytics_body"></div>
</div>

<script type="text/javascript">
    "use strict";

    !(function (c) {
    "use strict";
    var r = function () {
        (this.$body = c("body")), (this.charts = []);
    };
    (r.prototype.respChart = function (t, a, e, o) {
        var n = Chart.controllers.line.prototype.draw;
        Chart.controllers.line.prototype.draw = function () {
            n.apply(this, arguments);
            var r = this.chart.chart.ctx,
                t = r.stroke;
            r.stroke = function () {
                r.save(), (r.shadowColor = "rgba(0,0,0,0.01)"), (r.shadowBlur = 20), (r.shadowOffsetX = 0), (r.shadowOffsetY = 5), t.apply(this, arguments), r.restore();
            };
        };
        var s = Chart.controllers.doughnut.prototype.draw;
        Chart.controllers.doughnut = Chart.controllers.doughnut.extend({
            draw: function () {
                s.apply(this, arguments);
                var r = this.chart.chart.ctx,
                    t = r.fill;
                r.fill = function () {
                    r.save(), (r.shadowColor = "rgba(0,0,0,0.03)"), (r.shadowBlur = 4), (r.shadowOffsetX = 0), (r.shadowOffsetY = 3), t.apply(this, arguments), r.restore();
                };
            },
        });
        var l = Chart.controllers.bar.prototype.draw;
        (Chart.controllers.bar = Chart.controllers.bar.extend({
            draw: function () {
                l.apply(this, arguments);
                var r = this.chart.chart.ctx,
                    t = r.fill;
                r.fill = function () {
                    r.save(), (r.shadowColor = "rgba(0,0,0,0.01)"), (r.shadowBlur = 20), (r.shadowOffsetX = 4), (r.shadowOffsetY = 5), t.apply(this, arguments), r.restore();
                };
            },
        })),
            (Chart.defaults.global.defaultFontColor = "#8391a2"),
            (Chart.defaults.scale.gridLines.color = "#8391a2");
        var i = t.get(0).getContext("2d"),
            d = c(t).parent();
        return (function () {
            var r;
            switch ((t.attr("width", c(d).width()), a)) {
                case "Line":
                    r = new Chart(i, { type: "line", data: e, options: o });
                    break;
                case "Doughnut":
                    r = new Chart(i, { type: "doughnut", data: e, options: o });
                    break;
                case "Pie":
                    r = new Chart(i, { type: "pie", data: e, options: o });
                    break;
                case "Bar":
                    r = new Chart(i, { type: "bar", data: e, options: o });
                    break;
                case "Radar":
                    r = new Chart(i, { type: "radar", data: e, options: o });
                    break;
                case "PolarArea":
                    r = new Chart(i, { data: e, type: "polarArea", options: o });
            }
            return r;
        })();
    }),
        (r.prototype.initCharts = function () {
            var r = [];
            
            if (0 < c("#course_progress_chart").length) {
                var t = document.getElementById("course_progress_chart").getContext("2d").createLinearGradient(0, 500, 0, 150);
                t.addColorStop(0, "#fa5c7c"), t.addColorStop(1, "#727cf5");
                var a = {
                    labels: ["0-10(%)", "11-20(%)", "21-30(%)", "31-40(%)", "41-50(%)", "51-60(%)", "61-70(%)", "71-80(%)", "81-90(%)", "91-100(%)"],
                    datasets: [
                        { label: "<?php echo get_phrase('students'); ?>", backgroundColor: t, borderColor: t, hoverBackgroundColor: t, hoverBorderColor: t, data: ["<?php echo $analytics_values[0]; ?>", "<?php echo $analytics_values[1]; ?>", "<?php echo $analytics_values[2]; ?>", "<?php echo $analytics_values[3]; ?>", "<?php echo $analytics_values[4]; ?>", "<?php echo $analytics_values[5]; ?>", "<?php echo $analytics_values[6]; ?>", "<?php echo $analytics_values[7]; ?>", "<?php echo $analytics_values[8]; ?>", "<?php echo $analytics_values[9]; ?>"] },
                    ],
                };
                r.push(
                    this.respChart(c("#course_progress_chart"), "Bar", a, {
                        maintainAspectRatio: !1,
                        legend: { display: !1 },
                        tooltips: { backgroundColor: "#727cf5", titleFontColor: "#fff", bodyFontColor: "#fff", bodyFontSize: 14, displayColors: !1 },
                        scales: {
                            yAxes: [{ gridLines: { display: !1, color: "rgba(0,0,0,0.05)" }, stacked: !1, ticks: { stepSize: <?php echo max($analytics_values); ?> } }],
                            xAxes: [{ barPercentage: 0.7, categoryPercentage: 0.5, stacked: !1, gridLines: { color: "rgba(0,0,0,0.01)" } }],
                        },
                    })
                );
            }

            return r;
        }),
        (r.prototype.init = function () {
            var t = this;
            (Chart.defaults.global.defaultFontFamily = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif'),
                c("#dash-daterange").daterangepicker({ singleDatePicker: !0 }),
                (t.charts = this.initCharts()),
                this.initMaps(),
                c(window).on("resize", function (r) {
                    c.each(t.charts, function (r, t) {
                        try {
                            t.destroy();
                        } catch (r) {}
                    }),
                        (t.charts = t.initCharts());
                });
        }),
        (c.Dashboard = new r()),
        (c.Dashboard.Constructor = r);
})(window.jQuery),
    (function (r) {
        "use strict";
        window.jQuery.Dashboard.init();
    })();

</script>