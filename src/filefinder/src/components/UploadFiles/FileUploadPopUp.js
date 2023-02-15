import React, { useEffect, useState } from "react";
import "./FileUploadPopUp.css"
import { Button, FormControl, InputLabel, MenuItem,OutlinedInput } from "@mui/material";
import {FormHelperText, Checkbox, ListItemText} from "@mui/material";
import FileDrop from "./FileDrop/FileDrop";
import {Select} from "@mui/material"
import APIClient from '../../utils/APIClient'
import CircularProgress from '@mui/material/CircularProgress';



const ITEM_HEIGHT = 48;
const ITEM_PADDING_TOP = 8;
const MenuProps = {
  PaperProps: {
    style: {
      maxHeight: ITEM_HEIGHT * 4.5 + ITEM_PADDING_TOP,
      width: 250,
    },
  },
};


/* A function that takes in a parameter called setOpenPopUp. */
function FileUploadPopUp({closePopUp, parentCallback}) {
    
    const [uploadedDoc, setuploadedDoc] = useState([]);
    
    const [documentTypes, setDocumentTypes] = useState([]);
    const [documentTypeDisplay, setDocumentTypeDisplay] = useState('');

    const [businessUnits, setBusinessUnits] = useState([]);
    const [businessUnitDisplay, setbusinessUnitDisplay] = useState('');

    const [tags, setTags] = useState([]);
    const [selectedTagsArray, setSelectedTagsArray] = useState([]);

    const [parkCodes, setParkCodes] = useState([]);
    const [selectedParkCodeArray, setSelectedParkCodeArray] = useState([]);

    const [loading, setLoading] = useState(false);

    let fileJson = {};
    
    
    useEffect( ()=>{
        //THESE ARE JUST HARD CODED BUT WE WILL MAKE A API CALL TO BACK END TO FILL ALL THE SETS BELOW.
        //THIS HAPPENS ONCE EVERY TIME THE PAGE IS RELOADED.
        fetchData();
    },[]);


    const fetchData = async () => {
        try{

            await Promise.all([
                APIClient.FilterButton.getTagsByStatus(1),
                APIClient.FilterButton.getParkCodes(),
                APIClient.FilterButton.getDocumentTypesByStatus(1),
                APIClient.FilterButton.getBusinessUnitsByStatus(1),
            ]).then(([res1, res2, res3, res4]) => {
                console.log(res1)
                setTags(res1.tags.active);
                console.log(res2)
                setParkCodes(res2.parks);
                setDocumentTypes(res3.documenttypes.active);
                setBusinessUnits(res4.businessunits.active);
                setLoading(true);
            });

        } catch (err) {
            console.warn(err);
        }

    }
    console.log(parkCodes)

   /**
    * When the user changes the value of the dropdown, set the state of the documentTypeDisplay to the
    * value of the dropdown.
    * @param event - The event object is a JavaScript event that is sent to an element when an event
    * occurs.
    */
    const handleDocumentTypeChange = (event) => {
        setDocumentTypeDisplay(event.target.value);
    };

   /**
    * When the user changes the value of the dropdown, set the state of the businessUnitDisplay to the
    * value of the dropdown.
    * @param event - The event object
    */
    const handleBusinessUnitChange = (event) => {
        setbusinessUnitDisplay(event.target.value);
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

    /**
     * When the user uploads a file, the file is saved in the state variable 'uploadedDoc' and the file
     * is displayed in the 'uploadedDoc' div.
     * @param givenFile - The file that was uploaded
     */
    const handleUploadedFile = (givenFile) => {
        setuploadedDoc(givenFile);
    }

    /**
     * If the documentTypeDisplay and businessUnitDisplay are empty, then alert the user. If the
     * uploadedDoc.length is less than or equal to 0, then alert the user.
     */
    const handleSaveFile = () =>{
        // console.log(uploadedDoc.length);
        // console.log("Current selected Doc types: " + documentTypeDisplay);
        // console.log("Current selected Bus Units: " + businessUnitDisplay);
        // console.log("Current selected Tags: " + selectedTagsArray);
        // console.log("Current selected Park Codes: " + selectedParkCodeArray);
        // console.log(uploadedDoc);
        // console.log('SELECTED TAGS' + selectedTagsArray[0].id);
        if ( documentTypeDisplay === '' || businessUnitDisplay === '' || uploadedDoc.length <= 0 ) {
            window.alert("Please select all required field");
        } else {
            fileJson = {
                "filename": uploadedDoc[0].name,
                "business_unit_id": businessUnitDisplay,
                "document_type_id": documentTypeDisplay,
                "tags": selectedTagsArray,
                "parks": selectedParkCodeArray
            }
            parentCallback(fileJson, uploadedDoc[0]);
        }

        // formData.append(uploadedDoc[0].path, uploadedDoc[0]);
        // formData.append("file", uploadedDoc[0]);
        // formData.append("documenttypes", documentTypeDisplay);
        // formData.append("businessunits", businessUnitDisplay);
        // formData.append("tags", selectedTagsArray);
        // formData.append("parkcodes", selectedParkCodeArray);
        

        // console.log(fileJson);
        // console.log(uploadedDoc[0]);
 

    };

    /**
     * It sets the state of the component to an empty string.
     */
    const handleClosePopUp = () => {
        setBusinessUnits('');
        setDocumentTypes('');
        setuploadedDoc([]);
        setParkCodes([]);
        setTags([]);
        closePopUp(false);
    }

    function loadComp() {
        return (
        <div>
            <div className="UploadFilesContainer">
                <h2>Upload File</h2>

                {/* FILTER INPUTS */}

                <div className="FilterSelectContainer">
                    {/* Document Type Select */}
                    <FormControl required sx={{m: 1,  minWidth: 165 }}>
                    <InputLabel id="select-inputLabel-id">Document Type</InputLabel>
                        <Select
                            id="select-document-type"
                            value ={documentTypeDisplay}
                            label= "Document Type"
                            name = "Document Type"
                            onChange={handleDocumentTypeChange}

                        >
                            <MenuItem value="">
                            <em>None</em>
                            </MenuItem>
                            {documentTypes?.map((docType) => <MenuItem key={docType.title} value={docType.id}>{docType.title}</MenuItem>)}
                        </Select>
                        <FormHelperText>Required</FormHelperText>
                    </FormControl>


                    {/* Business Unit Select */}
                    <FormControl required sx={{m: 1,  minWidth: 165 }}>
                    <InputLabel id="select-inputLabel-id">Business Unit</InputLabel>
                        <Select
                            id="select-business-unit"
                            value ={businessUnitDisplay}
                            label= "Business Unit"
                            name= "Business Unit"
                            onChange={handleBusinessUnitChange}
                        >
                            <MenuItem value="">
                            <em>None</em>
                            </MenuItem>
                            {businessUnits.map((busUnit) => <MenuItem key={busUnit.title} value={busUnit.id}>{busUnit.title}</MenuItem>)}
                        </Select>
                        <FormHelperText>Required</FormHelperText>
                    </FormControl>

                    {/* Tags Select */}

                    <FormControl sx={{ m: 1, minWidth: 160 }}>
                    <InputLabel id="tags-multiple-checkbox-label">Tags</InputLabel>
                    <Select
                        labelId="tags-multiple-checkbox-label"
                        id="tags-multiple-checkbox"
                        multiple
                        autoWidth
                        value={selectedTagsArray}
                        name="Tags"
                        onChange={handleTagsChange}
                        input={<OutlinedInput label="Tag" />}
                        renderValue={(selected) => 
                            selected.map(targetId => tags.filter(currObj => currObj.id == targetId)[0].title).join(", ")}
                        MenuProps={MenuProps}
                    >
                        {tags?.map((name) => 
                        <MenuItem key={name.id} value={name.id}>
                            <Checkbox checked={selectedTagsArray.indexOf(name.id) > -1} />
                            <ListItemText primary={name.title} />
                        </MenuItem>
                        )} 
                    </Select>
                    <FormHelperText>Optional</FormHelperText>
                    </FormControl>


                    {/* Park Codes Select */}

                    <FormControl sx={{ m: 1, minWidth: 160 }}>
                    <InputLabel id="parkcodes-multiple-checkbox-label">Park Codes</InputLabel>
                    <Select
                        labelId="parkcodes-multiple-checkbox-label"
                        id="parkcodes-multiple-checkbox"
                        multiple
                        autoWidth
                        value={selectedParkCodeArray}
                        name="Park Codes"
                        onChange={handleParkCodeChange}
                        input={<OutlinedInput label="Park Codes" />}
                        renderValue={(selected) => 
                            selected.map(targetId => parkCodes.filter(currObj => currObj.id == targetId)[0].code).join(", ")}
                        MenuProps={MenuProps}
                    >
                        {parkCodes?.map((code) => 
                        <MenuItem key={code.id} value={code.id}>
                        <Checkbox checked={selectedParkCodeArray.indexOf(code.id) > -1} />
                        <ListItemText primary={code.code} />
                        </MenuItem>
                        )} 
                    </Select>
                    <FormHelperText>Optional</FormHelperText>
                    </FormControl>
                </div>

                {/* FILE UPLOAD */}
                <div className="DragAndDrop">
                    <FormHelperText sx={{ml: 2}}>Required</FormHelperText>
                    <FileDrop id="fileDrop" fileUploaded={handleUploadedFile}/>
                </div>
                
                <div className="buttonContainer">
                    <Button 
                        variant="contained" 
                        component="label"
                        onClick={handleClosePopUp} 
                        
                        sx={{backgroundColor: 'red'}}
                    >Close</Button>

                    <Button 
                        variant="contained" 
                        component="label"
                        onClick={handleSaveFile} 
                        
                        sx={{backgroundColor: 'green'}}
                    >Save</Button>
                </div>

            </div>
        </div>

        )
    }

    return (
        <div>{loading ? loadComp() : <CircularProgress color="success">
                                        <span className="visually-hidden">Loading...</span>
                                    </CircularProgress>}</div>
        
    )
}


export default FileUploadPopUp;