import * as React from 'react';
import { useEffect, useState} from "react";
import APIClient from "../../utils/APIClient";
import { Box, Tab } from "@mui/material";
import { TabList, TabContext, TabPanel,} from '@mui/lab';
import Switch from "react-switch";
import './AdminActions.css'

function AdminActions() {
    const [tags, setTags] = useState([]);
    const [tagStatus, setTagStatus] = useState([]);
    const [addTagValue, setAddTagValue] = useState('');

    const [businessUnits, setBusinessUnits] = useState([]);
    const [businessUnitStatus, setBusinessUnitStatus] = useState([]);
    const [addBusinessUnitValue, setAddBusinessUnitValue] = useState('');

    const [documentTypes, setDocumentTypes] = useState([]);
    const [documentTypeStatus, setDocumentTypeStatus] = useState([]);
    const [addDocumentTypeValue, setAddDocumentTypeValue] = useState('');

    const [currentTab, setCurrentTab] = useState("1");

    useEffect( ()=>{
        fetchData();
    },[]);

    
    const handleTabChange = (event, newTab) => {
        setCurrentTab(newTab);        
    };

    /**
     * Takes a tag id and flips the active status and changes the text in the activate/deactivate button
     * @param {int} tagId to flip active status
     */
    const changeTagStatus = (tagId) => {
        let currentStatus = getTagStatusById(tagId);
        if(currentStatus === false) {
            let newTagStatus = tagStatus.map(tag => {
                if(tag.id === tagId) {
                    return {id: tagId, status: true};
                }
                return tag;
            });
            setTagStatus(newTagStatus);
        } else if(currentStatus === true) {
            let newTagStatus = tagStatus.map(tag => {
                if(tag.id === tagId) {
                    return {id: tagId, status: false};
                }
                return tag;
            });
            setTagStatus(newTagStatus);
        }
        APIClient.AdminActions.changeTagActiveStatus(tagId);
    };

    /**
     * Takes a business unit id and flips the active status and changes
     * the text in the activate/deactivate button
     * @param int busUnitId 
     */
    const changeBusUnitStatus = (busUnitId) => {
        let currentStatus = getBusUnitStatusById(busUnitId);
        if(currentStatus === false) {
            let newBusUnitStatus = businessUnitStatus.map(busUnit => {
                if(busUnit.id === busUnitId) {
                    return {id: busUnitId, status: true};
                }
                return busUnit;
            });
            setBusinessUnitStatus(newBusUnitStatus);
        } else if(currentStatus === true) {
            let newBusUnitStatus = businessUnitStatus.map(busUnit => {
                if(busUnit.id === busUnitId) {
                    return {id: busUnitId, status: false};
                }
                return busUnit;
            });
            setBusinessUnitStatus(newBusUnitStatus);
        }
        APIClient.AdminActions.changeBusinessUnitActiveStatus(busUnitId);
    };

    /**
     * Takes a document type id and flips the active status and changes the text
     * in the activate/deactivate button
     * @param {int} docTypeId 
     */
    const changeDocTypeStatus = (docTypeId) => {
        let currentStatus = getDocTypeStatusById(docTypeId);
        if(currentStatus === false) {
            let newDocTypeStatus = documentTypeStatus.map(docType => {
                if(docType.id === docTypeId) {
                    return {id: docTypeId, status: true};
                }
                return docType;
            });
            setDocumentTypeStatus(newDocTypeStatus);
        } else if(currentStatus === true) {
            let newDocTypeStatus = documentTypeStatus.map(docType => {
                if(docType.id === docTypeId) {
                    return {id: docTypeId, status: false};
                }
                return docType;
            });
            setDocumentTypeStatus(newDocTypeStatus);
        }
        APIClient.AdminActions.changeDocumentTypeActiveStatus(docTypeId);
    };

    /**
     * Searches the tagStatus state array to get the current status of a tag by id
     * @param {int} id of the tag to be searched for
     * @returns {string} The current status of the tag (activate/deactivate)
     */
    const getTagStatusById = (id) => {
        return tagStatus.find(tag => tag.id === id).status;
    }

    /**
     * Searches the busUnitStatus state array to get the current status of a business unit by id
     * @param {int} id of the business unit to be searched for
     * @returns {string} The current status of the business unit (activate/deactivate)
     */
    const getBusUnitStatusById = (id) => {
        return businessUnitStatus.find(busUnit => busUnit.id === id).status;
    }

    /**
     * Searches the docTypeStatus state array to get the current status of a document type by id
     * @param {int} id of the document type to be searched for
     * @returns {string} The current status of the document type (activate/deactivate)
     */
    const getDocTypeStatusById = (id) => {
        return documentTypeStatus.find(docType => docType.id === id).status;
    }

    const addTag = () => {
        if(tags.active.find(tag => tag.title === addTagValue) == null &&
           tags.inactive.find(tag => tag.title === addTagValue) == null) {
            APIClient.AdminActions.addTag(addTagValue).then(res => {
                tags.active.push({id: res.data.id, title: res.data.title});
                setTagStatus(tagStatus => [...tagStatus, {id: res.data.id, status:true}]);
            });
        } else {
            window.alert(addTagValue + " already exists");
            setAddTagValue('');
        }
    }

    const addBusUnit = () => {
        if(businessUnits.active.find(busUnit => busUnit.title === addBusinessUnitValue) == null &&
           businessUnits.inactive.find(busUnit => busUnit.title === addBusinessUnitValue) == null) {
            APIClient.AdminActions.addBusinessUnit(addBusinessUnitValue).then(res => {
                businessUnits.active.push({id: res.data.id, title: res.data.title});
                setBusinessUnitStatus(businessUnitStatus => [...businessUnitStatus, {id: res.data.id, status:true}]);
            });
        } else {
            window.alert(addBusinessUnitValue + " already exists");
            setAddBusinessUnitValue('');
        }
    }

    const addDocType = () => {
        if(documentTypes.active.find(docType => docType.title === addDocumentTypeValue) == null &&
           documentTypes.inactive.find(docType => docType.title === addDocumentTypeValue) == null) {
            APIClient.AdminActions.addDocumentType(addDocumentTypeValue).then(res => {
                documentTypes.active.push({id: res.data.id, title: res.data.title});
                setDocumentTypeStatus(documentTypeStatus => [...documentTypeStatus, {id: res.data.id, status:true}]);
            });
        } else {
            window.alert(addDocumentTypeValue + " already exists");
            setAddDocumentTypeValue('');
        }
    }

    const fetchData = async () => {
        try{
            await Promise.all([
                APIClient.AdminActions.getTags(),
                APIClient.AdminActions.getDocumentTypes(),
                APIClient.AdminActions.getBusinessUnits(),
            ]).then(([res1, res2, res3]) => {
                setTags(res1.tags);
                setDocumentTypes(res2.documenttypes);
                setBusinessUnits(res3.businessunits);
                for(let i = 0; i < res1.tags.active.length; i++) {
                    setTagStatus(tagStatus => [...tagStatus, {id: res1.tags.active[i].id, status:true}]);
                }
                for(let i = 0; i < res1.tags.inactive.length; i++) {
                    setTagStatus(tagStatus => [...tagStatus, {id: res1.tags.inactive[i].id, status:false}]);
                }
                for(let i = 0; i < res2.documenttypes.active.length; i++) {
                    setDocumentTypeStatus(documentTypeStatus => [...documentTypeStatus, {id: res2.documenttypes.active[i].id, status:true}]);
                }
                for(let i = 0; i < res2.documenttypes.inactive.length; i++) {
                    setDocumentTypeStatus(documentTypeStatus => [...documentTypeStatus, {id: res2.documenttypes.inactive[i].id, status:false}]);
                }
                for(let i = 0; i < res3.businessunits.active.length; i++) {
                    setBusinessUnitStatus(businessUnitStatus => [...businessUnitStatus, {id: res3.businessunits.active[i].id, status:true}]);
                }
                for(let i = 0; i < res3.businessunits.inactive.length; i++) {
                    setBusinessUnitStatus(businessUnitStatus => [...businessUnitStatus, {id: res3.businessunits.inactive[i].id, status:false}]);
                }
                return [res1, res2, res3];
            });
        } catch (err) {
            console.warn(err);
        }
    }

    return (
        <div className="AdminActionsContainer">
            <TabContext value={currentTab}>
                <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
                    <TabList onChange={handleTabChange} aria-label="lab API tabs example">
                        <Tab label="Tags" value="1"/>
                        <Tab label="Business Units" value="2"/>
                        <Tab label="Document Types" value="3"/>
                    </TabList>
                </Box>
                <TabPanel value="1">
                    <div className="tab-view">
                        <ul className="list">
                            {tags.active && tags.active.map(tag =>
                                <div key={tag.title} className="list-item">
                                    <Switch uncheckedIcon={false} checkedIcon={false} onChange={() => changeTagStatus(tag.id)} checked={getTagStatusById(tag.id)}/>
                                    <p className="item-title">{tag.title}</p>
                                </div>
                            )}
                            {tags.inactive && tags.inactive.map(tag => 
                                <div key={tag.title} className="list-item">
                                    <Switch uncheckedIcon={false} checkedIcon={false} onChange={() => changeTagStatus(tag.id)} checked={getTagStatusById(tag.id)}/>
                                    <p className="item-title">{tag.title}</p>
                                </div>
                            )}
                        </ul>
                        <div className="right-half">
                            <div className="input-button">
                                <input type="text" placeholder="Tag title" className="input" value={addTagValue} onChange={(label) => {setAddTagValue(label.target.value)}}></input>
                                <button type="button" className="add-button" onClick={() => addTag()}>Add Tag</button>
                            </div>
                        </div>
                    </div>
                </TabPanel>
                <TabPanel value="2">
                    <div className="tab-view">
                        <ul className="list">
                            {businessUnits.active && businessUnits.active.map(busUnit =>
                                <div key={busUnit.title} className="list-item">
                                    <Switch uncheckedIcon={false} checkedIcon={false} onChange={() => changeBusUnitStatus(busUnit.id)} checked={getBusUnitStatusById(busUnit.id)}/>
                                    <p className="item-title">{busUnit.title}</p>
                                </div>
                            )}
                            {businessUnits.inactive && businessUnits.inactive.map(busUnit => 
                                <div key={busUnit.title} className="list-item">
                                    <Switch uncheckedIcon={false} checkedIcon={false} onChange={() => changeBusUnitStatus(busUnit.id)} checked={getBusUnitStatusById(busUnit.id)}/>
                                    <p className="item-title">{busUnit.title}</p>
                                </div>
                            )}
                        </ul>
                        <div className="right-half">
                            <div className="input-button">
                                <input type="text" placeholder="Business unit title" className="input" value={addBusinessUnitValue} onChange={(label) => {setAddBusinessUnitValue(label.target.value)}}></input>
                                <button type="button" className="add-button" onClick={() => addBusUnit()}>Add Business Unit</button>
                            </div>
                        </div>
                    </div>
                </TabPanel>
                <TabPanel value="3">
                    <div className="tab-view">
                        <ul className="list">
                            {documentTypes.active && documentTypes.active.map(docType =>
                                <div key={docType.title} className="list-item">
                                    <Switch uncheckedIcon={false} checkedIcon={false} onChange={() => changeDocTypeStatus(docType.id)} checked={getDocTypeStatusById(docType.id)}/>
                                    <li className="item-title">{docType.title}</li>
                                </div>
                            )}
                            {documentTypes.inactive && documentTypes.inactive.map(docType => 
                                <div key={docType.title} className="list-item">
                                    <Switch uncheckedIcon={false} checkedIcon={false} onChange={() => changeDocTypeStatus(docType.id)} checked={getDocTypeStatusById(docType.id)}/>
                                    <li className="item-title">{docType.title}</li>
                                </div>
                            )}
                        </ul>
                        <div className="right-half">
                            <div className="input-button">
                                <input type="text" placeholder="Document type title" className="input" value={addDocumentTypeValue} onChange={(label) => {setAddDocumentTypeValue(label.target.value)}}></input>
                                <button type="button" className="add-button" onClick={() => addDocType()}>Add Document Type</button>
                            </div>
                        </div>
                    </div>
                </TabPanel>
            </TabContext>
        </div>
    )
}

export default AdminActions;