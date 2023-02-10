import React from 'react';
import { useNavigate } from 'react-router-dom';
import Button from '@mui/material/Button';
//import './AdminLandingPage.css';
import { Box } from '@mui/system';
import AppHeader from '../AppHeader/AppHeader.js'
import Calendar from '../Calendar/Calendar.js';
import { Grid } from '@mui/material';

function AdminLandingPage() {

    const navigate = useNavigate();

    return (
        <div className='adminLandingPage'>
            <div className='adminHomeHeader'>
                <AppHeader headerText='Admin Landing Page' />
            </div>
            <Grid container spacing={2}>
                <Grid item xs={3}>
                    <Box>
                        <div className='adminLandingPage'>
                            <Box sx={{ pb: 2, pt: 10, width: 1 / 2 }}>
                                <Button variant='contained' fullWidth={true} onClick={() => { navigate('/CreateCourse') }}>Manage Courses</Button>
                            </Box>
                            <Box sx={{ pb: 2, pt: 2, width: 1 / 2 }}>
                                <Button variant='contained' fullWidth={true} onClick={() => { navigate('/AdminInstructorPage') }}>Manage Instructors</Button>
                            </Box>
                            <Box sx={{ pb: 2, pt: 2, width: 1 / 2 }}>
                                <Button variant='contained' fullWidth={true} onClick={() => { navigate('/CreateOrEditSection') }}>Manage Sections</Button>
                            </Box>
                            <Box sx={{ pb: 2, pt: 2, width: 1 / 2 }}>
                                <Button variant='contained' fullWidth={true} onClick={() => { navigate('/ManageRoster') }}>Manage Rosters</Button>
                            </Box>
                        </div>
                    </Box>
                </Grid>
                <Grid item xs={8}>
                    <Box sx={{ pt: 7 }}>
                        <Calendar />
                    </Box>
                </Grid>
            </Grid>
        </div>
    );
}

export default AdminLandingPage;