
import SearchBar from "./SearchBar/SearchBar";
import "./SearchPage.css"
import APIClient from "../../utils/APIClient";
import { useRef } from "react";



function SearchPage() {
    const showBothResults = useRef('');

    // const busUnitList = JSON.parse(window.sessionStorage.getItem("businessunits"));
    // const docTypeList = JSON.parse(window.sessionStorage.getItem("documenttypes"));
    

    /**
     * If the search returns topics, render the topics. If the search returns files, render the files.
     * If the search returns both, render both. If the search returns nothing, render nothing.
     * @param searchParamObj params that are needed to be sent to backend so proper arrays are returned.
     */
    const getSearchParams = (searchParamObj) => {

        APIClient.Search.executeSearch(searchParamObj).then(res=>{
            if (res.result === "success") { 

                if ( res.topics.length > 0 && res.files.length == 0 ) {
                    showBothResults.current = "true";
                    renderTopics(res.topics);   
                }else if( res.files.length > 0 && res.topics.length == 0){
                    showBothResults.current = "true";
                    renderFiles(res.files);
                }else if( res.topics.length > 0 && res.files.length > 0) {
                    showBothResults.current = "false";
                    renderTopics(res.topics);
                    renderFiles(res.files);
                } else {
                    showBothResults.current = "false";
                    document.getElementById("search-topic-results-container").innerHTML = "";
                    document.getElementById("search-topic-title").innerText="";

                    document.getElementById("search-file-results-container").innerHTML="";
                    document.getElementById("search-file-title").innerText="";

                    document.getElementById("search-topic-title").innerHTML = "<h3><i>No Results Found</i></h3>"
                }
                
            }
        });
    };


    /**
     * When the user clicks on a topic result, open a new tab with the topic's page.
     * @param id - the id of the topic
     */
    function topicResultClicked(id) {
        window.open(`/filefinder/topic/${id}`, "_blank")
    }

    /**
     * It takes in an array of objects, and for each object in the array, it creates a div, and appends
     * it to the DOM.
     * @param data - the data returned from the API call
     */
    function renderTopics(data) {

        let container = document.getElementById("search-topic-results-container");

        let files_container = document.getElementById("search-file-results-container");
        

        let title_sec = document.getElementById("search-topic-title");
        title_sec.innerText = "Topics:";

        container.innerHTML = "";
        if (showBothResults.current === "true") {
            files_container.innerHTML="";
            document.getElementById("search-file-title").innerText="";
        }        
        data.forEach(element => {

            let card = document.createElement('div');
            card.id = element.id;
            card.className = "card"
            let cardContent = ` 
                <div class="cardContainer"">
                    <h4><b>${element.title}</b></h4>
                    <p>${element.description}</p>
                </div>
            `;
            card.innerHTML += cardContent;
            container.appendChild(card);
            
        })
        addResultEventLisnters();
        

    };

    /**
     * When the user clicks on a card, the topicResultClicked function is called with the id of the
     * card as a parameter.
     */
    function addResultEventLisnters() {
        let cards = document.querySelectorAll(".card");
        cards.forEach(card => {
            card.addEventListener("click", ()=>{
                topicResultClicked(card.id);
            })
            
        });
    }

    /* Rendering the files that are returned from the search. */
    function renderFiles(data) {

        let container = document.getElementById("search-file-results-container");
        let file_sec = document.getElementById("search-file-title");
        file_sec.innerText = "Files:";

        container.innerHTML = "";
        if (showBothResults.current == "true") {
            document.getElementById("search-topic-results-container").innerHTML = "";
            document.getElementById("search-topic-title").innerText="";
        }
        
        data.forEach(element => {
            // console.log(busUnitList.find( e => e.id === element.businessUnitId));
            let busUnitList = JSON.parse(window.sessionStorage.getItem("businessunits"));
            let docTypeList = JSON.parse(window.sessionStorage.getItem("documenttypes"));

            
            let bus_unit = busUnitList.find( e => e.id === element.businessUnitId).title;
            let doc_type = docTypeList.find( e => e.id === element.documentTypeId).title;
            let card = document.createElement('div');
            card.id = element.id;
            card.className = "card";
            const encodedFilename = encodeURI(element.awsS3ObjectName);
            let cardContent = ` 
                <div class="cardContainer"">
                    <h3><b>${element.filename}</b> 
                        <button class="searchDownloadButton" id=${encodedFilename}>
                            <a class=${encodedFilename}>Download File</a>
                        </button>
                    </h3>
                    <div id="fileAtts">
                        <div>
                            <p>Document Type: <i><span>${doc_type}</span></i></p>
                        </div>
                            
                        <div>
                            <p>Business Unit: <i><span>${bus_unit}</span></i></p>
                        </div>

                        <div class="selectdiv">
                            <select class="search-tags-select-display" id="search-tags-select-display-${element.filename}"></select>   
                        </div>

                        <div>
                            <select class="search-prkcds-select-display" id ="search-prkcds-select-display-${element.filename}"></select>
                        </div>
                    <div>
                        
                </div>
            `;
            card.innerHTML += cardContent;
            container.appendChild(card);
            fillSearchTagsSelectMenu(element.tags, element.filename);
            fillSearchParkCdsSelectMenu(element.parks, element.filename);

        });
        addSearchOnclickForDownloadButton();
    };


  /**
   * It adds an onclick event listener to each download button, which calls the API client to get the
   * file link, and then redirects the user to the file link.
   */
    function addSearchOnclickForDownloadButton() {
        let downloadButtons = document.querySelectorAll(".searchDownloadButton");
        downloadButtons.forEach(button => {
            button.addEventListener('click', ()=>{
                const decodedFilename = decodeURI(button.id);
                APIClient.Search.getFileLink(decodedFilename).then(res => {
                    window.location.href = res.filePresignedUrl;
                });
            });
            
        });
    }


    function fillSearchTagsSelectMenu(tags, filename) {
        let tagsSelectMenu = document.getElementById(`search-tags-select-display-${filename}`);
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

    function fillSearchParkCdsSelectMenu(prkcds, filename){
        let prkSelectMenu = document.getElementById(`search-prkcds-select-display-${filename}`);
        if ( prkcds.length > 0 ) {
            const parkcodeList = JSON.parse(window.sessionStorage.getItem("parkcodes"));

            // let tagsSelectMenu = document.getElementById(`tags-select-display-${filename}`);

            let prkcds_list = parkcodeList.filter( function(e){
                return (prkcds.indexOf(e.id) !== -1);
            });

            // console.log("PARKS CODES")
            // console.log(prkcds_list);

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
        <div className="search-container">
            <div className="search-container-bar">
                <SearchBar searchParams={getSearchParams}></SearchBar>
            </div>

            
            <div id="search-result-container">
                <i><h2 id="search-topic-title"></h2></i>
                <div id="search-topic-results-container"></div>
                <i><h2 id="search-file-title"></h2></i>
                <div id="search-file-results-container"></div>
            </div>            
        </div>
    )
}

export default SearchPage;