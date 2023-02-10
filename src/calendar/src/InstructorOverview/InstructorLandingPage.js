import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import Button from '@mui/material/Button';
//import './AdminLandingPage.css';
import { Box } from '@mui/system';
import Calendar from '../Calendar/Calendar.js';
import { Grid } from '@mui/material';
import AppHeader from '../AppHeader/AppHeader.js'

function InstructorLandingPage() {
    const [instructor_id, setInstructor_id] = useState();
    const navigate = useNavigate();

    useEffect(() => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/users/me", requestOptions)
            .then(response => response.json())
            .then(data => getUserId(data));
    }, [])

    const getUserId = (user) => {
        const username = user.sub;
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/users/user/" + username, requestOptions)
            .then(response => response.json())
            .then(data => getInstructorId(data.id));
    }

    const getInstructorId = (id) => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/calendar/instructors/user_id/" + id, requestOptions)
            .then(response => response.json())
            .then(data => setInstructor_id(data.id));
    }


    return (
        <div className='instructorLandingPage'>
            <div className='instructorHomeHeader'>
                <AppHeader headerText='Instructor Home Page' />
            </div>
            <Grid container spacing={2}>
                <Grid item xs={3}>
                    <Box>
                        <div className='instructorLandingPage'>
                            <Box sx={{ pb: 2, pt: 10, width: 1/2}}>
                                <Button variant='contained' fullWidth={true} onClick={() => {navigate('/CreateOrEditSection/' + instructor_id)}}>Manage Sections</Button>
                            </Box>
                            <Box sx={{ pb: 2, pt: 2, width: 1 / 2 }}>
                                <Button variant='contained' fullWidth={true} onClick={() => { navigate('/ManageRoster/' + instructor_id) }}>Manage Rosters</Button>
                            </Box>
                        </div>
                    </Box>
                </Grid>
                <Grid item xs={8}>
                    <Box sx={{pt: 7}}>
                        <Calendar />
                    </Box>
                </Grid>
            </Grid>
        </div>
    );
}

export default InstructorLandingPage;