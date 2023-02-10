import React, {useEffect, useState} from 'react';
import {useLocation, useNavigate} from 'react-router-dom';
import Button from "@mui/material/Button";
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';
import TablePagination from '@mui/material/TablePagination';
import DatePicker, {CalendarContainer} from "react-datepicker";
import APIClient from "../../utils/APIClient";
import {Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle} from "@mui/material";

function ViewDevicePage() {
    const navigate = useNavigate();
    let hiddenDevice = true;
    let hiddenCounter = true;

    // Ensures that you have a device selected to look at (or returns you to ViewDevicesPage)
    const { state } = useLocation();
    if (state == null){
        window.location = "ViewDevicesPage";
    }
    const device = state.device;
    const back = state.back;

    const [counterRules,setCounterRules]=useState([]);
    const [rawData,setVisits]=useState([]);

    useEffect(() => {
        refresh();
    }, [])

    function refresh() {
        APIClient.CounterRules.getCounterRules(device.id).then(
            counterRules => {
                setCounterRules(counterRules)}).catch(error => {
                console.log("Error in getting all counter rules", error)
            }
        );
        APIClient.Visits.getVisitsByDevice(device.id).then(
            visits => {
                setVisits(visits)}).catch(error => {
                console.log("Error in getting all visits", error)
            }
        );
    }

    let sortedRowsVisits = [];
    for(let i = 0; i < rawData.length; i++){
        sortedRowsVisits.push(rowsFromData(rawData[i]));
    }

    // function used to sort visitation table by the Visit ID
    function compareVisitID(a, b){
        const dateA = new Date(a.vis_timestamp);
        const dateB = new Date(b.vis_timestamp);
        if(dateA > dateB){
            return -1;
        }
        if(dateA < dateB){
            return 1;
        }
        return 0;
    }
    sortedRowsVisits.sort(compareVisitID);

    // function that creates the rows from the data by converting the time stamp to a string
    function rowsFromData(
        visit
    ) {
        let vis_id = visit.id;
        let vis_par_id = visit.par_id;
        let vis_dev_id = visit.dev_id;
        let vis_timestamp = visit.timestamp;
        let vis_raw_timestamp = visit.timestamp;
        let vis_count = visit.count;
        let vis_count_calculated = visit.count_calculated;
        let vis_comments = visit.comments;
        let table_parkCode = device.table_parkCode;
        let table_deviceCode = device.table_deviceName;
        return { vis_id, table_parkCode, table_deviceCode, vis_timestamp, vis_count, vis_count_calculated,
            vis_comments, vis_par_id, vis_dev_id, vis_raw_timestamp};
    }

    // columnsDevice for the Device ID
    const columnsDevice= [
        { id: 'table_parkCode', label: 'Park Code', minWidth: 170 },
        { id: 'table_deviceName', label: 'Device Name', minWidth: 170 },
        { id: 'table_deviceFunction', label: 'Function', minWidth: 170 },
        { id: 'table_deviceType', label: 'Type', minWidth: 170 },
        { id: 'table_method', label: 'Method', minWidth: 170 },
        { id: 'table_counterBrand', label: 'Counter Brand', minWidth: 170 },
        { id: 'table_multiplier', label: 'Multiplier', minWidth: 170 },
        { id: 'table_dateUpdated', label: 'Date Updated', minWidth: 170 },
    ];

    // Counter Rules Table
    // Columns Labels for the Counter Rules Table
    const counterRulesTableColumns = [
        { id: 'multiplier', label: 'Multiplier', minWidth: 170 },
        { id: 'start', label: 'Start Date', minWidth: 170 },
        { id: 'end', label: 'End Date', minWidth: 170 },
    ];


    // Handlers and variables to allow the counter rules table to have multiple pages
    const [pageCounterRules, setPageCounterRules] = React.useState(0);
    const [rowsPerPageCounterRules, setRowsPerPageCounterRules] = React.useState(12);
    const handleChangePageCounterRules = (event, newPage) => {
        setPageCounterRules(newPage);
    };
    const handleChangeRowsPerPageCounterRules = (event) => {
        setRowsPerPageCounterRules(+event.target.value);
        setPageCounterRules(0);
    };

    // Function for when a row is selected in the counter rules
    function viewCounterRules(counterRule) {
        let back = "/ViewDevicePage";
        navigate('/EditMultiplierRule',
            {
                state: {device, counterRule, back}
            });
    }


    // Visitation Table
    // Columns Labels for the Visitation Table
    const visitTableColumns= [
        { id: 'vis_id', label: 'Visit ID', minWidth: 170 },
        { id: 'vis_timestamp', label: 'Timestamp', minWidth: 170 },
        { id: 'vis_count', label: 'Raw Count', minWidth: 170 },
        { id: 'vis_count_calculated', label: 'Calculated Count', minWidth: 170 },
        { id: 'vis_comments', label: 'Comments', minWidth: 170 },
    ];


    // Handlers and variables to allow the visitation table to have multiple pages
    const [pageVisitTable, setPageVisitTable] = React.useState(0);
    const [rowsPerPageVisitTable, setRowsPerPageVisitTable] = React.useState(10);
    const handleChangePageVisitTable = (event, newPage) => {
        setPageVisitTable(newPage);
    };
    const handleChangeRowsPerPageVisitTable = (event) => {
        setRowsPerPageVisitTable(+event.target.value);
        setPageVisitTable(0);
    };

    // Function for when a row is selected
    function viewVisit(visit) {
        let back = "/ViewDevicePage";
        navigate('/EditDataPage',
            {
                state: {visit, back, device}
            });
    }
    // Function to add a new Visit
    function addVisit() {
        let back = "/ViewDevicePage";
        navigate('/AddDataPage',
            {
                state: {back, device}
            });
    }

    // Sort By Dates section
    const[startDate, setStartDate] = useState();
    const [endDate, setEndDate] = useState();
    function onChangeDateHandler(value) {
        setStartDate(value[0]);
        setEndDate(value[1]);
    }

    if(startDate != null && endDate != null){
        sortedRowsVisits = [];
        for(let i = 0; i < rawData.length; i++){
            let tempDate = new Date(rawData[i].timestamp);
            if(tempDate > startDate && tempDate < endDate){
                sortedRowsVisits.push(rowsFromData(rawData[i]));
            }
        }
        sortedRowsVisits.sort(compareVisitID);
    }

    // Delete modal
    const [open, setOpen] = React.useState(false);

    const handleClickOpen = () => {
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
    };

    // function that deletes the device
    function deleteDevice(){
        console.log("Deleted");
        APIClient.Devices.deleteDevice(device.id).then(r => console.log("success"));
        navigate('/ViewDevicesPage');
    }
    const MyContainer = ({ className, children }) => {
        return (

            <CalendarContainer className={className}>
                <div style={{ background: "#f0f0f0" }}>
                    <br></br><br></br><br></br><br></br>
                </div>
                <div style={{ position: "relative" }}>{children}</div>
            </CalendarContainer>
        );
    };

    // Hides elements because of permissions
    // Permissions:
    // 0	NoAccess
    // 1	BaseLevel
    // 2	Manager
    // 3	Admin
    // 4	SuperAdmin
    let role = sessionStorage.getItem("role")
    if(role == "Manager"){
        hiddenDevice = false;
    }
    else if(role == "Admin" || role == "SuperAdmin" ){
        hiddenDevice = false;
        hiddenCounter = false;
    }

    return (
        <div className='mainLandingPage'>
            <div className='mainHeader'>
                <h2 id="title">
                    Viewing: {device.table_deviceName}
                </h2>
            </div>
            <Button onClick={() => {navigate(back);}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Back</Button>
            {!hiddenDevice ? <Button onClick={() => {
                let back = "/ViewDevicePage";
                navigate('/EditDevicePage',
                    {
                        state: {device, back}
                    });
            }} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Edit Device</Button> : null}
            {!hiddenDevice ? <Button variant="outlined" onClick={handleClickOpen} style={{ borderColor: '#000000',  color:'#000000' }}>Delete Device</Button> : null}

            {/*Confirmation Modal*/}
            <Dialog
                open={open}
                onClose={handleClose}
                aria-labelledby="alert-dialog-title"
                aria-describedby="alert-dialog-description"
            >
                <DialogTitle id="alert-dialog-title">
                    {"Confirm Delete Device"}
                </DialogTitle>
                <DialogContent>
                    <DialogContentText id="alert-dialog-description">
                        Are you sure you want to delete this device?<br></br><br></br>
                        This action cannot be undone.
                    </DialogContentText>
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleClose} style={{ borderColor: '#000000',  color:'#000000' }}>Cancel</Button>
                    <Button onClick={deleteDevice} autoFocus style={{ borderColor: '#000000',  color:'#000000' }}>
                        Delete
                    </Button>
                </DialogActions>
            </Dialog>


            {/*Device Table Section*/}
            <TableContainer component={Paper}>
                <Table sx={{ minWidth: 650 }} aria-label="simple table">
                    <TableHead>
                        <TableRow>
                            {columnsDevice.map((column) => (
                                <TableCell
                                    key={column.id}
                                    align={column.align}
                                    style={{ minWidth: column.minWidth }}
                                >
                                    {column.label}
                                </TableCell>
                            ))}
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        <TableRow >
                            {columnsDevice.map((column) => {
                                const value = device[column.id];
                                return (
                                    <TableCell key={column.id} align={column.align}>
                                        {column.format && typeof value === 'number'
                                            ? column.format(value)
                                            : value}
                                    </TableCell>
                                );
                            })}
                        </TableRow>
                    </TableBody>
                </Table>
            </TableContainer>

            {/*Counter Rules Section*/}
            <br></br><br></br>
            <h3>View Counter Rules:</h3>
            {!hiddenCounter ? <Button onClick={() => {
                let back = "/ViewDevicePage";
                navigate('/AddMultiplierRule',
                    {
                        state: {device, back}
                    });
                }} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Add Counter Rules</Button> : null }
            {/*Counter Rules Table*/}
            <Paper sx={{ width: '100%', overflow: 'hidden' }}>
                <TableContainer sx={{ maxHeight: 440 }}>
                    <Table stickyHeader aria-label="sticky table">
                        <TableHead>
                            <TableRow>
                                {counterRulesTableColumns.map((counterRulesTableColumn) => (
                                    <TableCell
                                        key={counterRulesTableColumn.id}
                                        align={counterRulesTableColumn.align}
                                        style={{ minWidth: counterRulesTableColumn.minWidth }}
                                    >
                                        {counterRulesTableColumn.label}
                                    </TableCell>
                                ))}
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {counterRules
                                .slice(pageCounterRules * rowsPerPageCounterRules, pageCounterRules * rowsPerPageCounterRules + rowsPerPageCounterRules)
                                .map((row) => {
                                    return (
                                        <TableRow hover role="checkbox" tabIndex={-1} key={row.code} onClick={() => viewCounterRules(row)}>
                                            {counterRulesTableColumns.map((counterRulesTableColumn) => {
                                                const value = row[counterRulesTableColumn.id];
                                                return (
                                                    <TableCell key={counterRulesTableColumn.id} align={counterRulesTableColumn.align}>
                                                        {counterRulesTableColumn.format && typeof value === 'number'
                                                            ? counterRulesTableColumn.format(value)
                                                            : value}
                                                    </TableCell>
                                                );
                                            })}
                                        </TableRow>
                                    );
                                })}
                        </TableBody>
                    </Table>
                </TableContainer>
                <TablePagination
                    rowsPerPageOptions={[5, 12, 25, 100]}
                    component="div"
                    count={counterRules.length}
                    rowsPerPage={rowsPerPageCounterRules}
                    page={pageCounterRules}
                    onPageChange={handleChangePageCounterRules}
                    onRowsPerPageChange={handleChangeRowsPerPageCounterRules}
                />
            </Paper>

            {/*Data Visitation Section*/}
            <br></br><br></br>
            <h3>View Visitation Data:</h3>
            <Button onClick={() => {addVisit()}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Add Visitation Data</Button>
            <h4>Sort By Dates:</h4>
            <div class='date'>
                <DatePicker sx={{}}
                    selectsRange={true}
                    startDate={startDate}
                    endDate={endDate}
                    onChange={onChangeDateHandler}
                    calendarContainer={MyContainer}
                    dateFormat="dd MMM yyyy"/>
            </div>

            {/*Visitation Table*/}
            <Paper sx={{ width: '100%', overflow: 'hidden' }}>
                <TableContainer sx={{ maxHeight: 440 }}>
                    <Table stickyHeader aria-label="sticky table">
                        <TableHead>
                            <TableRow>
                                {visitTableColumns.map((visitTableColumn) => (
                                    <TableCell
                                        key={visitTableColumn.id}
                                        align={visitTableColumn.align}
                                        style={{ minWidth: visitTableColumn.minWidth }}
                                    >
                                        {visitTableColumn.label}
                                    </TableCell>
                                ))}
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {sortedRowsVisits
                                .slice(pageVisitTable * rowsPerPageVisitTable, pageVisitTable * rowsPerPageVisitTable + rowsPerPageVisitTable)
                                .map((row) => {
                                    return (
                                        <TableRow hover role="checkbox" tabIndex={-1} key={row.code} onClick={() => viewVisit(row)}>
                                            {visitTableColumns.map((visitTableColumn) => {
                                                const value = row[visitTableColumn.id];
                                                return (
                                                    <TableCell key={visitTableColumn.id} align={visitTableColumn.align}>
                                                        {visitTableColumn.format && typeof value === 'number'
                                                            ? visitTableColumn.format(value)
                                                            : value}
                                                    </TableCell>
                                                );
                                            })}
                                        </TableRow>
                                    );
                                })}
                        </TableBody>
                    </Table>
                </TableContainer>
                <TablePagination
                    rowsPerPageOptions={[5, 10, 25, 100]}
                    component="div"
                    count={sortedRowsVisits.length}
                    rowsPerPage={rowsPerPageVisitTable}
                    page={pageVisitTable}
                    onPageChange={handleChangePageVisitTable}
                    onRowsPerPageChange={handleChangeRowsPerPageVisitTable}
                />
            </Paper>
        </div>
    );

}
export default ViewDevicePage;
