import React, {useEffect, useState} from 'react';
import {Link, useHref, useLocation, useNavigate} from 'react-router-dom';
import IterationOneTestPage from "../IterationOneTestPage/IterationOneTestPage";
import {
    Dialog, DialogActions,
    DialogContent,
    DialogContentText,
    DialogTitle,
    FormControl,
    InputLabel,
    MenuItem,
    Select
} from '@mui/material';
import Button from "@mui/material/Button";
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';
import TablePagination from '@mui/material/TablePagination';
import APIClient from "../../utils/APIClient";
import TextField from "@mui/material/TextField";
import DatePicker from "react-datepicker";



function EditDataPage(props) {
    const navigate = useNavigate();
    let hidden = true;

    useEffect(() => {
        refresh();
    }, [])


    function refresh() {
    }

    const { state } = useLocation();
    if (state == null){
        window.location = "ViewDevicesPage";
    }
    const device = state.device;
    const back = state.back;
    const visitation = state.visit;


    function goBack(){
        console.log(back);
        if (back == "/ViewDevicePage"){
            console.log("Here");
            console.log(device);
            let back = "/ViewDevicesPage";
            navigate('/ViewDevicePage',
                {
                    state: {device, back}
                });
        }
        if (back == "/ViewAllDataPage"){
            console.log("ViewAllDataPage")
            let back = "/ViewDevicesPage";
            navigate('/ViewAllDataPage');
        }
        else{
            navigate('/MainLandingPage')
        }
    }

    function deleteData(){
        console.log("Deleted");
        APIClient.Visits.deleteVisit(visit.vis_dev_id, visitation.vis_id).then(r => console.log("success"));
        goBack();
    }


    const visit = {
        vis_par_id: visitation.vis_par_id,
        vis_dev_id: visitation.vis_dev_id,
        vis_timestamp: visitation.vis_timestamp,
        vis_count: visitation.vis_count,
        vis_count_calculated: visitation.vis_count_calculated,
        vis_comments: visitation.vis_comments,
        status: 1,

    };


    // Form handlers

    // Device Section

    // Calculated Counts section
    const [count,setCount]=React.useState('');
    const handleCount = (event) => {
        setCount(event.target.value);
        visit.vis_count = event.target.value
    };

    // Timestamp section
    const [startDate, setStartDate] = useState(new Date(visitation.vis_timestamp));

    // Comments section
    const [comment,setComment]=React.useState('');
    const handleComment = (event) => {
        setComment(event.target.value);
        visit.vis_comments = event.target.value
    };

    const [errorMsg,setErrorMsg]=React.useState([]);

    function submitVisit() {
        let issue = false;
        if (count != "" && count < 1){
            errorMsg.push("Calculated Count must be greater than 1");
            console.log("Issue with count: negative");
            issue = true;
        }
        if(startDate == null){
            errorMsg.push("Please fix selected Timestamp");
            console.log("Issue with time")
            issue = true;
        }

        if (issue == false) {

            count == "" ? visit.vis_count_calculated = visitation.vis_count_calculated : visit.vis_count_calculated = count;

            comment == "" ? visit.vis_comments = visitation.vis_comments : visit.vis_comments = comment;

            visit.vis_timestamp = startDate.toISOString().slice(0, 19).replace('T', ' ');
            APIClient.Visits.updateVisit(visit.vis_dev_id, visitation.vis_id, visit).then(r => console.log("success"));
            goBack();
        }
        else{
            // console.log(tempMessage);
            // setErrorMsg(tempMessage);
            handleClickOpen();
        }
    }

    // Error modal
    const [open, setOpen] = React.useState(false);

    const handleClickOpen = () => {
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
    };

    // Delete modal
    const [openDelete, setOpenDelete] = React.useState(false);

    const handleClickOpenDelete = () => {
        setOpenDelete(true);
    };

    const handleCloseDelete = () => {
        setOpenDelete(false);
    };

    // Hides elements because of permissions
    // Permissions:
    // 0	NoAccess
    // 1	BaseLevel
    // 2	Manager
    // 3	Admin
    // 4	SuperAdmin
    let role = sessionStorage.getItem("role")
    if(role === "NoAccess"){
        goBack();
        window.location = "ViewAllDataPage";
    }
    if(role === "BaseLevel"){
        let curDate = new Date();
        if(curDate.getMonth() != startDate.getMonth() &&
            (curDate.getMonth() - 1 != startDate.getMonth() && curDate.getDate() < 7)){
            return (
                <div className='mainLandingPage'>
                    <div className='mainHeader'>
                        <h2 id="title">
                            ERROR:
                        </h2>
                        With your account permissions, you can only Edit & Delete current month’s data, or previous month’s data if within the first 7 days of the current month
                    </div>
                    <Button onClick={() => {goBack()}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Back</Button>
                </div>
            );
        }
        else {
            hidden = false;
        }
    }
    else if(role === "Manager"){
        let curDate = new Date();
        if(curDate.getMonth() == startDate.getMonth() ||
            (curDate.getMonth() - 1 == startDate.getMonth() && curDate.getDate() < 8)){
            hidden = false;
        }
        // FIXME: Add Request to edit4
    }
    else {
        hidden = false;
    }

    return (
        <div className='mainLandingPage'>
            <div className='mainHeader'>
                <h2 id="title">
                    Edit Data
                </h2>
            </div>
            <Button onClick={() => {goBack()}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Back</Button>
            {!hidden ? <Button onClick={() => {handleClickOpenDelete()}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Delete Data</Button> : null}
            <p></p>

            <React.Fragment>
                <form >
                    <div className="pageBody">
                        <p> </p>
                        <p> </p>
                        <b>Park Code</b>
                        <p> </p>
                        {visitation.table_parkCode}

                        <p> </p>


                        <p> </p>
                        <b>Device Code</b>
                        <p> </p>
                        {visitation.table_deviceCode}
                        <p> </p>


                        <p> </p>
                        <b>Raw Count</b>
                        <p> </p>
                        {visitation.vis_count}
                        <p> </p>

                        <p> </p>
                        <b>Calculated Count</b>
                        <p> </p>
                        <TextField required id="filled-basic" label="Enter Calculated Count"  onChange={handleCount} defaultValue={visitation.vis_count_calculated}/>
                        <p> </p>

                        <p> </p>
                        <b>Comments</b>
                        <p> </p>
                        <TextField required id="filled-basic" label="Enter Comments"  onChange={handleComment} defaultValue={visitation.vis_comments} />
                        <p> </p>

                        <p> </p>
                        <b>Timestamp</b>
                        <p> </p>
                        {/*Timestamp*/}
                        <DatePicker
                            selected={startDate}
                            onChange={(date) => setStartDate(date)}
                            showTimeSelect
                            timeFormat="HH:mm"
                            timeIntervals={15}
                            timeCaption="time"
                            dateFormat="MMMM d, yyyy h:mm aa"
                            maxDate={new Date()}
                        />
                        <p> </p>


                        <Button variant="outlined" onClick={submitVisit} style={{ borderColor: '#000000',  color:'#000000' }}>Save</Button>
                        <p> </p>
                    </div>
                </form>
            </React.Fragment>

            {/*Error Modal*/}
            <Dialog
                open={open}
                onClose={handleClose}
                aria-labelledby="alert-dialog-title"
                aria-describedby="alert-dialog-description"
            >
                <DialogTitle id="alert-dialog-title">
                    {"Error Creating Visit: "}
                </DialogTitle>
                <DialogContent>
                    <DialogContentText id="alert-dialog-description">
                        {errorMsg.map(item => {
                            return <p>{item}</p>;
                        })}
                    </DialogContentText>
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleClose} autoFocus style={{ borderColor: '#000000',  color:'#000000' }}>Dismiss</Button>
                </DialogActions>
            </Dialog>

            {/*Delete Confirmation Modal*/}
            <Dialog
                open={openDelete}
                onClose={handleCloseDelete}
                aria-labelledby="alert-dialog-title-delete"
                aria-describedby="alert-dialog-description-delete"
            >
                <DialogTitle id="alert-dialog-title-delete">
                    {"Confirm Delete Data"}
                </DialogTitle>
                <DialogContent>
                    <DialogContentText id="alert-dialog-description-delete">
                        Are you sure you want to delete this visitation data?<br></br><br></br>
                        This action cannot be undone.
                    </DialogContentText>
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleCloseDelete} style={{ borderColor: '#000000',  color:'#000000' }}>Cancel</Button>
                    <Button onClick={deleteData} autoFocus style={{ borderColor: '#000000',  color:'#000000' }}>
                        Delete
                    </Button>
                </DialogActions>
            </Dialog>
        </div>
    );

}
export default EditDataPage;
