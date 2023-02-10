const API_FILEFINDER_BASE = `https://localhost/api/filefinder`;

export default class SearchService {
 /**
  * It takes an object of key/value pairs and creates a URL with the key/value pairs as query
  * parameters.
  * @param urlParamsObj - 
  * @returns
  * https://localhost:44300/search?tags%5B%5D=tag1&tags%5B%5D=tag2&tags%5B%5D=tag3&parks%5B%5D=park1&parks%5B%5D=park2&parks%5B%5D=park3&
  */
    static executeSearch(urlParamsObj) {
        let url = new URL(`${API_FILEFINDER_BASE}/search`);
        let params = new URLSearchParams();
                
        for (var key in urlParamsObj) {
            if (urlParamsObj.hasOwnProperty(key)) {
                if (urlParamsObj[key]) {
                    if( key === "tags[]" || key === "parks[]" || key === "docTypes[]" || key === "busUnits[]") {
                        urlParamsObj[key].forEach(element => {
                            params.append(key, element);                            
                        });
                    } else {
                        params.append(key, urlParamsObj[key]);                        
                    }
                }
                
            }
        }

        // console.log(url + decodeURIComponent(params));

        const requestOptions = {
            method: "GET",
            credentials: "include",
          };
        return fetch( url +  "?" + params, requestOptions).then(res => {
            return res.json();
        });
    }


    /**
     * It takes a string (awsS3ObjectName) and returns a promise that resolves to a json object.
     * @param awsS3ObjectName - The name of the file in the S3 bucket.
     * @returns The response is a JSON object with the following structure:
     * {
     *     "url": "https://s3.amazonaws.com/mybucket/myfile.txt"
     * }
     */
    static getFileLink(awsS3ObjectName) {
        const requestOptions = {
            method: "GET",
            credentials: "include",
          };
        return fetch(`${API_FILEFINDER_BASE}/files/s3url/${awsS3ObjectName}`, requestOptions).then(res=>{
            return res.json();
        });
    }
}