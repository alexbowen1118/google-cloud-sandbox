import * as React from 'react';
import { useEffect, useState } from "react";
import { styled, alpha } from '@mui/material/styles';
// import { makeStyles } from '@mui/styles';

import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box';
import Toolbar from '@mui/material/Toolbar';
import InputBase from '@mui/material/InputBase';
import SearchIcon from '@mui/icons-material/Search';
import Switch from '@mui/material/Switch';
import { createTheme, FormControl, InputLabel, MenuItem,OutlinedInput } from "@mui/material";
import { Checkbox, ListItemText} from "@mui/material";
import {Select} from "@mui/material"

import "./SearchBar.css"
import APIClient from '../../../utils/APIClient';
import { ThemeProvider } from '@emotion/react';

import TextField from '@mui/material/TextField';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';

const ITEM_HEIGHT = 48;
const ITEM_PADDING_TOP = 8;
const MenuProps = {
  PaperProps: {
    style: {
      maxHeight: ITEM_HEIGHT * 4.5 + ITEM_PADDING_TOP,
      width: 325,
    },
  },
};

const Search = styled('div')(({ theme }) => ({
  position: 'relative',
  borderRadius: theme.shape.borderRadius,
  backgroundColor: alpha(theme.palette.common.white, 0.15),
  '&:hover': {
    backgroundColor: alpha(theme.palette.common.white, 0.25),
  },
  marginLeft:0,
  width: '100%',
  [theme.breakpoints.up('sm')]: {
    marginLeft: theme.spacing(1),
    width: 'auto',
  },
}));

const SearchIconWrapper = styled('div')(({ theme }) => ({
  padding: theme.spacing(0, 2),
  height: '100%',
  position: 'absolute',
  pointerEvents: 'none',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
}));

const StyledInputBase = styled(InputBase)(({ theme }) => ({
  color: 'inherit',
  '& .MuiInputBase-input': {
    padding: theme.spacing(1, 1, 1, 0),
    // vertical padding + font size from searchIcon
    paddingLeft: `calc(1em + ${theme.spacing(4)})`,
    transition: theme.transitions.create('width'),
    width: '100%',
    [theme.breakpoints.up('sm')]: {
      width: '100ch',
      '&:focus': {
        width: '110ch',
      },
    },
  },
}));

const theme = createTheme({
  palette: {
    primary: {
      main: '#ffffff',
    },
    secondary:{
        main:'#1976d2',
    }
  },
});

export default function SearchBar({searchParams}) {
    const [isSwitched, setIsSwithced] = useState(false);

    const [documentTypes, setDocumentTypes] = useState([]);
    //const [documentTypeDisplay, setDocumentTypeDisplay] = useState('');
    const [selectedDocTypeArray, setSelectedDocTypeArray] = useState([]);

    const [businessUnits, setBusinessUnits] = useState([]);
    //const [businessUnitDisplay, setbusinessUnitDisplay] = useState('');
    const [selectedBusUnitArray, setSelectedBusUnitArray] = useState([]);

    const [tags, setTags] = useState([]);
    const [selectedTagsArray, setSelectedTagsArray] = useState([]);

    const [parkCodes, setParkCodes] = useState([]);
    const [selectedParkCodeArray, setSelectedParkCodeArray] = useState([]);

    const [startDate, setStartDate] = useState(null);
    const [endDate, setEndDate] = useState(null);

    useEffect( ()=>{
      //THESE ARE JUST HARD CODED BUT WE WILL MAKE A API CALL TO BACK END TO FILL ALL THE SETS BELOW.
      //THIS HAPPENS ONCE EVERY TIME THE PAGE IS RELOADED.
      fetchData();
    },[]);


    const fetchData = async () => {
        try{

            await Promise.all([
                APIClient.FilterButton.getTags(),
                APIClient.FilterButton.getParkCodes(),
                APIClient.FilterButton.getDocumentTypes(),
                APIClient.FilterButton.getBusinessUnits(),
            ]).then(([res1, res2, res3, res4]) => {    
                setTags(res1.tags);
                setParkCodes(res2.parks);
                setDocumentTypes(res3.documenttypes);
                setBusinessUnits(res4.businessunits);
                return [res1, res2, res3, res4];
            }).then(res => {
                window.sessionStorage.setItem("tags", JSON.stringify(res[0].tags));
                window.sessionStorage.setItem("parkcodes", JSON.stringify(res[1].parks));
                window.sessionStorage.setItem("documenttypes", JSON.stringify(res[2].documenttypes));
                window.sessionStorage.setItem("businessunits", JSON.stringify(res[3].businessunits));

            });

        } catch (err) {
            console.warn(err);
        }
    }

    /**
    * When the user changes the value of the dropdown, set the state of the documentTypeDisplay to the
    * value of the dropdown.
    * @param event - The event object is a JavaScript event that is sent to an element when an event
    * occurs.
    */
     const handleDocumentTypeChange = (event) => {
    //   setDocumentTypeDisplay(event.target.value);

        const {
            target: { value },
        } = event;
        setSelectedDocTypeArray(
            // On autofill we get a stringified value.
            typeof value === 'string' ? value.split(',') : value,
        );
    };

  /**
    * When the user changes the value of the dropdown, set the state of the businessUnitDisplay to the
    * value of the dropdown.
    * @param event - The event object
    */
    const handleBusinessUnitChange = (event) => {
        // setbusinessUnitDisplay(event.target.value);
        const {
            target: { value },
        } = event;
        setSelectedBusUnitArray(
            // On autofill we get a stringified value.
            typeof value === 'string' ? value.split(',') : value,
        );
    };


    /**
     * If the value is a string, split it into an array, otherwise just use the value.
     * @param event - The event object.
     */
    const handleTagsChange = (event) => {
        const {
          target: { value },
        } = event;
        setSelectedTagsArray(
          // On autofill we get a stringified value.
          typeof value === 'string' ? value.split(',') : value,
        );
    };

    /**
     * When the user selects a park code, the function will take the value of the park code and set it
     * to the state of the selectedParkCodeArray.
     * @param event - The event object.
     */
    const handleParkCodeChange = (event) => {
        const {
          target: { value },
        } = event;
        setSelectedParkCodeArray(
          // On autofill we get a stringified value.
          typeof value === 'string' ? value.split(',') : value,
        );       
    };

    const handleSwitch=()=>{
        if (isSwitched) {
            setIsSwithced(false);
        }else {
            setIsSwithced(true);
        }
    }

    const executeSearch = (keyword) =>{
        let finalStartDate = "";
        let finalEndDate = "";
        let makeObj = false;

        //Valid date checking
        if ( startDate == null && endDate == null) {
            finalStartDate = "";
            finalEndDate = "";
            makeObj = true;
        } else if ( (startDate == null && endDate != null) || (startDate != null && endDate == null)  ) {
            window.alert("Please enter both Start and End dates");
        }else if(endDate < startDate){
            window.alert("Please enter correct Start and End dates");
        } else {
            // finalEndDate = new Date(endDate).toLocaleDateString();
            // finalStartDate = new Date(startDate).toLocaleDateString();
            finalEndDate = new Date(endDate).toISOString();
            finalStartDate = new Date(startDate).toISOString();
            makeObj = true;
        }


        if ( makeObj ) {
            var binaryArch = isSwitched ? 1 : 0;
            const searchParmObj = {
                "keyword": keyword,
                "docTypes[]": selectedDocTypeArray,
                "busUnits[]": selectedBusUnitArray,
                "parks[]": selectedParkCodeArray,
                "tags[]": selectedTagsArray,
                "startDate": finalStartDate ,
                "endDate": finalEndDate,
                "archived": binaryArch
            }
            searchParams(searchParmObj);
            makeObj = false;
        }
    }

    return (
        <Box sx={{ flexGrow: 1 }}>
        <AppBar position="static">
            <Toolbar>
            <Search
                onKeyPress={(event) => {
                    if (event.key === "Enter") {
                        executeSearch(event.target.value)
                    }
                }}
            >
                <SearchIconWrapper>
                <SearchIcon
                    onClick={(event) => {
                        executeSearch(event.target.value)  
                    }}
                />
                </SearchIconWrapper>
                <StyledInputBase
                    placeholder="Search…"
                    inputProps={{ 'aria-label': 'search' }}
                />
            </Search>
            <Switch color="warning" onClick={handleSwitch}/>
                <span>Archived</span>

            </Toolbar>



            <Toolbar>
                {/* FILTER INPUTS */}

            {/* <div className="FilterSelectContainer"> */}
                    {/* Document Type Select */}
                    <ThemeProvider theme={theme}>
                    <FormControl sx={{m: 1, minWidth: 160 }}>
                    <InputLabel id="search-select-inputLabel-id">Document Type</InputLabel>


                    <Select
                        labelId="search-doctype-multiple-checkbox-label"
                        id="search-select-document-type"
                        multiple
                        autoWidth
                        value={selectedDocTypeArray}
                        name="Document Types"
                        onChange={handleDocumentTypeChange}
                        input={<OutlinedInput label="Document Types" />}
                        renderValue={(selected) => 
                            selected.map(targetId => documentTypes.filter(currObj => currObj.id === targetId)[0].title).join(", ")}
                        MenuProps={MenuProps}
                    >
                        {documentTypes?.map((name) => 
                        <MenuItem key={name.id} value={name.id}>
                            <Checkbox color='secondary' checked={selectedDocTypeArray.indexOf(name.id) > -1} />
                            <ListItemText primary={name.title} />
                        </MenuItem>
                        )}
                    </Select>
                    </FormControl>
                    
                    {/* Business Unit Select */}
                    <FormControl sx={{m: 1,  minWidth: 160 }}>
                    <InputLabel id="search-select-inputLabel-id" color='warning'>Business Unit</InputLabel>

                    <Select
                        labelId="search-busunits-multiple-checkbox-label"
                        id="search-select-business-unit"
                        multiple
                        autoWidth
                        value={selectedBusUnitArray}
                        name="Business Units"
                        onChange={handleBusinessUnitChange}
                        input={<OutlinedInput label="Business Units" />}
                        renderValue={(selected) => 
                            selected.map(targetId => businessUnits.filter(currObj => currObj.id === targetId)[0].title).join(", ")}
                        MenuProps={MenuProps}
                    >
                        {businessUnits?.map((name) => 
                        <MenuItem key={name.id} value={name.id}>
                            <Checkbox color='secondary' checked={selectedBusUnitArray.indexOf(name.id) > -1} />
                            <ListItemText primary={name.title} />
                        </MenuItem>
                        )}
                    </Select>
                    </FormControl>

                    {/* Tags Select */}

                    <FormControl sx={{ m: 1, minWidth: 160 }}>
                    <InputLabel id="search-tags-multiple-checkbox-label">Tags</InputLabel>
                    <Select
                        labelId="search-tags-multiple-checkbox-label"
                        id="search-tags-multiple-checkbox"
                        multiple
                        autoWidth
                        value={selectedTagsArray}
                        name="Tags"
                        onChange={handleTagsChange}
                        input={<OutlinedInput label="Tag" />}
                        renderValue={(selected) => 
                            selected.map(targetId => tags.filter(currObj => currObj.id === targetId)[0].title).join(", ")}
                        MenuProps={MenuProps}
                    >
                        {tags?.map((name) => 
                        <MenuItem key={name.id} value={name.id}>
                            <Checkbox color='secondary' checked={selectedTagsArray.indexOf(name.id) > -1} />
                            <ListItemText primary={name.title} />
                        </MenuItem>
                        )} 
                    </Select>
                    </FormControl>

                    {/* Park Codes Select */}

                    <FormControl sx={{ m: 1, minWidth: 160 }}>
                    <InputLabel id="search-parkcodes-multiple-checkbox-label">Park Codes</InputLabel>
                    <Select
                        labelId="search-parkcodes-multiple-checkbox-label"
                        id="search-parkcodes-multiple-checkbox"
                        multiple
                        autoWidth
                        value={selectedParkCodeArray}
                        name="Park Codes"
                        onChange={handleParkCodeChange}
                        input={<OutlinedInput label="Park Codes" />}
                        renderValue={(selected) => 
                            selected.map(targetId => parkCodes.filter(currObj => currObj.id === targetId)[0].code).join(", ")}
                        MenuProps={MenuProps}
                    >
                        {parkCodes?.map((code) => 
                        <MenuItem key={code.id} value={code.id}>
                        <Checkbox color='secondary' checked={selectedParkCodeArray.indexOf(code.id) > -1} />
                        <ListItemText primary={code.code} />
                        </MenuItem>
                        )} 
                    </Select>
                    </FormControl>

                    <FormControl sx={{ m: 1, minWidth: 160 }}>
                        <LocalizationProvider dateAdapter={AdapterDayjs}>
                            <DatePicker
                                label="Start Date"
                                disableFuture
                                value={startDate}
                                onChange={(newDate) => {
                                setStartDate(newDate);
                                }}
                                renderInput={(params) => <TextField 
                                    id="search-date-input"
                                    sx={{
                                        width: 160,
                                        input: { color: 'white' },
                                    }} {...params} />}
                            />
                        </LocalizationProvider>
                    </FormControl>
                    
                    <FormControl sx={{ m: 1, minWidth: 160 }}>
                        <LocalizationProvider dateAdapter={AdapterDayjs}>
                            <DatePicker
                                label="End Date"
                                disableFuture
                                minDate={startDate}
                                value={endDate}
                                onChange={(newDate) => {
                                setEndDate(newDate);
                                }}
                                renderInput={(params) => <TextField 
                                    id="search-date-input"
                                    sx={{
                                        width: 160,
                                        input: { color: 'white' },
                                    }} {...params} />}
                            />
                        </LocalizationProvider>
                    </FormControl>
                </ThemeProvider>    
                {/* </div> */}
            </Toolbar>
        </AppBar>
        </Box>
    );
}
