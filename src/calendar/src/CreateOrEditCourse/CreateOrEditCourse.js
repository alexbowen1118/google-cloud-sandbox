import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import Button from '@mui/material/Button';
import { Box } from '@mui/system';
import AppHeader from '../AppHeader/AppHeader';
import {
    TextField,
    FormControl,
    Grid,
    Autocomplete,
    FormControlLabel,
    Checkbox
} from '@mui/material';

function CreateOrEditCourse() {

    const [fields, setFields] = useState({});
    const [editing, setEditing] = useState(false);
    const [courses, setCourses] = useState({});
    let [subjects, setSubjects] = useState([]);
    const [currentCourseId, setCurrentCourseId] = useState(-1);
    const navigate = useNavigate();

    function addCheckedProperty(data) {
        return data.map(value => ({...value, checked: false}));
    }

    //Gets the courses from the database to be used in the list of editable courses
    useEffect(() => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/calendar/courses", requestOptions)
            .then(response => response.json())
            .then(data => setCourses(data));
    }, [editing]);

    //Gets the subjects from the database to be used in the form
    //and creates the checked property that tracks their status
    useEffect(() => {
        const requestOptions = {
            method: "GET",
            credentials: "same-origin",
        }
        fetch("/api/calendar/subjects", requestOptions)
            .then(response => response.json())
            .then(data => setSubjects(addCheckedProperty(data)))
    }, []);

    //updates the fields when the form text fields change
    const handleChange = (event) => {
        const name = event.target.name;
        const value = event.target.value;
        setFields(values => ({ ...values, [name]: value }));
    }

    //updates the checked property when a checkbox is clicked and updates the subjects field
    const handleCheckboxChange = (event) => {
        let index = event.target.id - 1;
        subjects[index].checked = !subjects[index].checked;
        let subjectsCheckedState = subjects.map(subject => subject.checked);
        setFields(values => ({ ...values, 'subjects': JSON.stringify(subjectsCheckedState) }));
      };

    //handles setting the fields from a course data object
    function populateFields(data){
        let courseSubjects = data.subjects;
        courseSubjects.forEach(currentSubject => subjects[currentSubject.subject_id - 1].checked = true);
        delete data.subjects;
        setFields(data);
    }

    //Switches to editing mode and inserts data from selected course into the form
    const handleSelectCourse = (event, value) => {
        clearSubjects();
        const requestOptions = {
            credentials: "same-origin",
            method: "GET",
        }
        fetch("/api/calendar/courses/" + value.id, requestOptions)
            .then(response => response.json())
            .then(data => populateFields(data));
        setEditing(true);
        setCurrentCourseId(value.id);
    }

    function clearSubjects() {
        subjects.forEach(subject => subject.checked = false);
    }

    //clears the fields and switches out of editing mode
    const handleClear = (event) => {
        setFields({});
        setEditing(false);
        clearSubjects();
        setCurrentCourseId(-1);
    }

    //submits the form as a PUT if editing or a POST otherwise
    const handleSubmit = (event) => {
        if (editing) {
            const requestOptions = {
                method: "PUT",
                credentials: "same-origin",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(fields),
            }
            fetch("/api/calendar/courses/" + currentCourseId, requestOptions)
                .then(response => response.json())
                .then(data => console.log("Success:", data));
            console.log('putting');
            console.log(currentCourseId);
            setEditing(false);
            setCurrentCourseId(-1);
        }
        else {
            const requestOptions = {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(fields),
            }
            fetch("/api/calendar/courses", requestOptions)
                .then(response => response.json())
                .then(data => console.log("Success:", data));
        }
        handleClear();
    }

    //the html page generation
    return (
        <Box>
            <div className='createCourse'>
                <div className='createCourseHeader'>
                    <AppHeader headerText='Manage Courses' />
                </div>
                <Grid container spacing={2}>
                    <Grid item xs>
                        <div className='createOrEditCourseForm'>
                            <Box sx={{ pt: 7, m: 1 }}>
                                <form>
                                    <Box sx={{ width: 9 / 10 }}>
                                        <TextField sx={{ m: 1 }} multiline={true} minRows='2' label='Course Name:' name='name' id='name' value={fields.name || ''} onChange={handleChange} fullWidth={true} />
                                        <FormControl>
                                            {subjects.map((data) => (
                                                <FormControlLabel
                                                    control={
                                                        <Checkbox
                                                            id={data.id}
                                                            key={data.id}
                                                            checked={data.checked}
                                                            onChange={handleCheckboxChange}
                                                            inputProps={{ 'aria-label': 'controlled' }}
                                                        />
                                                    }
                                                    key={data.id}
                                                    label={data.name}
                                                />
                                            ))}
                                        </FormControl>
                                        <TextField sx={{ m: 1 }} label='Description:' name='description' id='description' value={fields.description || ''} onChange={handleChange} multiline={true} minRows='6' fullWidth={true} />
                                        <TextField sx={{ m: 1 }} label='Requirements:' name='requirements' id='requirements' value={fields.requirements || ''} onChange={handleChange} multiline={true} minRows='6' fullWidth={true} />
                                    </Box>
                                </form>
                            </Box>
                        </div>
                        <div className='submitOrExit'>
                            <Box sx={{ width: 9 / 10 }}>
                                <Button variant='contained' fullWidth={true} sx={{ color: 'black', backgroundColor: '#f1f167', borderRadius: 0, width: 1 / 3 }} onClick={() => { navigate('/AdminLandingPage') }}>Home</Button>
                                <Button variant='contained' fullWidth={true} sx={{ color: 'black', backgroundColor: 'yellowgreen', borderRadius: 0, width: 1 / 3 }} onClick={handleSubmit}>Save Course</Button>
                                <Button variant='contained' fullWidth={true} sx={{ color: 'black', backgroundColor: 'red', borderRadius: 0, width: 1 / 3 }} onClick={handleClear}>Clear Form</Button>
                            </Box>
                        </div>
                    </Grid>
                    <Grid item xs>
                        <Box sx={{ pt: 7, m: 1 }}>
                            <Autocomplete
                                disablePortal
                                id='select-course'
                                options={Object.values(courses).map((course) => { return { label: course.name, id: course.id } })}
                                onChange={handleSelectCourse}
                                renderInput={(params) => <TextField {...params} label="Edit Course" />}
                            />
                        </Box>
                    </Grid>
                </Grid>
            </div>
        </Box>
    );
}

export default CreateOrEditCourse;