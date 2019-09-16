	<script type="text/javascript">
        var dataTable_config = {
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            "pageLength": 15,
        };

        var dataTable_config2 = {
            lengthMenu : [5, 10, 50, 100],
            pageLength : 5,
            aaSorting : [],
            ordering : false,
        };

        var dataTable_config3 = {
            select : true,
            aaSorting : [],
            ordering : false,
        };

        var dataTable_config4 = {
            aaSorting : [],
            ordering : false,
            searching : false,
            lengthChange : false
        }

        var dataTable_config6 = {
            lengthMenu : [5, 10, 50, 100],
            pageLength : 10,
            aaSorting : [],
            ordering : false,
        }

        var dataTable_config7 = {
            lengthMenu : [5, 10, 50, 100],
            pageLength : 50,
            aaSorting : [],
            ordering : false,
        }

        var dataTable_config8 = {
            lengthMenu : [5, 10, 50, 100],
            pageLength : 100,
            aaSorting : [],
            ordering : false,
        }

        var dataTable_config5 = {
            aaSorting : [],
            ordering : false,
            searching : false,
            lengthChange : false,
            bPaginate : false,
            bInfo : false
        }

        var dataTable_short = {
            pageLength : 5,
            lengthChange : false,
            ordering : false,
            aaSorting : [],
        }

        var dataTable_long = {
            pageLength : 10,
            lengthChange : false,
            ordering : false,
            aaSorting : [],
        }

        var dataTable_short_ordered = {
            pageLength : 5,
            lengthChange : false,
            aaSorting : [],
        }

    	// var dateFormat_config = 'mm-dd-yy'
        var dateFormat_config = 'yy-mm-dd';

        var date_option = {
            changeMonth: true,
            changeYear: true,
            dateFormat: dateFormat_config,
            yearRange: "-1000:+0",
            maxDate: new Date,
        };

        var date_option2 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: dateFormat_config,
            yearRange: "-1000:+0",
            minDate: new Date,
        };

        {{-- 
            Include the following variables to the page:
            => $datepicker_defaultDate
        --}}
        var date_option3 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: dateFormat_config,
            yearRange: "-1000:+0",
            {{ isset($datepicker_defaultDate) ? $datepicker_defaultDate : '' }}
        };

        var date_option4 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: dateFormat_config,
            yearRange: "-1000:+1000",
            {{ isset($datepicker_defaultDate) ? $datepicker_defaultDate : '' }}
        };

        var date_option5 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: dateFormat_config,
            yearRange: "-1000:+0",
        };

        var date_option6 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: dateFormat_config,
            yearRange: "-1000:+1000",
            beforeShowDay: function(d) {
                return [(d.getDate() == 1)]; 
            },
            maxDate: new Date,
        };
	</script>