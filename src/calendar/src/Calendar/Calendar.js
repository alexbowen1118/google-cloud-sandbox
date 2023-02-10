import React, { useEffect, useState } from 'react';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import { Typography } from '@mui/material';
import Popover from '@mui/material/Popover';

function Calendar() {
    const [events, setEvents] = useState({});

    useEffect(() => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/calendar/sections", requestOptions)
            .then(response => response.json())
            .then(data => setEvents(data));
    }, []);

    const parseEvents = () => {
        const parsedEvents = Object.values(events).map((event) => {
            let daysOfWeek = [];
            for (let i = 0; i < event.meeting_days.length; i++) {
                if (event.meeting_days.charAt(i) === '1') {
                    daysOfWeek = [...daysOfWeek, i];
                }
            }
            return {
                daysOfWeek: daysOfWeek,
                startTime: event.start_time,
                endTime: event.end_time,
                startRecur: event.start_date,
                endRecur: event.end_date,
                title: event.course_name,
                location: event.location,
                details: event.details,
                display: 'block',
            }
        });
        return parsedEvents;
    }
    /*
            eventContent={(arg) => {return (
                <div className='eventDisplay'>
                    <Typography variant='h6'>{arg.event.title}</Typography>
                    <Typography variant='body2'>{'Location: ' + arg.event.extendedProps.location}</Typography>
                    <Typography variant='body2'>{'Details: ' + arg.event.extendedProps.details}</Typography>
                </div>
            );}}
    */
    return (
        <FullCalendar
            plugins={[ dayGridPlugin ]}
            initialView="dayGridMonth"
            events={parseEvents()}
            dayMaxEvents={3}
            displayEventEnd={{month: true}}
            eventContent={(arg) => {return (<EventPopover arg={arg}/>)}}
        />
    );
}

function EventPopover(props) {
    const arg = props.arg;
    const [anchorEl, setAnchorEl] = useState(null);
    /*let hour = arg.event.range.end.getHours();
    let endTime = '';
    if (hour > 12) {
        hour -= 12;
        endTime += '' + hour + ':' + arg.event.range.end.getMinutes().toString().padStart(2, 0) + ' p'
    }
    else {
        endTime += '' + hour + ':' + arg.event.range.end.getMinutes().toString().padStart(2, 0) + ' a'
    }*/

    const open = Boolean(anchorEl);

        return(
            <div className='eventDisplay'>
                <Typography variant='body2' onClick={(event) => {setAnchorEl(event.currentTarget)}}>{arg.timeText + ' ' + arg.event.title}</Typography>
                <Popover
                    open={open}
                    anchorEl={anchorEl}
                    onClose={() => {setAnchorEl(null)}}
                    anchorOrigin={{
                        vertical: 'bottom',
                        horizontal: 'left',
                    }}
                >
                    <Typography variant='h6'>{arg.event.title}</Typography>
                    <Typography variant='body2'>{'Location: ' + arg.event.extendedProps.location}</Typography>
                    <Typography variant='body2'>{'Details: ' + arg.event.extendedProps.details}</Typography>
                </Popover>
            </div>
        );
}

export default Calendar;