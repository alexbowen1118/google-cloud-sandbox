import React, {useEffect, useState} from "react";
import {
    ButtonGroup,
    FormControl,
    MenuItem,
    Select, Typography
} from "@mui/material";
import Button from "@mui/material/Button";
import TextField from "@mui/material/TextField";
import './AddDevicePage.css';
import APIClient from "../../utils/APIClient";
import {useNavigate} from "react-router-dom";
import DatePicker from "react-datepicker";

function AddDevicePage(props) {

    const navigate = useNavigate();
    const [showForm, setShowForm] = useState(true);
    const [showForm2, setShowForm2] = useState(false);
    const [showAddForm, setShowAddForm] = useState(true);
    const [showDisplayForm, setShowDisplayForm] = useState(false);
    const [startdatetime, setStartdatetime] = React.useState('');
    const [enddatetime, setEnddatetime] = React.useState('');
    const [dev_name, dev_number, dev_seeinsight_id, dev_multiplier, dev_lat, dev_lon, dev_brand, dev_date_uploaded, dev_status]=React.useState('');
    const [park,setPark, dev_par_id]=React.useState('');
    const [parks,setParks]=useState([])
    const [func,setFunc, dev_function]=React.useState('');
    const [funcs,setFuncs]=useState([])
    const [model,setModel, dev_model]=React.useState('');
    const [models,setModels]=useState([])
    const [brand,setBrand]=React.useState('');
    const [brands,setBrands]=useState([])
    const [parkname, functionname, modelname, methodname, typename, brandname]=React.useState('');
    const [rul_dev_id, rul_start, rul_end, rul_multiplier]=React.useState('');
    const [method,setMethod, dev_method]=React.useState('');
    const [methods,setMethods]=useState([])
    const [devs, setDevs]=useState([])
    const [type,setType, dev_type]=React.useState('');
    const [types,setTypes]=useState([])
    const [currentDate, setCurrentDate] = useState('');

    const handleStartDate = (newvalue) => {
        setStartdatetime(newvalue);
    };

    const handleEndDate = (newValue) => {
        setEnddatetime(newValue);
    };

    const handleCheck = (event) => {
        setShowForm(!showForm);
        setShowForm2(!showForm2);
    };


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

        APIClient.Devices.getDevices().then(
            devs => {
                setDevs(devs)}).catch(error => {
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


    const [device] = useState(props.device ? {...props.device} : {
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
        dev_status,
        dev_date_uploaded

    });

    const [counter_rule] = useState(props.counter_rule ? {...props.counter_rule} : {
        rul_dev_id,
        rul_start,
        rul_end,
        rul_multiplier

    });

    const [fieldname] = useState(props.fieldname ? {...props.fieldname} : {

        parkname,
        functionname,
        modelname,
        methodname,
        typename,
        brandname

    });

    function submitDevice() {
        setDate()
        setStatus()
        APIClient.Devices.createDevice(device).then(r => console.log("success"));
        setShowAddForm(false)
        setShowDisplayForm(true)
    }

    function setDate() {
        device.dev_date_uploaded = currentDate
    }

    function setStatus() {
        device.dev_status = 1
    }

    let handlePark = (event) => {
        setPark(event.target.value)
        device.dev_par_id = event.target.value
        console.log(JSON.stringify(event.target.value))
        for (let i = 0; i < parks.length; i++){
            if (parks[i].id === parseInt(event.target.value, 10)) {
                fieldname.parkname = parks[i].name;
            }
        }
        console.log(JSON.stringify(fieldname.parkname))
    }

    const handleFunc = (event) => {
        device.dev_function = event.target.value
        for (let i = 0; i < funcs.length; i++){
            if (funcs[i].id === parseInt(event.target.value, 10)) {
                fieldname.functionname = funcs[i].name;
            }
        }
    };

    const handleModel = (event) => {
        device.dev_model = event.target.value
        for (let i = 0; i < models.length; i++){
            if (models[i].id === parseInt(event.target.value, 10)) {
                fieldname.modelname = models[i].name;
            }
        }
    };

    const handleMethod = (event) => {
        device.dev_method = event.target.value
        for (let i = 0; i < methods.length; i++){
            if (methods[i].id === parseInt(event.target.value, 10)) {
                fieldname.methodname = methods[i].name;
            }
        }
    };

    const handleSeeInsights = (event) => {
        device.dev_seeinsight_id = event.target.value
    };

    const handleType = (event) => {
        device.dev_type = event.target.value
        for (let i = 0; i < types.length; i++){
            if (types[i].id === parseInt(event.target.value, 10)) {
                fieldname.typename = types[i].name;
            }
        }
    };

    const handleName = (event) => {
        device.dev_name = event.target.value
    };

    const handleNumber = (event) => {
        device.dev_number = event.target.value
    };

    const handleMultiplier = (event) => {
        device.dev_multiplier = event.target.value
    };

    const handleLat = (event) => {
        device.dev_lat = event.target.value
    };

    const handleLon = (event) => {
        device.dev_lon = event.target.value
    };

    const handleBrand = (event) => {
        device.dev_brand = event.target.value
        for (let i = 0; i < brands.length; i++){
            if (brands[i].id === parseInt(event.target.value, 10)) {
                fieldname.brandname = brands[i].name;
            }
        }
    };

    const switchPage = () => {

        let back = "/AddDevicePage";
        navigate('/AddMultiplierRule',
            {
                state: {device, back}
            });
    };

    // Hides elements because of permissions
    // Permissions:
    // 0	NoAccess
    // 1	BaseLevel
    // 2	Manager
    // 3	Admin
    // 4	SuperAdmin
    let role = sessionStorage.getItem("role")
    if(role !== "Manager" && role !== "Admin" && role !== "SuperAdmin" ){
        navigate('/ViewDevicesPage');
        return null;
    }

    return (
        <div style={{textAlign:'left', marginLeft: "1rem"}} className="addDevicePage">
            <div className="pageHeader">
                <h2 style={{textAlign:'left' }}
                > Add New Device</h2>
            </div>
            <Button onClick={() => {navigate('/ViewDevicesPage'); }} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Back</Button>
            <React.Fragment>
                {showAddForm ? (
                    <form >
            <div className="pageBody">
                <p> </p>
                <b>Device Name</b>
                <p> </p>
                <TextField required id="filled-basic" label="Enter Device Name" onChange={handleName} />
                <p> </p>
                <b>Device Number</b>
                <p> </p>
                <TextField required id="filled-basic" label="Enter Device Number" onChange={handleNumber}/>
                <p> </p>
                <b>SeeInsights Id</b>
                <p> </p>
                <TextField required id="filled-basic" label="Enter SeeInsights Id" onChange={handleSeeInsights}/>
                <p> </p>
                <b >Multiplier </b>
                <p> </p>
                <TextField required id="multiplier" label="Enter Multiplier" onChange={handleMultiplier} />
                <p> </p>
                <b>Park Code</b>
                <p> </p>
                <FormControl sx={{ minWidth: 300 }}>
                    <Select onChange={handlePark} >
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
                <b >Function</b>
                <p> </p>
                    <FormControl sx={{ minWidth: 300 }}>
                        <Select onChange={handleFunc}>
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
                <p> </p>
                <b >Type</b>
                <p> </p>
                <FormControl sx={{ minWidth: 300 }}>
                    <Select onChange={handleType}>
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
                <p> </p>
                <b >Method</b>
                <p> </p>
                <FormControl sx={{ minWidth: 300 }}>
                    <Select onChange={handleMethod}>
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
                <p> </p>
                <b >Model</b>
                <p> </p>
                <FormControl sx={{ minWidth: 300 }}>
                    <Select onChange={handleModel}>
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
                <p> </p>
                <b>Brand</b>
                <p></p>
                <FormControl sx={{ minWidth: 300 }}>
                    <Select onChange={handleBrand}>
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
                <p></p>
                <b>Latitude</b>
                <p></p>
                <TextField id="latitude" label="Enter Latitude" onChange={handleLat} />
                <p></p>
                <b>Longitude</b>
                <p></p>
                <TextField id="longitude" label="Enter Longitude" onChange={handleLon} />
                <p></p>
                <Button type="submit" variant="outlined" onClick={submitDevice} style={{ borderColor: '#000000',  color:'#000000' }}>Save</Button>
            </div>
                    </form>
                    ):(
                    <p></p>
                    )}
            </React.Fragment>
            {showDisplayForm ? (
                <form>
                    <Typography variant="h6" gutterBottom>
                        Device Created:
                    </Typography>
                    <Typography variant="body1" gutterBottom>
                        Device Name: {JSON.stringify(device.dev_name)}
                        <br></br>
                        Device Number: {JSON.stringify(device.dev_number)}
                        <br></br>
                        Device SeeInsight Id: {JSON.stringify(device.dev_seeinsight_id)}
                        <br></br>
                        Device Multiplier: {JSON.stringify(device.dev_multiplier)}
                        <br></br>
                        Device Park: {JSON.stringify(fieldname.parkname)}
                        <br></br>
                        Device Function: {JSON.stringify(fieldname.functionname)}
                        <br></br>
                        Device Type: {JSON.stringify(fieldname.typename)}
                        <br></br>
                        Device Method: {JSON.stringify(fieldname.methodname)}
                        <br></br>
                        Device Model: {JSON.stringify(fieldname.modelname)}
                        <br></br>
                        Device Brand: {JSON.stringify(fieldname.brandname)}
                        <br></br>
                        Device Latitude: {JSON.stringify(device.dev_lat)}
                        <br></br>
                        Device Longitude: {JSON.stringify(device.dev_lon)}
                    </Typography>
                    <Button variant="outlined" onClick={switchPage} style={{ borderColor: '#000000',  color:'#000000' }}>Add Multiplier Rule</Button>
                </form>
            ):(
                <p></p>
            )}
        </div>

    );
}
export default AddDevicePage;

