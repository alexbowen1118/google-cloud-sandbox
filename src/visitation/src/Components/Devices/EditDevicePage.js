import React, {useEffect, useState} from 'react';
import {Link, useHref, useLocation, useNavigate} from 'react-router-dom';
import IterationOneTestPage from "../IterationOneTestPage/IterationOneTestPage";
import {Autocomplete, Box, FormControl, Grid, InputLabel, MenuItem, NativeSelect, Select} from '@mui/material';
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


function handleLat() {

}

function EditDevicePage(props) {

    const navigate = useNavigate();

    // Ensures that you have a device selected to look at (or returns you to ViewDevicesPage)
    const { state } = useLocation();
    if (state == null){
        // FIXME: Change this
        // window.location = "ViewDevicesPage";
    }
    const device = state.device;

    console.log(device)

    const [parks,setParks]=useState([])
    const [funcs,setFuncs]=useState([])
    const [types,setTypes]=useState([])
    const [methods,setMethods]=useState([])
    const [models,setModels]=useState([])
    const [brands,setBrands]=useState([])
    const [dev_name]=React.useState('');
    const [dev_number]=React.useState('');
    const [dev_seeinsight_id]=React.useState('');
    const [dev_multiplier]=React.useState('');
    const [dev_function]=React.useState('');
    const [dev_method]=React.useState('');
    const [dev_model]=React.useState('');
    const [dev_type]=React.useState('');
    const [dev_par_id]=React.useState('');
    const [dev_lat]=React.useState('');
    const [dev_lon]=React.useState('');
    const [dev_brand]=React.useState('');
    const [dev_date_uploaded]=React.useState('');
    const parkId = device.par_id;
    const funcId = device.dev_function
    const methodId = device.method;
    const modelId = device.model;
    const typeId = device.type;
    const brandId = device.brand;




    useEffect(() => {
        refresh();
    }, [])

    function refresh() {
        APIClient.Parks.getParks().then(
            parks => {
                setParks(parks)}).catch(error => {
                console.log("error", error)
            }
        );

        APIClient.Functions.getFunctions().then(
            funcs => {
                setFuncs(funcs)}).catch(error => {
                console.log("error", error)
            }
        );

        APIClient.Models.getModels().then(
            models => {
                setModels(models)}).catch(error => {
                console.log("error", error)
            }
        );

        APIClient.Methods.getMethods().then(
            methods => {
                setMethods(methods)}).catch(error => {
                console.log("error", error)
            }
        );

        APIClient.Types.getTypes().then(
            types => {
                setTypes(types)}).catch(error => {
                console.log("error", error)
            }
        );

        APIClient.Brands.getBrands().then(
            brands => {
                setBrands(brands)}).catch(error => {
                console.log("error", error)
            }
        );
    }

    const [dev] = useState(props.dev ? {...props.dev} : {
        dev_name,
        dev_par_id,
        dev_number,
        dev_function,
        dev_type,
        dev_method,
        dev_model,
        dev_brand,
        dev_multiplier,
        dev_lat,
        dev_lon,
        dev_seeinsight_id,
        dev_date_uploaded
    });


    const handleFunc = (event) => {
        dev.dev_function = event.target.value
    };

    const handleModel = (event) => {
        device.dev_model = event.target.value
    };

    const handleMethod = (event) => {
        dev.dev_method = event.target.value
    };

    const handleSeeInsights = (event) => {
        dev.dev_seeinsight_id = event.target.value
    };

    const handleType = (event) => {
        dev.dev_type = event.target.value
    };

    const handleName = (event) => {
        dev.dev_name = event.target.value
    };

    const handleNumber = (event) => {
        dev.dev_number = event.target.value
    };

    const handleMultiplier = (event) => {
        dev.dev_multiplier = event.target.value
    };

    const handleLat = (event) => {
        dev.dev_lat = event.target.value
    };

    const handleLon = (event) => {
        dev.dev_lon = event.target.value
    };

    const handleBrand = (event) => {
        dev.dev_brand = event.target.value
    };

    let handlePark = (event) => {
        dev.dev_par_id = event.target.value
    }

    const [currentDate, setCurrentDate] = useState('');

    useEffect(() => {
        const date = new Date().getDate(); //Current Date
        const month = new Date().getMonth() + 1; //Current Month
        const year = new Date().getFullYear(); //Current Year
        const hours = new Date().getHours(); //Current Hours
        const min = new Date().getMinutes(); //Current Minutes
        const sec = new Date().getSeconds(); //Current Seconds
        setCurrentDate(
            year + '-' + month + '-' + date + ' ' + hours + ':' + min + ':' + sec
        );

    }, []);

    function setDate() {
        dev.dev_date_uploaded = device.date_uploaded
    }


    function submitDevice() {
        setDate()
        if (dev.dev_name === '') {
            dev.dev_name = device.name
        }
        if (dev.dev_number === '') {
            dev.dev_number = device.number
        }
        if (dev.dev_seeinsight_id === '') {
            dev.dev_seeinsight_id = device.seeinsight_id
        }
        if (dev.dev_multiplier === '') {
            dev.dev_multiplier = device.multiplier
        }
        if (dev.dev_par_id === '') {
            dev.dev_par_id = device.par_id
        }
        if (dev.dev_function === '') {
            dev.dev_function = device.dev_function
        }
        if (dev.dev_type === '') {
            dev.dev_type = device.type
        }
        if (dev.dev_method === '') {
            dev.dev_method = device.method
        }
        if (dev.dev_model === '') {
            dev.dev_model = device.model
        }
        if (dev.dev_brand === '') {
            dev.dev_brand = device.brand
        }
        if (dev.dev_lat === '') {
            dev.dev_lat = device.lat
        }
        if (dev.dev_lon === '') {
            dev.dev_lon = device.lon
        }
        APIClient.Devices.updateDevice(device.id, dev).then(r => console.log("success"));
    }

    function goBack() {
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
    if(role !== "Manager" && role !== "Admin" && role !== "SuperAdmin" ){
        goBack();
        return null;
    }

    return (
        <div className='editDevicePage'>
            <div className="pageHeader">
                <h2 > Edit {device.table_deviceName}</h2>
            </div>
            <Button onClick={() => {goBack()}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Back</Button>
            <p> </p>
            <TextField required id="devname" label="Enter Device Name" onChange={handleName} defaultValue={device.name} />
            <p> </p>
            <TextField required id="devnum" label="Enter Device Number" onChange={handleNumber} defaultValue={device.number}/>
            <p> </p>
            <TextField required id="devseeid" label="Enter SeeInsights Id" onChange={handleSeeInsights} defaultValue={device.seeinsight_id}/>
            <p> </p>
            <TextField required id="devmultiplier" label="Enter Multiplier" onChange={handleMultiplier} defaultValue={device.multiplier} />
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
            <FormControl sx={{ minWidth: 300 }}>
                <Select onChange={handleFunc} defaultValue={funcId}>
                    <option value="Select a Function"> -- Select a Function -- </option>
                    {funcs?.map(func => {
                        return (
                            <MenuItem key={func.id} value={func.id}>
                                {func.name}
                            </MenuItem>
                        );
                    })}
                </Select>
            </FormControl >
            <FormControl sx={{ minWidth: 300 }}>
                <Select onChange={handleType} defaultValue={typeId}>
                    <option value="Select a Type"> -- Select a Type -- </option>
                    {types?.map(type => {
                        return (
                            <MenuItem key={type.id} value={type.id}>
                                {type.name}
                            </MenuItem>
                        );
                    })}
                </Select>
            </FormControl >
            <FormControl sx={{ minWidth: 300 }}>
                <Select onChange={handleMethod} defaultValue={methodId}>
                    <option value="Select a Method"> -- Select a Method -- </option>
                    {methods?.map(method => {
                        return (
                            <MenuItem key={method.id} value={method.id}>
                                {method.name}
                            </MenuItem>
                        );
                    })}
                </Select>
            </FormControl >
            <FormControl sx={{ minWidth: 300 }}>
                <Select onChange={handleModel} defaultValue={modelId}>
                    <option value="Select a Model"> -- Select a Model -- </option>
                    {models?.map(model => {
                        return (
                            <MenuItem key={model.id} value={model.id}>
                                {model.name}
                            </MenuItem>
                        );
                    })}
                </Select>
            </FormControl >
            <FormControl sx={{ minWidth: 300 }}>
                <Select onChange={handleBrand} defaultValue={brandId}>
                    <option value="Select a Brand"> -- Select a Brand -- </option>
                    {brands?.map(brand => {
                        return (
                            <MenuItem key={brand.id} value={brand.id}>
                                {brand.name}
                            </MenuItem>
                        );
                    })}
                </Select>
            </FormControl >
            <p> </p>
            <TextField id="latitude" label="Enter Latitude" onChange={handleLat} defaultValue={device.lat} />
            <p> </p>
            <TextField id="longitude" label="Enter Longitude" onChange={handleLon} defaultValue={device.lon}/>
            <p> </p>
            <Button type="submit" variant="outlined" onClick={submitDevice} style={{ borderColor: '#000000',  color:'#000000' }}>Save</Button>

        </div>
    );

}
export default EditDevicePage;
