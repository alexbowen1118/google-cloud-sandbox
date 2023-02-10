import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import AppHeader from '../AppHeader/AppHeader';
import { Box } from '@mui/material';
import { TextField } from '@mui/material';
import { Autocomplete } from '@mui/material';
import { Grid } from '@mui/material';
import { Button } from '@mui/material';

// This is the function that loads the Admin Instructor Page
function AdminInstructorPage() {
    // the fields are used to store the data to be sent in the message
    const [fields, setFields] = useState({});
    const [editing, setEditing] = useState(false);
    const [instructors, setInstructors] = useState({});
    const [currentInstructorId, setCurrentInstructorId] = useState(-1);


    //used to switch between pages
    const navigate = useNavigate();

    useEffect(() => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/calendar/instructors", requestOptions)
            .then(response => response.json())
            .then(data => setInstructors(data));
    }, [editing]);

    //updates the fields when there is a change
    const handleChange = (event) => {
        const name = event.target.name;
        const value = event.target.value;
        setFields(values => ({ ...values, [name]: value }));
    }

    //handles editing an instructor
    const handleSelectInstructor = (event, value) => {
        const requestOptions = {
            credentials: "same-origin",
            method: "GET",
        }
        fetch("/api/calendar/instructors/" + value.id, requestOptions)
            .then(response => response.json())
            .then(data => setFields(data));
        setEditing(true);
        setCurrentInstructorId(value.id);
    }

    const handleClear = (event) => {
        setFields({});
        setEditing(false);
        setCurrentInstructorId(-1);
    }

    //handles the submission, will create a post api request
    const handleSubmit = (event) => {
        var userFields = {
            'first_name': fields.first_name,
            'last_name': fields.last_name,
            'role': 'INSTRUCTOR',
            'hash': '$2y$10$kglpeJqbVWsVws8ITdh4oejJQhfKXTe4Ir8r5qK7D3OSPEyhaaffW'
        };
        console.log(JSON.stringify(userFields));
        if (editing) {
            const requestOptions = {
                method: "PUT",
                credentials: "same-origin",
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(fields),
            }
            fetch("/api/calendar/instructors/" + currentInstructorId, requestOptions)
                .then(response => response.json())
                .then(data => console.log("Success:", data));
            console.log('putting');
            console.log(currentInstructorId);
            setEditing(false);
            setCurrentInstructorId(-1);
        }
        else {
            postUser(userFields);
            console.log(JSON.stringify(userFields));
        }
    }

    const postUser = (userFields) => {
        const requestOptionsForUser = {
            method: "POST",
            credentials: "same-origin",
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(userFields),
        }
        fetch("/api/users", requestOptionsForUser)
            .then(response => response.json())
            .then(data => postInstructor(data));
        console.log('posting user');
    }

    const postInstructor = (id) => {
        const submitFields = {
            ...fields,
            user_id: id,
        }
        const requestOptions = {
            method: "POST",
            credentials: "same-origin",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(submitFields),
        }
        fetch("/api/calendar/instructors", requestOptions)
            .then(response => response.json())
            .then(data => console.log("Success:", data));
    }


    //the actual frontend page
    return (
        <Box>
            <div className="adminInstructorPage">
                <AppHeader headerText='Manage Instructors' />
                <Grid container spacing={2}>
                    <Grid item xs>
                        <div className='createOrEditInstructorForm'>
                            <Box sx={{ pt: 7, m: 1 }}>
                                <form onSubmit={handleSubmit}>
                                    <TextField sx={{ m: 1 }} multiline={false} label='Title:' name='title' id='title' value={fields.title || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='First Name:' name='first_name' id='first_name' value={fields.first_name || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='Last Name:' name='last_name' id='last_name' value={fields.last_name || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='Address Line 1:' name='addr1' id='addr1' value={fields.addr1 || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='Address Line 2:' name='addr2' id='addr2' value={fields.addr2 || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='City:' name='city' id='city' value={fields.city || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='State:' name='state' id='state' value={fields.state || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='ZIP Code:' name='zip' id='zip' value={fields.zip || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='Phone Number:' name='phone' id='phone' value={fields.phone || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='Fax:' name='fax' id='fax' value={fields.fax || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='Email:' name='email' id='email' value={fields.email || ''} onChange={handleChange} fullWidth={true} />
                                    <TextField sx={{ m: 1 }} multiline={false} label='Website:' name='website' id='website' value={fields.website || ''} onChange={handleChange} fullWidth={true} />

                                </form>
                            </Box>
                        </div>
                        <div className='submitOrExit'>
                            <Box sx={{ width: 9 / 10 }}>
                                <Button variant='contained' fullWidth={true} sx={{ color: 'black', backgroundColor: '#f1f167', borderRadius: 0, width: 1 / 3 }} onClick={() => { navigate('/AdminLandingPage') }}>Home</Button>
                                <Button variant='contained' fullWidth={true} sx={{ color: 'black', backgroundColor: 'yellowgreen', borderRadius: 0, width: 1 / 3 }} onClick={handleSubmit}>Submit Form</Button>
                                <Button variant='contained' fullWidth={true} sx={{ color: 'black', backgroundColor: 'red', borderRadius: 0, width: 1 / 3 }} onClick={handleClear}>Clear Form</Button>
                            </Box>
                        </div>
                    </Grid>
                    <Grid item xs>
                        <Box sx={{ pt: 7, m: 1 }}>
                            <Autocomplete
                                disablePortal
                                id='select-instructor'
                                options={Object.values(instructors).map((instructor) => { return { label: instructor.first_name + " " + instructor.last_name, id: instructor.id } })}
                                onChange={handleSelectInstructor}
                                renderInput={(params) => <TextField {...params} label="Edit Instructor" />}
                            />
                        </Box>
                    </Grid>
                </Grid>
            </div>
        </Box>
    );
}

export default AdminInstructorPage;
