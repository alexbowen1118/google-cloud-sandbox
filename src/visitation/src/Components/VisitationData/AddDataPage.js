import React, {useEffect, useState} from 'react';
import {useLocation, useNavigate} from 'react-router-dom';
import {
    Dialog,
    DialogActions,
    DialogContent,
    DialogContentText,
    DialogTitle,
    FormControl,
    MenuItem,
    Select
} from '@mui/material';
import Button from "@mui/material/Button";
import APIClient from "../../utils/APIClient";
import TextField from "@mui/material/TextField";
import DatePicker from "react-datepicker";



function AddDataPage() {
    const navigate = useNavigate();

    useEffect(() => {
        refresh();
    }, [])


    function refresh() {
        APIClient.Parks.getParks().then(
            parks => {
                setParks(parks)
            }).catch(error => {
                console.log("Error in getting all parks", error)
            }
        );
        APIClient.Devices.getDevices().then(
            devices => {
                setDevices(devices)
            }).catch(error => {
                console.log("Error in getting all devices", error)
            }
        );
    }
    const { state } = useLocation();
    if (state == null){
        window.location = "ViewDevicesPage";
    }
    const device = state.device;
    const back = state.back;

    function goBack(){
        if (back == "/ViewDevicePage"){
            let back = "/ViewDevicesPage";
            navigate('/ViewDevicePage',
                {
                    state: {device, back}
                });
        }
        if (back == "/ViewAllDataPage"){
            let back = "/ViewDevicesPage";
            navigate('/ViewAllDataPage');
        }
    }

    const visit = {
        vis_par_id: -1,
        vis_dev_id: -1,
        vis_timestamp: "2022-08-01 16:00:00",
        vis_count: -1,
        vis_comments: null,
        status: 1,

    };

    // Form handlers

    // Device Section
    const [inputedDevice,setDevice]=React.useState('');
    const [devices,setDevices]=useState([])
    let [sortedDevices,setSortedDevices]=useState([])

    let handleDevice = (event) => {
        setDevice(event.target.value);
        visit.vis_dev_id = event.target.value;
        devId = "";
    }

    // Park Codes Section
    const [park,setPark]=React.useState('');
    const [parks,setParks]=useState([])

    let parkId = '';
    let devId = '';
    if (device == null) {
        parkId = '';
        devId = '';
    }
    else {
        parkId = device.par_id;
        sortedDevices = [];
        for(let i = 0; i < devices.length; i++){
            if(devices[i].par_id == parkId){
                sortedDevices.push(devices[i]);
            }
        }
        devId = device.id;
    }

    let handlePark = (event) => {
        let temp = event.target.value;
        setPark(temp);
        parkId = temp;
        visit.vis_par_id = temp;
    }


    if (park != ""){
        sortedDevices = [];
        for(let i = 0; i < devices.length; i++){
            if(devices[i].par_id == park){
                sortedDevices.push(devices[i]);
            }
        }
    }

    // Raw Counts section
    const [count,setCount]=React.useState('');
    const handleCount = (event) => {
        setCount(event.target.value);
        visit.vis_count = event.target.value
    };

    // Timestamp section
    const [startDate, setStartDate] = useState(new Date());

    // Comments section
    const [comment,setComment]=React.useState('');
    const handleComment = (event) => {
        setComment(event.target.value);
        visit.vis_comments = event.target.value
    };

    const [errorMsg,setErrorMsg]=React.useState([]);

    function submitVisit() {
        let issue = false;
        if (park == "" && parkId == ""){
            errorMsg.push("Please fix selected Park Code");
            console.log("Issue with park");
            issue = true
        }
        if (inputedDevice == "" && devId == ""){
            errorMsg.push("Please fix selected Device Code");
            console.log("Issue with device");
            issue = true
        }
        if (count == ""){
            errorMsg.push("Please fix selected Raw Count");
            console.log("Issue with count");
            issue = true
        }
        if (count < 1){
            errorMsg.push("Raw Count must be greater than 1");
            console.log("Issue with count: negative");
            issue = true;
        }
        if(startDate == null){
            errorMsg.push("Please fix selected Timestamp");
            console.log("Issue with time")
            issue = true;
        }
        // Check if the device code is valid
        if (issue == false){
            let found = false;
            let find = devId;
            if (devId == ""){
                find = inputedDevice;
            }
            for(let i = 0; i < sortedDevices.length; i++){
                if(sortedDevices[i].id == find){
                    found = true;
                }
            }
            if (!found){
                errorMsg.push("Please fix selected Device Code");
                console.log("Issue with device");
                issue = true;
            }
        }
        if (issue == false) {
            if (park == ""){
                visit.vis_par_id = parkId;
                if(inputedDevice == ""){
                    visit.vis_dev_id = devId;
                }
                else{
                    visit.vis_dev_id = inputedDevice;
                }
            }
            else{
                visit.vis_par_id = park;
                if(inputedDevice == ""){
                    visit.vis_dev_id = devId;
                }
                else{
                    visit.vis_dev_id = inputedDevice;
                }
            }
            visit.vis_count = count;
            visit.vis_comments = comment;
            visit.vis_timestamp = startDate.toISOString().slice(0, 19).replace('T', ' ');
            APIClient.Visits.createVisit(1, visit).then(r => console.log("success"));
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




    return (
        <div className='mainLandingPage'>
            <div className='mainHeader'>
                <h2 id="title">
                    Add Data
                </h2>
            </div>
            <Button onClick={() => {goBack()}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Back</Button>

            <React.Fragment>
                <form >
                    <div className="pageBody">
                        <p> </p>
                        <p> </p>
                        <b>Park Code</b>
                        <p> </p>
                        <FormControl sx={{ minWidth: 300 }}>
                            <Select onChange={handlePark} defaultValue={parkId}>
                                <option value="Select a Park"> -- Select a Park -- </option>
                                {parks?.map(park => {
                                    return (
                                        <MenuItem key={park.id} value={park.id}>
                                            {park.name}
                                        </MenuItem>
                                    );
                                })}
                            </Select>
                        </FormControl >
                        <p> </p>


                        <p> </p>
                        <b>Device Code</b>
                        <p> </p>
                        <FormControl sx={{ minWidth: 300 }}>
                            <Select onChange={handleDevice}  defaultValue={devId}>
                                <option value="Select a Device"> -- Select a Device -- </option>
                                {sortedDevices?.map(inputedDevice => {
                                    return (
                                        <MenuItem key={inputedDevice.id} value={inputedDevice.id}>
                                            {inputedDevice.name}
                                        </MenuItem>
                                    );
                                })}
                            </Select>
                        </FormControl >
                        <p> </p>

                        <p> </p>
                        <b>Raw Count</b>
                        <p> </p>
                        <TextField required id="filled-basic" label="Enter Raw Count"  onChange={handleCount} />
                        <p> </p>

                        <p> </p>
                        <b>Comments</b>
                        <p> </p>
                        <TextField required id="filled-basic" label="Enter Comments"  onChange={handleComment} />
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

        </div>
    );

}
export default AddDataPage;
