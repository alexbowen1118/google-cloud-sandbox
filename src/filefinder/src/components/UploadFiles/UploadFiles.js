import React, { useState } from "react";
import {Button, Modal} from "@mui/material"
import "./UploadFiles.css";
import { TextField, Box } from "@mui/material";
import FileUploadPopUp from "./FileUploadPopUp";
import APIClient from '../../utils/APIClient'


const style = {
    position: 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: window.width,
    bgcolor: 'background.paper',
    border: '2px solid #000',
    boxShadow: 24,
    p: 4,
  };

function UploadFiles() {
    const [topicTitle, setTopicTitle] = useState('');
    const [descriptionText, setDescriptionText] = useState('');
    const [openPopUp, setOpenPopUp] = useState(false);
    const [fileDataArray, setFileDataArray] = useState([]);
    const [fileObjArray, setFileObjArray] = useState([]);

    const currentUserId = window.sessionStorage.getItem("currentUserId");
    const currentUserRole = window.sessionStorage.getItem("currentUserRole");

    let printFileNames = [];
    
    // THIS LENGTH IS USED TO SET MAXIMUM NUMBER OF FILE UPLOADS
    // MUST BE SET TO ENV VARIABLE
    // fileDataArray.length = 5;

    /**
     * When the user types in the topic title input field, the value of the input field is set to the
     * topic title state variable.
     * @param event - The event that triggered the function
     */
    const handleTopicTitleChange = (event) => {
        setTopicTitle(event.target.value);
    }

    /**
     * When the user types in the textarea, the value of the textarea is set to the state variable
     * descriptionText.
     * @param event - The event object is a JavaScript event that is sent to an element when an event
     * occurs.
     */
    const handleDescriptionTextChange = (event) => {
        setDescriptionText(event.target.value);
        // console.log(descriptionText);
    }

    /**
     * When the user clicks on the button, the pop up will open.
     */
    const handleOpenPopUp = () => {
        setOpenPopUp(true);
    }

    /**
     * When the user clicks the close button, the pop up will close.
     */
    const handleClosePopUp = (data) => {
        setOpenPopUp(data);

    }

    const handleFormData = (fileData, fileObj) => {
        setOpenPopUp(false);
        // console.log("Form Data: " + formData.get("file").path);
        // console.log("Form Data: " + formData.get("documenttypes"));
        printFileNames.push(fileData);
        setFileDataArray((prevfileData)=> [...prevfileData, fileData]);
        setFileObjArray((prevFileObj)=> [...prevFileObj, fileObj]);
        // console.log(fileDataArray);
        // console.log(fileObjArray);
        addFileToList();
    }

    const addFileToList = () => {
        
        let list = document.getElementById("myList");
        printFileNames.forEach(element => {
            let li = document.createElement("li");
            li.innerText = element.filename;
            list.appendChild(li);
        })

    }

    const handleUploadFiles = () => {

        if (document.getElementById("topic-title-textfield").value.length > 0 ) {
            let formdata = new FormData();

            let metadata = {
                "topic": {
                    "title": topicTitle,
                    "description": descriptionText
                },
                "uploader_id": parseInt(currentUserId),
                "files": fileDataArray
            };
            console.log(metadata);

            formdata.append("metadata", JSON.stringify(metadata));

            fileObjArray.forEach(element => {
                formdata.append(element.name, element, element.path);
            });

            // for (const value of formdata.values()) {
            //     console.log(value);
            // }
            // console.log(JSON.stringify(formdata));

            APIClient.UploadFile.uploadFiles(formdata).then(res => {
                if (res) {
                    window.alert("Files have been successfully uploaded! Click OK");
                    window.location.reload(true);
                }
            });
            // window.location.reload(false);

        }else{
            window.alert("Please enter a topic title.");
        }

    }

    return(
        <div>
            <div className="TextFieldContainer">

                <div className="grid-item1">
                    <label>Topic Title*</label>
                    <TextField required 
                        id="topic-title-textfield" 
                        label="Topic Title" 
                        variant="outlined" 
                        sx={{width: 500}}
                        helperText="Required"
                        onChange={handleTopicTitleChange}
                    />    
                </div>


                <div className="grid-item2">
                    <label>Description</label>
                    <TextField
                        id="description-textfield"
                        label="Description"
                        multiline
                        sx={{ml: 1, width: 500}}
                        minRows={8}
                        maxRows={8}
                        onChange={handleDescriptionTextChange}
                    />
                </div>
            </div>

            <div className="addFileSection">
                <Button onClick={handleOpenPopUp} variant="contained" component="label">Add File +</Button>
                <Button onClick={handleUploadFiles} sx={{ml:10, backgroundColor:"green"}} variant="contained" component="label">Upload File(s)</Button>
                <Modal 
                    open = {openPopUp}
                    onClose = {handleClosePopUp}
                    disableEscapeKeyDown 
                    // data-backdrop="static"
                    aria-labelledby="modal-modal-title"
                    aria-describedby="modal-modal-description"
                >

                    <Box sx={style}>
                        <FileUploadPopUp closePopUp={handleClosePopUp} parentCallback={handleFormData}></FileUploadPopUp>
                    </Box>


                </Modal>
            </div>

            <div className="addedFileList">
                <ul id="myList"></ul>
            </div>


        </div>

        

    )
    

    
}


export default UploadFiles;