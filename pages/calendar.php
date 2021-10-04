<html>
    <head>
        <link rel="stylesheet" href="pages/css/styles.css" />
        <link rel="stylesheet" href="pages/src/calendarjs.css" />
        <script src="pages/src/calendarjs.js"></script>
        <style>
            .row{
                padding: 10px 0;
            }
            .cover{
                background-color: rgba(255, 255, 255, 0.1);
                width: 95%;
                height: 100vh;
                margin: 0 auto;
                position: absolute;
                z-index: 2;
            }
        </style>
    </head>

    <body>

        <div class="contents">
            <?php 
                if($_SESSION['role']!=="officer")
                {
            ?>
            <div class="cover"> </div>
            <?php
                }
            ?>
            <div id="myCalendar" style="width: 90%; margin: 0 auto;">
                <p>Calendar is being constructed...</p>
            </div>
    
            <!-- <h2>Export Events:</h2>
            <button onclick="calendarInstance.exportAllEvents( 'csv' );">Export All Events (csv)</button>
            <br /> -->
        </div>
    </body>

    <script>
        var calendarInstance = new calendarJs( "myCalendar", { 
            exportEventsEnabled: true, 
            manualEditingEnabled: true, 
            showTimesInMainCalendarEvents: false,
            minimumDayHeight: 0,
            manualEditingEnabled: true,
            organizerName: "Your Name",
            organizerEmailAddress: "your@email.address",
            visibleDays: [ 0, 1, 2, 3, 4, 5, 6 ]
        } );

        calendarInstance.addEvents( getEvents() );

        function turnOnEventNotifications() {
            calendarInstance.setOptions( {
                eventNotificationsEnabled: true
            } );
        }

        function setEvents() {
            calendarInstance.setEvents( getEvents() );
        }

        function removeEvent() {
            calendarInstance.removeEvent( new Date(), "Test Title 2" );
        }

        function daysInMonth( year, month ) {
            return new Date( year, month + 1, 0 ).getDate();
        }

        function setOptions() {
            calendarInstance.setOptions( {
                minimumDayHeight: 70,
                manualEditingEnabled: false,
                exportEventsEnabled: false,
                showDayNumberOrdinals: false,
                fullScreenModeEnabled: false,
                maximumEventsPerDayDisplay: 0,
                showTimelineArrowOnFullDayView: false,
                maximumEventTitleLength: 10,
                maximumEventDescriptionLength: 10,
                maximumEventLocationLength: 10,
                maximumEventGroupLength: 10,
                showDayNamesInMainDisplay: false,
                tooltipsEnabled: false,
                visibleDays: [ 0, 1, 2, 3, 4 ]
            } );
        }

        function onlyDotsDisplay() {
            calendarInstance.setOptions( {
                useOnlyDotEventsForMainDisplay: true
            } );
        }

        function getEvents() {
            var previousDay = new Date(),
                today9 = new Date(),
                today11 = new Date(),
                tomorrow = new Date(),
                firstDayInNextMonth = new Date(),
                lastDayInNextMonth = new Date(),
                today = new Date(),
                today3HoursAhead = new Date();

            previousDay.setDate( previousDay.getDate() - 1 );
            today11.setHours( 11 );
            tomorrow.setDate( today11.getDate() + 1 );
            today9.setHours( 9 );

            firstDayInNextMonth.setDate( 1 );
            firstDayInNextMonth.setDate( firstDayInNextMonth.getDate() + daysInMonth( firstDayInNextMonth.getFullYear(), firstDayInNextMonth.getMonth() ) );

            lastDayInNextMonth.setDate( 1 );
            lastDayInNextMonth.setMonth( lastDayInNextMonth.getMonth() + 1 );
            lastDayInNextMonth.setDate( lastDayInNextMonth.getDate() + daysInMonth( lastDayInNextMonth.getFullYear(), lastDayInNextMonth.getMonth() ) - 1 );

            today.setHours( 21, 59, 0, 0 );
            today.setDate( today.getDate() + 3 );
            today3HoursAhead.setHours( 23, 59, 0, 0 );
            today3HoursAhead.setDate( today3HoursAhead.getDate() + 3 );
            var newArray=[];
            $.ajax(
            {
                url :"config/config.php",
                type:"POST",
                async: false,
                data:{get_calendar_events:1},
                success:function(data)
                {                    
                    var dataToJson=JSON.parse(data);
                    newArray.push(...dataToJson);
                    // newArray[1]["from"]=today;
                    // newArray[1]["to"]=today3HoursAhead;
                }
            });
            return newArray;
            // return [
            //     {
            //         from: previousDay,
            //         to: previousDay,
            //         title: "Previous Day",
            //         description: "This is a another description of the event that has been added, so it can be shown in the pop-up dialog.",
            //         location: "Teams Meeting",
            //         isAllDay: true,
            //         color: "#FF0000",
            //         colorText: "#FFFF00",
            //         colorBorder: "#00FF00",
            //         repeatEvery: 5,
            //         id: "1234-5678-9",
            //         group: "Group 1"
            //     },
            //     {
            //         from: today11,
            //         to: tomorrow,
            //         title: "Title 1",
            //         description: "This is a another description of the event that has been added, so it can be shown in the pop-up dialog.",
            //         location: "Teams Meeting",
            //         isAllDay: false,
            //         group: "group 1"
            //     },
            //     {
            //         from: tomorrow,
            //         to: today11,
            //         title: "Title Bad (should not show)",
            //         description: "This is a another description of the event that has been added, so it can be shown in the pop-up dialog.",
            //         location: "Teams Meeting",
            //         isAllDay: false,
            //         group: "group 1"
            //     },
            //     {
            //         from: today9,
            //         to: today9,
            //         title: "Title 2",
            //         description: "This is a another description of the event that has been added, so it can be shown in the pop-up dialog.",
            //         location: "Teams Meeting",
            //         isAllDay: true,
            //         group: "Group 1"
            //     },
            //     {
            //         from: firstDayInNextMonth,
            //         to: firstDayInNextMonth,
            //         title: "First Day 1",
            //         description: "This is a another description of the event that has been added, so it can be shown in the pop-up dialog.",
            //         location: "Teams Meeting",
            //         isAllDay: true,
            //         color: "#00FF00",
            //         colorText: "#FF0000",
            //         repeatEvery: 4,
            //         group: "Group 2"
            //     },
            //     {
            //         from: firstDayInNextMonth,
            //         to: firstDayInNextMonth,
            //         title: "First Day 2",
            //         description: "This is a another description of the event that has been added, so it can be shown in the pop-up dialog.",
            //         location: "Teams Meeting",
            //         isAllDay: true,
            //         color: "#00FF00",
            //         colorText: "#FF0000",
            //         repeatEvery: 4,
            //         group: "Group 2"
            //     },
            //     {
            //         from: lastDayInNextMonth,
            //         to: lastDayInNextMonth,
            //         title: "Last Day 1",
            //         description: "This is a another description of the event that has been added, so it can be shown in the pop-up dialog.",
            //         location: "Teams Meeting",
            //         isAllDay: true,
            //         color: "#0000FF",
            //         repeatEvery: 2,
            //         group: "Group 2"
            //     },
            //     {
            //         from: today,
            //         to: today3HoursAhead,
            //         title: "Regular Event",
            //         description: "This is a another description of the event that has been added, so it can be shown in the pop-up dialog.",
            //         repeatEvery: 1,
            //         repeatEveryExcludeDays: [ 6, 0 ],
            //         repeatEnds: new Date( today.getFullYear() + 1, 0, 1 ),
            //         group: "Group 1"
            //     }
            // ];
        }
    </script>
</html>