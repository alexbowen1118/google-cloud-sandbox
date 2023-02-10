import React, {useEffect, useState} from 'react';
import {Navigate, useNavigate} from 'react-router-dom';
import {FormControl, InputLabel, MenuItem, Select, useTheme} from '@mui/material';
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
import { tokens } from "../../theme.js";


function ViewDevicesPage() {
    let hidden = true;
    const navigate = useNavigate();
    const theme = useTheme();
    const colors = tokens(theme.palette.mode);
    const parkCodes = ["All Parks"];
    const parkMap = new Map();
    const functionMap = new Map();
    const typeMap = new Map();
    const methodMap = new Map();
    const brandMap = new Map();

    const [parks,setParks]=useState([])
    const [devices,setDevices]=useState([])
    const [functions,setFunctions]=useState([])
    const [types,setTypes]=useState([])
    const [methods,setMethods]=useState([])
    const [brands,setBrands]=useState([])
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
        APIClient.Functions.getFunctions().then(
            functions => {
                setFunctions(functions)}).catch(error => {
                console.log("Error in getting all functions", error)
            }
        );
        APIClient.Types.getTypes().then(
            types => {
                setTypes(types)}).catch(error => {
                console.log("Error in getting all types", error)
            }
        );
        APIClient.Methods.getMethods().then(
            methods => {
                setMethods(methods)}).catch(error => {
                console.log("Error in getting all methods", error)
            }
        );
        APIClient.Brands.getBrands().then(
            brands => {
                setBrands(brands)}).catch(error => {
                console.log("Error in getting all brands", error)
            }
        );

    }
    // Handling the calls
    // Put all park codes into an array to use in the dropdown
    // Create map with key:value of park.id:park.name
    for (let i = 0; i < parks.length; i++){
        parkCodes.push(parks[i].name);
        parkMap.set(parks[i].id, parks[i].name);
    }
    // Create map with k:v of function.id:function.name
    for (let i = 0; i < functions.length; i++){
        functionMap.set(functions[i].id, functions[i].name);
    }
    // Create map with k:v of type.id:type.name
    for (let i = 0; i < types.length; i++){
        typeMap.set(types[i].id, types[i].name);
    }
    // Create map with k:v of method.id:method.name
    for (let i = 0; i < methods.length; i++){
        methodMap.set(methods[i].id, methods[i].name);
    }
    // Create map with k:v of brand.id:brand.name
    for (let i = 0; i < brands.length; i++){
        brandMap.set(brands[i].id, brands[i].name);
    }

    // Creates the data from a device object
    function createDeviceData(device) {
        let brand = device.brand;
        let date_uploaded = device.date_uploaded;
        let dev_function = device.function;
        let id = device.id;
        let lat = device.lat;
        let lon = device.lon;
        let method = device.method;
        let model = device.model;
        let multiplier = device.multiplier;
        let name = device.name;
        let number = device.number;
        let par_id = device.par_id;
        let seeinsight_id = device.seeinsight_id;
        let status = device.status;
        let type = device.type;


        // Variables used by table
        let table_parkCode = parkMap.get(device.par_id);
        let table_deviceName = device.name;
        let table_deviceFunction = functionMap.get(device.function);
        let table_deviceType = typeMap.get(device.type);
        let table_method = methodMap.get(device.method);
        let table_counterBrand = brandMap.get(device.brand);
        let table_multiplier = device.multiplier;
        let table_dateUpdated = device.date_uploaded;
        return { brand, date_uploaded, dev_function, id, lat, lon, method, model, multiplier, name, number, par_id,
                seeinsight_id, status, type, table_parkCode, table_deviceName, table_deviceFunction, table_deviceType,
                table_method, table_counterBrand, table_multiplier, table_dateUpdated}
    }
    const rows = [];
    for(let i = 0; i < devices.length; i++){
        rows.push(createDeviceData(devices[i]));
    }

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

    // Columns Labels for the Device Table
    const deviceTableColumns= [
        { id: 'table_parkCode', label: 'Park Code', minWidth: 170 },
        { id: 'table_deviceName', label: 'Device Name', minWidth: 170 },
        { id: 'table_deviceFunction', label: 'Function', minWidth: 170 },
        { id: 'table_deviceType', label: 'Type', minWidth: 170 },
        { id: 'table_method', label: 'Method', minWidth: 170 },
        { id: 'table_counterBrand', label: 'Counter Brand', minWidth: 170 },
        { id: 'table_multiplier', label: 'Multiplier', minWidth: 170 },
        { id: 'table_dateUpdated', label: 'Date Updated', minWidth: 170 },
    ];

    // Setters for the parkCode to allow for the device table to sort based off of the park selected
    let sortedRows;
    const [parkCode, setParkCode] = React.useState('All Parks');

    // Code to actually sort the rows based off the park
    if(parkCode === "All Parks"){
        sortedRows = rows;
        sortedRows.sort(compareVisitID);
    }
    else{
        sortedRows = [];
        for (let i = 0; i < rows.length; i++){
            if (rows[i].table_parkCode === parkCode){
                sortedRows.push(rows[i]);
            }
        }
        sortedRows.sort(compareVisitID);
    }

    // function used to sort table by the park code, then by device name
    function compareVisitID(a, b){
        if(a.table_parkCode < b.table_parkCode){
            return -1;
        }
        if(a.table_parkCode > b.table_parkCode){
            return 1;
        }
        else{
            if(a.table_deviceName < b.table_deviceName){
                return -1;
            }
            if(a.table_deviceName > b.table_deviceName){
                return 1;
            }
            return 0;
        }
    }
    sortedRows.sort(compareVisitID);

    // This is what happens when someone selects a park
    const handleChangeSelectParks = (event) => {
        setParkCode(event.target.value);
        let tempParkCode = event.target.value;
        document.getElementById("title").innerHTML = "Viewing: " + tempParkCode;

        // update the table
        if (tempParkCode !== "All Parks") {
            sortedRows = [];
            for (let i = 0; i < rows.length; i++){
                if (rows[i].table_parkCode === tempParkCode){
                    sortedRows.push(rows[i]);
                }
            }
        }
        setPage(0);
    };

    // Function for when a row is selected
    function viewDevice(device) {
        let back = "/ViewDevicesPage";
        navigate('/ViewDevicePage',
            {
                state: {device, back}
            });
    }

    // Hides elements because of permissions
    // Permissions:
    // 0	NoAccess
    // 1	BaseLevel
    // 2	Manager
    // 3	Admin
    // 4	SuperAdmin
    let role = sessionStorage.getItem("role")
    if(role == "Manager" || role == "Admin" || role == "SuperAdmin" ){
        hidden = false;
    }

    return (
        <div className = 'mainLandingPage'>
            <div className='mainHeader'>
                <h2 id="title">
                    Viewing: All Parks
                </h2>
            </div>
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
            {!hidden ? <Button onClick={() => {navigate('/AddDevicePage');}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Add Device</Button> : null }

            {/*Devices Table*/}
            <Paper sx={{ width: '100%', overflow: 'hidden' }}>
                <TableContainer sx={{ maxHeight: 440 }}>
                    <Table stickyHeader aria-label="sticky table">
                        <TableHead>
                            <TableRow>
                                {deviceTableColumns.map((deviceTableColumn) => (
                                    <TableCell
                                        key={deviceTableColumn.id}
                                        align={deviceTableColumn.align}
                                        style={{ minWidth: deviceTableColumn.minWidth }}
                                    >
                                        {deviceTableColumn.label}
                                    </TableCell>
                                ))}
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {sortedRows
                                .slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
                                .map((row) => {
                                    return (
                                        <TableRow hover role="checkbox" tabIndex={-1} key={row.code} onClick={() => viewDevice(row)}>
                                            {deviceTableColumns.map((deviceTableColumn) => {
                                                const value = row[deviceTableColumn.id];
                                                return (
                                                    <TableCell key={deviceTableColumn.id} align={deviceTableColumn.align}>
                                                        {deviceTableColumn.format && typeof value === 'number'
                                                            ? deviceTableColumn.format(value)
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
                    rowsPerPageOptions={[10, 25, 100]}
                    component="div"
                    count={sortedRows.length}
                    rowsPerPage={rowsPerPage}
                    page={page}
                    onPageChange={handleChangePage}
                    onRowsPerPageChange={handleChangeRowsPerPage}
                />
            </Paper>
        </div>
    );

}
export default ViewDevicesPage;
