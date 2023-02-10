import React, {useEffect, useState} from "react";
import Button from "@mui/material/Button";
import TextField from "@mui/material/TextField";
import {Box, ButtonGroup, MenuItem, Typography} from "@mui/material";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import APIClient from "../../utils/APIClient";
import {useNavigate} from "react-router-dom";
import {useSearchParams} from "react-router-dom";
import { useLocation } from 'react-router-dom';
import './AddMultiplierRule.css';

function AddMultiplierRule(props) {

    const navigate = useNavigate();
    const { state } = useLocation();

    if (state == null || state.back == null) {
        window.location = "MainLandingPage";
    }
    const device = state.device
    const back = state.back;

    const [showAddForm, setShowAddForm] = useState(true);
    const [showRuleForm, setShowRuleForm] = useState(false);

    const [rul_dev_id, rul_start, rul_end, rul_multiplier]=React.useState('');

    const [counter_rule] = useState(props.counter_rule ? {...props.counter_rule} : {
        rul_dev_id,
        rul_start,
        rul_end,
        rul_multiplier

    });

    const [devs, setDevs]=useState([])
    const [rules, setRules]=useState([])

    const handleMult = (event) => {
        counter_rule.rul_multiplier = event.target.value
    };

    useEffect(() => {
        APIClient.Devices.getDevices().then(
            devs => {
                setDevs(devs)}).catch(error => {
                console.log("error", error)
            }
        );
        APIClient.CounterRules.getCounterRules(state.device.id).then(
            rules => {
                setRules(rules)}).catch(error => {
                console.log("error", error)
            }
        );
    }, [])

    function submitCounterRule() {
        let startpart1 = JSON.stringify(startDate).substring(1, 11)
        let startpart2 = JSON.stringify(startDate).substring(13, 20)
        const finalstart = startpart1 + " " + startpart2
        let endpart1 = JSON.stringify(endDate).substring(1, 11)
        let endpart2 = JSON.stringify(endDate).substring(13, 20)
        const finalend = endpart1 + " " + endpart2

        counter_rule.rul_dev_id = state.device.id
        counter_rule.rul_start = finalstart;
        counter_rule.rul_end = finalend;
        APIClient.CounterRules.createCounterRules(counter_rule.rul_dev_id, counter_rule).then(r => console.log("success"))
        setShowRuleForm(true)
    }


    let [startDate, setStartDate] = useState('');
    const [endDate, setEndDate] = useState('');


    function onChangeDateHandler(value) {
        setStartDate(value[0]);
        setEndDate(value[1]);
    }


    function moveBack() {
        // if (back == "/ViewDevicePage"){
        navigate('/ViewDevicePage',
            {
                state: {device, back}
            });
        // }
        // if (back == "/AddDevicePage"){
        //     navigate('/AddDevicePage');
        // }
    }


    // Hides elements because of permissions
    // Permissions:
    // 0	NoAccess
    // 1	BaseLevel
    // 2	Manager
    // 3	Admin
    // 4	SuperAdmin
    let role = sessionStorage.getItem("role")
    if(role !== "Admin" && role !== "SuperAdmin" ){
        moveBack();
        return null;
    }


    function addOtherRule() {
        window.location.reload();
    }

    return (
        <div className='addMultiplierRulePage'>
            <div className="pageHeader">
                <h2 > Add Multiplier Rule To: {state.device.name}</h2>
            </div>
            <React.Fragment>
                {showAddForm ? (
                    <form >
                        <ButtonGroup variant="outlined" aria-label="outlined button group">
                            <Button onClick={moveBack} style={{ borderColor: '#000000',  color:'#000000' }}>Back</Button>
                        </ButtonGroup>
            <p> </p>
            <b >Multiplier </b>
            <p> </p>
            <TextField required id="multiplier" label="Enter Multiplier" onChange={handleMult} />
            <p> </p>
            <b>Date Range</b>
            <div>
                <DatePicker
                    selectsRange={true}
                    startDate={startDate}
                    endDate={endDate}
                    onChange={onChangeDateHandler}
                    dateFormat="yyyy-MM-dd hh:mm:ss"/>
            </div>
            <p> </p>
                        <div className="btn-toolbar">
                <Button variant="outlined" onClick={submitCounterRule} style={{ borderColor: '#000000',  color:'#000000' }}>Save</Button>
                <Button variant="outlined" onClick={addOtherRule} style={{ borderColor: '#000000',  color:'#000000' }}>Add Another Multiplier Rule</Button>
                        </div>

                    </form>
                ):(
                    <p></p>
                )}
            </React.Fragment>
            {showRuleForm ? (
                <form>
                    <p></p>
                    <Typography variant="h6" gutterBottom>
                        Rule Created:
                    </Typography>

                    <div>
                            <div >
                                <p>Multiplier: {counter_rule.rul_multiplier}</p>
                                <p>Start Date: {counter_rule.rul_start}</p>
                                <p>End Date: {counter_rule.rul_end}</p>
                            </div>
                    </div>
                </form>
            ):(
                <p></p>
            )}
        </div>
    );

}

export default AddMultiplierRule;