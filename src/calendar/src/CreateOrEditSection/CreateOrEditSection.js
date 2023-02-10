import React, { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import Button from '@mui/material/Button';
import { Box } from '@mui/system';
import AppHeader from '../AppHeader/AppHeader.js'
import { TextField } from '@mui/material';
import { FormControl } from '@mui/material';
import { Grid } from '@mui/material';
import { Autocomplete } from '@mui/material';
import { TimePicker } from '@mui/x-date-pickers/TimePicker';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';
import FormLabel from '@mui/material/FormLabel';
import FormGroup from '@mui/material/FormGroup';
import FormControlLabel from '@mui/material/FormControlLabel';
import Checkbox from '@mui/material/Checkbox';

function CreateOrEditSection(props) {
    const adminMode = props.admin;
    const {instructorId} = useParams();
    const [currentSectionId, setCurrentSectionId] = useState(-1);
    const [currentCourse, setCurrentCourse] = useState({});
    const [currentInstructor, setCurrentInstructor] = useState({});
    const [editing, setEditing] = useState(false);
    const [fields, setFields] = useState({meeting_days: [false, false, false, false, false, false, false]});
    const [courses, setCourses] = useState({});
    const [sections, setSections] = useState({});
    const [instructors, setInstructors] = useState({});
    const navigate = useNavigate();

    useEffect(() => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/calendar/courses", requestOptions)
            .then(response => response.json())
            .then(data => setCourses(data));
        fetch("/api/calendar/instructors", requestOptions)
            .then(response => response.json())
            .then(data => setInstructors(data));
    }, []);

    useEffect(() => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        if (adminMode) {
            fetch("/api/calendar/sections", requestOptions)
                .then(response => response.json())
                .then(data => setSections(data));
        }
        else {
            fetch("/api/calendar/instructors/" + instructorId + "/sections", requestOptions)
                .then(response => response.json())
                .then(data => setSections(data));
            fetch("/api/calendar/instructors/" + instructorId, requestOptions)
                .then(response => response.json())
                .then(data => setCurrentInstructor(data));
            setFields(values => ({...values, instructor_id: instructorId}));
        }
    }, [editing, adminMode, instructorId]);

    const handleChange = (event) => {
        const name = event.target.name;
        const value = event.target.value;
        setFields(values => ({...values, [name]: value}));
    }

    const handleDayChange = (event) => {
        const name = event.target.name;
        let tempDays = fields.meeting_days;
        tempDays[name] = event.target.checked;
        setFields(values => ({...values, meeting_days: tempDays}));
    }

    const handleSelectSection = (event, value) => {
        setEditing(true);
        setCurrentSectionId(value.id);
        let meetingDays = [false, false, false, false, false, false, false];
        for (let i = 0; i < meetingDays.length; i++) {
            if (value.value.meeting_days.charAt(i) === '1') {
                meetingDays[i] = true;
            }
        }
        setFields({
            ...value.value,
            meeting_days: meetingDays,
            start_time: new Date('1970-01-01 ' + value.value.start_time),
            end_time: new Date('1970-01-01 ' + value.value.end_time),
            start_date: new Date(value.value.start_date + ' 00:00:00'),
            end_date: new Date(value.value.end_date + ' 00:00:00'),
        });
        setCurrentCourse(courses[value.value.course_id - 1]);
        setCurrentInstructor(instructors[value.value.instructor_id - 1]);
    }

    const handleClear = (event) => {
        setFields({meeting_days: [false, false, false, false, false, false, false]});
        setEditing(false);
        setCurrentSectionId(-1);
        setCurrentCourse({});
        setCurrentInstructor({});
    }

    const handleSubmit = (event) => {
        let tempDayString = '';
        for (let i = 0; i < fields.meeting_days.length; i++) {
            if (fields.meeting_days[i]) {
                tempDayString += '1';
            }
            else {
                tempDayString += '0';
            }
        }
        const body = {
            ...fields,
            course_name: currentCourse.name,
            meeting_days: tempDayString,
            course_id: currentCourse.id,
            start_date: fields.start_date.getFullYear() + '-' + (fields.start_date.getMonth() + 1) + '-' + (fields.start_date.getDate()),
            end_date: fields.end_date.getFullYear() + '-' + (fields.end_date.getMonth() + 1) + '-' + (fields.end_date.getDate()),
            start_time: fields.start_time.getHours().toString().padStart(2, '0') + ':' + fields.start_time.getMinutes().toString().padStart(2, '0'),
            end_time: fields.end_time.getHours().toString().padStart(2, '0') + ':' + fields.end_time.getMinutes().toString().padStart(2, '0'),
        }
        if (editing) {
            const requestOptions = {
                method: "PUT",
                credentials: "same-origin",
                headers: {
                    'Content-Type': 'application/json',
                  },
                body: JSON.stringify(body),
            }
            fetch("/api/calendar/courses/" + currentCourse.id + "/sections/" + currentSectionId, requestOptions)
                .then(response => response.json())
                .then(data => console.log("Success:", data));
            setEditing(false);
            setCurrentCourse({});
            setCurrentSectionId(-1);
            setCurrentInstructor({});
        }
        else {
            const requestOptions = {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(body),
            }
            fetch("/api/calendar/courses/"+ currentCourse.id + "/sections", requestOptions)
                .then(response => response.json())
                .then(data => console.log("Success:", data));
            setCurrentCourse({});
            if (adminMode) {
                setCurrentInstructor({});
            }
        }
        setFields({meeting_days: [false, false, false, false, false, false, false]});
    }

    return (
        <Box>
            <div className='createSection'>
                <div class='createSectionHeader'>
                    <AppHeader headerText='Manage Sections' />
                </div>
                <Grid container spacing={2}>
                    <Grid item xs>
                        <div class='createOrEditSectionForm'>
                            <Box sx={{pt: 7, m: 1}}>
                                <form>
                                    <Box sx={{width: 9/10}}>
                                        <Autocomplete
                                            disablePortal
                                            sx={{m:1}}
                                            id='course'
                                            options={Object.values(courses).map((course) => { return {label: course.name, id: course.id, value: course} } ) }
                                            onChange={(placeholder, event) => {setCurrentCourse(event.value);}}
                                            renderInput={(params) => <TextField {...params} label="Select Course" />}
                                            value={currentCourse.name || ''}
                                        />
                                       <Autocomplete
                                            disablePortal
                                            sx={{m:1}}
                                            id='instructor_id'
                                            disabled={!adminMode}
                                            options={Object.values(instructors).map((instructor) => { return {label: instructor.first_name + " " + instructor.last_name, id: instructor.id, value: instructor} } ) }
                                            onChange={(placeholder, event) => {setCurrentInstructor(event.value); setFields(values => ({...values, instructor_id: event.value.id}));}}
                                            renderInput={(params) => <TextField {...params} label="Select Instructor" />}
                                            value={currentInstructor.first_name ? (currentInstructor.first_name + " " + currentInstructor.last_name) : ''}
                                        />
                                        <TextField sx={{m:1}} multiline={true} minRows='2' label='Location:' name='location' id='location' value={fields.location || ''} onChange={handleChange} fullWidth={true}/>
                                        <TextField sx={{m:1}} label='Details:' name='details' id='details' value={fields.details || ''} onChange={handleChange} multiline={true} minRows='6' fullWidth={true}/>
                                        <TimePicker sx={{m:1}} format='HH:mm' ampm={false} label="Start Time" value={fields.start_time || null} onChange={(event) => {setFields(values => ({...values, start_time: event}));}} name='start_time' id="start_time" renderInput={(params) => <TextField {...params} />} />
                                        <TimePicker sx={{m:1}} format='HH:mm' ampm={false} label="End Time" value={fields.end_time || null} onChange={(event) => {setFields(values => ({...values, end_time: event}));}} name='end_time' id="end_time" renderInput={(params) => <TextField {...params} />} />
                                        <DatePicker sx={{m:1}} label="Start Date" value={fields.start_date || null} onChange={(event) => {setFields(values => ({...values, start_date: event}));}} name="start_date" id="start_date" renderInput={(params) => <TextField {...params} />} />
                                        <DatePicker sx={{m:1}} label="End Date" value={fields.end_date || null} onChange={(event) => {setFields(values => ({...values, end_date: event}));}} name="end_date" id="end_date" renderInput={(params) => <TextField {...params} />} />
                                        <FormControl sx={{m:1}} fullWidth={true}>
                                            <FormLabel component='legend'>Days of week</FormLabel>
                                            <FormGroup>
                                                <FormControlLabel
                                                    control={<Checkbox onChange={handleDayChange} name={1} checked={fields.meeting_days[1]} />}
                                                    label='Monday'
                                                />
                                                <FormControlLabel
                                                    control={<Checkbox onChange={handleDayChange} name={2} checked={fields.meeting_days[2]} />}
                                                    label='Tuesday'
                                                />
                                                <FormControlLabel
                                                    control={<Checkbox onChange={handleDayChange} name={3} checked={fields.meeting_days[3]} />}
                                                    label='Wednesday'
                                                />
                                                <FormControlLabel
                                                    control={<Checkbox onChange={handleDayChange} name={4} checked={fields.meeting_days[4]} />}
                                                    label='Thursday'
                                                />
                                                <FormControlLabel
                                                    control={<Checkbox onChange={handleDayChange} name={5} checked={fields.meeting_days[5]} />}
                                                    label='Friday'
                                                />
                                                <FormControlLabel
                                                    control={<Checkbox onChange={handleDayChange} name={6} checked={fields.meeting_days[6]}/>}
                                                    label='Saturday'
                                                />
                                                <FormControlLabel
                                                    control={<Checkbox onChange={handleDayChange} name={0} checked={fields.meeting_days[0]} />}
                                                    label='Sunday'
                                                />
                                            </FormGroup>
                                        </FormControl>
                                    </Box>
                                </form>
                            </Box>
                        </div>
                        <div class='submitOrExit'>
                            <Box sx={{width: 9/10}}>
                                <Button variant='contained' fullWidth={true} sx={{color: 'black', backgroundColor: '#f1f167', borderRadius: 0, width: 1/3}} onClick={() => {
                                        if (adminMode) {
                                            navigate('/AdminLandingPage');
                                        }
                                        else {
                                            navigate('/InstructorLandingPage');
                                        }
                                    }}
                                >
                                Home
                                </Button>
                                <Button variant='contained' fullWidth={true} sx={{color: 'black', backgroundColor: 'yellowgreen', borderRadius: 0, width: 1/3}} onClick={handleSubmit}>Save Section</Button>
                                <Button variant='contained' fullWidth={true} sx={{color: 'black', backgroundColor: 'red', borderRadius: 0, width: 1/3}} onClick={handleClear}>Clear Form</Button>
                            </Box>
                        </div>
                    </Grid>
                    <Grid item xs>
                        <Box sx={{pt: 8, m: 1}}>
                            <Autocomplete
                                disablePortal
                                id='select-section'
                                options={Object.values(sections).map((section) => { return {label: section.course_name + " " + section.id, id: section.id, value: section} } ) }
                                onChange={handleSelectSection}
                                renderInput={(params) => <TextField {...params} label="Edit Section" />}
                                value={(currentCourse.name && editing) ? currentCourse.name + " " + currentSectionId : ''}
                            />
                        </Box>
                    </Grid>
                </Grid>
            </div>
        </Box>
    );
}

export default CreateOrEditSection;