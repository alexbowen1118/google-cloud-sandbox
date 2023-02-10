import React, {useEffect, useState} from 'react';
import {Link, useHref, useNavigate} from 'react-router-dom';
import {FormControl, InputLabel, MenuItem, Select} from '@mui/material';
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



function ViewAllDataPage() {
    const navigate = useNavigate();

    const parkCodes = ["All Parks"];
    const parkMap = new Map();
    const deviceMap = new Map();

    const [parkCode, setParkCode] = React.useState('All Parks');
    const [parks,setParks]=useState([]);
    const [devices,setDevices]=useState([]);
    const [visits,setVisits]=useState([]);

    useEffect(() => {
        refresh();
    }, [])

    function refresh() {
        APIClient.Parks.getParks().then(
            parks => {
                setParks(parks)}).catch(error => {
                console.log("Error in getting all parks", error)
            }
        );
        APIClient.Devices.getDevices().then(
            devices => {
                setDevices(devices)}).catch(error => {
                console.log("Error in getting all devices", error)
            }
        );
        // FIXME: If Auth:
        APIClient.Visits.getVisits().then(
            visit => {
                setVisits(visit)}).catch(error => {
                console.log("Error in getting all visits", error)
            }
        );
        // FIXME: Else:
        // let parkId = 218;
        // APIClient.Visits.getVisitsByPark(parkId).then(
        //     visit => {
        //         setVisits(visit)}).catch(error => {
        //         console.log(`Error in getting visits in park number ${parkId}`, error)
        //     }
        // );


    }
    // Handling the calls
    // Put all park codes into an array to use in the dropdown
    // Create map with key:value of park.id:park.name
    for (let i = 0; i < parks.length; i++){
        parkCodes.push(parks[i].name);
        parkMap.set(parks[i].id, parks[i].name);
    }
    // Create map with k:v of function.id:function.name
    for (let i = 0; i < devices.length; i++){
        deviceMap.set(devices[i].id, devices[i].name);
    }


    // TODO: This is temporary to get static data
    function createDataVisits(
        visit
    ) {
        let vis_id = visit.id;
        let vis_par_id = visit.par_id;
        let vis_dev_id = visit.dev_id;
        let vis_timestamp = visit.timestamp;
        let vis_count = visit.count;
        let vis_count_calculated = visit.count_calculated;
        let vis_comments = visit.comments;
        let table_parkCode = parkMap.get(vis_par_id);
        let table_deviceCode = deviceMap.get(vis_dev_id);
        return { vis_id, vis_par_id, vis_dev_id, vis_timestamp, vis_count, vis_count_calculated, vis_comments,
                table_parkCode, table_deviceCode};
    }

    const rawData = [];
    for (let i = 0; i < visits.length; i++){
        rawData.push(createDataVisits(visits[i]));
    }
    // TODO: Fix the timeStamp format
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
        let vis_par_id = visit.vis_par_id;
        let vis_dev_id = visit.vis_dev_id;
        let vis_raw_timestamp = visit.vis_timestamp;
        let vis_id = visit.vis_id;
        let table_parkCode = visit.table_parkCode;
        let table_deviceCode = visit.table_deviceCode;
        let vis_timestamp = visit.vis_timestamp.toString();
        let vis_count = visit.vis_count;
        let vis_count_calculated = visit.vis_count_calculated;
        let vis_comments = visit.vis_comments;

        return { vis_id, table_parkCode, table_deviceCode, vis_timestamp, vis_count, vis_count_calculated,
            vis_comments, vis_par_id, vis_dev_id, vis_raw_timestamp};
    }

    // Visitation Table

    // Columns Labels for the Visitation Table
    const visitTableColumns= [
        //         vis_id,
        //         table_parkCode,
        //         vis_timestamp,
        //         rowCount,
        //         vis_count_calculated,
        //         vis_comments
        { id: 'vis_id', label: 'Visit ID', minWidth: 170 },
        { id: 'table_parkCode', label: 'Park', minWidth: 170 },
        { id: 'table_deviceCode', label: 'Park', minWidth: 170 },
        { id: 'vis_timestamp', label: 'Timestamp', minWidth: 170 },
        { id: 'vis_count', label: 'Raw Count', minWidth: 170 },
        { id: 'vis_count_calculated', label: 'Calculated Count', minWidth: 170 },
        { id: 'vis_comments', label: 'Comments', minWidth: 170 },
    ];


    // Handlers and variables to allow the device table to have multiple pages
    const [page, setPage] = React.useState(0);
    const [rowsPerPage, setRowsPerPage] = React.useState(10);
    const handleChangePage = (event, newPage) => {
        setPage(newPage);
    };
    const handleChangeRowsPerPage = (event) => {
        setRowsPerPage(+event.target.value);
        setPage(0);
    };

    // Function for when a row is selected
    function viewVisit(visit) {
        let back = "/ViewAllDataPage";
        let device = null;
        navigate('/EditDataPage',
            {
                state: {visit, back, device}
            });
    }
    // Function to add a new Visit
    function addVisit() {
        let back = "/ViewAllDataPage";
        let device = null;
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
            let tempDate = new Date(rawData[i].vis_timestamp);
            if(tempDate > startDate && tempDate < endDate){
                if(parkCode !== "All Parks"){
                    if(rawData[i].table_parkCode == parkCode){
                        sortedRowsVisits.push(rowsFromData(rawData[i]));
                    }
                }
                else{
                    sortedRowsVisits.push(rowsFromData(rawData[i]));
                }
            }
        }
        sortedRowsVisits.sort(compareVisitID);
    }
    if ((startDate == null || endDate == null) && parkCode !== "All Parks"){
        sortedRowsVisits = [];
        for(let i = 0; i < rawData.length; i++){
            if(rawData[i].table_parkCode == parkCode){
                sortedRowsVisits.push(rowsFromData(rawData[i]));
            }
        }
        sortedRowsVisits.sort(compareVisitID);
    }

    // This is what happens when someone selects a park
    // FIXME
    const handleChangeSelectParks = (event) => {
        setParkCode(event.target.value);
        let tempParkCode = event.target.value;
        document.getElementById("title").innerHTML = "Viewing: Visitations for " + tempParkCode;

        // update the table
        if (tempParkCode !== "All Parks") {
            console.log("Here")
            sortedRowsVisits = [];

        }
        //     console.log(tempParkCode);
        //     sortedRows = [];
        //     for (let i = 0; i < rows.length; i++){
        //         if (rows[i].parkCode === tempParkCode){
        //             sortedRows.push(rows[i]);
        //         }
        //     }
        // }
        setPage(0);
    };

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

    return (
        <div className='mainLandingPage'>
            <div className='mainHeader'>
                <h2 id="title">
                    Viewing: Visitations for All Parks
                </h2>
            </div>
            <Button onClick={() => {addVisit()}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Add Visitation Data</Button>

            <br></br><br></br>
            <FormControl sx={{ m: 1, minWidth: 120 }}>
                <InputLabel id="parks-code-select-label">All Parks</InputLabel>
                <Select
                    labelId="parks-code-select-label"
                    id="parks-code-select"
                    onChange={handleChangeSelectParks}
                    label="All Parks"
                >

                    {parkCodes.map((parkCode) => (
                        <MenuItem
                            key={parkCode}
                            value={parkCode}
                        >
                            {parkCode}
                        </MenuItem>
                    ))}
                </Select>
            </FormControl>


            <h3>Sort By Dates:</h3>
            <div className='date'>
                <DatePicker
                    selectsRange={true}
                    startDate={startDate}
                    endDate={endDate}
                    onChange={onChangeDateHandler}
                    popperProps={{ strategy: 'fixed' }}
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
                                .slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
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
                    rowsPerPage={rowsPerPage}
                    page={page}
                    onPageChange={handleChangePage}
                    onRowsPerPageChange={handleChangeRowsPerPage}
                />
            </Paper>
        </div>
    );

}
export default ViewAllDataPage;
