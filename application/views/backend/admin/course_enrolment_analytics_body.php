<?php $enrollment_analytics_values = json_decode($enrollment_analytics_values); ?>
<div class="row">
    <div class="col-lg-9 p-0 m-0">
        <div class="card">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-lg-8 col-sm-12 float-left pt-2">
                        <h4 class="header-title mb-3 w-100">
                            <?php echo get_phrase('course_enrolments'); ?>
                            <i onclick="showAjaxModal('<?php echo site_url('addons/course_analytics/about_of_course_enrolments'); ?>', 'Student enrolments')" class="mdi mdi-information-outline cursor-pointer"></i>
                        </h4>
                    </div>
                    <div class="col-lg-4 col-sm-12 float-right pt-2">
                        <div class="input-group mb-3">
                            <select class="form-control" id="date_for_filter_chart">
                                <?php $course_added_year = date('Y', $course_added_date); ?>
                                <?php $course_added_month = date('m', $course_added_date); ?>
                                <?php $current_year = date('Y'); ?>
                                <?php $current_month = date('m'); ?>
                                <?php for($i = $current_year; $i >= $course_added_year; $i--): ?>
                                    <optgroup label="<?php echo $i; ?>">
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 1 && $i == $current_year || $course_added_year == $i && $course_added_month <= 1): ?>
                                            <option value="Jan <?php echo $i; ?>" <?php if($selected_month == 1 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Jan').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 2 && $i == $current_year || $course_added_year == $i && $course_added_month <= 2): ?>
                                            <option value="Feb <?php echo $i; ?>" <?php if($selected_month == 2 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Feb').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 3 && $i == $current_year || $course_added_year == $i && $course_added_month <= 3): ?>
                                            <option value="Mar <?php echo $i; ?>" <?php if($selected_month == 3 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Mar').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 4 && $i == $current_year || $course_added_year == $i && $course_added_month <= 4): ?>
                                            <option value="Apr <?php echo $i; ?>" <?php if($selected_month == 4 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Apr').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 5 && $i == $current_year || $course_added_year == $i && $course_added_month <= 5): ?>
                                            <option value="May <?php echo $i; ?>" <?php if($selected_month == 5 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('May').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 6 && $i == $current_year || $course_added_year == $i && $course_added_month <= 6): ?>
                                            <option value="Jun <?php echo $i; ?>" <?php if($selected_month == 6 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Jun').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 7 && $i == $current_year || $course_added_year == $i && $course_added_month <= 7): ?>
                                            <option value="Jul <?php echo $i; ?>" <?php if($selected_month == 7 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Jul').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 8 && $i == $current_year || $course_added_year == $i && $course_added_month <= 8): ?>
                                            <option value="Aug <?php echo $i; ?>" <?php if($selected_month == 8 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Aug').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 9 && $i == $current_year || $course_added_year == $i && $course_added_month <= 9): ?>
                                            <option value="Sep <?php echo $i; ?>" <?php if($selected_month == 9 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Sep').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 10 && $i == $current_year || $course_added_year == $i && $course_added_month <= 10): ?>
                                            <option value="Oct <?php echo $i; ?>" <?php if($selected_month == 10 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Oct').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 11 && $i == $current_year || $course_added_year == $i && $course_added_month <= 11): ?>
                                            <option value="Nov <?php echo $i; ?>" <?php if($selected_month == 11 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Nov').'-'.$i; ?></option>
                                        <?php endif; ?>
                                        <?php if($i != $course_added_year && $i != $current_year || $current_month >= 12 && $i == $current_year || $course_added_year == $i && $course_added_month <= 12): ?>
                                            <option value="Dec <?php echo $i; ?>" <?php if($selected_month == 12 && $selected_year == $i) echo 'selected'; ?>><?php echo get_phrase('Dec').'-'.$i; ?></option>
                                        <?php endif; ?>
                                    </optgroup>
                                    
                                <?php endfor; ?>
                            </select>
                            <div class="input-group-append">
                                <a href="javascript:;" onclick="filter_enrolment_analytics()" class="input-group-text btn btn-light"><?php echo get_phrase('go'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Resources -->
                <script src="<?php echo base_url(); ?>assets/backend/amcharts/v4/core.js"></script>
                <script src="<?php echo base_url(); ?>assets/backend/amcharts/v4/charts.js"></script>
                <script src="<?php echo base_url(); ?>assets/backend/amcharts/v4/animated.js"></script>

                <!-- Chart code -->
                <script>
                    "use strict";
                    am4core.ready(function() {

                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    var chart = am4core.create("chartdiv", am4charts.XYChart);

                    chart.data = [
                        <?php for($i=1; $i <= $total_days_in_this_month; $i++): ?>
                            {"day": <?php echo $i; ?>,
                            "students": <?php echo $enrollment_analytics_values[$i-1]; ?>,},
                        <?php endfor; ?>
                    ];

                    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.renderer.grid.template.location = 0;
                    categoryAxis.renderer.ticks.template.disabled = true;
                    categoryAxis.renderer.line.opacity = 0;
                    categoryAxis.renderer.grid.template.disabled = true;
                    categoryAxis.renderer.minGridDistance = 1;
                    categoryAxis.dataFields.category = "day";


                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.tooltip.disabled = false;
                    valueAxis.renderer.line.opacity = 0;
                    valueAxis.renderer.ticks.template.disabled = true;
                    valueAxis.renderer.minGridDistance = 50;
                    valueAxis.min = 0;
                    valueAxis.max = <?php echo max($enrollment_analytics_values); ?>;


                    var lineSeries = chart.series.push(new am4charts.LineSeries());
                    lineSeries.dataFields.categoryX = "day";
                    lineSeries.dataFields.valueY = "students";
                    lineSeries.tooltipText = "<?php echo get_phrase('students'); ?>: {valueY.value}";
                    lineSeries.fillOpacity = 0;
                    lineSeries.strokeWidth = 2;
                    lineSeries.propertyFields.stroke = "lineColor";
                    lineSeries.propertyFields.fill = "lineColor";

                    var bullet = lineSeries.bullets.push(new am4charts.CircleBullet());
                    bullet.circle.radius = 1;
                    bullet.circle.fill = am4core.color("#fff");
                    bullet.circle.strokeWidth = 6;

                    chart.cursor = new am4charts.XYCursor();
                    chart.cursor.behavior = "panX";
                    chart.cursor.lineX.opacity = 0.5;
                    chart.cursor.lineY.opacity = 0;
                    chart.hideCredits = true


                    });
                </script>
                <div class="course-progress-text-90 float-left text-muted text-12"><?php echo get_phrase('students'); ?></div>
                <div class="overflow-hidden">
                    <div id="chartdiv" class="h-300px" style="height: 300px;"></div>
                </div>
                <div class="w-100 pb-2 pt-0 mt-0 pl-4 text-muted text-12 float-right text-center">
                    <?php echo get_phrase('the_dates_of_month'); ?>
                </div>
            </div> <!-- end card-body-->
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body p-2">
                <h4 class="header-title w-100 pb-2">
                    <?php echo get_phrase('enrolled_students'); ?>
                </h4>
                
                <table class="table-bordered w-100">
                    <?php $filter_timestamp_on_enrolment = strtotime('15-'.$selected_month.'-'.$selected_year); ?>
                    <thead>
                        <tr>
                            <th class="bg-secondary text-white py-1 px-2"><?php echo get_phrase('date').'('.date('F Y' , $filter_timestamp_on_enrolment).')'; ?></th>
                            <th class="bg-secondary text-white py-1 px-2"><?php echo get_phrase('number_of_students'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i=1; $i <= $total_days_in_this_month; $i++): ?>
                            <?php $student_number_in_this_day = $enrollment_analytics_values[$i-1]; ?>
                            <?php if($student_number_in_this_day == 0) continue; ?>
                            <tr>
                                <td class="px-2 text-12"><?php echo $i; ?></td>
                                <td class="px-2 text-12"><?php echo $student_number_in_this_day; ?></td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>