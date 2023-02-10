import React, {useEffect, useState} from 'react';
import {useNavigate, useParams} from 'react-router-dom';
import { Autocomplete} from '@mui/material';
import { Box } from '@mui/system';
import AppHeader from '../AppHeader/AppHeader';
import List from '@mui/material/List';
import ListItem from '@mui/material/ListItem';
import ListItemText from '@mui/material/ListItemText';
import DeleteIcon from '@mui/icons-material/Delete';
import IconButton from '@mui/material/IconButton';
import Popover from '@mui/material/Popover';
import { Grid } from '@mui/material';
import { Button } from '@mui/material';
import { TextField } from '@mui/material';
import { Typography } from '@mui/material';

function ManageRoster(props) {
    const navigate = useNavigate();
    const adminMode = props.admin;
    const {instructorId} = useParams();
    const [sections, setSections] = useState({});
    const [currentSection, setCurrentSection] = useState({});
    const [students, setStudents] = useState({});
    const [users, setUsers] = useState({});
    const [action, setAction] = useState(false);

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
        }
        fetch("/api/users", requestOptions)
            .then(response => response.json())
            .then(data => setUsers(data));
    }, [adminMode, instructorId])

    useEffect(() => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/calendar/courses/" + currentSection.course_id + "/sections/" + currentSection.id + "/roster", requestOptions)
            .then(response => response.json())
            .then(data => setStudents(data));
    }, [currentSection, action])

    const handleChange = (event, value) => {
        const body = {
            section_id: currentSection.id,
            user_id: value.value.id,
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
        setAction(action ? false : true);
    }

    const handleSectionChange = (event,value) => {
        setCurrentSection(value.value);
    }

    return(
        <div className='createCourse'>
            <div className='createCourseHeader'>
                <AppHeader headerText='Manage Section Rosters'/>
            </div>
            <div className='manageSectionForm'>
                <Grid container spacing={2}>
                    <Grid item xs={4}>
                        <Box sx={{pt: 8, m: 1}}>
                            <Autocomplete
                                disablePortal
                                id='select-section'
                                options={Object.values(sections).map((section) => { return {label: section.course_name + ' ' + section.id, id: section.id, value: section} } ) }
                                onChange={handleSectionChange}
                                renderInput={(params) => <TextField {...params} label="Select Section" />}
                            />
                        </Box>
                        <Box sx={{pt: 7, m: 1}}>
                            <Autocomplete
                                disablePortal
                                id='select-user'
                                options={Object.values(users).map((user) => { return {label: user.id + ": " + user.first_name + " " + user.last_name, id: user.id, value: user} } ) }
                                onChange={handleChange}
                                renderInput={(params) => <TextField {...params} label="Add student to section" />}
                            />
                        </Box>
                    </Grid>
                    <Grid item xs={2} />
                    <Grid item xs={2}>
                        <Box sx={{pt: 7, m: 1}}>
                            <List dense={false}>
                                {Object.values(students).map((student) => {
                                    return (<StudentListItem student={student} section={currentSection} action={() => {setAction(action ? false : true)}}/>);
                                })}
                            </List>
                        </Box>
                    </Grid>
                    <Grid item xs={4} />
                </Grid>
            </div>
            <div class='submitOrExit'>
                <Box sx={{ pt: 7, m: 1, width: 9/10}}>
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
                </Box>
            </div>
        </div>
    );
}

function StudentListItem(props) {
    const student = props.student;
    const section = props.section;
    const action = props.action;
    const [anchorEl, setAnchorEl] = useState(null);

    const open = Boolean(anchorEl);

    const handleDelete = () => {
        const requestOptions = {
            method: "DELETE",
            credentials: "same-origin",
        }
        fetch("/api/calendar/courses/" + section.course_id + "/sections/" + section.id + "/roster/" + student.id, requestOptions)
            .then(response => response.json())
            .then(data => console.log(data));
        setAnchorEl(null);
        action();
    }

    return (
        <div className='studentListItem'>
            <ListItem secondaryAction={
                <IconButton onClick={(event) => {setAnchorEl(event.currentTarget)}} edge='end' aria-label='delete'>
                    <DeleteIcon />
                </IconButton>
            }>
                <ListItemText primary={student.user_id + ": " + student.first_name + " " + student.last_name} />
            </ListItem>
            <Popover
                    open={open}
                    anchorEl={anchorEl}
                    onClose={() => {setAnchorEl(null)}}
                    anchorOrigin={{
                        vertical: 'bottom',
                        horizontal: 'left',
                    }}
                >
                    <Typography variant='h6'>You are about to remove {student.first_name + ' ' + student.last_name} from the roster</Typography>
                    <Button variant='contained' fullWidth={true} sx={{color: 'black', backgroundColor: 'red', borderRadius: 0, width: 1/2}} onClick={handleDelete}>Remove</Button>
                    <Button variant='contained' fullWidth={true} sx={{color: 'black', backgroundColor: 'greenyellow', borderRadius: 0, width: 1/2}} onClick={() => {setAnchorEl(null)}}>Cancel</Button>
                </Popover>
        </div>
    );
}

export default ManageRoster;
