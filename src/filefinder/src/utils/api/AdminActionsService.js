const API_FILEFINDER_BASE = `https://localhost/api/filefinder`;
export default class AdminActionsService {

  /**
   * Get all tags from the server separated by active status and return them as a promise.
   * @returns An array of tags.
   */
  static getTags() {
    const requestOptions = {
      method: "GET",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/tags?active[]=0&active[]=1`, requestOptions)
      .then((json) => json.json())
      .catch((error) => console.log(error));
  }

  /**
   * Get all document types from the server separated by active status and return them as a promise.
   * @returns An array of tags.
   */
  static getDocumentTypes() {
    const requestOptions = {
      method: "GET",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/documenttypes?active[]=0&active[]=1`, requestOptions)
      .then((json) => json.json())
      .catch((error) => console.log(error));
  }

  /**
   * Get all business units from the server separated by active status and return them as a promise.
   * @returns An array of tags.
   */
  static getBusinessUnits() {
    const requestOptions = {
      method: "GET",
      credentials: "include",
    };
  
    return fetch(`${API_FILEFINDER_BASE}/businessunits?active[]=0&active[]=1`, requestOptions)
      .then((json) => json.json())
      .catch((error) => console.log(error));
  }

  /**
   * Takes an tag id and changes the active status.
   * @param int A tag id
   */
  static changeTagActiveStatus(tagId) {
    const requestOptions = {
      method: "PATCH",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/tags/${tagId}/active`, requestOptions).then(res=>{
      return res.json();
    });
  }

  /**
   * Takes an business unit id and changes the active status.
   * @param int A business unit id
   */
  static changeBusinessUnitActiveStatus(busUnitId) {
    const requestOptions = {
      method: "PATCH",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/businessunits/${busUnitId}/active`, requestOptions).then(res=>{
      return res.json();
    });
  }

  /**
   * Takes an document type id and changes the active status.
   * @param int A document type id
   */
  static changeDocumentTypeActiveStatus(docTypeId) {
    const requestOptions = {
      method: "PATCH",
      credentials: "include",
    };

    return fetch(`${API_FILEFINDER_BASE}/documenttypes/${docTypeId}/active`, requestOptions).then(res=>{
      return res.json();
    });
  }

  /**
   * Adds a tag to the database with the parameter title and returns the tag id
   * @param {string} tagTitle The title of the new tag
   */
  static addTag(tagTitle) {
    const requestOptions = {
      method: "POST",
      credential: "include",
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        title: tagTitle
      })
    };

    return fetch(`${API_FILEFINDER_BASE}/tags`, requestOptions)
      .then((res) => {return res.json();})
      .catch((err) => console.log("ERROR", err));
  }

  /**
   * Adds a business unit to the database with the parameter title and returns the busUnit id
   * @param {string} busUnitTitle The title of the new bus unit
   */
  static addBusinessUnit(busUnitTitle) {
    const requestOptions = {
      method: "POST",
      credential: "include",
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        title: busUnitTitle
      })
    };

    return fetch(`${API_FILEFINDER_BASE}/businessunits`, requestOptions)
      .then((res) => {return res.json();})
      .catch((err) => console.log("ERROR", err));
  }

    /**
   * Adds a document type to the database with the parameter title and returns the docType id
   * @param {string} docTypeTitle The title of the new document type
   */
  static addDocumentType(docTypeTitle) {
    const requestOptions = {
      method: "POST",
      credential: "include",
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        title: docTypeTitle
      })
    };

    return fetch(`${API_FILEFINDER_BASE}/documenttypes`, requestOptions)
      .then((res) => {return res.json();})
      .catch((err) => console.log("ERROR", err));
  }
      
}