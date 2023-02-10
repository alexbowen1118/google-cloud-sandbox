import React, {useState, useEffect} from 'react';
import { useNavigate } from 'react-router-dom';
import Button from '@mui/material/Button';
import { Box } from '@mui/system';
import AppHeader from '../AppHeader/AppHeader.js';
import Calendar from '../Calendar/Calendar.js';
import { Grid } from '@mui/material';

function AttendeeLandingPage() {
    const [user_id, setUser_id] = useState();
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
            .then(data => setUser_id(data.id));
    }

    return (
        <div className='attendeeLandingPage'>
            <div class='attendeeHomeHeader'>
                <AppHeader headerText='Attendee Home Page' />
            </div>
            <Grid container spacing={2}>
                <Grid item xs={3}>
                    <Box>
                        <div className='attendeeLandingPage'>
                            <Box sx={{ pb: 2, pt: 10, width: 1/2}}>
                                <Button variant='contained' fullWidth={true} onClick={() => {navigate('/EnrollmentForm/' + user_id)}}>Enrollment Form</Button>
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

export default AttendeeLandingPage;