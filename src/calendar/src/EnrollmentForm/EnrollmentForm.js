import React, { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import Button from '@mui/material/Button';
import { Box } from '@mui/system';
import AppHeader from '../AppHeader/AppHeader.js'
import { TextField } from '@mui/material';
import { Grid } from '@mui/material';
import { Autocomplete } from '@mui/material';

function EnrollmentForm() {
    const {userId} = useParams();
    const [courses, setCourses] = useState({});
    const [sections, setSections] = useState({});
    const [currentCourse, setCurrentCourse] = useState({});
    const [currentSection, setCurrentSection] = useState({});
    const navigate = useNavigate();

    useEffect(() => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/calendar/courses", requestOptions)
            .then(response => response.json())
            .then(data => setCourses(data));
    }, []);

    useEffect(() => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/calendar/courses/" + currentCourse.id + "/sections", requestOptions)
            .then(response => response.json())
            .then(data => setSections(data));
    }, [currentCourse]);

    const handleSubmit = () => {
        const body = {
            section_id: currentSection.id,
            user_id: userId,
        }
        const requestOptions = {
            method: "POST",
            credentials: "same-origin",
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(body),
        }
        fetch("/api/calendar/courses/" + currentSection.course_id + "/sections/" + currentSection.id + "/roster", requestOptions)
            .then(response => response.json())
            .then(data => console.log("Success:", data));
    }

    return (
        <Box>
            <div className='enrollmentForm'>
                <div class='enrollmentFormHeader'>
                    <AppHeader headerText='Attendee Enrollment' />
                </div>
                <Grid container spacing={2}>
                    <Grid item xs>
                        <div class='enrollmentForm2'>
                        <Box sx={{pt: 8, m: 1, width: 1/2}}>
                            <Autocomplete
                                disablePortal
                                id='course'
                                options={Object.values(courses).map((course) => { return {label: course.name, id: course.id, value: course} } ) }
                                onChange={(placeholder, event) => {setCurrentCourse(event.value);}}
                                renderInput={(params) => <TextField {...params} label="Select Course" />}
                            />
                        </Box>
                        <Grid item xs>
                        <Box sx={{pt: 7, m: 1, width: 1/2}}>
                            <Autocomplete
                                disablePortal
                                id='section'
                                options={Object.values(sections).map((section) => { return {label: section.course_name + " " + section.id, id: section.id, value: section} } ) }
                                onChange={(placeholder, event) => {setCurrentSection(event.value);}}
                                renderInput={(params) => <TextField {...params} label="Select Section" />}
                            />
                        </Box>
                        </Grid>
                        </div>
                        <div class='submitOrExit'>
                            <Box sx={{width: 9/10}}>
                                <Button variant='contained' fullWidth={true} sx={{color: 'black', backgroundColor: '#f1f167', borderRadius: 0, width: 1/4}} onClick={() => {navigate('/AttendeeLandingPage')}}>Home</Button>
                                <Button variant='contained' fullWidth={true} sx={{color: 'black', backgroundColor: 'yellowgreen', borderRadius: 0, width: 1/4}} onClick={handleSubmit}>Enroll</Button>
                            </Box>
                        </div>
                    </Grid>
                </Grid>
            </div>
        </Box>
    );
}

export default EnrollmentForm;