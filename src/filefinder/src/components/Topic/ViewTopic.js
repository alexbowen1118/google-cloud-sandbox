import { useParams } from "react-router";
import "./ViewTopic.css"
import { useEffect, useRef, useState } from "react";
import { useNavigate } from "react-router";
import APIClient from "../../utils/APIClient";
import { CircularProgress, Modal, Snackbar, Alert, Switch, FormControlLabel } from "@mui/material";
import {Box, Typography, Button} from "@mui/material";
import FileUploadPopUp from "../UploadFiles/FileUploadPopUp";

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

function ViewTopic() {

    const {topicId} = useParams();
    const [loaded, setLoaded] = useState(false);
    const [topicTitle, setTopicTitle] = useState('');
    const [topicDescription, setTopicDescription] = useState('');
    const [currentTopicActiveFileLinks, setCurrentTopicActiveFileLinks] = useState([]);
    const [currentTopicArchFileLinks, setCurrentTopicArchFileLinks] = useState([]);
    const [openPopUp, setOpenPopUp] = useState(false);
    const [isSwitched, setIsSwithced] = useState(false);
    // const [canReplaceFile, setCanReplaceFile] = useState(false);
    let canReplaceFile = useRef('');

    const navigate = useNavigate();

    const currentUserId = window.sessionStorage.getItem("currentUserId");
    let currentUserRole = window.sessionStorage.getItem("currentUserRole");

    const busUnitList = JSON.parse(window.sessionStorage.getItem("businessunits"));
    const tagsList = JSON.parse(window.sessionStorage.getItem("tags"));
    const parkcodeList = JSON.parse(window.sessionStorage.getItem("parkcodes"));
    const docTypeList = JSON.parse(window.sessionStorage.getItem("documenttypes"));


  
    /**
     * If the switch is on, turn it off and clear the display. If the switch is off, turn it on.
     */
    const handleSwitch=()=>{
        if (isSwitched) {
            setIsSwithced(false);
            let archivedFileStatus = document.getElementById("archivedFileStatus");
            let archivedFileDisplay = document.getElementById("archivedFileDisplay");
            archivedFileStatus.innerText = "";
            archivedFileDisplay.innerHTML = "";
        }else {
            setIsSwithced(true);
        }
    }


    useEffect( ()=>{
        setCurrentTopicActiveFileLinks([]);
        setCurrentTopicArchFileLinks([]);
        fetchData();
    },[]);


    /**
     * If the data is already loaded, don't load it again. If it's not loaded, load it and set the
     * state of the component to loaded.
     */
    const fetchData = async () => {

        if (loaded) {
            return;
        }

        try{

            await APIClient.RelatedTopicFiles.getFilesRelatedToTopic(topicId).then(res => {
                setTopicTitle(res.topic.title);
                setTopicDescription(res.topic.description);
                // console.log("ALL ACTIVE FILES");
                // console.log(res.files.unarchived);
                getActiveFileList(res.files.unarchived);
                getArchivedFileList(res.files.archived);
                setLoaded(true);
            });

        } catch (err) {
            console.warn(err);
        }
    }

    /**
     * It takes an array of objects, and for each object in the array, it makes an API call to get the
     * object's link, and then adds the object to a new array.
     * @param res - an array of objects
     */
    const getActiveFileList = (res) => {
        res.forEach(file => {
            let bus_unit = busUnitList.find( e => e.id === file.businessUnitId).title;
            let doc_type = docTypeList.find( e => e.id === file.documentTypeId).title;
            
            // console.log(file.awsS3ObjectName);
            APIClient.RelatedTopicFiles.getCurrentTopicsFileLink(file.awsS3ObjectName).then( res => {
                setCurrentTopicActiveFileLinks(currentArray => [...currentArray,{
                    "id": file.id,
                    "filename": file.filename,
                    "awsS3ObjectName": file.awsS3ObjectName,
                    "businessUnit": bus_unit,
                    "documentType": doc_type,
                    "parks": file.parks,
                    "tags": file.tags
                }]);
            });
        });
    }

    /**
     * trying to get the title of the business unit, document type, parks, and tags from their
     * respective lists.
     * </code>
     * @param res - an array of objects that contain the following properties:
     */
    const getArchivedFileList = (res) => {
        res.forEach(file => {
            let bus_unit = busUnitList.find( e => e.id === file.businessUnitId).title;
            let doc_type = docTypeList.find( e => e.id === file.documentTypeId).title;
            
            // let parks_list = parkcodeList.filter( function(e){
            //     return (file.parks.indexOf(e.id) !== -1);
            // } );

            // let tags_list = tagsList.filter( function(e){
            //     return (file.tags.indexOf(e.id) !== -1);
            // } );


           // APIClient.RelatedTopicFiles.getCurrentTopicsFileLink(file.awsS3ObjectName).then( res => {
                setCurrentTopicArchFileLinks(currentArray => [...currentArray,{
                    "id": file.id,
                    "filename": file.filename,
                    "awsS3ObjectName": file.awsS3ObjectName,
                    "businessUnit": bus_unit,
                    "documentType": doc_type,
                    "parks": file.parks,
                    "tags": file.tags
                }]);
            //}); 
        });
    }


    /**
     * It takes the topicId, topicTitle, and topicDescription from the current page and stores them in
     * sessionStorage. Then it navigates to the edit page.
     */
    function takeToEditTopicPage() {
        window.sessionStorage.setItem(`topic${topicId}`, JSON.stringify({
            "id": topicId,
            "title" : topicTitle,
            "description": topicDescription
        }))

        navigate(`/topic/${topicId}/edit`);
        
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

    /**
     * trying to upload a file to a server, and by using a formData object to do so. Trying to
     * add a JSON object to the formData object, but when I do so, the server doesn't receive the JSON
     * object.
     * @param fileData - the file object
     * @param fileObj - the file object that is being uploaded
     */
    const handleFormData = (fileData, fileObj) => {
        setOpenPopUp(false);
        
        let formdata = new FormData();
        // let metadata = {
        //     "topic": {
        //         "title": topicTitle,
        //         "description": topicDescription
        //     },
        //     "uploader_id": parseInt(currentUserId),
        //     "files": [fileData]
        // };
        let metadata = {};
        if ( canReplaceFile.current > 0 ) {
            let x = [];
            x.push(canReplaceFile.current)
            metadata = {
                "topic": {
                    "title": topicTitle,
                    "description": topicDescription
                },
                "uploader_id": parseInt(currentUserId),
                "files": [fileData],
                "replace":x
            };
        } else {
            metadata = {
                "topic": {
                    "title": topicTitle,
                    "description": topicDescription
                },
                "uploader_id": parseInt(currentUserId),
                "files": [fileData]
            };
        }
        formdata.append("metadata", JSON.stringify(metadata));
        formdata.append(fileObj.name, fileObj, fileObj.path);
        APIClient.UploadFile.uploadFiles(formdata)
        .then(res => {
            window.location.reload(false);
        });
    }


    /**
     * It renders the content of the page
     * @returns The return statement is returning the JSX code that will be rendered on the page.
     */
    function renderViewTopicContent() {

        return (
            <Box>

                <div style={{float:"right", padding:"10px"}}>

                    {/* <span>Show Archived Files</span> */}
                        <FormControlLabel label="Show Archived" control={<Switch color="warning" onClick={handleSwitch} />}  />
                    
                
                    
                    
                    <Button  id="addFileButton" variant="contained" onClick={handleOpenPopUp}>
                        Add File
                    </Button>

                    <Modal 
                        open = {openPopUp}
                        onClose = {handleClosePopUp}
                        disableEscapeKeyDown 
                        aria-labelledby="modal-modal-title"
                        aria-describedby="modal-modal-description"
                    >

                        <Box sx={style}>
                            <FileUploadPopUp closePopUp={handleClosePopUp} parentCallback={handleFormData}></FileUploadPopUp>
                        </Box>


                    </Modal>
                    
                    <Button variant="contained" onClick={()=>{takeToEditTopicPage()}}>
                        Edit Topic
                    </Button>
                </div>

                <div className="contentContainer">
                    <div>
                        <Typography id="topic-title" gutterBottom variant="h4" component="div">
                            {topicTitle}
                        </Typography>
                        <Typography id="topic-desc" variant="body1" color="text.secondary">
                            {topicDescription}
                        </Typography>
                    </div>

                    <div id="activeFileContent">
                        <div id="header-status">
                            <h3 id="activeFileStatus"></h3>
                        </div>
                        
                        <div id="activeFileDisplay">
                            {renderActiveFiles()}
                        </div>
                    </div>
                    <div id="archivedFileContent">
                        <div id="arch-header-status">
                            <h4 id="archivedFileStatus"></h4>
                        </div>
                        
                        <div id="archivedFileDisplay">
                            {renderArchivedFiles()}
                        </div>
                    </div>
                </div>

            </Box>
        )
    }

    /**
     * It renders a list of files that are currently active. 
     * The function is called when the page loads and when a file is deleted. 
     * The function is also called when a file is archived. 
     * The function is also called when a file is replaced. 
     * The function is also called when a file is added. 
     * The function is also called when a file is unarchived. 
     * The function is also called when a file is restored. 
     */
    function renderActiveFiles() {
        let activeFileStatus = document.getElementById("activeFileStatus");
        
        if (currentTopicActiveFileLinks.length > 0) {
            let activeFileDisplay = document.getElementById("activeFileDisplay");
            if (activeFileDisplay !== null) {
                activeFileDisplay.innerHTML = "";
            }
            if (activeFileStatus != null) {
                activeFileStatus.innerText = "Active Files";
            }

            currentTopicActiveFileLinks.forEach((element,index) => {
                let card = document.createElement('div');
                card.id = element.filename;
                card.className = "activeFileCard"
                let selectIdExtention = element.filename + element.id
                // console.log("WHILE MAKING CARD")
                // console.log(encodeURI(element.awsS3ObjectName));
                const encodedFilename = encodeURI(element.awsS3ObjectName);
                
                let cardContent = `

    

                <div class="cardContainer"">
                    <h3><b>${element.filename}</b> 
                        <button class="downloadButton" id=${encodedFilename}>
                            <a class=${encodedFilename}>Download File</a>
                        </button>

                        <button class="archiveFileButton" id="${element.id}">Archive File</button>

                        <button class="replaceFileButton" id="${element.id}">Replace File</button>

                        ${renderDeleteButton(element, index)}
                    </h3>

                    <div id="fileAtts">
                        <div>
                            <p>Document Type: <i><span>${element.documentType}</span></i></p>
                        </div>
                            
                        <div>
                            <p>Business Unit: <i><span>${element.businessUnit}</span></i></p>
                        </div>

                        <div class="selectdiv">
                            <select class="tags-select-display" id="tags-select-display-${selectIdExtention}"></select>   
                        </div>

                        <div>
                            <select class="prkcds-select-display" id ="prkcds-select-display-${selectIdExtention}"></select>
                        </div>
                    <div>
                               
                </div>
                    
                    
                `; 
                card.innerHTML += cardContent;
                activeFileDisplay.appendChild(card);
                fillTagsSelectMenu(element.tags, selectIdExtention);
                fillParkCdsSelectMenu(element.parks, selectIdExtention);
            });
            addOnclickForDownloadButton();
            addOnclickForDeleteButton();
            addOnclickForArchiveButton();
            addOnclickForReplaceButton();
        } else {
            if (activeFileStatus != null) {
                activeFileStatus.innerText = "No Active Files";
            }
        }
    }

   /**
    * It renders a list of archived files for a given topic. 
    * 
    * The function is called when the user clicks on a show archive toggle in the right hand side of the screen. 
    * 
    * The function is called from the following function:
    * @returns the value of the last expression evaluated.
    */
    function renderArchivedFiles(){
        if (!isSwitched) {
            return;
        }

        let archivedFileStatus = document.getElementById("archivedFileStatus");
        
        if (currentTopicArchFileLinks.length > 0) {
            let archivedFileDisplay = document.getElementById("archivedFileDisplay");
            if (archivedFileDisplay !== null) {
                archivedFileDisplay.innerHTML = "";
            }
            if (archivedFileStatus != null) {
                archivedFileStatus.innerText = "Archived Files";
            }

            currentTopicArchFileLinks.forEach((element,index) => {
                let card = document.createElement('div');
                card.id = element.filename;
                card.className = "archivedFileCard";
                const encodedFilename = encodeURI(element.awsS3ObjectName);
                let cardContent = `

    

                    <div class="cardContainer"">
                    <h3><b>${element.filename}</b> 
                        <button class="downloadButton" id=${encodedFilename}>
                            <a class=${encodedFilename}>Download File</a>
                        </button>

                        <button class = "unarchiveFileButton" id=${element.id}>Unarchive File</button>

                        ${renderDeleteButton(element, index)}
                    </h3>

                    <div id="fileAtts">
                        <div>
                            <p>Document Type: <i><span>${element.documentType}</span></i></p>
                        </div>
                            
                        <div>
                            <p>Business Unit: <i><span>${element.businessUnit}</span></i></p>
                        </div>

                        <div class="selectdiv">
                            <select class="tags-select-display" id="tags-select-display-${element.filename}"></select>   
                        </div>

                        <div>
                            <select class="prkcds-select-display" id ="prkcds-select-display-${element.filename}"></select>
                        </div>
                    <div>
                               
                </div>
                    
                    
                `; 
                card.innerHTML += cardContent;
                archivedFileDisplay.appendChild(card);
                fillTagsSelectMenu(element.tags, element.filename);
                fillParkCdsSelectMenu(element.parks, element.filename);
            });
            addOnclickForDownloadButton();
            addOnclickForDeleteButton();
            addOnClickForUnarchiveButton();
        } else {
            if (archivedFileStatus != null) {
                archivedFileStatus.innerText = "No Archived Files";
            }
        }
    }


    function addOnClickForUnarchiveButton() {
        let archiveFileButtons = document.querySelectorAll(".unarchiveFileButton");
        archiveFileButtons.forEach(button => {
            button.addEventListener('click', () => {
                APIClient.RelatedTopicFiles.archiveFile(button.id).then(res => {
                    if (res.status == 200) {
                        window.location.reload(false);
                    }
                });
            });
        });

    }

   /**
    * If the currentUserRole is not equal to 'Base', then return the delete button. Otherwise, return
    * an empty string.
    * @param element - the current element in the array
    * @param index - the index of the current element in the array
    * @returns A string of HTML.
    */
    function renderDeleteButton(element,index){
        // currentUserRole = "Base";
        if( currentUserRole === 'Admin' || currentUserRole === "Super-Admin" ) {
            return(
                `
                    <button class="adminDeleteButton" id="${element.id}">Delete File</button>

                `
            );
        } else {
            return (``);
        }
    }

    /**
     * When the user clicks on the archive button, the file is archived and the page is reloaded.
     */
    function addOnclickForArchiveButton() {
        let archiveFileButtons = document.querySelectorAll(".archiveFileButton");
        archiveFileButtons.forEach(button => {
            button.addEventListener('click', () => {
                APIClient.RelatedTopicFiles.archiveFile(button.id).then(res => {
                    if (res.status == 200) {
                        window.location.reload(false);
                    }
                });
            });
        });
    }

    /**
     * It adds an onclick event listener to each download button, which when clicked, calls the API to
     * get the file link, and then redirects the user to the file link.
     */
    function addOnclickForDownloadButton() {
        let downloadButtons = document.querySelectorAll(".downloadButton");
        downloadButtons.forEach(button => {
            button.addEventListener('click', ()=>{
                const decodedFilename = decodeURI(button.id);
                // console.log("DOWNLOAD CLICKED SENT THIS");
                // console.log(decodedFilename);
                
                APIClient.Search.getFileLink(decodedFilename).then(res => {
                    window.location.href = res.filePresignedUrl;
                });
            });
            
        });
    }

   /**
    * It adds an onclick event to all the buttons with the class "adminDeleteButton" and when the
    * button is clicked, it calls the deleteFile function in the APIClient.RelatedTopicFiles object
    */
    function addOnclickForDeleteButton() {
        let adminDeleteButtons = document.querySelectorAll(".adminDeleteButton");
        adminDeleteButtons.forEach(button => {
            button.addEventListener('click', ()=>{
                APIClient.RelatedTopicFiles.deleteFile(button.id).then(res=>{
                    if (res.status == 200) {
                        window.location.reload(false);
                    }
                });
            });
            
        });
    }


    /**
     * It adds an onclick event to each button with the class "replaceFileButton" and when the button
     * is clicked, it sets the value of the variable "canReplaceFile.current" to the id of the button
     * that was clicked.
     * </code>
     */
    function addOnclickForReplaceButton(){
        let replaceFileButtons = document.querySelectorAll(".replaceFileButton");
        replaceFileButtons.forEach(button=>{
            button.addEventListener('click', ()=>{
                canReplaceFile.current = button.id;
                handleOpenPopUp();
            })
        })

    }

    /**
     * It takes an array of tag ids and a filename, and then it creates a select menu with the tag
     * titles as options.
     * @param tags - an array of tag ids
     * @param filename - the name of the file
     * @returns The tagsSelectMenu is being returned.
     */
    function fillTagsSelectMenu(tags, filename) {
        let tagsSelectMenu = document.getElementById(`tags-select-display-${filename}`);
        if ( tags.length > 0 ) {
            const tagsList = JSON.parse(window.sessionStorage.getItem("tags"));

            // let tagsSelectMenu = document.getElementById(`tags-select-display-${filename}`);

            let tags_list = tagsList.filter( function(e){
                return (tags.indexOf(e.id) !== -1);
            });

            let title = document.createElement('option');
            title.innerText='Tags';
            title.setAttribute("hidden", "selected");

            tagsSelectMenu.appendChild(title);
            
            tags_list.forEach((element) => {
                let option = document.createElement('option');
                option.innerText= element.title;
                option.setAttribute("disabled","");
                tagsSelectMenu.appendChild(option);
            });
        } else {
            tagsSelectMenu.remove();
        }
    }

    /**
     * "If the prkcds array is not empty, then create a new option element for each element in the
     * prkcds array and append it to the prkSelectMenu element."
     * @param prkcds - an array of park codes
     * @param filename - the name of the file that is being uploaded
     * @returns the value of the variable prkSelectMenu.
     */
    function fillParkCdsSelectMenu(prkcds, filename){
        let prkSelectMenu = document.getElementById(`prkcds-select-display-${filename}`);
        if ( prkcds.length > 0 ) {
            const parkcodeList = JSON.parse(window.sessionStorage.getItem("parkcodes"));

            // let tagsSelectMenu = document.getElementById(`tags-select-display-${filename}`);

            let prkcds_list = parkcodeList.filter( function(e){
                return (prkcds.indexOf(e.id) !== -1);
            });

            let title = document.createElement('option');
            title.innerText='Park Codes';
            title.setAttribute("hidden", "selected");

            prkSelectMenu.appendChild(title);
            
            prkcds_list.forEach((element) => {
                let option = document.createElement('option');
                option.innerText= element.parkCode;
                option.setAttribute("disabled","");
                prkSelectMenu.appendChild(option);
            });
        } else {
            prkSelectMenu.remove();
        }
    }





    return (
        <div>{loaded ? renderViewTopicContent() : 
            <CircularProgress 
                id="circularProgress"
                size="4rem"
                disableShrink
                sx={{color:"primary"}}>
                <span className="visually-hidden">Loading...</span>
            </CircularProgress>}
        </div>
    )
}

export default ViewTopic;