const API_FILEFINDER_BASE = `https://localhost/api/filefinder`;
const API_COMMON_BASE = `https://localhost/api`;
// const API_FILEFINDER_BASE = `${process.env.DPR_API_BASE}/filefinder`;

export default class FilterButtonService {
  /**
   * Get all tags from the server and return them as a promise.
   * @returns An array of tags.
   */
  static getTags() {
    const requestOptions = {
      method: "GET",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/tags`, requestOptions)
      .then((json) => json.json())
      .catch((error) => console.log(error));
  }


 
/**
 * It's a function that takes a status as a parameter and returns a fetch request that returns a json
 * object.
 * @param status - 0 or 1
 * @returns An array of objects.
 */
  static getTagsByStatus(status) {
    const requestOptions = {
      method: "GET",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/tags?active[]=${status}`, requestOptions)
      .then((json) => json.json())
      .catch((error) => console.log(error));
  }

  /**
   * going to return a promise that will resolve to the response.parkCode property of the response
   * object returned by the UploadFileService.get() function.
   */
  static getParkCodes() {
    const requestOptions = {
      method: "GET",
      credentials: "include",
    };

    return fetch(`${API_COMMON_BASE}/parks`, requestOptions)
      .then((json) => json.json())
      .catch((error) => console.log(error));
  }


 /**
  * This function makes a GET request to the API_FILEFINDER_BASE/documenttypes endpoint and returns the
  * response as a JSON object.
  * @returns An array of objects.
  */
  static getDocumentTypes() {
    const requestOptions = {
      method: "GET",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/documenttypes`, requestOptions)
      .then((json) => json.json())
      .catch((error) => console.log(error));
  }

 /**
  * It's a function that takes a status as a parameter and returns a promise that resolves to a json
  * object.
  * @param status - 0 or 1
  * @returns An array of objects.
  */
  static getDocumentTypesByStatus(status) {
    const requestOptions = {
      method: "GET",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/documenttypes?active[]=${status}`, requestOptions)
      .then((json) => json.json())
      .catch((error) => console.log(error));
  }


  /**
   * It's a GET request to the API endpoint /businessunits.
   * @returns An array of objects.
   */
  static getBusinessUnits() {
    const requestOptions = {
      method: "GET",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/businessunits`, requestOptions)
      .then((json) => json.json())
      .catch((error) => console.log(error));
  }



  /**
   * It's a function that takes a status as a parameter and returns a fetch request to the API with the
   * status as a query parameter.
   * @param status - 0 or 1
   * @returns An array of objects.
   */
  static getBusinessUnitsByStatus(status) {
    const requestOptions = {
      method: "GET",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/businessunits?active[]=${status}`, requestOptions)
      .then((json) => json.json())
      .catch((error) => console.log(error));
  }

}
