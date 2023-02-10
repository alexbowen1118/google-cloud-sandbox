import SearchService from "./SearchService";

const API_FILEFINDER_BASE = `https://localhost/api/filefinder`;
export default class TopicViewService {

    /**
     * It takes a topicId, and returns a list of files related to that topicId.
     * @param topicId - 1
     * @returns The response is a JSON object with the following structure:
     */
    static getFilesRelatedToTopic(topicId){

        // console.log("TOPIC ID: " + topicId)

        const requestOptions = {
            method: "GET",
            credentials: "include",
        };
        return fetch(`${API_FILEFINDER_BASE}/topics/${topicId}`, requestOptions).then(res=>{
            return res.json();
        });
    }

    /**
     * "This function returns a link to a file on AWS S3."
     * </code>
     * @param awsS3ObjectName - The name of the file in the S3 bucket.
     * @returns the value of the function getFileLink.
     */
    static getCurrentTopicsFileLink(awsS3ObjectName) {
        return SearchService.getFileLink(awsS3ObjectName);
    }


    /**
     * It takes an id and a payload, and then it makes a PATCH request to the server with the id and
     * payload.
     * @param id - the id of the topic you want to edit
     * @param payload - the new text that needs to be updated
     * @returns The response object.
     */
    static editCurrentTopic(id,payload) {

        const requestOptions = {
            method: "PATCH",
            headers: {
              'Content-Type': 'application/json',
            },
            body: payload,
            credentials: "include",
        };

        return fetch(`${API_FILEFINDER_BASE}/topics/${id}/edit`, requestOptions).then(res=>{
            return res;
        });

    }


    static deleteFile(id) {
        ///deleterequests/{id}/response

        const requestOptions = {
            method: "DELETE",
            credentials: "include",
        };

        return fetch(`${API_FILEFINDER_BASE}/files/${id}`, requestOptions).then(res =>{
            // console.log("RESPONSE FOR DELETE");
            // console.log(res);
            return res;
        });
    }


    static archiveFile(id) {
        const requestOptions = {
            method: "PATCH",
            credentials: "include",
        };

        return fetch(`${API_FILEFINDER_BASE}/files/${id}/archive`, requestOptions).then(res=>{
            // console.log("RESPONSE FROM ARCHIVE");
            // console.log(res);
            return res;
        })
    }
}