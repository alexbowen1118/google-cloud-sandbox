import { useParams } from "react-router";
import "./EditTopic.css"
import { useEffect, useState } from "react";
import { useNavigate } from "react-router";
import APIClient from "../../../utils/APIClient";
import { CircularProgress } from "@mui/material";
import {Box, TextField, Button} from "@mui/material";


function EditTopic() {

    /* Destructuring the topicId from the useParams hook. */
    const {topicId} = useParams();
    /* Setting the state of the component to false. */
    const [loaded, setLoaded] = useState(false);
    /* Setting the state of the component to an empty string. */
    const [topicTitle, setTopicTitle] = useState('');
    /* Setting the state of the component to an empty string. */
    const [topicDescription, setTopicDescription] = useState('');

    /* A hook that is used to navigate to a different page. */
    const navigate = useNavigate();

    /* A hook that is called when the component is mounted. */
    useEffect( ()=>{
        fetchData();
    },[]);


    /**
     * If the topicId in the sessionStorage object is the same as the topicId in the URL, then set the
     * state of the component to the values in the sessionStorage object.
     */
    const fetchData = async () => {

        try{

            let sessionTopicObject = JSON.parse(window.sessionStorage.getItem(`topic${topicId}`));

            if( sessionTopicObject.id === topicId) {
                setTopicTitle(sessionTopicObject.title);
                setTopicDescription(sessionTopicObject.description);
                setLoaded(true);
                window.sessionStorage.removeItem(`topic${topicId}`);

            }else {
                console.log("ERROR WHILE GETTING TOPIC FROM S.STORAGE")
            }

        } catch (err) {
            console.warn(err);
        }
    }


    /**
     * When the user types in the textarea, the value of the textarea is set to the state variable
     * topicDescription.
     * @param event - The event object is a JavaScript event that is sent to an element when an event
     * occurs on the element.
     */
    const handleDescriptionChange = (event) => {
        setTopicDescription(event.target.value);
    }

    /**
     * When the user types in the topic title input field, the value of the input field is set to the
     * topic title state variable.
     * @param event - The event that triggered the function
     */
    const handleTopicTitleChange = (event) => {
        setTopicTitle(event.target.value);
    }

    /**
     * The function is called when the user clicks the save button. It checks if the topic title and
     * description are not empty. If they are not empty, it makes an API call to the backend to save
     * the changes.
     */
    function saveEditedText() {
        // MAKE API CALL FOR BACKEND
        // console.log("Title: " + topicTitle);
        // console.log("Desc: " + topicDescription);
        if ( topicDescription !== '' && topicTitle !== '' ){
            let payload = JSON.stringify({
                "title": topicTitle,
                "description": topicDescription
            })
            // console.log(payload)
            APIClient.RelatedTopicFiles.editCurrentTopic(topicId, payload).then(res => {
                if (res.status === 200) {
                    navigate(`/topic/${topicId}`);
                }
            });
            // console.log(topicDescription)
        } else {
            window.alert("Topic title and Description cannot be empty");
        }
  
    }


    /**
     * The function renders a text field for the title and description of a topic. The text field is
     * populated with the title and description of the topic. The user can edit the text field and
     * click save to save the changes. The user can also click cancel to cancel the changes.
     * @returns The return statement is returning the JSX code that will be rendered to the screen.
     */
    function renderViewTopicContent() {
        return(
            <Box>
                
                <div className="editContentContainer">
                    {/* TEXT FIELD FOR TITLE AND DESCRIPTION */}
                    <div className="TextFieldContainer">

                        <div className="grid-item1">
                            <TextField required 
                                id="topic-title-textfield" 
                                label="Topic Title" 
                                variant="outlined" 
                                sx={{width: 500}}
                                helperText="Required"
                                defaultValue={topicTitle}
                                onChange={handleTopicTitleChange}
                            />    
                        </div>


                        <div className="grid-item2">
                            <TextField
                                id="description-textfield"
                                label="Description"
                                multiline
                                sx={{ml: 1, width: 500}}
                                minRows={8}
                                maxRows={8}
                                defaultValue={topicDescription}
                                onChange={handleDescriptionChange}
                            />
                        </div>
                    </div>

                    
                    <div id="saveAndCancelCont">
                        <div>
                            <Button  id="saveButton" variant="contained"  size="medium" color="success" onClick={()=>{
                                saveEditedText()
                                }}>
                            Save</Button>
                        </div>

                        <div>
                            <Button  id="cancelButton" variant="contained" size="medium" color="error" onClick={()=>{
                                navigate(`/topic/${topicId}`)}}>
                            Cancel</Button>
                        </div>
                    </div>

                </div>
            </Box>
        );
    }

    /* Returning the JSX code that will be rendered to the screen. */
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

export default EditTopic;